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
                        Edit Pengajuan Ijin
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Perbarui data ijin atau cuti Anda
                    </p>
                </div>

                {{-- PESAN ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <strong class="font-black text-red-800 uppercase text-xs tracking-wide">Gagal
                                Update!</strong>
                        </div>
                        <p class="text-red-700 text-sm font-medium">Silakan periksa kembali inputan Anda.</p>
                    </div>
                @endif

                <form action="{{ route('ijin.update', $ijin->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Grid Tanggal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Mulai Tanggal
                            </label>
                            <input type="date" name="mulai" value="{{ old('mulai', $ijin->mulai) }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                            @error('mulai')
                                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">
                                    {{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Sampai Tanggal
                            </label>
                            <input type="date" name="selesai" value="{{ old('selesai', $ijin->selesai) }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                            @error('selesai')
                                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide animate-pulse">
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Alasan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Keterangan / Alasan
                        </label>
                        <textarea name="alasan" rows="4" required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">{{ old('alasan', $ijin->alasan) }}</textarea>
                        @error('alasan')
                            <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Bukti Foto --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Bukti Foto / Dokumen (Opsional)
                        </label>

                        @if ($ijin->bukti_foto)
                            <div
                                class="mb-3 inline-flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 text-green-700 text-xs font-bold rounded-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Bukti sudah terlampir. Upload ulang jika ingin mengganti.</span>
                            </div>
                        @endif

                        <input type="file" name="bukti_foto"
                            class="block w-full text-xs text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-gray-700 transition border-2 border-gray-200 bg-gray-50 cursor-pointer">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <a href="{{ route('ijin.show', $ijin->id) }}"
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
