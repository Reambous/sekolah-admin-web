<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Input Kegiatan Humas') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- 1. BLOK ERROR (INI YANG MEMUNCULKAN NOTIF "UPS!") --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Ups! Ada kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('humas.kegiatan.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan</label>
                            <input type="date" name="tanggal"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan / Program</label>
                            <input type="text" name="nama_kegiatan"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- 2. TEXTAREA YANG BERSIH (Tanpa pesan error di bawahnya) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refleksi / Keterangan</label>
                            <textarea name="refleksi" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('humas.kegiatan.index') }}"
                                class="text-gray-600 py-2 px-4 text-sm font-medium hover:bg-gray-100 rounded">Batal</a>
                            <button type="submit"
                                class="bg-gray-900 text-white font-bold py-2 px-6 rounded shadow hover:bg-gray-800 transition">
                                Simpan Laporan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
