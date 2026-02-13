<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Perizinan') }}
        </h2>
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- NOTIFIKASI --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- HEADER: JUDUL & TOMBOL --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-xl font-bold text-gray-900 whitespace-nowrap">Daftar Pengajuan</h3>

                            @if (Auth::user()->role == 'admin')
                                <div class="flex items-center gap-2 border-l pl-3 border-gray-300">
                                    <input type="checkbox" id="select-all"
                                        class="rounded border-gray-300 text-black focus:ring-black cursor-pointer w-5 h-5">
                                    <button type="button" id="btn-hapus-massal"
                                        class="bg-red-100 text-red-700 px-3 py-1.5 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                        Hapus Terpilih
                                    </button>
                                </div>
                                <a href="{{ route('ijin.export') }}"
                                    class="px-3 py-1.5 bg-green-600 text-white rounded font-bold text-xs hover:bg-green-700 transition flex items-center gap-1 whitespace-nowrap">
                                    📥 Export Excel
                                </a>
                            @endif
                            <a href="{{ route('ijin.create') }}"
                                class="px-3 py-1.5 bg-black text-white text-xs font-bold rounded hover:bg-gray-800 transition shadow-sm whitespace-nowrap">
                                + Ajukan Ijin
                            </a>
                        </div>


                    </div>

                    {{-- ======================================================== --}}
                    {{-- 1. TAMPILAN MOBILE (HP) - KARTU --}}
                    {{-- ======================================================== --}}
                    <div id="tampilan-hp" class="space-y-4">
                        @forelse($data_ijin as $item)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 relative">

                                {{-- Badge Status di Atas --}}
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-xs font-bold text-gray-500 uppercase">
                                            {{ \Carbon\Carbon::parse($item->mulai)->translatedFormat('d M Y') }}
                                            @if ($item->mulai != $item->selesai)
                                                <span class="text-gray-400"> s.d </span>
                                                {{ \Carbon\Carbon::parse($item->selesai)->translatedFormat('d M Y') }}
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wider">
                                            🕒 {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                        </div>
                                    </div>

                                    @if ($item->status == 'pending')
                                        <span
                                            class="px-2 py-1 text-[10px] font-bold rounded bg-yellow-100 text-yellow-800 border border-yellow-200">⏳
                                            Menunggu</span>
                                    @elseif($item->status == 'disetujui')
                                        <span
                                            class="px-2 py-1 text-[10px] font-bold rounded bg-green-100 text-green-800 border border-green-200">✅
                                            Disetujui</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-[10px] font-bold rounded bg-red-100 text-red-800 border border-red-200">❌
                                            Ditolak</span>
                                    @endif
                                </div>

                                {{-- Isi Informasi --}}
                                <div class="mb-4">
                                    <div class="text-xs font-bold text-blue-600 mb-1 uppercase tracking-wide">Guru:
                                        {{ $item->nama_guru }}</div>
                                    <p class="text-gray-700 text-sm font-medium leading-relaxed italic truncate">
                                        "{{ $item->alasan }}"
                                    </p>
                                </div>

                                {{-- Bukti & Checkbox Admin --}}
                                <div class="flex justify-between items-center">
                                    @if ($item->bukti_foto)
                                        <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                            class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-200 uppercase">
                                            📷 Lihat Bukti
                                        </a>
                                    @else
                                        <span
                                            class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded italic uppercase">Tanpa
                                            Bukti</span>
                                    @endif

                                    @if (Auth::user()->role == 'admin')
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="item-checkbox w-6 h-6 rounded border-gray-300 text-red-600 focus:ring-red-500 shadow-sm">
                                    @endif
                                </div>

                                {{-- Action Buttons Mobile --}}
                                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-2">
                                    <a href="{{ route('ijin.show', $item->id) }}"
                                        class="text-xs bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg font-extrabold uppercase">Detail</a>

                                    @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                        <form action="{{ route('ijin.approve', $item->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg font-bold shadow-sm uppercase">✓
                                                ACC</button>
                                        </form>
                                        <form action="{{ route('ijin.reject', $item->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold shadow-sm uppercase">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500 italic text-sm">
                                Belum ada riwayat perizinan.</div>
                        @endforelse
                    </div>

                    {{-- ======================================================== --}}
                    {{-- 2. TAMPILAN LAPTOP (DESKTOP) - TABEL --}}
                    {{-- ======================================================== --}}
                    <div id="tampilan-laptop"
                        class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if (Auth::user()->role == 'admin')
                                        <th class="px-4 py-3 text-left w-10"><input type="checkbox"
                                                id="select-all-desktop"
                                                class="rounded border-gray-300 text-black focus:ring-black"></th>
                                    @endif
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nama Guru</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Bukti</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($data_ijin as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        @if (Auth::user()->role == 'admin')
                                            <td class="px-4 py-3 align-middle">
                                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                    class="item-checkbox rounded border-gray-300 text-black focus:ring-black">
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($item->mulai)->translatedFormat('d M Y') }}
                                            </div>
                                            @if ($item->mulai != $item->selesai)
                                                <div class="text-[10px] text-gray-500 uppercase">s.d
                                                    {{ \Carbon\Carbon::parse($item->selesai)->translatedFormat('d M Y') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle font-medium text-gray-900">
                                            {{ $item->nama_guru }}</td>
                                        <td class="px-6 py-4 align-middle">
                                            <div class="text-sm text-gray-700 max-w-xs truncate"
                                                title="{{ $item->alasan }}">
                                                {{ $item->alasan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            @if ($item->bukti_foto)
                                                <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                                    class="text-blue-600 hover:underline font-bold text-xs">Lihat
                                                    Bukti</a>
                                            @else
                                                <span class="text-gray-400 italic text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <span
                                                class="px-2 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase
                                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($item->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium align-middle">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('ijin.show', $item->id) }}"
                                                    class="text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded border border-blue-200 text-xs">Detail</a>
                                                @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                    <form action="{{ route('ijin.approve', $item->id) }}"
                                                        method="POST">@csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs font-bold shadow-sm">✓
                                                            ACC</button>
                                                    </form>
                                                    <form action="{{ route('ijin.reject', $item->id) }}"
                                                        method="POST">@csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold shadow-sm">Tolak</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Bulk Delete --}}
    <form id="bulk-delete-form" action="{{ route('ijin.bulk_delete') }}" method="POST">@csrf</form>

    <script>
        // Handler Select All (Menyatukan Logika HP & Laptop)
        const selectAllHP = document.getElementById('select-all');
        const selectAllLaptop = document.getElementById('select-all-desktop');

        const syncCheckboxes = (checked) => {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = checked);
        };

        selectAllHP?.addEventListener('change', (e) => syncCheckboxes(e.target.checked));
        selectAllLaptop?.addEventListener('change', (e) => syncCheckboxes(e.target.checked));

        document.getElementById('btn-hapus-massal')?.addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('⚠️ Pilih minimal satu data!');
                return;
            }
            if (confirm('❓ Yakin hapus ' + checkboxes.length + ' data terpilih?')) {
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
