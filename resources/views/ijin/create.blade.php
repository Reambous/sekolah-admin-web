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
                        Pengajuan Ijin Baru
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Formulir permohonan ijin atau cuti kerja
                    </p>
                </div>

                {{-- NOTIF ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <strong class="font-black text-red-800 uppercase text-xs tracking-wide">Gagal
                                Mengirim!</strong>
                        </div>
                        <p class="text-red-700 text-sm font-medium">Mohon periksa kembali isian formulir Anda.</p>
                    </div>
                @endif

                <form action="{{ route('ijin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Grid Tanggal --}}
                    {{-- Grid Waktu (Tanggal & Jam) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Tanggal Ijin
                            </label>
                            <input type="date" name="tanggal" value="{{ old('tanggal') }}" required
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                            @error('tanggal')
                                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">
                                    {{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Jam Keluar (Opsional)
                            </label>
                            <input type="time" name="jam_mulai"
                                value="{{ old('jam_mulai', $ijin->jam_mulai ?? '') }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                            <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-wide">* Kosongkan jika
                                ijin 1 hari penuh</p>
                            @error('jam_mulai')
                                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">
                                    {{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Jam Kembali (Opsional)
                            </label>
                            <input type="time" name="jam_selesai"
                                value="{{ old('jam_selesai', $ijin->jam_selesai ?? '') }}"
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                            @error('jam_selesai')
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
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400"
                            placeholder="Contoh: Sakit demam, Acara keluarga mendesak, Dinas luar kota, dll...">{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Bukti Foto --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Bukti Foto / Surat (Opsional)
                        </label>
                        <input type="file" name="bukti_foto"
                            class="block w-full text-xs text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-gray-700 transition border-2 border-gray-200 bg-gray-50 cursor-pointer">
                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-wide">* Format: JPG, PNG,
                            PDF (Max 2MB)</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <a href="{{ route('ijin.index') }}"
                            class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-gray-900 text-white px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-blue-900 transition shadow-sm border-b-4 border-black hover:border-blue-950 transform hover:-translate-y-0.5">
                            Kirim Pengajuan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
