<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tulis Berita Baru') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8">

                    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Judul Berita <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul"
                                class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black"
                                placeholder="Judul..." required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Gambar Sampul
                                    (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="gambar" id="input-gambar"
                                        class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 border border-gray-300 rounded cursor-pointer"
                                        onchange="document.getElementById('nama-gambar').innerText = 'Terpilih: ' + this.files[0].name">
                                </div>
                                {{-- Teks Indikator --}}
                                <p id="nama-gambar" class="text-xs text-blue-600 mt-1 font-bold italic"></p>
                                <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Lampiran Dokumen
                                    (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="lampiran" id="input-lampiran"
                                        class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 border border-gray-300 rounded cursor-pointer"
                                        onchange="document.getElementById('nama-lampiran').innerText = 'Terpilih: ' + this.files[0].name">
                                </div>
                                {{-- Teks Indikator --}}
                                <p id="nama-lampiran" class="text-xs text-blue-600 mt-1 font-bold italic"></p>
                                <p class="text-[10px] text-gray-400 mt-1">Format: PDF, Word (Max 5MB)</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Isi Berita</label>
                            <textarea name="isi" rows="8"
                                class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black"
                                placeholder="Tulis isi pengumuman..."></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('berita.index') }}"
                                class="px-5 py-2 bg-white border border-gray-300 rounded text-gray-700 font-bold hover:bg-gray-50 text-sm">Batal</a>
                            <button type="submit"
                                class="px-5 py-2 bg-black text-white rounded font-bold hover:bg-gray-800 text-sm">Terbitkan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
