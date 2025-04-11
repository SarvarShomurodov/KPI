<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Design</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            @include('layouts.inc.client.sidebar')
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 content">
                <!-- Top Menu -->
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('client.index') }}"
                    class="btn btn-outline-light {{ request()->routeIs('client.index') ? 'active' : '' }}">
                     DATASET
                 </a>
                 
                 <a href="{{ route('client.subtask') }}"
                    class="btn btn-outline-light {{ request()->routeIs('client.subtask') ? 'active' : '' }}">
                     ALL DATA FOR EACH SUBTASK
                 </a>                    <a href="#" class="btn btn-outline-light">REYTING</a>
                    <a href="#" class="btn btn-outline-light">PROFIL MA'LUMOTLARI</a>
                </div>
                <hr />
                @yield('content')
            </main>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
        