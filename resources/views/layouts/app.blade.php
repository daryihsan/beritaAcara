<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi BBPOM - Lawangsewu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {background-color: #f4f6f9; padding: 20px;}
        .panel-heading {font-weight: bold; font-size: 20px;}
        .tab-content {background: #fff; border: 1px solid #ddd; border-top: none; padding: 20px;}
        .petugas-row { background: #f9f9f9; padding: 15px; border: 1px solid #e5e5e5; margin-bottom: 10px; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    @stack('scripts')
</body>
</html>