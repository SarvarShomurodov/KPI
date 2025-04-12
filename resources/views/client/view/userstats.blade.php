@extends('layouts.client')

@section('content')
<div class="container py-4">
    {{-- <h2 class="mb-4 text-center">Foydalanuvchilar umumiy ballari ({{ $from }} - {{ $to }})</h2> --}}

    <div class="card p-4 shadow">
        <canvas id="userChart" height="400" ></canvas>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('userChart').getContext('2d');

    const data = {
        labels: {!! json_encode($userStats->pluck('user')) !!},
        datasets: [{
            label: 'Umumiy ball',
            data: {!! json_encode($userStats->pluck('sum')) !!},
            backgroundColor: {!! json_encode($userStats->map(function ($item) use ($user) {
                return $item['user_id'] == $user->id ? 'red' : '#3B82F6';
            })->values()) !!},
            // borderRadius: 6,
            barThickness: 40,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            indexAxis: 'y', // Gorizontal grafik
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Reyting',
                        color: '#FFFFFF'
                    },
                    ticks: {
                        precision: 0,
                        color: '#FFFFFF'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'To`liq ism',
                        color: '#FFFFFF'
                    },
                    ticks: {
                        color: '#FFFFFF'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + " ball";
                        }
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
</script>
@endsection
