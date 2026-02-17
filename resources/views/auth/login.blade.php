<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Portal Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- BAGIAN KIRI: BRANDING (HITAM/NAVY TEGAS) --}}
        <div class="lg:w-1/2 bg-gray-900 flex flex-col justify-center px-12 py-12 lg:px-24 relative overflow-hidden">
            {{-- Aksen Dekorasi --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gray-800 rounded-tl-full opacity-50"></div>

            <div class="relative z-10">
                <span
                    class="inline-block py-1 px-3 bg-blue-800 text-white text-xs font-bold uppercase tracking-widest mb-4">
                    Official Portal
                </span>
                <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight uppercase tracking-tighter mb-6">
                    Sistem <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">Informasi</span>
                    <br>
                    Sekolah
                </h1>
                <div class="w-20 h-2 bg-yellow-400 mb-6"></div>
                <p class="text-gray-400 text-lg font-medium leading-relaxed max-w-md border-l-4 border-gray-700 pl-4">
                    Platform manajemen terpadu untuk administrasi kegiatan, perizinan, dan jurnal refleksi guru.
                </p>
            </div>
        </div>

        {{-- BAGIAN KANAN: FORM LOGIN (PUTIH BERSIH) --}}
        <div class="lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white relative">
            <div class="w-full max-w-md">

                {{-- Header Form --}}
                <div class="mb-10">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-2">
                        Silakan Masuk
                    </h2>
                    <p class="text-gray-500 text-sm font-medium">
                        Masukkan kredensial akun guru atau staf Anda.
                    </p>
                </div>

                @if (session('status'))
                    <div
                        class="mb-6 bg-green-50 border-l-4 border-green-600 p-4 text-green-800 text-sm font-bold shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Email Sekolah
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none placeholder-gray-400"
                            placeholder="nama@sekolah.sch.id">
                        @error('email')
                            <p class="text-red-600 text-xs font-bold mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Password
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-600 text-xs font-bold mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded-none border-2 border-gray-400 text-gray-900 shadow-sm focus:ring-gray-900 w-4 h-4">
                            <span
                                class="ml-2 text-xs font-bold text-gray-500 uppercase group-hover:text-gray-900 transition">Ingat
                                Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-blue-700 hover:text-black uppercase border-b-2 border-transparent hover:border-black transition"
                                href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    {{-- Tombol Login --}}
                    <button type="submit"
                        class="w-full bg-gray-900 text-white font-black py-4 px-4 uppercase tracking-widest hover:bg-blue-900 transition duration-300 shadow-lg transform hover:-translate-y-1 rounded-none border-b-4 border-black hover:border-blue-950">
                        Masuk Sistem &rarr;
                    </button>
                </form>

                <div class="mt-10 pt-6 border-t-2 border-gray-100 text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                        &copy; {{ date('Y') }} Sistem Informasi Sekolah. Versi 1.0
                    </p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
