<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'F1 Database')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 56px;
        }
        .pagination{text-align:center;margin-top:20px;margin-bottom:20px}.pagination .page-link{display:inline-block;padding:.5rem .75rem;margin:0 2px;color:#007bff;background-color:#fff;border:1px solid #dee2e6;border-radius:.25rem}.pagination .page-item.disabled .page-link{color:#6c757d;pointer-events:none;background-color:#fff;border-color:#dee2e6}.pagination .page-item.active .page-link{z-index:1;color:#fff;background-color:#007bff;border-color:#007bff}.pagination svg{width:20px;height:20px;vertical-align:middle;margin-bottom:2px}.pagination li{display:inline-block;margin:0 5px;font-size:1rem}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">F1 Database</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('races.index') }}">Races</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('drivers.index') }}">Drivers</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('constructors.index') }}">Constructors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('circuits.index') }}">Circuits</a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
