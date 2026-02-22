<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border-2 border-gray-200 p-8 shadow-sm relative">

                {{-- HEADER HALAMAN --}}
                <div class="border-b-4 border-gray-900 pb-4 mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Tulis Berita Baru
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Buat pengumuman atau artikel baru untuk sekolah
                    </p>
                </div>
                {{-- NOTIFIKASI ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <strong class="font-black text-red-800 uppercase text-xs tracking-wide">Gagal Menyimpan
                                Data!</strong>
                        </div>
                        <ul class="list-disc list-inside text-red-700 text-sm font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- JUDUL --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" required placeholder="Tulis judul berita yang menarik..."
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-lg font-bold focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">
                    </div>

                    {{-- GRID FILE UPLOAD --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b-2 border-gray-100">

                        {{-- Gambar Sampul --}}
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Gambar Sampul (Opsional)
                            </label>
                            <input type="file" name="gambar" id="input-gambar"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-gray-700 transition border-2 border-gray-200 bg-gray-50 cursor-pointer"
                                onchange="document.getElementById('nama-gambar').innerText = this.files[0] ? 'File: ' + this.files[0].name : ''">
                            <div class="flex justify-between mt-2">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Format: JPG, PNG (Max 2MB)</p>
                                <p id="nama-gambar"
                                    class="text-[10px] text-blue-600 font-bold italic truncate w-32 text-right"></p>
                            </div>
                        </div>

                        {{-- Lampiran Dokumen --}}
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Lampiran Dokumen (Opsional)
                            </label>
                            <input type="file" name="lampiran" id="input-lampiran"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-gray-700 transition border-2 border-gray-200 bg-gray-50 cursor-pointer"
                                onchange="document.getElementById('nama-lampiran').innerText = this.files[0] ? 'File: ' + this.files[0].name : ''">
                            <div class="flex justify-between mt-2">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Format: PDF, DOCX (Max 5MB)</p>
                                <p id="nama-lampiran"
                                    class="text-[10px] text-blue-600 font-bold italic truncate w-32 text-right"></p>
                            </div>
                        </div>
                    </div>

                    {{-- ISI BERITA --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Isi Berita
                        </label>
                        <textarea name="isi" rows="10" placeholder="Tulis isi berita selengkapnya di sini..."
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-base leading-relaxed focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400"></textarea>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <a href="{{ route('berita.index') }}"
                            class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-gray-900 text-white px-8 py-3 text-xs font-black uppercase tracking-wider hover:bg-blue-900 transition shadow-sm border-b-4 border-black hover:border-blue-950 transform hover:-translate-y-0.5">
                            Terbitkan Berita
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
