<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Pengajuan Ijin') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8">

                    {{-- PESAN ERROR --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                            <p class="font-bold">Gagal Update!</p>
                            <p>Cek kembali tanggal atau data yang Anda masukkan.</p>
                        </div>
                    @endif

                    <form action="{{ route('ijin.update', $ijin->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Mulai Tanggal</label>
                                <input type="date" name="mulai" value="{{ old('mulai', $ijin->mulai) }}"
                                    class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black">
                                @error('mulai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Sampai Tanggal</label>
                                <input type="date" name="selesai" value="{{ old('selesai', $ijin->selesai) }}"
                                    class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black">
                                @error('selesai')
                                    <p class="text-red-500 text-xs mt-1 font-bold animate-pulse">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Keterangan / Alasan</label>
                            <textarea name="alasan" rows="4"
                                class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black" required>{{ old('alasan', $ijin->alasan) }}</textarea>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Bukti Foto (Opsional)</label>

                            {{-- Preview Lama --}}
                            @if ($ijin->bukti_foto)
                                <div
                                    class="mb-2 p-2 bg-gray-50 border rounded text-xs text-gray-600 flex items-center gap-2 w-fit">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Bukti sudah ada. Upload lagi untuk mengganti.</span>
                                </div>
                            @endif

                            <input type="file" name="bukti_foto"
                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 border border-gray-300 rounded">
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('ijin.show', $ijin->id) }}"
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
