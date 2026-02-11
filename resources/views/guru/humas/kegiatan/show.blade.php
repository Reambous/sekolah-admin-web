<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Kegiatan') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                <div class="p-8 border-b border-gray-200">

                    <div class="mb-6">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase tracking-wide">
                            HUMAS
                        </span>

                        {{-- JUDUL: break-words agar turun ke bawah --}}
                        <h1 class="text-3xl font-extrabold text-gray-900 mt-2 mb-2 leading-tight break-words">
                            {{ $kegiatan->nama_kegiatan }}
                        </h1>

                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span>📅
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }}</span>

                            {{-- Jam (Ambil dari waktu input/created_at) --}}
                            <span class="text-xs text-gray-500 mt-1">
                                🕒 {{ \Carbon\Carbon::parse($kegiatan->created_at)->format('H:i') }} WIB
                            </span>
                            <span>👤 {{ $kegiatan->nama_guru }}</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="font-bold text-gray-900 mb-2 border-b pb-1">Keterangan / Laporan:</h4>

                        {{-- 
                            whitespace-pre-line : Menjaga enter/paragraf
                            break-words         : Memaksa teks panjang turun
                            text-justify        : Meratakan kanan-kiri
                        --}}
                        <div
                            class="prose max-w-none text-gray-800 text-base leading-relaxed whitespace-pre-line break-words text-justify bg-gray-50 p-6 rounded-lg border border-gray-100">
                            {{ $kegiatan->refleksi ?? 'Tidak ada keterangan detail.' }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-100">
                        <a href="{{ route('humas.kegiatan.index') }}"
                            class="text-gray-600 font-bold text-sm hover:text-gray-900">
                            &larr; Kembali
                        </a>

                        <div class="flex gap-2">
                            @if (Auth::user()->role == 'admin' || $kegiatan->user_id == Auth::id())
                                <a href="{{ route('humas.kegiatan.edit', $kegiatan->id) }}"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded font-bold text-sm hover:bg-yellow-600 transition">
                                    Edit
                                </a>
                                <form action="{{ route('humas.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-600 text-white px-4 py-2 rounded font-bold text-sm hover:bg-red-700 transition">
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
