<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Papan Pengumuman') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-xl font-bold text-gray-900">Berita Terbaru</h3>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('berita.create') }}"
                        class="px-4 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 transition">
                        + Buat Berita
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($berita as $item)
                    <div
                        class="bg-white rounded-lg border border-gray-300 shadow-sm hover:shadow-md transition duration-200 flex flex-col p-5 h-full relative group">

                        {{-- TOMBOL HAPUS (ADMIN) --}}
                        @if (Auth::user()->role == 'admin')
                            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('berita.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-400 hover:text-red-600 transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div
                            class="flex items-center gap-2 mb-3 text-xs font-bold text-gray-500 uppercase tracking-wide">
                            <span class="font-bold text-black">📅
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y - H:i') }}
                                WIB</span>
                            @if ($item->created_at != $item->updated_at)
                                <span class="italic text-gray-400">(Diedit:
                                    {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }} WIB)</span>
                            @endif
                            <span>✍️ {{ $item->penulis }}</span>
                        </div>

                        {{-- Tambahkan class 'break-words' dan 'whitespace-normal' --}}
                        <h3
                            class="text-lg font-bold text-gray-900 mb-2 leading-snug hover:text-blue-700 transition break-words whitespace-normal">
                            <a href="{{ route('berita.show', $item->id) }}">
                                {{ $item->judul }}
                            </a>
                        </h3>

                        <div class="mb-4 flex flex-wrap gap-2">

                            {{-- 1. TANDA GAMBAR TERLAMPIR --}}
                            @if ($item->gambar)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded border border-green-200 uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Gambar
                                </span>
                            @endif

                            {{-- 2. TANDA DOKUMEN TERLAMPIR --}}
                            @if ($item->lampiran)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-200 uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                    Dokumen
                                </span>
                            @endif
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-sm">
                            <div class="flex items-center gap-1 text-gray-600 text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                    </path>
                                </svg>
                                {{ $item->total_komentar }}
                            </div>
                            <a href="{{ route('berita.show', $item->id) }}"
                                class="text-xs font-bold text-gray-900 hover:underline">
                                LIHAT DETAIL &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-3 text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded text-gray-500">
                        Belum ada berita.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
