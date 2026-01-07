<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bap.css') }}">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- KONTEN --}}
    <div class="flex-1 flex flex-col">

        {{-- NAVBAR --}}
        @include('layouts.navbar')

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="{{ asset('assets/js/bap.js') }}"></script>
</body>
</html>
