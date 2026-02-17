<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    {{-- CSS ANTI-GAGAL --}}
    <style>
        #tampilan-hp {
            display: block;
        }

        #tampilan-laptop {
            display: none;
        }

        @media (min-width: 768px) {
            #tampilan-hp {
                display: none !important;
            }

            #tampilan-laptop {
                display: block !important;
            }
        }
    </style>

    <div class="py-8 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-[95%] mx-auto">

            {{-- JUDUL HALAMAN --}}
            <div
                class="border-b-4 border-gray-900 mb-8 pb-4 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Papan Pengumuman
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Berita terkini, agenda, dan informasi sekolah
                    </p>
                </div>

                {{-- TOMBOL AKSI UTAMA --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if (Auth::user()->role == 'admin')
                        <div class="flex items-center gap-2 border-r border-gray-300 pr-3 mr-1">
                            <input type="checkbox" id="select-all"
                                class="w-5 h-5 border-2 border-gray-400 text-gray-900 focus:ring-gray-900">
                            <button type="button" id="btn-hapus-massal"
                                class="bg-red-600 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
                                Hapus Terpilih
                            </button>
                        </div>
                        <a href="{{ route('berita.export') }}"
                            class="bg-green-700 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-green-800 transition shadow-sm flex items-center gap-2">
                            <span>📥</span> Export Excel
                        </a>
                    @endif

                    <a href="{{ route('berita.create') }}"
                        class="bg-gray-900 text-white px-5 py-2 text-xs font-bold uppercase tracking-wider hover:bg-yellow-500 hover:text-black transition shadow-lg transform hover:-translate-y-0.5">
                        + Tulis Berita
                    </a>
                </div>
            </div>

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border-l-4 border-green-600 p-4 text-green-800 text-sm font-bold flex items-center gap-2 shadow-sm">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            {{-- GRID BERITA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($berita as $item)
                    <div
                        class="bg-white border-2 border-gray-200 shadow-sm hover:shadow-lg hover:border-blue-900 transition duration-300 group flex flex-col h-full relative overflow-hidden">

                        {{-- 1. Checkbox Admin (Absolute Top Right) --}}
                        @if (Auth::user()->role == 'admin')
                            <div class="absolute top-3 right-3 z-20">
                                <input type="checkbox" value="{{ $item->id }}"
                                    class="item-checkbox w-5 h-5 border-2 border-black bg-white text-red-600 focus:ring-red-500 rounded shadow-md">
                            </div>
                        @endif

                        {{-- 2. GAMBAR THUMBNAIL (KECIL/PENDEK) --}}
                        {{-- Height di-set h-40 (sekitar 160px) agar tidak terlalu besar --}}
                        <div class="w-full h-40 bg-gray-100 overflow-hidden relative border-b border-gray-200">
                            <a href="{{ route('berita.show', $item->id) }}">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-in-out">
                                @else
                                    {{-- Placeholder jika tidak ada gambar --}}
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                        <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-[10px] font-bold uppercase">Tanpa Gambar</span>
                                    </div>
                                @endif
                            </a>
                        </div>

                        {{-- KONTEN TEKS --}}
                        <div class="p-6 flex flex-col flex-1">

                            {{-- 3. Meta Data --}}
                            <div
                                class="flex items-center gap-2 mb-2 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <span class="text-blue-700">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </span>
                                <span>•</span>
                                <span class="truncate max-w-[100px]">{{ $item->penulis }}</span>
                            </div>

                            {{-- 4. Judul Berita --}}
                            <h3
                                class="text-lg font-black text-gray-900 leading-tight mb-2 group-hover:text-blue-800 transition break-words">
                                <a href="{{ route('berita.show', $item->id) }}">
                                    {{ $item->judul }}
                                </a>
                            </h3>

                            {{-- 5. Dokumen Badge (Jika ada lampiran) --}}
                            @if ($item->lampiran)
                                <div class="mb-3">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold border border-gray-300 uppercase">
                                        📎 Ada Dokumen
                                    </span>
                                </div>
                            @endif

                            {{-- 6. Footer (Read More & Comments) --}}
                            <div class="mt-auto pt-4 border-t-2 border-gray-100 flex justify-between items-center">
                                <a href="{{ route('berita.show', $item->id) }}"
                                    class="text-xs font-bold text-black border-b-2 border-transparent hover:border-black transition uppercase tracking-wide">
                                    Baca Selengkapnya
                                </a>

                                {{-- Komentar Count --}}
                                <div class="flex items-center gap-1 text-gray-400 text-xs font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                        </path>
                                    </svg>
                                    {{ $item->total_komentar }}
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div
                        class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-gray-50 border-2 border-dashed border-gray-200 text-gray-400 italic">
                        Belum ada berita yang diterbitkan.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- FORM RAHASIA & SCRIPT --}}
    <form id="bulk-delete-form" action="{{ route('berita.bulk_delete') }}" method="POST">@csrf</form>

    <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

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
