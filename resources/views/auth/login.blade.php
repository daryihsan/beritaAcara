<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMBAP BPOM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('auth.custom_css')
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    {{-- 1. BACKGROUND IMAGE (BLURRY) --}}
    <div class="absolute inset-0 z-0">
        {{-- Placeholder warna gelap --}}
        <div class="absolute inset-0 bg-slate-900"></div> 
        
        {{-- Gambar Background --}}
        <img src="{{ asset('assets/img/bg_login.png') }}" 
             alt="Background" 
             class="w-full h-full object-cover blur-[6px] scale-105 brightness-90 opacity-100">
        
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-slate-900/50"></div>
    </div>

    {{-- 2. CARD CONTAINER --}}
    <div class="w-full max-w-6xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[300px] relative z-10 animate-fade-in-up">
        
        {{-- BAGIAN KIRI: DARK GLOOM GRADIENT --}}
        {{-- Flex-col agar Footer bisa ditaruh di bawah (mt-auto) --}}
        <div class="hidden md:flex w-5/12 relative flex-col p-8 text-white overflow-hidden">
            
            {{-- Background Gradient --}}
            <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-black"></div>
            
            {{-- Efek Glow Background --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-80 h-80 bg-blue-900/50 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-indigo-900/40 rounded-full blur-[60px]"></div>
            </div>

            <div class="flex items-center gap-3 mb-6 opacity-80">
                <div class="h-px w-8 bg-blue-400 box-glow-weak"></div>
                <span class="text-xs font-bold tracking-[0.2em] uppercase text-blue-200 text-glow-weak">Official Portal</span>
            </div>
            
            {{-- CONTENT TENGAH (LOGO & PENJELASAN) --}}
            {{-- flex-1 + justify-center + items-center membuat isinya pas di tengah vertikal & horizontal --}}
            <div class="relative z-10 flex flex-col flex-1 justify-center items-center text-center">
                
                {{-- Logo Visual --}}
                <div class="relative w-72 flex items-center justify-center mb-8">
                    
                    {{-- EFEK CAHAYA (GLOW) PERMANEN --}}
                    <div class="absolute inset-0 bg-blue-400/20 blur-2xl rounded-full opacity-60 pointer-events-none"></div>

                    {{-- GAMBAR LOGO --}}
                    <img src="{{ asset('assets/img/SIMBAP.png') }}" 
                         alt="Logo Utama" 
                         class="relative z-10 w-full h-auto object-contain drop-shadow-[0_0_25px_rgba(255,255,255,0.5)]">
                </div>
                
                {{-- Penjelasan --}}
                <p class="text-slate-300 font-semibold text-sm leading-relaxed max-w-xs mx-auto glow-subtle">
                    Sistem Informasi Manajemen<br>
                    Berita Acara Pemeriksaan<br> 
                    Terintegrasi Badan POM
                </p>
            </div>

            {{-- FOOTER KIRI (INTEGRATED WITH) --}}
            {{-- mt-auto memaksanya turun ke paling bawah --}}
            <div class="relative z-10 mt-auto pt-8">
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-500 mb-4 text-center">Integrated With</p>
                <div class="flex justify-center gap-3">
                    <div class="px-4 py-2 bg-white/5 hover:bg-white/10 transition rounded-lg border border-white/5 backdrop-blur-sm text-xs font-medium text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-server text-blue-400"></i> BBPOM
                    </div>
                    <div class="px-4 py-2 bg-white/5 hover:bg-white/10 transition rounded-lg border border-white/5 backdrop-blur-sm text-xs font-medium text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-indigo-400"></i> LawangSewu
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: GLASSMORPHISM --}}
        <div class="w-full md:w-7/12 p-6 md:p-8 flex flex-col justify-center relative overflow-hidden md:rounded-r-[30px] border border-l-0 border-white/20 shadow-[inset_0px_0px_30px_rgba(255,255,255,0.3)]">
            
            {{-- Background Glass Effect --}}
            <div class="absolute inset-0 bg-white/50 backdrop-blur-[20px]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/40 via-transparent to-white/10 pointer-events-none"></div>
            {{-- Border Kiri Halus --}}
            <div class="absolute inset-y-0 left-0 w-px bg-gradient-to-b from-transparent via-white/70 to-transparent hidden md:block z-20"></div>

            {{-- Content Form --}}
            <div class="relative z-30 w-full max-w-md mx-auto">

                {{-- Header Kanan --}}
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/bpom.png') }}" alt="Logo BPOM" class="h-14 mx-auto mb-4 drop-shadow-md">
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight drop-shadow-sm">Selamat Datang</h2>
                    <p class="text-slate-600 text-sm mt-2 font-medium">Silakan masuk untuk mengakses dashboard</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="/login" class="space-y-5 p-2">
                    @csrf

                    @error('login')
                        <div class="bg-red-50/90 backdrop-blur-sm text-red-600 text-sm p-4 rounded-xl border border-red-200/50 flex items-center gap-3 shadow-sm animate-pulse">
                            <i class="fa-solid fa-circle-exclamation text-lg"></i> {{ $message }}
                        </div>
                    @enderror

                    {{-- 1. INPUT CARI NAMA --}}
                    <div class="group">
                        <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2 ml-1 text-shadow-sm">
                            <i class="fa-solid fa-user text-slate-400 mr-1"></i>  Nama Pegawai
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <i class="fa-solid fa-user text-slate-400 group-focus-within:text-blue-700 transition-colors"></i>
                            </div>
                            <input type="text" id="input-nama" list="list-users" 
                                   class="w-full bg-white/60 border border-white/40 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 block w-full pl-11 p-3.5 transition-all shadow-sm focus:bg-white/90 placeholder-slate-500 backdrop-blur-sm" 
                                   placeholder="Ketik nama Anda..." autocomplete="off">
                        </div>
                        <datalist id="list-users">
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" data-nip="{{ $user->nip }}">
                            @endforeach
                        </datalist>
                    </div>

                    {{-- 2. INPUT NIP (Auto) --}}
                    <div class="group">
                        <div class="flex justify-between mb-2 ml-1">
                            <label class="text-slate-700 text-xs font-bold uppercase tracking-wider text-shadow-sm">
                                <i class="fa-solid fa-id-card text-slate-400 mr-1"></i>  Nomor Induk Pegawai
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <i class="fa-solid fa-id-badge text-slate-500"></i>
                            </div>
                            <input name="nip" id="input-nip" 
                                   class="w-full bg-slate-200/50 border border-white/30 text-slate-600 text-sm rounded-xl block w-full pl-11 p-3.5 cursor-not-allowed backdrop-blur-sm" 
                                   placeholder="NIP otomatis terisi..." readonly required>
                        </div>
                    </div>

                    {{-- 3. INPUT PASSWORD --}}
                    <div class="group">
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label class="text-slate-700 text-xs font-bold uppercase tracking-wider text-shadow-sm">
                                <i class="fa-solid fa-lock text-slate-400 mr-1"></i>  Password
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <i class="fa-solid fa-lock text-slate-500 group-focus-within:text-blue-700 transition-colors"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                   class="w-full bg-white/60 border border-white/40 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 block w-full pl-11 pr-12 p-3.5 transition-all shadow-sm focus:bg-white/90 placeholder-slate-500 backdrop-blur-sm" 
                                   placeholder="Masukkan password..." required>
                            
                            {{-- Toggle Button --}}
                            <button type="button" id="toggle-password" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-700 transition focus:outline-none">
                                <i class="fa-regular fa-eye text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- TOMBOL LOGIN --}}
                    <button type="submit" 
                            class="w-full text-white bg-slate-900/90 hover:bg-slate-800 backdrop-blur-md focus:ring-4 focus:ring-slate-300/50 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg hover:shadow-slate-900/30 transform transition active:scale-[0.98] flex items-center justify-center gap-3 group border border-white/10">
                        <span>Masuk Aplikasi</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-600 font-medium text-shadow-sm">
                        &copy; {{ date('Y') }} Badan Pengawas Obat dan Makanan
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('auth.custom_js')

</body>
</html>