<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tulis Jurnal Baru') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('jurnal.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <input type="hidden" name="kategori" value="{{ $kategori }}">

                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-md p-4 flex items-center">
                            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-blue-600 font-bold uppercase tracking-wide">Refleksi Kategori</p>
                                <p class="text-lg font-bold text-gray-800">{{ ucfirst($kategori) }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Refleksi</label>
                            <input type="text" name="judul_refleksi"
                                placeholder="Contoh: Evaluasi Proker Bulan Januari"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Jurnal</label>
                            <textarea name="isi_refleksi" rows="6"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="Tulis catatan lengkap di sini..." required></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('jurnal.index') }}"
                                class="text-gray-600 hover:text-gray-900 text-sm font-medium py-2">Batal</a>
                            <button type="submit"
                                class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
