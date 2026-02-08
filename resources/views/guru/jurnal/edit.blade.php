<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Jurnal Refleksi') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('jurnal.update', $jurnal->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                            <input type="text"
                                value="{{ $jurnal->user_id == Auth::id() ? Auth::user()->name : 'Orang Lain' }}"
                                disabled
                                class="w-full rounded-md border-gray-200 bg-gray-100 text-gray-500 text-sm cursor-not-allowed">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ $jurnal->tanggal }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Topik / Konteks</label>
                            <input type="text" name="kategori" value="{{ $jurnal->kategori }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Refleksi</label>
                            <input type="text" name="judul_refleksi" value="{{ $jurnal->judul_refleksi }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Refleksi / Catatan</label>
                            <textarea name="isi_refleksi" rows="10"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>{{ $jurnal->isi_refleksi }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('jurnal.index') }}"
                                class="text-gray-600 py-2 px-4 text-sm font-medium hover:bg-gray-100 rounded">Batal</a>
                            <button type="submit"
                                class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow hover:bg-indigo-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
