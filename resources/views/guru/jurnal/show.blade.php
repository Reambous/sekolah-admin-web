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
                        <span
                            class="bg-blue-900 text-white text-xs font-bold px-3 py-1 uppercase tracking-widest break-words max-w-[70%]">
                            {{ $jurnal->kategori }}
                        </span>

                        {{-- Tanggal Besar di Pojok Kanan --}}
                        <div class="text-right">
                            <div class="text-2xl font-black text-gray-900 leading-none">
                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d') }}
                            </div>
                            <div class="text-xs font-bold text-gray-500 uppercase">
                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('M Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Judul Refleksi --}}
                    <h1
                        class="text-3xl md:text-4xl font-black text-gray-900 mb-3 uppercase leading-tight tracking-tight break-words">
                        {{ $jurnal->judul_refleksi }}
                    </h1>

                    {{-- Meta Data Bar --}}
                    <div
                        class=" truncate flex flex-wrap items-center gap-4 text-xs font-bold text-gray-500 uppercase tracking-wide">
                        <span class="flex items-center gap-1 truncate  ">
                            <span class="text-blue-700 ">👤</span> Penulis: {{ $jurnal->nama_guru }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-1">
                            <span class="text-blue-700">🕒</span> Dibuat:
                            {{ \Carbon\Carbon::parse($jurnal->created_at)->format('H:i') }} WIB
                        </span>
                    </div>
                </div>

                {{-- 2. ISI JURNAL --}}
                <div class="mb-10">
                    <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                        <span class="w-2 h-6 bg-yellow-400 inline-block"></span>
                        Isi Catatan
                    </h3>

                    <div
                        class="bg-gray-50 p-6 border border-gray-200 text-gray-800 text-base md:text-lg leading-relaxed whitespace-pre-line text-justify font-serif break-words">
                        {{ $jurnal->isi_refleksi }}
                    </div>
                </div>

                {{-- 3. FOOTER / TOMBOL AKSI --}}
                <div
                    class="flex flex-col md:flex-row justify-between items-center pt-6 border-t-2 border-gray-100 gap-4 mt-8">

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('jurnal.index') }}"
                        class="text-xs font-bold text-gray-900 uppercase tracking-widest hover:text-blue-700 hover:underline transition">
                        &larr; Kembali ke Daftar
                    </a>

                    {{-- Grup Tombol Aksi --}}
                    <div class="flex gap-3 w-full md:w-auto">
                        @php
                            $isAdmin = Auth::user()->role == 'admin';
                            $isOwner = $jurnal->user_id == Auth::id();
                        @endphp

                        @if ($isAdmin || $isOwner)
                            <a href="{{ route('jurnal.edit', $jurnal->id) }}"
                                class="flex-1 md:flex-none text-center bg-yellow-400 text-black px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm">
                                Edit Jurnal
                            </a>

                            <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan jurnal ini?')"
                                class="flex-1 md:flex-none">
                                @csrf @method('DELETE')
                                <button
                                    class="w-full md:w-auto bg-red-600 text-white px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
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
