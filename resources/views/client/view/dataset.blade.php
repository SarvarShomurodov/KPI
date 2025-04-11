@extends('layouts.client')

@section('content')
@foreach($taskStats as $taskName => $data)
@php
    $uniqueId = Str::slug($taskName) . '_' . uniqid();
@endphp

<div class="kpi-container" onclick="toggleDropdown('{{ $uniqueId }}')">
    <div class="kpi-select-text">
        {{ $taskName }} {{ $data['sum'] }}
    </div>
    <div class="kpi-select-icon" id="arrowIcon_{{ $uniqueId }}">
        <svg width="16" height="16" viewBox="0 0 24 24">
            <path d="M7 10l5 5 5-5H7z"/>
        </svg>
    </div>
</div>

<!-- Dropdown -->
<div class="kpi-dropdown mb-3" id="kpiDropdown_{{ $uniqueId }}" style="display: none;">
    <div class="summary-box">
   
    </div>

    <div class="switch-wrapper">
        <label class="switch">
            <input type="checkbox" id="tableToggle_{{ $uniqueId }}" onchange="toggleTable('{{ $uniqueId }}')">
            <span class="slider"></span>
        </label>
        <span class="switch-label">Data sifatida ko‘rish</span> 
    </div>

    <div class="table-container" id="dataTable_{{ $uniqueId }}" style="display: none;">
        <table id="myTable2">
            <thead>
                <tr>
                    <th></th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Rating</th>
                    <th>Subtask</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['assignments'] as $index => $assignment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $assignment->comment ?? 'Comment yo‘q' }}</td>
                        <td>{{ $assignment->addDate }}</td>
                        <td>{{ $assignment->rating }}</td>
                        <td>{{ $assignment->subtask->title ?? 'Nomaʼlum' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach


<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById('kpiDropdown_' + id);
        const arrow = document.getElementById('arrowIcon_' + id).querySelector('svg');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        arrow.classList.toggle('rotate');
    }

    function toggleTable(id) {
        const checkbox = document.getElementById('tableToggle_' + id);
        const table = document.getElementById('dataTable_' + id);
        table.style.display = checkbox.checked ? 'block' : 'none';
    }
</script>
@endsection
