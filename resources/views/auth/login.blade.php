<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Sistem Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen flex">

        <div class="hidden lg:flex w-1/2 bg-blue-900 justify-center items-center relative overflow-hidden">
            <div
                class="absolute bg-blue-800 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -top-10 -left-10">
            </div>
            <div
                class="absolute bg-indigo-800 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl opacity-50 bottom-10 right-10">
            </div>

            <div class="relative z-10 text-center px-10">
                <h2 class="text-4xl font-bold text-white mb-4">Sistem Informasi Sekolah</h2>
                <p class="text-blue-200 text-lg leading-relaxed">
                    Kelola Kegiatan, Perizinan, dan Jurnal Guru dalam satu pintu yang terintegrasi.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-100">

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">Selamat Datang</h1>
                    <p class="text-gray-500 mt-2 text-sm">Silakan masukkan akun Anda untuk masuk.</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Sekolah</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-sm transition outline-none"
                            placeholder="nama@sekolah.sch.id">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-sm transition outline-none"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-800 font-bold"
                                href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-900 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-800 transition duration-300 shadow-md transform hover:-translate-y-0.5">
                        Masuk Sistem
                    </button>

                </form>

                <div class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Sistem Informasi Sekolah. Versi 1.0
                </div>
            </div>
        </div>

    </div>
</body>

</html>
