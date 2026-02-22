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
                            Pengajuan Ijin
                        </span>

                        {{-- Status Badge (Kotak) --}}
                        @if ($ijin->status == 'pending')
                            <span
                                class="bg-yellow-400 text-yellow-900 text-xs font-black px-3 py-1 uppercase tracking-widest border border-yellow-500">
                                ⏳ Menunggu Konfirmasi
                            </span>
                        @elseif($ijin->status == 'disetujui')
                            <span class="bg-green-600 text-white text-xs font-black px-3 py-1 uppercase tracking-widest">
                                ✅ Disetujui
                            </span>
                        @else
                            <span class="bg-red-600 text-white text-xs font-black px-3 py-1 uppercase tracking-widest">
                                ❌ Ditolak
                            </span>
                        @endif
                    </div>

                    {{-- Judul (Nama Guru) --}}
                    <h1
                        class="text-3xl md:text-4xl font-black text-gray-900 mb-3 uppercase leading-tight tracking-tight break-words">
                        {{ $ijin->nama_guru }}
                    </h1>

                    {{-- Meta Data Bar --}}
                    <div
                        class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-500 uppercase tracking-wide">
                        <span class="flex items-center gap-1">
                            <span class="text-blue-700">📅</span> Diajukan:
                            {{ \Carbon\Carbon::parse($ijin->created_at)->translatedFormat('l, d F Y, H:i') }}
                        </span>
                    </div>
                </div>

                {{-- 2. INFORMASI DETAIL (GRID) --}}
                {{-- 2. INFORMASI DETAIL (GRID WAKTU) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    {{-- Kolom 1: Tanggal --}}
                    <div class="bg-gray-50 p-5 border-l-4 border-blue-900">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Ijin</h4>
                        <p class="text-lg font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($ijin->tanggal)->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    {{-- Kolom 2: Jam Mulai --}}
                    {{-- Kolom 2: Waktu Ijin --}}
                    <div class="bg-gray-50 p-5 border-l-4 border-yellow-400">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Waktu Ijin</h4>
                        <p class="text-lg font-bold text-gray-900">
                            @if ($ijin->jam_mulai && $ijin->jam_selesai)
                                {{ \Carbon\Carbon::parse($ijin->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($ijin->jam_selesai)->format('H:i') }} WIB
                            @else
                                <span class="text-red-600 font-black tracking-widest">1 HARI PENUH</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- 3. KETERANGAN / ALASAN --}}
                <div class="mb-10">
                    <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                        <span class="w-2 h-6 bg-yellow-400 inline-block"></span>
                        Keterangan / Alasan
                    </h3>
                    <div
                        class="bg-gray-50 p-6 border border-gray-200 text-gray-800 text-base md:text-lg leading-relaxed whitespace-pre-line text-justify font-serif break-words">
                        {{ $ijin->alasan }}
                    </div>
                </div>

                {{-- 4. BUKTI LAMPIRAN --}}
                @if ($ijin->bukti_foto)
                    <div class="mb-10">
                        <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                            <span class="w-2 h-6 bg-gray-400 inline-block"></span>
                            Bukti Lampiran
                        </h3>

                        @php
                            $extension = pathinfo($ijin->bukti_foto, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp

                        @if ($isImage)
                            <div
                                class="inline-block border-2 border-gray-200 p-1 bg-white shadow-sm hover:border-blue-500 transition">
                                <a href="{{ asset('storage/' . $ijin->bukti_foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $ijin->bukti_foto) }}"
                                        class="max-h-80 object-contain">
                                </a>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $ijin->bukti_foto) }}" target="_blank"
                                class="flex items-center gap-4 p-4 bg-gray-50 border-2 border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition group w-full md:w-fit">
                                <div
                                    class="bg-white p-2 border border-gray-200 shadow-sm group-hover:scale-110 transition">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 uppercase tracking-wide">Unduh Dokumen</p>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $extension }} FILE
                                    </p>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                {{-- 5. FOOTER / TOMBOL AKSI --}}
                <div
                    class="flex flex-col md:flex-row justify-between items-center pt-6 border-t-2 border-gray-100 gap-4 mt-8">

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('ijin.index') }}"
                        class="text-xs font-bold text-gray-900 uppercase tracking-widest hover:text-blue-700 hover:underline transition">
                        &larr; Kembali ke Daftar
                    </a>

                    {{-- Grup Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

                        {{-- Tombol Edit (Pemilik & Pending) --}}
                        @if ($ijin->user_id == Auth::id() && $ijin->status == 'pending')
                            <a href="{{ route('ijin.edit', $ijin->id) }}"
                                class="flex-1 md:flex-none text-center bg-yellow-400 text-black px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm">
                                Edit Pengajuan
                            </a>
                        @endif

                        {{-- Tombol Admin (ACC / Tolak) --}}
                        @if (Auth::user()->role == 'admin' && $ijin->status == 'pending')
                            <form action="{{ route('ijin.approve', $ijin->id) }}" method="POST"
                                class="flex-1 md:flex-none">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full md:w-auto bg-green-600 text-white px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-green-700 transition shadow-sm">
                                    Setujui (ACC)
                                </button>
                            </form>

                            <form action="{{ route('ijin.reject', $ijin->id) }}" method="POST"
                                class="flex-1 md:flex-none">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full md:w-auto bg-red-600 text-white px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
                                    Tolak Ijin
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Hapus (Pending Only) --}}
                        @if ($ijin->status == 'pending' && (Auth::user()->role == 'admin' || $ijin->user_id == Auth::id()))
                            <form action="{{ route('ijin.destroy', $ijin->id) }}" method="POST"
                                onsubmit="return confirm('Batalkan pengajuan ini?')" class="flex-1 md:flex-none">
                                @csrf @method('DELETE')
                                <button
                                    class="w-full md:w-auto bg-gray-200 text-gray-600 px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-gray-300 transition shadow-sm">
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
