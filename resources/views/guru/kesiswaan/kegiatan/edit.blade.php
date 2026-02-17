<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA --}}
            <div class="bg-white border-2 border-gray-200 p-8 shadow-sm relative">

                {{-- HEADER HALAMAN --}}
                <div class="border-b-4 border-gray-900 pb-4 mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Edit Kegiatan Kesiswaan
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Perbarui data laporan kegiatan kesiswaan
                    </p>
                </div>

                <div class="p-0 text-gray-900">
                    <form action="{{ route('kesiswaan.kegiatan.update', $kegiatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Tanggal Kegiatan --}}
                        <div class="mb-6">
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Tanggal Kegiatan
                            </label>
                            <input type="date" name="tanggal" value="{{ $kegiatan->tanggal }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                        </div>

                        {{-- Nama Kegiatan --}}
                        <div class="mb-6">
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Nama Kegiatan
                            </label>
                            <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">
                        </div>

                        {{-- Refleksi --}}
                        <div class="mb-8">
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Refleksi / Keterangan
                            </label>
                            <textarea name="refleksi" rows="6"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">{{ $kegiatan->refleksi }}</textarea>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                            <a href="{{ route('kesiswaan.kegiatan.index') }}"
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-yellow-400 text-black px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm border-b-4 border-yellow-600 hover:border-yellow-700">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
