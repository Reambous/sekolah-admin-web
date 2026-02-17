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

            {{-- JUDUL HALAMAN (GAYA PORTAL) --}}
            <div
                class="border-b-4 border-gray-900 mb-8 pb-4 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Laporan Kegiatan Kurikulum
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Kelola data kegiatan akademik dan pembelajaran sekolah
                    </p>
                </div>

                {{-- TOMBOL AKSI UTAMA --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if (Auth::user()->role == 'admin')
                        {{-- Hapus Massal --}}
                        <div class="flex items-center gap-2 border-r border-gray-300 pr-3 mr-1">
                            <input type="checkbox" id="select-all"
                                class="w-5 h-5 border-2 border-gray-400 text-gray-900 focus:ring-gray-900">
                            <button type="button" id="btn-hapus-massal"
                                class="bg-red-600 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
                                Hapus Terpilih
                            </button>
                        </div>

                        {{-- Export Excel --}}
                        <a href="{{ route('kurikulum.kegiatan.export') }}"
                            class="bg-green-700 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-green-800 transition shadow-sm flex items-center gap-2">
                            <span>📥</span> Export Excel
                        </a>
                    @endif

                    {{-- Input Baru --}}
                    <a href="{{ route('kurikulum.kegiatan.create') }}"
                        class="bg-gray-900 text-white px-5 py-2 text-xs font-bold uppercase tracking-wider hover:bg-yellow-500 hover:text-black transition shadow-lg transform hover:-translate-y-0.5">
                        + Input Kegiatan Baru
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
            @if (session('error'))
                <div
                    class="mb-6 bg-red-50 border-l-4 border-red-600 p-4 text-red-800 text-sm font-bold flex items-center gap-2 shadow-sm">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            {{-- 1. TAMPILAN MOBILE (HP) --}}
            <div id="tampilan-hp" class="space-y-4">
                @forelse($kegiatan as $item)
                    <div
                        class="bg-white border-2 border-gray-100 p-5 shadow-sm relative group hover:border-blue-200 transition">
                        <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-2">
                            <div>
                                <div class="text-xs font-black text-blue-700 uppercase tracking-wide">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                </div>
                            </div>
                            <span
                                class="px-2 py-1 text-[10px] font-bold uppercase tracking-wide border {{ $item->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-green-50 text-green-700 border-green-200' }}">
                                {{ $item->status }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-lg font-black text-gray-900 leading-tight mb-1 truncate">
                                {{ $item->nama_kegiatan }}
                            </h4>
                            <div class="text-xs font-bold text-gray-500 uppercase mb-3 truncate">
                                Oleh: <span class="text-black">{{ $item->nama_guru }}</span>
                            </div>
                            <p class="text-gray-600 text-sm italic bg-gray-50 p-3 border-l-2 border-gray-300 truncate">
                                "{{ $item->refleksi ?? ($item->keterangan ?? '-') }}"
                            </p>
                        </div>

                        @if (Auth::user()->role == 'admin')
                            <div class="absolute bottom-4 right-4 z-10">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                    class="item-checkbox w-6 h-6 border-2 border-gray-300 text-red-600 focus:ring-red-500 rounded-none">
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('kurikulum.kegiatan.show', $item->id) }}"
                                class="text-xs bg-gray-900 text-white px-3 py-1.5 font-bold uppercase tracking-wider hover:bg-blue-700">
                                Detail
                            </a>

                            @if (Auth::user()->role == 'admin')
                                @if ($item->status == 'pending')
                                    <form action="{{ route('kurikulum.kegiatan.approve', $item->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs bg-green-600 text-white px-3 py-1.5 font-bold uppercase tracking-wider hover:bg-green-700">
                                            ✓ ACC
                                        </button>
                                    </form>
                                @elseif ($item->status == 'disetujui')
                                    <form action="{{ route('kurikulum.kegiatan.unapprove', $item->id) }}"
                                        method="POST" onsubmit="return confirm('Batalkan?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs bg-red-600 text-white px-3 py-1.5 font-bold uppercase tracking-wider hover:bg-red-700">
                                            ✕ Batal
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="text-center py-10 bg-gray-50 border-2 border-dashed border-gray-200 text-gray-400 italic">
                        Belum ada data kegiatan.
                    </div>
                @endforelse
            </div>

            {{-- 2. TAMPILAN LAPTOP (DESKTOP) --}}
            <div id="tampilan-laptop" class="overflow-hidden border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            @if (Auth::user()->role == 'admin')
                                <th class="px-4 py-4 text-center w-12">
                                    {{-- GANTI INPUT JADI TEKS --}}
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">
                                        PILIH
                                    </span>
                                </th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-32">Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-48">Guru</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest">Nama Kegiatan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-64">
                                Refleksi/Ket</th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest w-28">Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest w-40">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kegiatan as $item)
                            <tr class="hover:bg-blue-50 transition duration-150 group">
                                @if (Auth::user()->role == 'admin')
                                    <td class="px-4 py-4 text-center align-middle bg-gray-50 group-hover:bg-blue-50">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="item-checkbox w-5 h-5 border-2 border-gray-300 text-black focus:ring-black rounded-none">
                                    </td>
                                @endif

                                {{-- Tanggal --}}
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400 font-bold mt-1">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                    </div>
                                </td>

                                {{-- Guru --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="text-sm font-bold text-blue-700 uppercase truncate max-w-xs">
                                        {{ $item->nama_guru }}
                                    </div>
                                </td>

                                {{-- Nama Kegiatan --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="text-sm font-bold text-gray-900 leading-snug truncate max-w-xs">
                                        {{ $item->nama_kegiatan }}
                                    </div>
                                </td>

                                {{-- Refleksi --}}
                                <td class="px-6 py-4 align-top">
                                    <div
                                        class="text-sm text-gray-500 italic truncate w-64 border-l-2 border-gray-300 pl-2">
                                        "{{ $item->refleksi ?? ($item->keterangan ?? '-') }}"
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 align-top text-center">
                                    <span
                                        class="px-3 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-wide border {{ $item->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-300' : 'bg-green-50 text-green-700 border-green-300' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 align-top text-center">
                                    <div class="flex flex-col gap-2 items-center">
                                        <a href="{{ route('kurikulum.kegiatan.show', $item->id) }}"
                                            class="text-xs font-bold text-blue-600 hover:text-black border-b border-blue-600 hover:border-black transition uppercase tracking-wide">
                                            Lihat Detail
                                        </a>

                                        @if (Auth::user()->role == 'admin')
                                            @if ($item->status == 'pending')
                                                <form action="{{ route('kurikulum.kegiatan.approve', $item->id) }}"
                                                    method="POST" class="w-full">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full bg-green-600 text-white text-[10px] font-bold px-2 py-1 uppercase hover:bg-green-700 transition shadow-sm">
                                                        ✓ ACC
                                                    </button>
                                                </form>
                                            @elseif ($item->status == 'disetujui')
                                                <form action="{{ route('kurikulum.kegiatan.unapprove', $item->id) }}"
                                                    method="POST" onsubmit="return confirm('Batalkan?')"
                                                    class="w-full">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full bg-red-600 text-white text-[10px] font-bold px-2 py-1 uppercase hover:bg-red-700 transition shadow-sm">
                                                        ✕ Batal
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic bg-gray-50">
                                    Belum ada data kegiatan kurikulum.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- SCRIPT PENDUKUNG (CHECKBOX) --}}
    <form id="bulk-delete-form" action="{{ route('kurikulum.kegiatan.bulk_delete') }}" method="POST">@csrf</form>
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
                alert('⚠️ Harap pilih minimal satu data untuk dihapus!');
                return;
            }
            if (confirm('❓ Apakah Anda YAKIN ingin menghapus ' + checkboxes.length + ' data terpilih?')) {
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
