<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tulis Jurnal Refleksi Baru') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('jurnal.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled
                                class="w-full rounded-md border-gray-200 bg-gray-100 text-gray-500 text-sm cursor-not-allowed">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Topik / Konteks</label>
                            <input type="text" name="kategori"
                                placeholder="Contoh: Rapat Guru, Kesiswaan, Masalah Kelas, dll"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                            <p class="text-xs text-gray-500 mt-1">Tuliskan topik utama jurnal ini secara bebas.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Refleksi</label>
                            <input type="text" name="judul_refleksi" placeholder="Judul singkat..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Refleksi / Catatan</label>
                            <textarea name="isi_refleksi" rows="6"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="Tuliskan detail refleksi Anda di sini..." required></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('jurnal.index') }}"
                                class="text-gray-600 py-2 px-4 text-sm font-medium hover:bg-gray-100 rounded">Batal</a>
                            <button type="submit"
                                class="bg-gray-900 text-white font-bold py-2 px-6 rounded shadow hover:bg-gray-800 transition">
                                Simpan Jurnal
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
