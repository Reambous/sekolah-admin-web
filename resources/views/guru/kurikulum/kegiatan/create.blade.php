<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kegiatan Kurikulum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Ups! Ada kesalahan:</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kurikulum.kegiatan.store') }}" method="POST">
                        @csrf <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Kegiatan</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                                placeholder="Contoh: Rapat OSIS Bulanan" value="{{ old('nama_kegiatan') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div class="mb-6">
                            <label for="refleksi" class="block text-sm font-medium text-gray-700 mb-1">Refleksi /
                                Catatan Evaluasi</label>
                            <textarea name="refleksi" id="refleksi" rows="5"
                                placeholder="Tuliskan kendala, solusi, atau hasil kegiatan di sini..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('refleksi') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">*Data ini akan dikunci setelah divalidasi Admin.</p>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('kurikulum.kegiatan.index') }}"
                                class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition">
                                Simpan Laporan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
