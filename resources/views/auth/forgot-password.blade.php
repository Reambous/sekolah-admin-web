<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Portal Sekolah</title>
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

        {{-- BAGIAN KIRI: BRANDING --}}
        <div class="lg:w-1/2 bg-gray-900 flex flex-col justify-center px-12 py-12 lg:px-24 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gray-800 rounded-tl-full opacity-50"></div>
            <div class="relative z-10">
                <span
                    class="inline-block py-1 px-3 bg-blue-800 text-white text-xs font-bold uppercase tracking-widest mb-4">
                    Security Center
                </span>
                <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight uppercase tracking-tighter mb-6">
                    Pemulihan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">Akses
                        Akun</span>
                </h1>
                <div class="w-20 h-2 bg-yellow-400 mb-6"></div>
                <p class="text-gray-400 text-lg font-medium leading-relaxed max-w-md border-l-4 border-gray-700 pl-4">
                    Kami akan mengirimkan tautan aman ke email Anda untuk mengatur ulang kata sandi.
                </p>
            </div>
        </div>

        {{-- BAGIAN KANAN: FORM --}}
        <div class="lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white relative">
            <div class="w-full max-w-md">

                <div class="mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-2">
                        Lupa Password?
                    </h2>
                    <p class="text-gray-500 text-sm font-medium">
                        Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password.
                    </p>
                </div>

                {{-- Notifikasi Sukses --}}
                @if (session('status'))
                    <div
                        class="mb-6 bg-green-50 border-l-4 border-green-600 p-4 text-green-800 text-sm font-bold shadow-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Email Terdaftar
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none placeholder-gray-400"
                            placeholder="nama@gmail.com">
                        @error('email')
                            <p class="text-red-600 text-xs font-bold mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-4">
                        <button type="submit"
                            class="w-full bg-gray-900 text-white font-black py-4 px-4 uppercase tracking-widest hover:bg-blue-900 transition duration-300 shadow-lg transform hover:-translate-y-1 rounded-none border-b-4 border-black hover:border-blue-950">
                            Kirim Link Reset
                        </button>

                        <a href="{{ route('login') }}"
                            class="text-center text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                            &larr; Kembali ke Login
                        </a>
                    </div>
                </form>

                <div class="mt-10 pt-6 border-t-2 border-gray-100 text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                        &copy; {{ date('Y') }} Sistem Informasi Sekolah.
                    </p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
