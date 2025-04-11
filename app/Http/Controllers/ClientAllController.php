<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TaskAssignment;

class ClientAllController extends Controller
{
    public function filterByDate($query, $from, $to)
{
    // If from_date and to_date are provided, filter the query
    if ($from && $to) {
        $query->whereBetween('addDate', [$from, $to]);
    }

    return $query; // Return the filtered query
}
public function index(Request $request)
{
    $year = $request->input('year');
    $month = $request->input('month');

    $from = null;
    $to = null;

    // Faqat agar year va month tanlangan bo‘lsa, from/to ni aniqlaymiz
    if ($year && $month) {
        $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->startOfMonth()->toDateString();
        $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->endOfMonth()->toDateString();
    }

    // So‘rovni qurish
    $query = TaskAssignment::with('subtask.task');

    // Agar from va to mavjud bo‘lsa, sana bo‘yicha filter
    if ($from && $to) {
        $query->whereBetween('addDate', [$from, $to]);
    }

    // Ma’lumotlarni olish va guruhlash
    $assignments = $query->get()
        ->groupBy('user_id')
        ->map(function ($assignments) {
            $groupedTasks = $assignments->groupBy(function ($a) {
                return $a->subtask->task->taskName ?? 'Noma\'lum';
            });

            $tasks = $groupedTasks->map(function ($group, $taskName) {
                return [
                    'task_name' => $taskName,
                    'total_rating' => $group->sum('rating'),
                ];
            })->values();

            return [
                'total_rating' => $assignments->sum('rating'),
                'tasks' => $tasks,
            ];
        });

    $currentUser = auth()->user();
    $currentUserAssignment = $assignments[$currentUser->id] ?? null;

    return view('client.view.index', compact(
        'assignments',
        'from',
        'to',
        'currentUserAssignment'
    ));
}

public function subtask(){
    return view('client.view.dataset');
}    
    
    

}
