<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kegiatan Kesiswaan') }}
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
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-xl font-bold text-gray-900 whitespace-nowrap">Daftar Kegiatan </h3>

                            @if (Auth::user()->role == 'admin')
                                <div class="flex items-center gap-2 border-l pl-3 border-gray-300">
                                    <input type="checkbox" id="select-all"
                                        class="rounded border-gray-300 text-black focus:ring-black cursor-pointer w-5 h-5">
                                    <button type="button" id="btn-hapus-massal"
                                        class="bg-red-100 text-red-700 px-3 py-1.5 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                        Hapus Terpilih
                                    </button>
                                </div>
                                <a href="{{ route('kesiswaan.kegiatan.export') }}"
                                    class="px-3 py-1.5 bg-green-600 text-white rounded font-bold text-xs hover:bg-green-700 transition flex items-center gap-1 whitespace-nowrap">
                                    📥 Export Excel
                                </a>
                            @endif
                            <a href="{{ route('kesiswaan.kegiatan.create') }}"
                                class="px-3 py-1.5 bg-black text-white text-xs font-bold rounded hover:bg-gray-800 transition shadow-sm whitespace-nowrap">
                                + Input Kegiatan Kesiswaan
                            </a>
                        </div>
                    </div>

                    {{-- 1. TAMPILAN MOBILE (HP) --}}
                    <div id="tampilan-hp" class="space-y-4">
                        @forelse($kegiatan as $item)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 relative">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-xs font-bold text-gray-500 uppercase">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                                        <div class="text-[10px] text-gray-400">🕒
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</div>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-[10px] font-bold rounded {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <div class="text-xs font-bold text-blue-600 mb-1 truncate">{{ $item->nama_guru }}
                                    </div>
                                    <h4 class="text-gray-900 font-bold text-base leading-tight truncate">
                                        {{ $item->nama_kegiatan }}</h4>
                                    <p class="text-gray-500 text-xs italic mt-2 bg-gray-50 p-2 rounded truncate">
                                        "{{ $item->refleksi ?? ($item->keterangan ?? '-') }}"</p>
                                </div>
                                @if (Auth::user()->role == 'admin')
                                    <div class="absolute bottom-3 right-3 z-10">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="item-checkbox w-6 h-6 rounded border-gray-300 text-red-600 focus:ring-red-500 bg-gray-50">
                                    </div>
                                @endif
                                <div class="flex flex-wrap gap-2 pr-12 mt-4">
                                    <a href="{{ route('kesiswaan.kegiatan.show', $item->id) }}"
                                        class="text-xs bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded font-bold">Detail</a>
                                    @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                        <form action="{{ route('kesiswaan.kegiatan.approve', $item->id) }}"
                                            method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs bg-green-600 text-white px-3 py-1.5 rounded font-bold shadow-sm">✓
                                                ACC</button>
                                        </form>
                                    @endif
                                    @if (Auth::user()->role == 'admin' && $item->status == 'disetujui')
                                        <form action="{{ route('kesiswaan.kegiatan.unapprove', $item->id) }}"
                                            method="POST" onsubmit="return confirm('Batalkan?')">@csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-xs bg-red-100 text-red-700 border border-red-200 px-3 py-1.5 rounded font-bold">X
                                                Batal</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500 italic text-sm">
                                Belum ada data.</div>
                        @endforelse
                    </div>

                    {{-- 2. TAMPILAN LAPTOP (DESKTOP) --}}
                    <div id="tampilan-laptop" class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if (Auth::user()->role == 'admin')
                                        <th class="px-4 py-3 text-left w-10"><input type="checkbox" id="select-all"
                                                class="rounded border-gray-300 text-black focus:ring-black"></th>
                                    @endif
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Guru</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kegiatan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Refleksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($kegiatan as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        @if (Auth::user()->role == 'admin')
                                            <td class="px-4 py-3 align-middle"><input type="checkbox" name="ids[]"
                                                    value="{{ $item->id }}"
                                                    class="item-checkbox rounded border-gray-300 text-black focus:ring-black">
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-bold">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">🕒
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                            {{ $item->nama_guru }}</td>
                                        <td class="px-4 py-3 align-middle">
                                            <div class="w-64 truncate font-bold text-gray-900"
                                                title="{{ $item->nama_kegiatan }}">{{ $item->nama_kegiatan }}</div>
                                        </td>
                                        <td class="px-4 py-3 align-middle">
                                            <div class="w-40 truncate text-sm text-gray-500 italic"
                                                title="{{ $item->refleksi ?? $item->keterangan }}">
                                                {{ $item->refleksi ?? $item->keterangan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('kesiswaan.kegiatan.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded-md transition">Detail</a>
                                                @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                    <form action="{{ route('kesiswaan.kegiatan.approve', $item->id) }}"
                                                        method="POST">@csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded shadow">✓
                                                            ACC</button>
                                                    </form>
                                                @endif
                                                @if (Auth::user()->role == 'admin' && $item->status == 'disetujui')
                                                    <form
                                                        action="{{ route('kesiswaan.kegiatan.unapprove', $item->id) }}"
                                                        method="POST" onsubmit="return confirm('Batalkan?')">@csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded shadow">X
                                                            Batal</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada
                                            data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div> {{-- Penutup p-6 --}}
            </div> {{-- Penutup bg-white --}}
        </div> {{-- Penutup max-w-7xl --}}
    </div> {{-- Penutup py-12 --}}

    <form id="bulk-delete-form" action="{{ route('kesiswaan.kegiatan.bulk_delete') }}" method="POST">@csrf</form>
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
                alert('⚠️ Pilih minimal satu data!');
                return;
            }
            if (confirm('❓ Yakin hapus ' + checkboxes.length + ' data?')) {
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
