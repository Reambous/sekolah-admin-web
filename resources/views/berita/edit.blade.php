<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Berita') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8">

                    <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Judul Berita <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ $berita->judul }}"
                                class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black"
                                required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6 border-b border-gray-100 pb-6">

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Gambar Sampul</label>

                                {{-- Preview Lama --}}
                                @if ($berita->gambar)
                                    <div
                                        class="mb-2 p-2 bg-gray-50 border rounded text-xs text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Gambar tersimpan saat ini.</span>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 mb-2 italic">Belum ada gambar.</p>
                                @endif

                                <input type="file" name="gambar" id="input-gambar-edit"
                                    class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 border border-gray-300 rounded cursor-pointer"
                                    onchange="document.getElementById('nama-gambar-edit').innerText = 'Akan diganti: ' + this.files[0].name">

                                <p id="nama-gambar-edit" class="text-xs text-blue-600 mt-1 font-bold italic"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Lampiran Dokumen</label>

                                {{-- Preview Lama --}}
                                @if ($berita->lampiran)
                                    <div
                                        class="mb-2 p-2 bg-blue-50 border border-blue-100 rounded text-xs text-blue-800 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span
                                            class="truncate w-40">{{ $berita->nama_file_asli ?? 'File Tersimpan' }}</span>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 mb-2 italic">Belum ada dokumen.</p>
                                @endif

                                <input type="file" name="lampiran" id="input-lampiran-edit"
                                    class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 border border-gray-300 rounded cursor-pointer"
                                    onchange="document.getElementById('nama-lampiran-edit').innerText = 'Akan diganti: ' + this.files[0].name">

                                <p id="nama-lampiran-edit" class="text-xs text-blue-600 mt-1 font-bold italic"></p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Isi Berita</label>
                            <textarea name="isi" rows="10"
                                class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black">{{ $berita->isi }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('berita.show', $berita->id) }}"
                                class="px-5 py-2 bg-white border border-gray-300 rounded text-gray-700 font-bold hover:bg-gray-50 text-sm">Batal</a>
                            <button type="submit"
                                class="px-5 py-2 bg-black text-white rounded font-bold hover:bg-gray-800 text-sm">Simpan
                                Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
