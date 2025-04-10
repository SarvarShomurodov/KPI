@extends('layouts.admin')

@section('content')
    <h4 class="mb-4">
        @if ($from && $to)
            <br><small class="text-muted">({{ $from }} dan {{ $to }} gacha natijalari)</small>
        @endif
    </h4>

    @if ($assignments->isEmpty())
        <div class="alert alert-warning">Bu topshiriq bo‘yicha ma'lumot topilmadi.</div>
    @else
        <table class="table table-bordered" id="myTable2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subtask nomi</th>
                    <th>Ball (Rating)</th>
                    <th>Izoh</th>
                    <th>Sana</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignments as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->subtask->title }}</td>
                        <td>{{ $item->rating }}</td>
                        <td>{{ $item->comment }}</td>
                        <td>{{ $item->addDate }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Ortga</a>
@endsection
