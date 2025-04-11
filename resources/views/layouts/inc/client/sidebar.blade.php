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
        <form action="{{ route('client.index') }}" method="GET">
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
                    <option value="01" {{ request('month') == '01' ? 'selected' : '' }}>Yanvar</option>
                    <option value="02" {{ request('month') == '02' ? 'selected' : '' }}>Fevral</option>
                    <option value="03" {{ request('month') == '03' ? 'selected' : '' }}>Mart</option>
                    <option value="04" {{ request('month') == '04' ? 'selected' : '' }}>Aprel</option>
                    <option value="05" {{ request('month') == '05' ? 'selected' : '' }}>May</option>
                    <option value="06" {{ request('month') == '06' ? 'selected' : '' }}>Iyun</option>
                    <option value="07" {{ request('month') == '07' ? 'selected' : '' }}>Iyul</option>
                    <option value="08" {{ request('month') == '08' ? 'selected' : '' }}>Avgust</option>
                    <option value="09" {{ request('month') == '09' ? 'selected' : '' }}>Sentabr</option>
                    <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Oktabr</option>
                    <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>Noyabr</option>
                    <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Dekabr</option>
                </select>
            </div>
        
            <button type="submit" class="btn btn-primary">Filtrlash</button>
        </form>
        
        
        
    </div>
</nav>