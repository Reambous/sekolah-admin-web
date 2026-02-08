<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jurnal Refleksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('jurnal.update', $jurnal->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ $jurnal->tanggal }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Jurnal</label>
                            <select name="kategori"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                                <option value="kesiswaan" {{ $jurnal->kategori == 'kesiswaan' ? 'selected' : '' }}>
                                    Kesiswaan</option>
                                <option value="humas" {{ $jurnal->kategori == 'humas' ? 'selected' : '' }}>Humas
                                </option>
                                <option value="kurikulum" {{ $jurnal->kategori == 'kurikulum' ? 'selected' : '' }}>
                                    Kurikulum</option>
                                <option value="sarpras" {{ $jurnal->kategori == 'sarpras' ? 'selected' : '' }}>Sarpras
                                </option>
                                <option value="lainnya" {{ $jurnal->kategori == 'lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Refleksi</label>
                            <input type="text" name="judul_refleksi" value="{{ $jurnal->judul_refleksi }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Jurnal</label>
                            <textarea name="isi_refleksi" rows="8"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>{{ $jurnal->isi_refleksi }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('jurnal.index') }}"
                                class="text-gray-600 hover:text-gray-900 text-sm font-medium py-2 px-4 rounded hover:bg-gray-100 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
