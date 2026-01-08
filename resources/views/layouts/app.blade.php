
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bap.css') }}">
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
        <style>
            /* Agar tampilan sorting DataTables tidak merusak desain Tailwind kamu */

            html,
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
            }

            ttable.dataTable thead .sorting_asc:after {
                content: " ▲" !important;
                opacity: 0.7;
            }

            table.dataTable thead .sorting_desc:after {
                content: " ▼" !important;
                opacity: 0.7;
            }

            .dataTables_wrapper {
                border: 1px solid #d1d5db;
                /* border-slate-300 */
                border-radius: 12px;
                padding: 20px;
                background-color: white
            }

            .dataTables_length select,
            .dataTables_filter input {
                border: 1px solid #d1d5db !important;
                border-radius: 8px !important;
                padding: 6px 12px !important;
                margin-bottom: 15px !important;
                font-weight: 600;
            }

            table.dataTable {
                border-collapse: collapse !important;
                margin-top: 15px !important;
                border: 1px solid #d1d5db;
            }

            table.dataTable thead th {
                border-bottom: 1px solid #e5e7eb !important;
            }

            .dataTables_info,
            .dataTables_paginate {
                margin-top: 20px !important;
                font-size: 14px;
            }

            .paginate_button {
                padding: 5px 10px !important;
                margin: 0 2px;
                border-radius: 5px;
                cursor: pointer;
            }

            .paginate_button.current {
                background-color: #2563eb !important;
                color: white !important;
                border-radius: 6px;
            }

            #tableBap tbody td {
                padding: 10px !important;
                line-height: 1.6 !important;
                vertical-align: top !important;
                border: 1px solid #e5e7eb !important;
            }

            #tableBap thead th {
                padding: 20px 10px !important;
                background-color: #ffffff;
                /* slate-50 */
                border: 1px solid #e5e7eb !important;
            }

            #tableBap tbody tr:nth-child(even) {
                background-color: #ffffff !important;
            }

            /* Baris Ganjil (Odd) - Lebih Gelap/Abu-abu Halus */
            #tableBap tbody tr:nth-child(odd) {
                background-color: #f1f5f9 !important;
                /* Warna slate-100 */
            }

            /* Efek Hover - Agar baris yang ditunjuk berubah warna */
            #tableBap tbody tr:hover {
                background-color: #e2e8f0 !important;
                /* Warna slate-200 */
                transition: background-color 0.2s;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .dataTables_wrapper .dataTables_length {
                float: left;
            }

            .dataTables_wrapper .dataTables_filter {
                float: right;
                text-align: right;
            }

            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_length label {
                margin-bottom: 0 !important;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                display: inline-flex;
                align-items: center;
            }

            .dataTables_wrapper .dataTables_info {
                float: left;
            }

            .dataTables_wrapper .dataTables_paginate {
                float: right;
            }

            .dataTables_wrapper::after {
                content: "";
                display: block;
                clear: both;
            }
            .nav-tabs > li.tab-error > a {
        color: #f43f5e !important; 
        font-weight: bold;
    }

    /* Saat Tab Error sedang AKTIF (Background Merah, Tulisan Putih) */
    .nav-tabs > li.tab-error.active > a {
        background-color: #f43f5e !important;
        color: #ffffff !important;
        border: 1px solid #f43f5e !important;
    }

    /* Indikator Tab Sukses (Hijau) */
    .nav-tabs > li.tab-success > a {
        color: #10b981 !important;
        font-weight: bold;
    }

    /* Transisi halus saat berubah warna */
    .nav-tabs > li > a {
        transition: all 0.2s ease-in-out;
    }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        @stack('scripts')

        <script src="{{ asset('assets/js/bap.js') }}"></script>

</body>

</html>