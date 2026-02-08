<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Jurnal Refleksi') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="border-b pb-4 mb-6">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase tracking-wide border border-blue-200">
                            {{ $jurnal->kategori }}
                        </span>

                        <h1 class="text-3xl font-bold text-gray-900 mt-3 leading-tight">
                            {{ $jurnal->judul_refleksi }}
                        </h1>

                        <div class="flex flex-col sm:flex-row gap-4 mt-3 text-sm text-gray-600">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Penulis: <span
                                        class="font-bold text-blue-600">{{ $jurnal->nama_guru }}</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="font-bold text-gray-700 mb-2">Isi Catatan:</h4>
                        <div
                            class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-gray-800 leading-relaxed whitespace-pre-line text-justify">
                            {{ $jurnal->isi_refleksi }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-100 mt-4">

                        <a href="{{ route('jurnal.index') }}"
                            class="text-gray-600 font-medium hover:text-gray-900 px-4 py-2 rounded hover:bg-gray-100 transition flex items-center gap-2">
                            &larr; Kembali
                        </a>

                        <div class="flex gap-3">
                            @php
                                $isAdmin = Auth::user()->role == 'admin';
                                $isOwner = $jurnal->user_id == Auth::id();
                            @endphp

                            @if ($isAdmin || $isOwner)
                                <a href="{{ route('jurnal.edit', $jurnal->id) }}"
                                    class="bg-indigo-600 text-white px-5 py-2 rounded font-bold hover:bg-indigo-700 shadow transition">
                                    Edit
                                </a>

                                <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-600 text-white px-5 py-2 rounded font-bold hover:bg-red-700 shadow transition">
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
