<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - Portal Sekolah</title>
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
            <div class="relative z-10">
                <span
                    class="inline-block py-1 px-3 bg-blue-800 text-white text-xs font-bold uppercase tracking-widest mb-4">
                    Account Activation
                </span>
                <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight uppercase tracking-tighter mb-6">
                    Verifikasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">Email
                        Anda</span>
                </h1>
                <div class="w-20 h-2 bg-yellow-400 mb-6"></div>
            </div>
        </div>

        {{-- BAGIAN KANAN --}}
        <div class="lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white relative">
            <div class="w-full max-w-md">

                <div class="mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-4">
                        Cek Email Masuk
                    </h2>
                    <p class="text-gray-600 text-sm font-medium leading-relaxed mb-4 text-justify">
                        Terima kasih telah mendaftar. Sebelum memulai, mohon verifikasi email Anda dengan mengklik
                        tautan yang baru saja kami kirimkan ke email Anda.
                    </p>
                    <p class="text-gray-500 text-xs italic">
                        Jika Anda tidak menerima email, kami dengan senang hati akan mengirimkannya lagi.
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div
                        class="mb-6 bg-green-50 border-l-4 border-green-600 p-4 text-green-800 text-sm font-bold shadow-sm">
                        Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                    </div>
                @endif

                <div class="flex flex-col gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-gray-900 text-white font-black py-4 px-4 uppercase tracking-widest hover:bg-blue-900 transition duration-300 shadow-lg transform hover:-translate-y-1 rounded-none border-b-4 border-black hover:border-blue-950">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-center text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-red-600 hover:underline transition">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</body>

</html>
