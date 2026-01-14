<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BAP Digital BPOM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- Font & Icon --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-900 p-4">
    
    {{-- CARD CONTAINER (Split Layout) --}}
    {{-- Saya ganti w-[1000px] jadi max-w-6xl (standar) agar lebih aman --}}
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        {{-- BAGIAN KIRI (DARK BLUE / BRANDING) --}}
        {{-- hidden md:flex artinya: di HP hilang, di Laptop muncul --}}
        <div class="hidden md:flex w-5/12 bg-slate-900 relative flex-col justify-between p-10 text-white">
            
            {{-- Background Gradient Effect --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-blue-900 opacity-90"></div>
            
            {{-- Hiasan Blur --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
                <div class="absolute -top-20 -left-20 w-60 h-60 bg-blue-500 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl"></div>
            </div>

            {{-- Content Atas --}}
            <div class="relative z-10 text-center mt-10">
                <h3 class="text-xl font-medium tracking-wide opacity-90">Welcome to</h3>
                <h1 class="text-4xl font-extrabold mt-2 tracking-tight">SIMBAP BPOM</h1>
                
                {{-- Logo Tengah --}}
                <div class="my-12 flex justify-center">
                   <div class="w-32 h-32 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 shadow-inner">
                        <i class="fa-solid fa-file-signature text-6xl text-blue-200"></i>
                   </div>
                </div>
            </div>

            {{-- Content Bawah --}}
            <div class="relative z-10">
                <p class="text-[10px] font-bold tracking-widest uppercase text-center text-blue-200 mb-4">
                    INTEGRATED WITH
                </p>
                <div class="flex justify-center gap-4 opacity-80 mb-8">
                    <div class="px-4 py-2 bg-white/10 rounded border border-white/10 text-xs font-semibold">
                        <i class="fa-solid fa-server mr-1"></i> BBPOM
                    </div>
                    <div class="px-4 py-2 bg-white/10 rounded border border-white/10 text-xs font-semibold">
                        <i class="fa-solid fa-shield-halved mr-1"></i> LawangSewu
                    </div>
                </div>
            
            </div>
        </div>

        {{-- BAGIAN KANAN (FORM LOGIN PUTIH) --}}
        <div class="w-full md:w-7/12 bg-white p-8 md:p-12 flex flex-col justify-center relative">
            
            {{-- Logo Mobile (Muncul cuma di HP) --}}
            <div class="md:hidden flex justify-center mb-6">
                 <i class="fa-solid fa-file-signature text-4xl text-blue-900"></i>
            </div>

            {{-- Logo BPOM Kecil --}}
            <div class="flex justify-center mb-6">
                <div class="flex flex-col items-center">
                    {{-- Ganti Icon Flask dengan Gambar Logo Asli --}}
                    <img src="{{ asset('assets/img/bpom.jpeg') }}" 
                         alt="Logo BPOM" 
                         class="h-16 mb-2 object-contain">
                </div>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-800">Login to Your Account</h2>
                <p class="text-slate-500 text-sm mt-2">Enter your credentials to access the system</p>
            </div>

            <form method="POST" action="/login" class="space-y-5">
                @csrf

                @error('login')
                    <div class="bg-red-50 text-red-600 text-sm p-3 rounded-lg border border-red-100 flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </div>
                @enderror

                {{-- 1. INPUT CARI NAMA --}}
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">
                        <i class="fa-solid fa-user text-slate-400 mr-1"></i> Nama Pegawai
                    </label>
                    <input type="text" id="input-nama" list="list-users" 
                           class="w-full border border-slate-300 rounded-lg px-4 py-3 text-slate-700 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition placeholder-slate-400"
                           placeholder="Ketik nama Anda..." autocomplete="off">
                    <datalist id="list-users">
                        @foreach($users as $user)
                            <option value="{{ $user->name }}" data-nip="{{ $user->nip }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- 2. INPUT NIP (Auto) --}}
                <div>
                    <div class="flex justify-between">
                        <label class="block text-slate-700 text-sm font-semibold mb-2">
                            <i class="fa-solid fa-id-card text-slate-400 mr-1"></i> NIP
                        </label>
                    </div>
                    <input name="nip" id="input-nip" 
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-600 font-mono cursor-not-allowed focus:outline-none"
                           placeholder="NIP akan muncul otomatis..." readonly required>
                </div>

                {{-- 3. INPUT PASSWORD --}}
                <div class="relative">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-slate-700 text-sm font-semibold">
                            <i class="fa-solid fa-lock text-slate-400 mr-1"></i> Password
                        </label>
                    </div>
                    <input type="password" name="password" id="password"
                           class="w-full border border-slate-300 rounded-lg px-4 py-3 text-slate-700 focus:ring-2 focus:ring-blue-900 focus:border-blue-900 outline-none transition placeholder-slate-400"
                           placeholder="Enter your password" required>
                    <button type="button" id="toggle-password" class="absolute right-4 top-[38px] text-slate-400 hover:text-slate-600 focus:outline-none">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>

                {{-- TOMBOL LOGIN --}}
                <button type="submit" 
                        class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-slate-800 hover:shadow-lg transform transition active:scale-[0.98] flex items-center justify-center gap-2 mt-4">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Login to SIMBAP
                </button>
            </form>

            <p class="text-center text-xs text-slate-400 mt-8">
                &copy; {{ date('Y') }} Badan POM. All rights reserved.
            </p>
        </div>
    </div>

    {{-- SCRIPT AUTO-FILL --}}
    <script>
        $(document).ready(function() {
            // 1. Auto Fill NIP
            $('#input-nama').on('input', function() {
                var val = $(this).val();
                var list = $('#list-users option');
                var match = list.filter(function() { return this.value === val; });

                if (match.length > 0) {
                    var nip = match.data('nip');
                    $('#input-nip').val(nip);
                    $('#password').focus();
                } else {
                    $('#input-nip').val('');
                }
            });

            // 2. TOGGLE PASSWORD VISIBILITY
            $('#toggle-password').on('click', function() {
                var passwordInput = $('#password');
                var icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>
</html>