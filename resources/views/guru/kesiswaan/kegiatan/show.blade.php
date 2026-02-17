<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA --}}
            <div class="bg-white border-2 border-gray-200 p-8 md:p-10 shadow-sm relative">

                {{-- 1. HEADER BAGIAN ATAS (JUDUL & META) --}}
                <div class="border-b-4 border-gray-900 pb-6 mb-8">
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-blue-900 text-white text-xs font-bold px-3 py-1 uppercase tracking-widest">
                            Laporan Kegiatan Kesiswaan
                        </span>

                        {{-- Tanggal Besar di Pojok Kanan --}}
                        <div class="text-right">
                            <div class="text-2xl font-black text-gray-900 leading-none">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d') }}
                            </div>
                            <div class="text-xs font-bold text-gray-500 uppercase">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('M Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Judul Kegiatan --}}
                    <h1
                        class="text-3xl md:text-4xl font-black text-gray-900 mb-3 uppercase leading-tight tracking-tight break-words">
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>

                    {{-- Meta Data Bar --}}
                    <div
                        class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-500 uppercase tracking-wide">
                        <span class="flex items-center gap-1">
                            <span class="text-blue-700">👤</span> {{ $kegiatan->nama_guru }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-1">
                            <span class="text-blue-700">🕒</span> Input:
                            {{ \Carbon\Carbon::parse($kegiatan->created_at)->format('H:i') }} WIB
                        </span>
                    </div>
                </div>

                {{-- 2. ISI KONTEN / LAPORAN --}}
                <div class="mb-10">
                    <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                        <span class="w-2 h-6 bg-yellow-400 inline-block"></span>
                        Detail Keterangan
                    </h3>

                    {{-- Box Konten --}}
                    <div
                        class="bg-gray-50 p-6 border border-gray-200 text-gray-800 text-base md:text-lg leading-relaxed whitespace-pre-line text-justify font-serif break-words">
                        {{ $kegiatan->refleksi ?? 'Tidak ada keterangan detail yang dilampirkan.' }}
                    </div>
                </div>

                {{-- 3. FOOTER / TOMBOL AKSI --}}
                <div
                    class="flex flex-col md:flex-row justify-between items-center pt-6 border-t-2 border-gray-100 gap-4">

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('kesiswaan.kegiatan.index') }}"
                        class="text-xs font-bold text-gray-900 uppercase tracking-widest hover:text-blue-700 hover:underline transition">
                        &larr; Kembali ke Daftar
                    </a>

                    {{-- Tombol Edit/Hapus --}}
                    <div class="flex gap-3 w-full md:w-auto">
                        @if (Auth::user()->role == 'admin' || $kegiatan->user_id == Auth::id())
                            <a href="{{ route('kesiswaan.kegiatan.edit', $kegiatan->id) }}"
                                class="flex-1 md:flex-none text-center bg-yellow-400 text-black px-5 py-2 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm">
                                Edit Data
                            </a>

                            <form action="{{ route('kesiswaan.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                class="flex-1 md:flex-none">
                                @csrf @method('DELETE')
                                <button
                                    class="w-full md:w-auto bg-red-600 text-white px-5 py-2 text-xs font-black uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
