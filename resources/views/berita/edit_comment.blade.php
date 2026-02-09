<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Komentar') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('berita.comment.update', $komentar->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Isi Komentar</label>
                        <textarea name="isi_komentar" rows="3" class="w-full rounded border-gray-300 text-sm" required>{{ $komentar->isi_komentar }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('berita.show', $komentar->berita_id) }}"
                            class="px-4 py-2 bg-gray-200 rounded text-sm text-gray-700">Batal</a>
                        <button class="px-4 py-2 bg-blue-600 rounded text-sm text-white font-bold">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
