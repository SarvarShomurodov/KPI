<nav class="col-md-2 d-none d-md-block sidebar">
    <div class="position-sticky">
        <h2 class="mt-5 mb-3">Xodimlar uchun</h2>
        <p><strong><i class="fas fa-user text-primary"></i> User: {{ auth()->user()->email  }}</strong></p>
        <p><strong><i class="fas fa-id-badge text-success"></i> User ID: {{ auth()->user()->id  }}</strong></p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="btn btn-primary mb-5" style="color: white"  type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
              {{ __('Чиқиш') }}
            </a>
        </form>
        @php
            $routes = [
                'client.index' => route('client.index'),
                'client.subtask' => route('client.subtask'),
                'client.allsubtask' => route('client.allsubtask'),
            ];
        @endphp
        <form action="{{ $routes[Route::currentRouteName()] ?? '#' }}" method="GET">
            <div class="mb-3">
                <label for="yearSelect" class="form-label">Yilni tanlang:</label>
                <select class="form-select" id="yearSelect" name="year">
                    <option value="">Barcha yillar</option> 
                    <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                    <option value="2023" {{ request('year') == '2023' ? 'selected' : '' }}>2023</option>
                </select>
            </div>
        
            <div class="mb-3">
                <label for="monthSelect" class="form-label">Oyni tanlang:</label>
                <select class="form-select" id="monthSelect" name="month">
                    <option value="">Barcha oylar</option> 
                    @foreach ([
                        '01' => 'Yanvar', '02' => 'Fevral', '03' => 'Mart', '04' => 'Aprel',
                        '05' => 'May', '06' => 'Iyun', '07' => 'Iyul', '08' => 'Avgust',
                        '09' => 'Sentabr', '10' => 'Oktabr', '11' => 'Noyabr', '12' => 'Dekabr'
                    ] as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <button type="submit" class="btn btn-primary">Filtrlash</button>
        </form>
    </div>
</nav>