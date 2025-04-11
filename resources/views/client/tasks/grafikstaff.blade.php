@extends('layouts.admin')

@section('content')
    <h4><strong>{{ $user->firstName }} {{ $user->lastName }}</strong> ({{ $user->position }})</h4>

    <canvas id="kpiChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode(array_keys($userAssignments->toArray())) !!};
        const data = {!! json_encode(array_values($userAssignments->pluck('kpi')->toArray())) !!};

        const ctx = document.getElementById('kpiChart');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'KPI foizi',
                    data: data,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 86, 162, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                        display: true,
                        text: 'KPI (%)'
                    }
                    }
                }
            }
        });
    </script>
@endsection
