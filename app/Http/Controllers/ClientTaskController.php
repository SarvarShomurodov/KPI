<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\SubTask;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ClientTaskController extends Controller
{
    // Barcha tasksni ko'rsatish
    public function index()
    {
        $tasks = Task::all();
        return view('client.tasks.index', compact('tasks'));
    }

    // Taskga bosganda, uning xodimlar ro'yxatini ko'rsatish
    public function show($taskId)
    {
        $task = Task::findOrFail($taskId);
        $staffUsers = User::all();  // Xodimlar ro'yxatini olish
    
        // Har bir user_id uchun umumiy rating
        $ratings = TaskAssignment::select('user_id', DB::raw('SUM(rating) as total_rating'))
            ->groupBy('user_id')
            ->pluck('total_rating', 'user_id'); // [user_id => total_rating]
    
        return view('client.tasks.show', compact('task', 'staffUsers', 'ratings'));
    }

    // Foydalanuvchiga baho berish formasi
    public function assignTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $staffUser = User::findOrFail($userId);
        $subtasks = SubTask::where('task_id', $taskId)->get(); 
        return view('client.tasks.assign', compact('task', 'staffUser', 'subtasks'));
    }

    // Baho saqlash
    public function storeRating(Request $request, $taskId, $userId)
    {
        // 1. To'g'ri validation qilish
        $request->validate([
            'subtask_id' => 'required|exists:sub_tasks,id',
            'rate' => 'required|numeric',         // ✅ formdan `rate` kelyapti
            'comment' => 'nullable|string',
            'date' => 'required|date',            // ✅ formdan `date` kelyapti
        ]);
    
        // 2. Task va userni olish
        $task = Task::findOrFail($taskId);
        $staffUser = User::findOrFail($userId);
        $subtask = SubTask::findOrFail($request->subtask_id);
    
        // 3. Agar rate min va max oralig'ida bo'lishini tekshirish kerak bo'lsa
        // if ($request->rate < $subtask->min || $request->rate > $subtask->max) {
        //     return back()->withErrors(['rate' => 'Baho SubTaskning belgilangan minimal va maksimal qiymatlariga mos kelmaydi.']);
        // }
    
        // 4. Ma'lumotni saqlash
        TaskAssignment::create([
            'subtask_id' => $request->subtask_id,
            'user_id' => $staffUser->id,
            'rating' => $request->rate,          // ✅ formdagi `rate` ni bazadagi `rating` ga
            'comment' => $request->comment,
            'addDate' => $request->date,         // ✅ formdagi `date` ni bazadagi `addDate` ga
        ]);
    
        // 5. Redirect qilish
        return redirect()->route('tasks.assign', ['taskId' => $taskId, 'staffId' => $userId])
                     ->with('success', 'Baho muvaffaqiyatli saqlandi');
    }

    public function swod(Request $request)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $tasks = Task::all();
        $staffUsers = User::all();

        $query = TaskAssignment::with('subtask.task');

        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }

        $assignments = $query->get()
            ->groupBy('user_id')
            ->map(function ($assignments) {
                return [
                    'total_rating' => $assignments->sum('rating'),
                    'tasks' => $assignments->map(function ($a) {
                        return [
                            'task_id' => $a->subtask->task->id ?? null,
                            'task_name' => $a->subtask->task->taskName ?? null,
                            'rating' => $a->rating,
                        ];
                    }),
                ];
            });

        return view('client.swod.swod', compact('tasks', 'staffUsers', 'assignments', 'from', 'to'));
    }


    public function grafik(Request $request)
    {
        // Formdan kelgan filterlar
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $position = $request->input('position');
    
        // Default sanalar
        $today = now()->format('Y-m-d');
        $oneMonthAgo = now()->subMonth()->format('Y-m-d');
    
        // Barcha topshiriqlar (tasklar)
        $tasks = Task::all();
    
        // Positionlar ro'yxati (dropdown uchun)
        $positions = User::select('position')->distinct()->pluck('position');
    
        // Filtering: xodimlar ro'yxati
        $staffUsers = User::when($position && $position !== 'all', function ($query) use ($position) {
            $query->where('position', $position);
        })->get();
    
        // KPI natijalari (TaskAssignment) – addDate va position bo'yicha filtering
        $query = TaskAssignment::with('subtask.task', 'user');
    
        // Sana filtering
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        } elseif (!$from && !$to) {
            // Default sanalar bo'lsa (hozirgi sanadan 1 oylik oraliq)
            $query->whereBetween('addDate', [$oneMonthAgo, $today]);
        }
    
        // Position filtering
        if ($position && $position !== 'all') {
            $query->whereHas('user', function ($q) use ($position) {
                $q->where('position', $position);
            });
        }
    
        // Assignments ma'lumotlarini olish
        $assignments = $query->get()
            ->groupBy('user_id')
            ->map(function ($assignments) {
                return [
                    'total_rating' => $assignments->sum('rating'),
                    'tasks' => $assignments->map(function ($a) {
                        return [
                            'task_id' => $a->subtask->task->id ?? null,
                            'task_name' => $a->subtask->task->taskName ?? null,
                            'rating' => $a->rating,
                        ];
                    }),
                ];
            });
    
        // Bladega ma'lumotlarni yuborish
        return view('client.swod.index', compact(
            'tasks',
            'staffUsers',
            'assignments',
            'positions',
            'today',
            'oneMonthAgo',
            'from',       // from_date qiymatini yuborish
            'to' 
        ));
    }

    public function showAssign($userId)
    {
        $user = User::findOrFail($userId);

        $assignments = TaskAssignment::with('subtask.task')
            ->where('user_id', $userId)
            ->get();

        return view('client.swod.show', compact('user', 'assignments'));
    }

    public function taskDetails($userId, $taskId, Request $request)
    {
        $user = User::findOrFail($userId);
        $task = Task::findOrFail($taskId);
    
        $from = $request->input('from_date');
        $to = $request->input('to_date');
    
        $query = TaskAssignment::with('subtask.task')
            ->where('user_id', $userId)
            ->whereHas('subtask', function ($q) use ($taskId) {
                $q->where('task_id', $taskId);
            });
        
        // Sana bo‘yicha filterlash (agar mavjud bo‘lsa)
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }
    
        $assignments = $query->get();
    
        return view('client.swod.task-details', compact('user', 'task', 'assignments', 'from', 'to'));
    }
}
