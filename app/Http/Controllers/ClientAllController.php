<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

    public function subtask(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // So‘rovdan yil va oyni olish
        $year = $request->input('year');
        $month = $request->input('month');

        $from = null;
        $to = null;

        // Agar yil va oy tanlangan bo‘lsa, sanani aniqlaymiz
        if ($year && $month) {
            $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->startOfMonth()->toDateString();
            $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->endOfMonth()->toDateString();
        }

        // So‘rovni quramiz
        $query = TaskAssignment::with('subtask.task')
            ->where('user_id', $user->id);

        // Agar sana filtri mavjud bo‘lsa, qo‘llaymiz
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }

        // Natijalarni olish
        $assignments = $query->get();

        // Vazifalarni topshiriq nomiga qarab guruhlash
        $userAssignment = $assignments->groupBy(function ($item) {
            return $item->subtask->task->taskName ?? 'Nomaʼlum';
        });

        // Har bir topshiriq bo‘yicha statistika
        $taskStats = $userAssignment->map(function ($group) {
            return [
                'sum' => $group->sum('rating'),
                'avg' => round($group->avg('rating'), 2),
                'assignments' => $group,
            ];
        });

        // Umumiy statistika
        $totalSum = $assignments->sum('rating');
        $totalAvg = round($assignments->avg('rating'), 2);
        $totalCount = $assignments->count();

        return view('client.view.dataset', compact(
            'user',
            'taskStats',
            'totalSum',
            'totalAvg',
            'totalCount',
            'from',
            'to'
        ));
    }

    public function allsubtask(Request $request)
    {
        $user = auth()->user();

            if (!$user) {
                return redirect()->route('login');
            }
        // So‘rovdan yil va oyni olish
        $year = $request->input('year');
        $month = $request->input('month');

        $from = null;
        $to = null;

        if ($year && $month) {
            $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->startOfMonth()->toDateString();
            $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->endOfMonth()->toDateString();
        }

        // Foydalanuvchilar kesimida umumiy statistikani olish
        $query = TaskAssignment::with('user');

        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }

        $assignments = $query->get();

        // Foydalanuvchiga qarab guruhlash
        $userStats = $assignments->groupBy('user_id')->map(function ($group,$userId) {
            return [
                'user' => ($group->first()->user->lastName ?? 'Nomaʼlum') . ' ' . ($group->first()->user->firstName ?? ''),
                'user_id' => $userId, // qaysi userga tegishli – kerak bo'ladi frontendda
                'sum' => $group->sum('rating'),
            ];
        })->sortByDesc('sum');

        return view('client.view.userstats', compact(
            'user',
            'userStats',
            'from',
            'to'
        ));
    }
public function editProfile()
{
    return view('client.view.profile', ['user' => auth()->user()]);
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'email' => 'required|email|unique:users,email,' . $user->id,
        'old_password' => 'required',
        'new_password' => 'nullable|min:8|confirmed',
    ]);

    if (!Hash::check($request->old_password, $user->password)) {
        return back()->withErrors(['old_password' => 'Eski parol noto‘g‘ri']);
    }

    $user->email = $request->email;

    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return back()->with('success', 'Profil yangilandi!');
}
}
