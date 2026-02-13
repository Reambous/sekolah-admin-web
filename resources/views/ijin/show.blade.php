<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Pengajuan Ijin') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Pengajuan Ijin</h1>
                        <p class="text-sm text-gray-500">Diajukan pada:
                            {{ \Carbon\Carbon::parse($ijin->created_at)->translatedFormat('l, d F Y, H:i') }}</p>
                    </div>

                    {{-- STATUS BADGE --}}
                    @if ($ijin->status == 'pending')
                        <span
                            class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold border border-yellow-200 shadow-sm">
                            ⏳ Menunggu Konfirmasi
                        </span>
                    @elseif($ijin->status == 'disetujui')
                        <span
                            class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold border border-green-200 shadow-sm">
                            ✅ Disetujui
                        </span>
                    @else
                        <span
                            class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold border border-red-200 shadow-sm">
                            ❌ Ditolak
                        </span>
                    @endif
                </div>

                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Guru</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $ijin->nama_guru }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Ijin</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ \Carbon\Carbon::parse($ijin->mulai)->translatedFormat('d M Y') }}
                                @if ($ijin->mulai != $ijin->selesai)
                                    <span class="text-gray-400 mx-1">-</span>
                                    {{ \Carbon\Carbon::parse($ijin->selesai)->translatedFormat('d M Y') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan / Alasan</p>

                        {{-- MODIFIKASI: Tambahkan whitespace-pre-line dan break-words --}}
                        <div
                            class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-800 leading-relaxed whitespace-pre-line break-words text-justify">
                            {{ $ijin->alasan }}
                        </div>
                    </div>

                    @if ($ijin->bukti_foto)
                        <div class="mb-8">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bukti Lampiran</p>

                            @php
                                $extension = pathinfo($ijin->bukti_foto, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp

                            @if ($isImage)
                                {{-- TAMPILAN JIKA FOTO --}}
                                <div class="inline-block border p-1 rounded bg-white shadow-sm">
                                    <a href="{{ asset('storage/' . $ijin->bukti_foto) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $ijin->bukti_foto) }}"
                                            class="max-h-64 rounded cursor-zoom-in hover:opacity-90 transition">
                                    </a>
                                </div>
                            @else
                                {{-- TAMPILAN JIKA DOKUMEN (PDF/WORD) --}}
                                <a href="{{ asset('storage/' . $ijin->bukti_foto) }}" target="_blank"
                                    class="flex items-center gap-4 p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition group w-full md:w-fit">

                                    {{-- Ikon Dokumen --}}
                                    <div class="bg-white p-2 rounded shadow-sm group-hover:scale-110 transition">
                                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>

                                    {{-- Info File --}}
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">Lihat Dokumen Lampiran</p>
                                        <p class="text-xs text-gray-500 uppercase">{{ $extension }} File</p>
                                    </div>

                                    {{-- Ikon External Link --}}
                                    <div class="ml-auto pl-4">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @else
                        <div
                            class="mb-8 p-4 bg-gray-50 border border-dashed border-gray-300 rounded text-center text-gray-400 italic text-sm">
                            Tidak ada bukti yang dilampirkan.
                        </div>
                    @endif

                    <div
                        class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-100 gap-4">

                        {{-- TOMBOL KEMBALI: Di HP lebarnya penuh --}}
                        <a href="{{ route('ijin.index') }}"
                            class="w-full sm:w-auto text-center text-gray-600 font-bold text-sm hover:text-gray-900 px-4 py-2 rounded-lg border border-gray-200 sm:border-transparent transition">
                            &larr; Kembali
                        </a>

                        {{-- GRUP TOMBOL AKSI --}}
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

                            {{-- TOMBOL EDIT (Hanya Pemilik & Pending) --}}
                            @if ($ijin->user_id == Auth::id() && $ijin->status == 'pending')
                                <a href="{{ route('ijin.edit', $ijin->id) }}"
                                    class="w-full sm:w-auto text-center bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-bold text-sm shadow transition">
                                    Edit Pengajuan
                                </a>
                            @endif

                            {{-- TOMBOL ADMIN (ACC / TOLAK) --}}
                            @if (Auth::user()->role == 'admin' && $ijin->status == 'pending')
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <form action="{{ route('ijin.approve', $ijin->id) }}" method="POST"
                                        class="flex-1 sm:flex-none">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow transition">
                                            Setujui (ACC)
                                        </button>
                                    </form>

                                    <form action="{{ route('ijin.reject', $ijin->id) }}" method="POST"
                                        class="flex-1 sm:flex-none">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow transition">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            @endif

                            {{-- TOMBOL HAPUS (Pending Only) --}}
                            @if ($ijin->status == 'pending' && (Auth::user()->role == 'admin' || $ijin->user_id == Auth::id()))
                                <form action="{{ route('ijin.destroy', $ijin->id) }}" method="POST"
                                    class="w-full sm:w-auto" onsubmit="return confirm('Batalkan pengajuan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full text-red-600 font-bold text-sm hover:bg-red-200 px-4 py-2 rounded-lg transition sm:px-3 bg-red-50">
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
