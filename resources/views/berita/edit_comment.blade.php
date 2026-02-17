<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-gray-200 p-8 shadow-sm relative">

                <div class="border-b-4 border-gray-900 pb-4 mb-6">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                        Edit Komentar
                    </h2>
                </div>

                <form action="{{ route('berita.comment.update', $komentar->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Isi Komentar
                        </label>
                        <textarea name="isi_komentar" rows="4" required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">{{ $komentar->isi_komentar }}</textarea>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('berita.show', $komentar->berita_id) }}"
                            class="text-xs font-bold text-gray-500 uppercase hover:text-black hover:underline">
                            Batal
                        </a>
                        <button
                            class="bg-gray-900 text-white px-5 py-2 text-xs font-black uppercase tracking-wider hover:bg-blue-900 transition shadow-sm border-b-4 border-black hover:border-blue-950">
                            Update Komentar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
