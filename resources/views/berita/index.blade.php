<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Papan Pengumuman') }}</h2>
    </x-slot>
    {{-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa --}}
    {{-- lanflenzj --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-bold text-gray-900">Berita Terbaru</h3>

                    {{-- ADMIN ONLY: Tombol Hapus Massal & Checkbox All --}}
                    @if (Auth::user()->role == 'admin')
                        <div class="flex items-center gap-2 ml-4 border-l pl-4 border-gray-300">
                            <input type="checkbox" id="select-all"
                                class="rounded border-gray-300 text-black focus:ring-black cursor-pointer"
                                title="Pilih Semua">
                            <button type="button" id="btn-hapus-massal"
                                class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                Hapus Terpilih
                            </button>
                        </div>
                        <a href="{{ route('berita.export') }}"
                            class="px-2 py-1 bg-green-600 text-white border border-green-700 rounded font-bold text-sm hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export Excel
                        </a>
                    @endif

                </div>

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

                        {{-- 1. CHECKBOX HAPUS (PENGGANTI TOMBOL SAMPAH) --}}
                        {{-- Posisi: Pojok Kanan Atas (absolute top-3 right-3) --}}
                        @if (Auth::user()->role == 'admin')
                            <div class="absolute top-3 right-3 z-10">
                                <input type="checkbox" value="{{ $item->id }}"
                                    class="item-checkbox rounded border-gray-300 text-black focus:ring-black w-5 h-5 cursor-pointer shadow-sm">
                            </div>
                        @endif

                        {{-- 2. INFO TANGGAL & PENULIS --}}
                        {{-- pr-8: Memberi jarak kanan agar tidak menabrak checkbox --}}
                        <div
                            class="flex flex-wrap items-center gap-2 mb-3 text-xs font-bold text-gray-500 uppercase tracking-wide pr-8">
                            <span class="font-bold text-black">
                                📅 {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-xs text-gray-500 mt-1">
                                🕒 {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                            </span>
                            <span class="truncate max-w-[150px]">✍️ {{ $item->penulis }}</span>
                        </div>

                        {{-- 3. JUDUL BERITA --}}
                        <h3
                            class="text-lg font-bold text-gray-900 mb-2 leading-snug hover:text-blue-700 transition break-words whitespace-normal">
                            <a href="{{ route('berita.show', $item->id) }}">
                                {{ $item->judul }}
                            </a>
                        </h3>

                        {{-- 4. LABEL (GAMBAR/DOKUMEN) --}}
                        <div class="mb-4 flex flex-wrap gap-2">
                            @if ($item->gambar)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded border border-green-200 uppercase tracking-wide">
                                    📷 Gambar
                                </span>
                            @endif

                            @if ($item->lampiran)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-200 uppercase tracking-wide">
                                    📎 Dokumen
                                </span>
                            @endif
                        </div>

                        {{-- 5. FOOTER KARTU (KOMENTAR & LINK) --}}
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-sm">
                            {{-- Total Komentar --}}
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
                        class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-10 bg-gray-50 border border-dashed border-gray-300 rounded text-gray-500">
                        Belum ada berita.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    {{-- FORM RAHASIA DI BAWAH (UNTUK HAPUS MASSAL) --}}
    <form id="bulk-delete-form" action="{{ route('berita.bulk_delete') }}" method="POST">
        @csrf
        {{-- Input ID akan disisipkan via Javascript --}}
    </form>

    <script>
        // 1. Script Select All
        document.getElementById('select-all')?.addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // 2. Script Tombol Hapus
        document.getElementById('btn-hapus-massal')?.addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox:checked');

            if (checkboxes.length === 0) {
                alert('⚠️ Harap pilih minimal satu berita untuk dihapus!');
                return;
            }

            if (confirm('❓ Apakah Anda YAKIN ingin menghapus ' + checkboxes.length + ' berita terpilih?')) {
                let form = document.getElementById('bulk-delete-form');
                checkboxes.forEach(chk => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = chk.value;
                    form.appendChild(input);
                });
                form.submit();
            }
        });
    </script>
</x-app-layout>
