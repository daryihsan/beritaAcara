
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="flex min-h-screen">
        <div class="flex min-h-screen w-screen overflow-hidden">
            {{-- SIDEBAR --}}
            @include('layouts.sidebar')

            {{-- KONTEN --}}
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden"> {{-- NAVBAR --}}
                @include('layouts.navbar')

                {{-- PAGE CONTENT --}}
                <main class="flex-1 p-8 py-4 overflow-y-auto"> @yield('content')
                </main>

            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        @stack('scripts')

</body>

</html>