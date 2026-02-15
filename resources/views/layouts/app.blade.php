<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    {{-- PENTING: Baris di bawah ini mencegah klik 'lari' dari gambarnya --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        {{-- Navigasi Atas --}}
        @include('layouts.navigation')

        {{-- Header Halaman (Jika ada)
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif --}}
        <h3 class="text-center text-xl font-bold text-gray-800 py-4">SELAMAT DATANG DI PORTAL SEKOLAH</h3>

        {{-- Konten Utama --}}
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
