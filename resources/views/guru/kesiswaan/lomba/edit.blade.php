<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA --}}
            <div class="bg-white border-2 border-gray-200 p-8 shadow-sm relative">

                {{-- HEADER HALAMAN --}}
                <div class="border-b-4 border-gray-900 pb-4 mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Edit Data Prestasi
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Perbarui informasi lomba dan daftar peserta
                    </p>
                </div>

                <form action="{{ route('kesiswaan.lomba.update', $lomba->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 1. INFORMASI UTAMA --}}
                    <div class="mb-10">
                        <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                            <span class="w-2 h-6 bg-yellow-400 inline-block"></span>
                            Informasi Lomba
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Tanggal --}}
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ $lomba->tanggal }}"
                                    class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                            </div>

                            {{-- Jenis Lomba --}}
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Jenis
                                    Lomba</label>
                                <input type="text" name="jenis_lomba" value="{{ $lomba->jenis_lomba }}"
                                    class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">
                            </div>

                            {{-- Prestasi --}}
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Prestasi
                                    / Juara</label>
                                <input type="text" name="prestasi" value="{{ $lomba->prestasi }}"
                                    class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Catatan
                                    (Opsional)</label>
                                <input type="text" name="refleksi" value="{{ $lomba->refleksi }}"
                                    class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400">
                            </div>
                        </div>
                    </div>

                    {{-- 2. DAFTAR PESERTA (ALPINE JS) --}}
                    <div class="mb-8" x-data="{ rows: {{ Js::from($formattedPeserta) }} }">

                        <div class="flex justify-between items-end mb-4">
                            <h3 class="text-lg font-black text-gray-900 uppercase flex items-center gap-2">
                                <span class="w-2 h-6 bg-blue-900 inline-block"></span>
                                Daftar Peserta
                            </h3>
                            <button type="button" @click="rows.push({kelas: '', nama: ''})"
                                class="bg-blue-900 text-white px-4 py-2 text-[10px] font-black uppercase tracking-wider hover:bg-blue-800 transition shadow-sm">
                                + Tambah Baris
                            </button>
                        </div>

                        <div class="border-2 border-gray-200 overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-900 text-white">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-black uppercase tracking-widest w-12">
                                            No</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest w-1/4">
                                            Kelas</th>
                                        <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest">
                                            Nama Siswa</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-black uppercase tracking-widest w-20">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr class="hover:bg-blue-50 transition">
                                            <td class="px-4 py-3 text-center font-bold text-gray-500 text-sm align-top pt-4"
                                                x-text="index + 1"></td>

                                            <td class="px-4 py-3 align-top">
                                                <input type="text" :name="'peserta[' + index + '][kelas]'"
                                                    x-model="row.kelas" placeholder="Cth: XII RPL 1" required
                                                    class="w-full bg-gray-50 border-2 border-gray-200 text-xs font-bold uppercase focus:bg-white focus:border-blue-900 focus:ring-0 rounded-none px-2 py-2">
                                            </td>

                                            <td class="px-4 py-3 align-top">
                                                <textarea :name="'peserta[' + index + '][nama]'" x-model="row.nama" rows="1" placeholder="Nama siswa..." required
                                                    class="w-full bg-gray-50 border-2 border-gray-200 text-sm focus:bg-white focus:border-blue-900 focus:ring-0 rounded-none px-2 py-2 placeholder-gray-400"></textarea>
                                            </td>

                                            <td class="px-4 py-3 text-center align-top pt-3">
                                                <button type="button"
                                                    @click="rows.length > 1 ? rows.splice(index, 1) : alert('Minimal 1 kelas!')"
                                                    class="text-red-600 hover:text-black transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-2 tracking-wide">* Pisahkan nama
                            siswa dengan koma jika lebih dari satu.</p>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <a href="{{ route('kesiswaan.lomba.index') }}"
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
