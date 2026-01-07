<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

<form method="POST" action="/login"
      class="bg-white p-6 rounded-lg shadow w-80">
    @csrf

    <h1 class="text-lg font-semibold mb-4 text-center">
        Login
    </h1>

    @error('login')
        <div class="text-red-600 text-sm mb-2">
            {{ $message }}
        </div>
    @enderror

    <input name="nip"
           placeholder="NIP"
           class="w-full border px-3 py-2 rounded mb-3">

    <input type="password"
           name="password"
           placeholder="Password"
           class="w-full border px-3 py-2 rounded mb-4">

    <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
        Masuk
    </button>
</form>

</body>
</html>
