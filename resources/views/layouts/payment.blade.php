<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href={{ asset('assets/images/apple-logo.png') }}>

    <title>@yield('title', config('app.name', 'Prak'))</title>

    <!-- Global CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @stack('styles')
</head>

<body class="d-flex" style="min-height: 100vh; overflow: hidden;">

    {{-- Sidebar component (shared) --}}
    <x-sidebarcusto />

    {{-- Main content wrapper --}}
    <main class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow: auto;">
        {{-- Header component --}}

        {{-- Page content container --}}
        <div class="flex-grow-1">
            <div class="container-fluid p-3">
                @yield('content')
            </div>
        </div>


    </main>

    <!-- Global JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    @stack('scripts')


</body>

</html>
