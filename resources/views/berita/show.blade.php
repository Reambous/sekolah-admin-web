<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA BERITA --}}
            <div class="bg-white border-2 border-gray-200 shadow-sm relative mb-8">

                <div class="p-8 md:p-12">

                    {{-- 1. HEADER (Kategori, Tanggal, Judul) --}}
                    <div class="border-b-4 border-gray-900 pb-6 mb-8">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="bg-blue-900 text-white text-xs font-black px-3 py-1 uppercase tracking-widest">
                                Berita Sekolah
                            </span>
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wide">
                                {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>

                        <h1
                            class="text-3xl md:text-5xl font-black text-gray-900 mb-4 uppercase leading-tight tracking-tight break-words">
                            {{ $berita->judul }}
                        </h1>

                        <div class="flex items-center gap-2 text-xs font-bold text-gray-500 uppercase tracking-widest">
                            <span class="text-blue-700">Penulis: {{ $berita->penulis }}</span>
                            @if ($berita->created_at != $berita->updated_at)
                                <span class="text-gray-300">|</span>
                                <span class="italic text-gray-400">Diedit:
                                    {{ \Carbon\Carbon::parse($berita->updated_at)->format('H:i') }} WIB</span>
                            @endif
                        </div>
                    </div>

                    {{-- 2. GAMBAR UTAMA --}}
                    @if ($berita->gambar)
                        <div class="mb-10 border border-gray-200 p-1 bg-gray-50">
                            <img src="{{ asset('storage/' . $berita->gambar) }}"
                                class="w-full h-auto object-cover max-h-[500px]" alt="Gambar Berita">
                        </div>
                    @endif

                    {{-- 3. LAMPIRAN (Jika Ada) --}}
                    @if ($berita->lampiran)
                        <div
                            class="mb-10 bg-blue-50 border-l-4 border-blue-900 p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 border border-gray-200 shadow-sm">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase">Dokumen Lampiran</p>
                                    <p class="text-xs text-gray-600 truncate max-w-xs">
                                        {{ $berita->nama_file_asli ?? 'Unduh File' }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $berita->lampiran) }}" download
                                class="bg-gray-900 text-white px-5 py-2 text-xs font-bold uppercase tracking-wider hover:bg-gray-700 transition shadow-sm flex-shrink-0">
                                📥 Download
                            </a>
                        </div>
                    @endif

                    {{-- 4. ISI BERITA (SERIF FONT) --}}
                    <div
                        class="prose max-w-none text-gray-900 text-lg leading-relaxed whitespace-pre-line text-justify font-serif break-words mb-10">
                        {{ $berita->isi }}
                    </div>

                    {{-- 5. FOOTER & NAVIGASI --}}
                    <div
                        class="flex flex-col md:flex-row justify-between items-center pt-8 border-t-2 border-gray-100 gap-4">
                        <a href="{{ route('berita.index') }}"
                            class="text-xs font-bold text-gray-900 uppercase tracking-widest hover:text-blue-700 hover:underline transition">
                            &larr; Kembali ke Papan Pengumuman
                        </a>

                        <div class="flex gap-2">
                            @if (Auth::user()->role == 'admin' || $berita->user_id == Auth::id())
                                <a href="{{ route('berita.edit', $berita->id) }}"
                                    class="bg-yellow-400 text-black px-5 py-2 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm">
                                    Edit
                                </a>
                                <form action="{{ route('berita.destroy', $berita->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-600 text-white px-5 py-2 text-xs font-black uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- AREA KOMENTAR --}}
                <div class="bg-gray-50 border-t-2 border-gray-200 p-8 md:p-12">
                    <h3
                        class="text-xl font-black text-gray-900 uppercase tracking-tighter mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-black inline-block"></span>
                        Diskusi ({{ $komentar->count() }})
                    </h3>

                    {{-- Form Komentar --}}
                    <form action="{{ route('berita.comment', $berita->id) }}" method="POST" class="mb-10">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-0 shadow-sm">
                            <textarea name="isi_komentar" rows="3"
                                class="w-full bg-white border-2 border-gray-300 text-sm focus:border-black focus:ring-0 rounded-none p-4 placeholder-gray-400"
                                placeholder="Tulis tanggapan Anda di sini..." required></textarea>
                            <button
                                class="bg-black text-white px-8 py-3 md:py-0 font-bold uppercase tracking-wider hover:bg-gray-800 transition">
                                Kirim
                            </button>
                        </div>
                    </form>

                    {{-- List Komentar --}}
                    <div class="space-y-6"> {{-- JANGAN PAKAI TRUNCATE DISINI --}}
                        @foreach ($komentar as $k)
                            <div class="flex gap-4 group">
                                {{-- Avatar Inisial --}}
                                <div
                                    class="w-10 h-10 bg-gray-200 flex items-center justify-center text-gray-500 font-black text-sm uppercase flex-shrink-0 border border-gray-300">
                                    {{ substr($k->nama_user, 0, 1) }}
                                </div>

                                {{-- Wrapper Konten --}}
                                <div class="flex-1 min-w-0"> {{-- min-w-0 PENTING --}}

                                    {{-- Header: Nama & Tombol --}}
                                    <div class="flex justify-between items-center mb-1 gap-2">

                                        {{-- BAGIAN KIRI: Nama & Waktu --}}
                                        {{-- flex-1 & min-w-0 agar bagian ini mengisi ruang sisa tapi bisa mengecil --}}
                                        <div class="flex items-center gap-2 flex-1 min-w-0">

                                            {{-- 1. NAMA USER (TRUNCATE DISINI) --}}
                                            <div class="font-bold text-gray-900 text-sm uppercase truncate">
                                                {{ $k->nama_user }}
                                            </div>

                                            {{-- 2. WAKTU (Whitespace-nowrap agar waktu tidak hancur/turun) --}}
                                            <span
                                                class="text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap flex-shrink-0">
                                                {{ \Carbon\Carbon::parse($k->created_at)->diffForHumans() }}
                                            </span>
                                        </div>

                                        {{-- BAGIAN KANAN: Tombol Aksi --}}
                                        {{-- flex-shrink-0 agar tombol TIDAK PERNAH hilang/mengecil --}}
                                        @if (Auth::user()->role == 'admin' || $k->user_id == Auth::id())
                                            <div
                                                class="flex-shrink-0 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                                                @if ($k->user_id == Auth::id())
                                                    <a href="{{ route('berita.comment.edit', $k->id) }}"
                                                        class="text-[10px] font-bold text-blue-600 hover:underline uppercase">Edit</a>
                                                    <span class="text-gray-300 text-[10px]">|</span>
                                                @endif
                                                <form action="{{ route('berita.comment.destroy', $k->id) }}"
                                                    method="POST" onsubmit="return confirm('Hapus komentar?')">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="text-[10px] font-bold text-red-600 hover:underline uppercase">Hapus</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Isi Komentar --}}
                                    <p
                                        class="text-gray-700 text-sm leading-relaxed whitespace-pre-line break-words bg-white p-3 border border-gray-200">
                                        {{ $k->isi_komentar }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
