<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Jurnal Refleksi') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8 text-gray-900">

                    {{-- HEADER: Kategori, Judul, Info --}}
                    <div class="border-b pb-6 mb-6">
                        <span class=" break-words text-blue-800 text-xs font-bold  py-1  uppercase tracking-wide ">
                            {{ $jurnal->kategori }}
                        </span>

                        {{-- JUDUL: break-words agar turun ke bawah --}}
                        <h1 class="text-3xl font-extrabold text-gray-900 mt-3 mb-3 leading-tight break-words">
                            {{ $jurnal->judul_refleksi }}
                        </h1>

                        <div class="flex flex-col sm:flex-row gap-4 text-sm text-gray-500">
                            {{-- Tanggal --}}
                            <div class="flex items-center gap-1">
                                <span>📅
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>

                            {{-- Jam --}}
                            <div class="flex items-center gap-1">
                                <span>🕒 {{ \Carbon\Carbon::parse($jurnal->created_at)->format('H:i') }} WIB</span>
                            </div>

                            {{-- Penulis --}}
                            <div class="flex items-center gap-1">
                                <span>👤 Penulis: <span
                                        class="font-bold text-blue-600">{{ $jurnal->nama_guru }}</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- ISI JURNAL --}}
                    <div class="mb-8">
                        <h4 class="font-bold text-gray-900 mb-2">Isi Catatan:</h4>

                        {{-- KONTEN UTAMA: Rapi, Justify, Break-Words --}}
                        <div
                            class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-gray-800 text-base leading-relaxed whitespace-pre-line break-words text-justify">
                            {{ $jurnal->isi_refleksi }}
                        </div>
                    </div>

                    {{-- FOOTER: TOMBOL KEMBALI & AKSI --}}
                    <div class="flex justify-between items-center pt-6 border-t border-gray-100 mt-4">

                        <a href="{{ route('jurnal.index') }}"
                            class="text-gray-600 font-bold text-sm hover:text-gray-900 px-4 py-2 rounded hover:bg-gray-100 transition flex items-center gap-2">
                            &larr; Kembali
                        </a>

                        <div class="flex gap-3">
                            @php
                                $isAdmin = Auth::user()->role == 'admin';
                                $isOwner = $jurnal->user_id == Auth::id();
                            @endphp

                            @if ($isAdmin || $isOwner)
                                <a href="{{ route('jurnal.edit', $jurnal->id) }}"
                                    class="bg-indigo-600 text-white px-5 py-2 rounded font-bold hover:bg-indigo-700 shadow transition text-sm">
                                    Edit
                                </a>

                                <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-600 text-white px-5 py-2 rounded font-bold hover:bg-red-700 shadow transition text-sm">
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
