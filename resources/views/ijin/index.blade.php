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
                        Riwayat Perizinan
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Daftar pengajuan ijin dan cuti guru/staf
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
                        <a href="{{ route('ijin.export') }}"
                            class="bg-green-700 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-green-800 transition shadow-sm flex items-center gap-2">
                            <span>📥</span> Export Excel
                        </a>
                    @endif

                    {{-- Input Baru --}}
                    <a href="{{ route('ijin.create') }}"
                        class="bg-gray-900 text-white px-5 py-2 text-xs font-bold uppercase tracking-wider hover:bg-yellow-500 hover:text-black transition shadow-lg transform hover:-translate-y-0.5">
                        + Ajukan Ijin
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
                @forelse($data_ijin as $item)
                    <div
                        class="bg-white border-2 border-gray-100 p-5 shadow-sm relative group hover:border-blue-200 transition">

                        {{-- Header Kartu (Tanggal & Status) --}}
                        <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-2">
                            <div>
                                <div class="text-xs font-black text-blue-700 uppercase tracking-wide">
                                    {{ \Carbon\Carbon::parse($item->mulai)->translatedFormat('d M Y') }}
                                    @if ($item->mulai != $item->selesai)
                                        <span class="text-gray-400 font-normal"> s.d </span>
                                        {{ \Carbon\Carbon::parse($item->selesai)->translatedFormat('d M Y') }}
                                    @endif
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">
                                    Diajukan: {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            @if ($item->status == 'pending')
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase tracking-wide border bg-yellow-50 text-yellow-700 border-yellow-200">⏳
                                    Menunggu</span>
                            @elseif($item->status == 'disetujui')
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase tracking-wide border bg-green-50 text-green-700 border-green-200">✅
                                    Disetujui</span>
                            @else
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase tracking-wide border bg-red-50 text-red-700 border-red-200">❌
                                    Ditolak</span>
                            @endif
                        </div>

                        {{-- Isi Kartu --}}
                        <div class="mb-4">
                            <div class="text-xs font-bold text-gray-500 uppercase mb-1">
                                Guru: <span class="text-black">{{ $item->nama_guru }}</span>
                            </div>
                            <p class="text-gray-600 text-sm italic bg-gray-50 p-3 border-l-2 border-gray-300">
                                "{{ $item->alasan }}"
                            </p>
                        </div>

                        {{-- Bukti Foto & Checkbox --}}
                        <div class="flex justify-between items-center mb-4">
                            @if ($item->bukti_foto)
                                <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                    class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-1 border border-indigo-200 uppercase hover:bg-indigo-100">
                                    📷 Lihat Bukti
                                </a>
                            @else
                                <span
                                    class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-1 uppercase border border-gray-200">
                                    Tanpa Bukti
                                </span>
                            @endif

                            @if (Auth::user()->role == 'admin')
                                <div class="z-10">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="item-checkbox w-6 h-6 border-2 border-gray-300 text-red-600 focus:ring-red-500 rounded-none">
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('ijin.show', $item->id) }}"
                                class="text-xs bg-gray-900 text-white px-3 py-1.5 font-bold uppercase tracking-wider hover:bg-blue-700">
                                Detail
                            </a>

                            @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                <form action="{{ route('ijin.approve', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-xs bg-green-600 text-white px-3 py-1.5 font-bold uppercase tracking-wider hover:bg-green-700 shadow-sm">
                                        ✓ ACC
                                    </button>
                                </form>
                                <form action="{{ route('ijin.reject', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-xs bg-red-600 text-white px-3 py-1.5 font-bold uppercase tracking-wider hover:bg-red-700 shadow-sm">
                                        ✕ Tolak
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="text-center py-10 bg-gray-50 border-2 border-dashed border-gray-200 text-gray-400 italic">
                        Belum ada riwayat perizinan.
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
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">
                                        PILIH
                                    </span>
                                </th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-40">Tanggal
                                Ijin</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-48">Nama Guru
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest">Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-24">Bukti</th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest w-32">Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest w-40">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data_ijin as $item)
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
                                        {{ \Carbon\Carbon::parse($item->mulai)->translatedFormat('d M Y') }}
                                    </div>
                                    @if ($item->mulai != $item->selesai)
                                        <div class="text-[10px] text-gray-500 font-bold uppercase mt-1">
                                            s.d {{ \Carbon\Carbon::parse($item->selesai)->translatedFormat('d M Y') }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Guru --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="text-sm font-bold text-blue-700 uppercase truncate max-w-xs">
                                        {{ $item->nama_guru }}
                                    </div>
                                </td>

                                {{-- Keterangan --}}
                                <td class="px-6 py-4 align-top">
                                    <div
                                        class="text-sm text-gray-600 italic truncate max-w-xs border-l-2 border-gray-300 pl-2">
                                        "{{ $item->alasan }}"
                                    </div>
                                </td>

                                {{-- Bukti --}}
                                <td class="px-6 py-4 align-top">
                                    @if ($item->bukti_foto)
                                        <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                            class="text-[10px] font-bold text-indigo-700 hover:underline uppercase">
                                            📷 Lihat
                                        </a>
                                    @else
                                        <span class="text-[10px] text-gray-400 font-bold uppercase ">-</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 align-top text-center">
                                    @if ($item->status == 'pending')
                                        <span
                                            class="px-2 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-wide border bg-yellow-50 text-yellow-700 border-yellow-300">
                                            Menunggu
                                        </span>
                                    @elseif($item->status == 'disetujui')
                                        <span
                                            class="px-2 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-wide border bg-green-50 text-green-700 border-green-300">
                                            Disetujui
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-wide border bg-red-50 text-red-700 border-red-300">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 align-top text-center">
                                    <div class="flex flex-col gap-2 items-center">
                                        <a href="{{ route('ijin.show', $item->id) }}"
                                            class="text-xs font-bold text-blue-600 hover:text-black border-b border-blue-600 hover:border-black transition uppercase tracking-wide">
                                            Lihat Detail
                                        </a>

                                        @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                            <div class="flex gap-1 w-full justify-center">
                                                <form action="{{ route('ijin.approve', $item->id) }}" method="POST"
                                                    class="w-1/2">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full bg-green-600 text-white text-[10px] font-bold px-1 py-1 uppercase hover:bg-green-700 transition shadow-sm">
                                                        ACC
                                                    </button>
                                                </form>
                                                <form action="{{ route('ijin.reject', $item->id) }}" method="POST"
                                                    class="w-1/2">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full bg-red-600 text-white text-[10px] font-bold px-1 py-1 uppercase hover:bg-red-700 transition shadow-sm">
                                                        TOLAK
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic bg-gray-50">
                                    Belum ada data pengajuan ijin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- SCRIPT PENDUKUNG (CHECKBOX) --}}
    <form id="bulk-delete-form" action="{{ route('ijin.bulk_delete') }}" method="POST">@csrf</form>
    <script>
        // Logic Checkbox untuk HP dan Laptop
        const selectAllHP = document.getElementById('select-all');
        const selectAllLaptop = document.getElementById(
            'select-all-desktop'); // Pastikan ID ini ada di checkbox header desktop jika dibuat

        const syncCheckboxes = (checked) => {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = checked);
        };

        if (selectAllHP) selectAllHP.addEventListener('change', (e) => syncCheckboxes(e.target.checked));
        // Jika Anda ingin menambahkan select all di desktop header, beri ID 'select-all-desktop' pada checkbox header tsb

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
