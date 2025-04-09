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
    
}
