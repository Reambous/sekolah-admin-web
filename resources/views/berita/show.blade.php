<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan --}}
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen font-sans text-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA BERITA --}}
            <div class="bg-white border border-gray-200 shadow-sm mb-8">

                <div class="p-6 md:p-10">

                    {{-- 1. HEADER (Kategori, Tanggal, Judul) --}}
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 uppercase tracking-wider">
                                Pengumuman
                            </span>
                            <span class="text-gray-500 text-sm">
                                {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 leading-snug">
                            {{ $berita->judul }}
                        </h1>

                        <div class="text-sm text-gray-500 flex flex-wrap items-center gap-2">
                            <span>Oleh: <span class="font-semibold text-gray-700">{{ $berita->penulis }}</span></span>
                            @if ($berita->created_at != $berita->updated_at)
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-400 italic">Diedit:
                                    {{ \Carbon\Carbon::parse($berita->updated_at)->format('H:i') }} WIB</span>
                            @endif
                        </div>
                    </div>

                    {{-- 2. GAMBAR UTAMA --}}
                    @if ($berita->gambar)
                        <div class="mb-8">
                            <img src="{{ asset('storage/' . $berita->gambar) }}"
                                class="w-full h-auto object-cover max-h-[400px] border border-gray-200 rounded-sm"
                                alt="Gambar Berita">
                        </div>
                    @endif

                    {{-- 3. LAMPIRAN (Jika Ada) --}}
                    @if ($berita->lampiran)
                        <div
                            class="mb-8 bg-gray-50 border border-gray-200 p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="text-red-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Dokumen Lampiran</p>
                                    <p class="text-xs text-gray-500 truncate max-w-[200px] md:max-w-xs">
                                        {{ $berita->nama_file_asli ?? 'Unduh_Lampiran' }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $berita->lampiran) }}" download
                                class="w-full md:w-auto inline-flex items-center justify-center bg-gray-900 text-white px-5 py-2.5 text-sm font-semibold hover:bg-gray-700 transition">
                                Download File
                            </a>
                        </div>
                    @endif

                    {{-- 4. ISI BERITA --}}
                    <div class="text-base text-gray-800 leading-relaxed whitespace-pre-line text-justify mb-8">
                        {{ $berita->isi }}
                    </div>

                    {{-- 5. FOOTER & NAVIGASI --}}
                    {{-- Beri sedikit jarak atas mt-8 --}}
                    <div
                        class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gray-200 gap-4 mt-8">

                        {{-- Tombol Kembali (Ubah jadi Full Button Biru Tua) --}}
                        {{-- w-full md:w-auto agar full width di HP, normal di laptop --}}
                        {{-- Tombol Kembali (Ubah jadi Full Button Biru) --}}
                        <a href="{{ route('berita.index') }}"
                            class="w-full md:w-auto inline-flex items-center justify-center bg-blue-900 text-white px-5 py-2 text-sm font-bold hover:bg-blue-800 transition flex-shrink-0">
                            &larr; &nbsp; Kembali ke Daftar
                        </a>

                        <div class="flex gap-2 w-full md:w-auto justify-end">
                            @if (Auth::user()->role == 'admin' || $berita->user_id == Auth::id())
                                <a href="{{ route('berita.edit', $berita->id) }}"
                                    class="flex-1 md:flex-none inline-flex items-center justify-center bg-yellow-400 text-yellow-900 px-5 py-2 text-sm font-bold hover:bg-yellow-500 transition">
                                    Edit
                                </a>
                                <form action="{{ route('berita.destroy', $berita->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')"
                                    class="flex-1 md:flex-none">
                                    @csrf @method('DELETE')
                                    <button
                                        class="w-full inline-flex items-center justify-center bg-white text-red-600 border border-red-600 px-5 py-2 text-sm font-bold hover:bg-red-50 transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- AREA KOMENTAR --}}
                <div class="bg-gray-50 border-t border-gray-200 p-6 md:p-10">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        Komentar ({{ $komentar->count() }})
                    </h3>

                    {{-- Form Komentar --}}
                    <form action="{{ route('berita.comment', $berita->id) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex flex-col gap-2">
                            <textarea name="isi_komentar" rows="3"
                                class="w-full bg-white border border-gray-300 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-sm p-3 placeholder-gray-400 resize-y"
                                placeholder="Tulis komentar Anda di sini..." required></textarea>
                            <div class="flex justify-end">
                                <button
                                    class="inline-flex items-center justify-center bg-gray-900 text-white px-6 py-2 text-sm font-semibold hover:bg-gray-800 transition">
                                    Kirim Komentar
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- List Komentar --}}
                    <div class="space-y-4">
                        @forelse ($komentar as $k)
                            <div class="flex gap-4 bg-white p-4 border border-gray-200 shadow-sm">

                                {{-- Avatar Inisial --}}
                                <div
                                    class="w-10 h-10 bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm uppercase flex-shrink-0 rounded-sm">
                                    {{ substr($k->nama_user, 0, 1) }}
                                </div>

                                {{-- Wrapper Konten --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-1 gap-2">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 min-w-0">
                                            <span class="font-bold text-gray-900 text-sm truncate">
                                                {{ $k->nama_user }}
                                            </span>
                                            <span class="hidden sm:inline text-gray-300">•</span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($k->created_at)->diffForHumans() }}
                                            </span>
                                        </div>

                                        {{-- Tombol Aksi Komentar --}}
                                        @if (Auth::user()->role == 'admin' || $k->user_id == Auth::id())
                                            <div class="flex-shrink-0 flex items-center gap-2">
                                                @if ($k->user_id == Auth::id())
                                                    <a href="{{ route('berita.comment.edit', $k->id) }}"
                                                        class="text-xs font-semibold text-blue-600 hover:text-blue-800">Edit</a>
                                                    <span class="text-gray-300 text-xs">|</span>
                                                @endif
                                                <form action="{{ route('berita.comment.destroy', $k->id) }}"
                                                    method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Isi Komentar --}}
                                    <p
                                        class="text-gray-700 text-sm leading-relaxed whitespace-pre-line break-words mt-1">
                                        {{ $k->isi_komentar }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm">Belum ada komentar. Jadilah yang pertama berkomentar!
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
