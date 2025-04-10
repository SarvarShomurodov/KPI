@extends('layouts.admin')

@section('content')
    <h4 class="mb-4">SWOD Tahliliy ma'lumotlar jadvali</h4>

    <form method="GET" action="{{ route('task.swod') }}" class="row justify-content-end mb-4">
        <div class="col-md-2 mb-2">
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-2 mb-2">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-auto mb-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    @if(request('from_date') && request('to_date'))
        <h5 class="mb-4">Natijalar: <b>{{ request('from_date') }}</b> dan <b>{{ request('to_date') }}</b> gacha</h5>
    @endif

    <table class="table table-bordered" id="myTable2">
        <thead>
            <tr>
                <th>#</th>
                <th>FISH</th>
                @foreach ($tasks as $task)
                    <th>{{ $task->taskName }}</th>
                @endforeach
                <th>Bonus</th>
                <th>Umumiy baxosi</th>
                <th>KPI</th>
            </tr>
        </thead>
        <tbody>
            @php
                $maxTotalRating = 0;
                foreach ($staffUsers as $user) {
                    $roles = $user->getRoleNames();
                    if ($roles->contains('Super Admin') || $roles->contains('Admin')) continue;

                    $data = $assignments[$user->id] ?? ['total_rating' => 0];
                    if ($data['total_rating'] > $maxTotalRating) {
                        $maxTotalRating = $data['total_rating'];
                    }
                }
                $i = 1;
            @endphp

            @foreach ($staffUsers as $staffUser)
                @php
                    $roles = $staffUser->getRoleNames();
                    if ($roles->contains('Super Admin') || $roles->contains('Admin')) continue;

                    $userData = $assignments[$staffUser->id] ?? ['total_rating' => 0, 'tasks' => collect()];
                    $taskRatings = [];

                    foreach ($userData['tasks'] as $item) {
                        $taskRatings[$item['task_id']] = ($taskRatings[$item['task_id']] ?? 0) + $item['rating'];
                    }

                    $bonus = 0;
                    $totalWithBonus = $userData['total_rating'] + $bonus;
                    $kpi = $maxTotalRating > 0 ? round(($totalWithBonus / $maxTotalRating) * 100, 2) : 0;
                @endphp

                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        <a href="{{ route('client-task.show', ['user' => $staffUser->id, 'from_date' => request('from_date'), 'to_date' => request('to_date')]) }}">
                            {{ $staffUser->firstName }} {{ $staffUser->lastName }}
                        </a>
                    </td>
                    @foreach ($tasks as $task)
                        <td>
                            <a href="{{ route('client-task.task-details', [
                                'user' => $staffUser->id,
                                'task' => $task->id,
                                'from_date' => request('from_date'),
                                'to_date' => request('to_date'),
                            ]) }}">
                                {{ $taskRatings[$task->id] ?? 0 }}
                            </a>
                        </td>
                    @endforeach
                    <td>{{ $bonus }}</td>
                    <td>{{ $totalWithBonus }}</td>
                    <td>{{ $kpi }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
