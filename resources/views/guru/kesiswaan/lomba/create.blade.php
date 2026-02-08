<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Input Lomba Tim / Individu') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('kesiswaan.lomba.store') }}" method="POST">
                        @csrf

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Informasi Lomba</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Lomba</label>
                                    <input type="text" name="jenis_lomba" placeholder="Contoh: Voli Putra O2SN"
                                        class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prestasi / Juara</label>
                                    <input type="text" name="prestasi" placeholder="Contoh: Juara 2"
                                        class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                        (Opsional)</label>
                                    <input type="text" name="refleksi" placeholder="Catatan tambahan..."
                                        class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div x-data="{ rows: [{ kelas: '', nama: '' }] }">

                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-700">Daftar Peserta</h3>
                                    <p class="text-xs text-gray-500">Tips: Masukkan nama dipisah koma (cth: Budi, Siti,
                                        Ahmad) untuk satu kelas yang sama.</p>
                                </div>
                                <button type="button" @click="rows.push({kelas: '', nama: ''})"
                                    class="text-xs bg-blue-600 text-white px-3 py-2 rounded font-bold hover:bg-blue-700 transition shadow-sm">
                                    + Tambah Baris Kelas
                                </button>
                            </div>

                            <div class="border rounded-lg overflow-hidden shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase w-10">
                                                No</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase w-1/4">
                                                Kelas</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                                                Nama Siswa (Pisahkan Koma)</th>
                                            <th
                                                class="px-4 py-2 text-center text-xs font-bold text-gray-600 uppercase w-16">
                                                Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="(row, index) in rows" :key="index">
                                            <tr>
                                                <td class="px-4 py-3 text-center text-gray-500 text-sm"
                                                    x-text="index + 1"></td>

                                                <td class="px-4 py-3 align-top">
                                                    <input type="text" :name="'peserta[' + index + '][kelas]'"
                                                        x-model="row.kelas" placeholder="Cth: XII RPL 1"
                                                        class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"
                                                        required>
                                                </td>

                                                <td class="px-4 py-3 align-top">
                                                    <textarea :name="'peserta[' + index + '][nama]'" x-model="row.nama" rows="2"
                                                        placeholder="Cth: Budi Santoso, Siti Aminah, Ahmad Dhani..."
                                                        class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                                </td>

                                                <td class="px-4 py-3 text-center align-middle">
                                                    <button type="button"
                                                        @click="rows.length > 1 ? rows.splice(index, 1) : alert('Minimal 1 kelas!')"
                                                        class="text-red-500 hover:text-red-700 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8 pt-4 border-t">
                            <a href="{{ route('kesiswaan.lomba.index') }}"
                                class="text-gray-600 py-2 px-4 text-sm font-medium hover:bg-gray-100 rounded">Batal</a>
                            <button type="submit"
                                class="bg-gray-900 text-white font-bold py-2 px-6 rounded shadow hover:bg-gray-800 transition">
                                Simpan Data Lomba
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
