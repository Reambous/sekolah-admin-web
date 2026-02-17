<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border-2 border-gray-200 p-8 shadow-sm relative">

                {{-- HEADER HALAMAN --}}
                <div class="border-b-4 border-gray-900 pb-4 mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Edit Berita
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Perbarui konten pengumuman atau artikel
                    </p>
                </div>

                <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- JUDUL BERITA --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ $berita->judul }}"
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-lg font-bold focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3"
                            required>
                    </div>

                    {{-- GRID GAMBAR & LAMPIRAN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b-2 border-gray-100">

                        {{-- 1. GAMBAR SAMPUL --}}
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-3">
                                Gambar Sampul
                            </label>

                            @if ($berita->gambar)
                                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-xs">
                                    <div class="flex items-center gap-2 mb-2 font-bold text-green-800 uppercase">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Gambar Terpasang</span>
                                    </div>
                                    <label
                                        class="flex items-center gap-2 mt-2 pt-2 border-t border-green-200 cursor-pointer hover:bg-red-50 p-1 transition group">
                                        <input type="checkbox" name="hapus_gambar" value="1"
                                            class="rounded-none border-2 border-gray-400 text-red-600 focus:ring-red-500">
                                        <span class="text-red-600 font-bold group-hover:underline">Hapus Gambar
                                            Ini</span>
                                    </label>
                                </div>
                            @else
                                <div
                                    class="mb-4 p-3 bg-gray-50 border border-gray-200 text-xs text-gray-400 italic text-center">
                                    Belum ada gambar sampul.
                                </div>
                            @endif

                            <input type="file" name="gambar"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-gray-700 transition border-2 border-gray-200 bg-gray-50 cursor-pointer">
                        </div>

                        {{-- 2. LAMPIRAN DOKUMEN --}}
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-3">
                                Lampiran Dokumen
                            </label>

                            @if ($berita->lampiran)
                                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-xs">
                                    <div class="flex items-center gap-2 mb-2 font-bold text-blue-800 uppercase">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span
                                            class="truncate w-40">{{ $berita->nama_file_asli ?? 'File Tersimpan' }}</span>
                                    </div>
                                    <label
                                        class="flex items-center gap-2 mt-2 pt-2 border-t border-blue-200 cursor-pointer hover:bg-red-50 p-1 transition group">
                                        <input type="checkbox" name="hapus_lampiran" value="1"
                                            class="rounded-none border-2 border-gray-400 text-red-600 focus:ring-red-500">
                                        <span class="text-red-600 font-bold group-hover:underline">Hapus Dokumen
                                            Ini</span>
                                    </label>
                                </div>
                            @else
                                <div
                                    class="mb-4 p-3 bg-gray-50 border border-gray-200 text-xs text-gray-400 italic text-center">
                                    Belum ada dokumen lampiran.
                                </div>
                            @endif

                            <input type="file" name="lampiran"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-gray-700 transition border-2 border-gray-200 bg-gray-50 cursor-pointer">
                        </div>
                    </div>

                    {{-- ISI BERITA --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Isi Berita
                        </label>
                        <textarea name="isi" rows="10"
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-base leading-relaxed focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">{{ $berita->isi }}</textarea>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <a href="{{ route('berita.show', $berita->id) }}"
                            class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-yellow-400 text-black px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm border-b-4 border-yellow-600 hover:border-yellow-700 transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
