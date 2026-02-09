<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Berita') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                <div class="p-8 border-b border-gray-200">



                    <div class="text-sm text-gray-600 mb-1 pb-2 border-b border-gray-100 flex flex-wrap gap-4">
                        <span class="font-bold text-black">📅
                            {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y - H:i') }}
                            WIB</span>
                        @if ($berita->created_at != $berita->updated_at)
                            <span class="italic text-gray-400">(Diedit:
                                {{ \Carbon\Carbon::parse($berita->updated_at)->format('H:i') }} WIB)</span>
                        @endif
                        <span>✍️ {{ $berita->penulis }}</span>
                    </div>

                    {{-- 1. JUDUL UTAMA (FIX: break-words) --}}
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4 leading-tight break-words">
                        {{ $berita->judul }}
                    </h1>

                    @if ($berita->gambar)
                        <div class="mb-6 bg-gray-50 p-2 border border-gray-200 rounded inline-block">
                            <img src="{{ asset('storage/' . $berita->gambar) }}"
                                class="max-h-[350px] w-auto object-contain rounded">
                        </div>
                    @endif

                    @if ($berita->lampiran)
                        <div
                            class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 rounded border border-gray-200 text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-gray-900">Lampiran Dokumen</p>
                                    {{-- Nama file juga dipotong jika terlalu panjang --}}
                                    <p class="text-xs text-gray-600 truncate">
                                        {{ $berita->nama_file_asli ?? 'Dokumen.pdf' }}</p>
                                </div>
                            </div>

                            <a href="{{ asset('storage/' . $berita->lampiran) }}"
                                download="{{ $berita->nama_file_asli ?? 'download' }}"
                                class="bg-black text-white px-4 py-2 rounded text-xs font-bold hover:bg-gray-800 transition flex items-center gap-2 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path>
                                </svg>
                                Unduh
                            </a>
                        </div>
                    @endif

                    {{-- 2. ISI BERITA (FIX: break-words + whitespace-pre-line) --}}
                    <div
                        class="prose max-w-none text-gray-800 text-base leading-relaxed whitespace-pre-line text-justify break-words">
                        {{ $berita->isi ?? 'Tidak ada keterangan tambahan.' }}
                    </div>

                    @if (Auth::user()->role == 'admin')
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('berita.edit', $berita->id) }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 font-bold hover:bg-gray-50 text-xs">Edit
                                Berita</a>
                            <form action="{{ route('berita.destroy', $berita->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button
                                    class="px-4 py-2 bg-red-600 text-white rounded font-bold hover:bg-red-700 text-xs">Hapus</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 p-8">
                    <h3 class="font-bold text-gray-900 mb-4 border-b pb-2 border-gray-200">
                        Komentar ({{ $komentar->count() }})
                    </h3>

                    <form action="{{ route('berita.comment', $berita->id) }}" method="POST" class="mb-6 flex gap-3">
                        @csrf
                        <div class="flex-1">
                            <textarea name="isi_komentar" rows="2"
                                class="w-full rounded border-gray-300 text-sm focus:border-black focus:ring-black" placeholder="Tulis komentar..."
                                required></textarea>
                        </div>
                        <button
                            class="bg-black text-white px-5 py-2 rounded font-bold text-sm hover:bg-gray-800 h-fit">Kirim</button>
                    </form>

                    <div class="space-y-4">
                        @foreach ($komentar as $k)
                            <div class="bg-white p-4 rounded border border-gray-200 shadow-sm">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-bold text-gray-900 text-sm">{{ $k->nama_user }}</span>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span
                                            class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($k->created_at)->diffForHumans() }}</span>
                                        @if (Auth::user()->role == 'admin' || $k->user_id == Auth::id())
                                            @if ($k->user_id == Auth::id())
                                                <a href="{{ route('berita.comment.edit', $k->id) }}"
                                                    class="text-xs font-bold text-blue-600 hover:underline">Edit</a>
                                            @endif
                                            <form action="{{ route('berita.comment.destroy', $k->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="text-xs font-bold text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- 3. ISI KOMENTAR (FIX: break-words + whitespace-pre-line) --}}
                                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line break-words">
                                    {{ $k->isi_komentar }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
