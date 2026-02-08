<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Jurnal Refleksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $jurnal->judul_refleksi }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Penulis: <span class="font-semibold text-blue-600">{{ $jurnal->nama_guru }}</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Tanggal: {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-2">Isi Jurnal / Catatan:</h4>
                        <div
                            class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $jurnal->isi_refleksi }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-100">

                        <a href="{{ route('jurnal.index') }}"
                            class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                            &larr; Kembali ke Daftar
                        </a>

                        <div class="flex gap-3">

                            {{-- Syarat: Admin ATAU Pemilik Data --}}
                            @if (Auth::user()->role == 'admin' || Auth::id() == $jurnal->user_id)
                                <a href="{{ route('jurnal.edit', $jurnal->id) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded shadow transition">
                                    Edit Jurnal
                                </a>

                                <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus jurnal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded shadow transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
