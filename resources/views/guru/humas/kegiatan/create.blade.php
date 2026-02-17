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
                        Tambah Kegiatan Humas
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Input laporan baru terkait kegiatan humas
                    </p>
                </div>

                <div class="p-0 text-gray-900">

                    {{-- Notifikasi Error --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-600 p-4 text-red-800">
                            <strong class="font-black uppercase text-xs tracking-wide block mb-1">Ups! Ada kesalahan
                                input:</strong>
                            <ul class="list-disc list-inside text-sm font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('humas.kegiatan.store') }}" method="POST">
                        @csrf

                        {{-- Tanggal --}}
                        <div class="mb-6">
                            <label for="tanggal"
                                class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Tanggal Kegiatan
                            </label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                        </div>

                        {{-- Nama Kegiatan --}}
                        <div class="mb-6">
                            <label for="nama_kegiatan"
                                class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Nama Kegiatan
                            </label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                                placeholder="Contoh: Perbaikan AC Ruang Guru" value="{{ old('nama_kegiatan') }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">
                        </div>

                        {{-- Refleksi --}}
                        <div class="mb-8">
                            <label for="refleksi"
                                class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Refleksi / Keterangan Evaluasi
                            </label>
                            <textarea name="refleksi" id="refleksi" rows="6"
                                placeholder="Tuliskan kendala, solusi, atau hasil kegiatan di sini secara rinci..."
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">{{ old('refleksi') }}</textarea>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-2 tracking-wide">* Data ini akan
                                dikunci setelah divalidasi oleh Admin.</p>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                            <a href="{{ route('humas.kegiatan.index') }}"
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-gray-900 text-white px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-blue-900 transition shadow-sm border-b-4 border-black hover:border-blue-950 transform hover:-translate-y-0.5">
                                Simpan Laporan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
