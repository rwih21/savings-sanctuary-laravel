<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Savings Sanctuary')</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="header">
            <img class="logo" src="{{ asset('img/12.svg') }}" alt="">
            <h1>Savings Sanctuary</h1>
        </div>
    </nav>

    @yield('body')
</body>
</html>