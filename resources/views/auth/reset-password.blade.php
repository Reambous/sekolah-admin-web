<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Portal Sekolah</title>
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

        {{-- BAGIAN KIRI --}}
        <div class="lg:w-1/2 bg-gray-900 flex flex-col justify-center px-12 py-12 lg:px-24 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-gray-800 rounded-tl-full opacity-50"></div>
            <div class="relative z-10">
                <span
                    class="inline-block py-1 px-3 bg-blue-800 text-white text-xs font-bold uppercase tracking-widest mb-4">
                    New Credentials
                </span>
                <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight uppercase tracking-tighter mb-6">
                    Buat Password <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">Baru
                        Anda</span>
                </h1>
                <div class="w-20 h-2 bg-yellow-400 mb-6"></div>
                <p class="text-gray-400 text-lg font-medium leading-relaxed max-w-md border-l-4 border-gray-700 pl-4">
                    Pastikan password baru Anda kuat dan tidak mudah ditebak oleh orang lain.
                </p>
            </div>
        </div>

        {{-- BAGIAN KANAN --}}
        <div class="lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white relative">
            <div class="w-full max-w-md">

                <div class="mb-10">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-2">
                        Reset Password
                    </h2>
                    <p class="text-gray-500 text-sm font-medium">
                        Masukkan email dan password baru Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                            autofocus
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none placeholder-gray-400">
                        @error('email')
                            <p class="text-red-600 text-xs font-bold mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Password Baru
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none placeholder-gray-400">
                        @error('password')
                            <p class="text-red-600 text-xs font-bold mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none placeholder-gray-400">
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-900 text-white font-black py-4 px-4 uppercase tracking-widest hover:bg-blue-900 transition duration-300 shadow-lg transform hover:-translate-y-1 rounded-none border-b-4 border-black hover:border-blue-950">
                        Ubah Password &rarr;
                    </button>
                </form>

            </div>
        </div>

    </div>
</body>

</html>
