<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Riwayat Perizinan') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}</div>
            @endif

            {{-- HEADER: JUDUL & TOMBOL HAPUS MASSAL --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Pengajuan</h3>

                        {{-- TOMBOL HAPUS MASSAL (HANYA ADMIN) --}}
                        @if (Auth::user()->role == 'admin')
                            <button type="button" id="btn-hapus-massal"
                                class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                Hapus Terpilih
                            </button>
                            <a href="{{ route('ijin.export') }}"
                                class="px-2 py-1 bg-green-600 text-white rounded font-bold text-sm hover:bg-green-700 transition">📥
                                Excel</a>
                        @endif
                    </div>

                    <p class="text-sm text-gray-500 mt-1">
                        @if (Auth::user()->role == 'admin')
                            Data seluruh perizinan guru.
                        @else
                            Riwayat pengajuan ijin Anda.
                        @endif
                    </p>
                </div>

                {{-- TOMBOL AJUKAN IJIN --}}
                <a href="{{ route('ijin.create') }}"
                    class="px-4 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 transition shadow">
                    + Ajukan Ijin
                </a>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- CHECKBOX HEADER (ADMIN ONLY) --}}
                                @if (Auth::user()->role == 'admin')
                                    <th class="px-4 py-3 text-left w-10">
                                        <input type="checkbox" id="select-all"
                                            class="rounded border-gray-300 text-black focus:ring-black">
                                    </th>
                                @endif

                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Nama Guru</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-64">
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
                            @forelse($data_ijin as $item)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- CHECKBOX ROW (ADMIN ONLY) --}}
                                    @if (Auth::user()->role == 'admin')
                                        <td class="px-4 py-3 align-middle">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                class="item-checkbox rounded border-gray-300 text-black focus:ring-black">
                                        </td>
                                    @endif

                                    {{-- KOLOM TANGGAL & JAM --}}
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->mulai)->translatedFormat('d M Y') }}
                                        </div>
                                        @if ($item->mulai != $item->selesai)
                                            <div class="text-xs text-gray-500">
                                                s.d
                                                {{ \Carbon\Carbon::parse($item->selesai)->translatedFormat('d M Y') }}
                                            </div>
                                        @endif

                                        {{-- JAM INPUT --}}
                                        <div class="text-xs text-gray-400 mt-1">
                                            🕒 {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                        </div>
                                    </td>

                                    {{-- KOLOM NAMA GURU --}}
                                    <td class="px-6 py-4 whitespace-nowrap align-middle truncate max-w-[150px]">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_guru }}</div>
                                    </td>

                                    {{-- KOLOM KETERANGAN (TEXT WRAPPING & LIMIT) --}}
                                    <td class="px-6 py-4 align-middle">
                                        <div class="text-sm text-gray-700 font-medium break-words whitespace-normal max-w-xs line-clamp-2"
                                            title="{{ $item->alasan }}">
                                            {{ $item->alasan }}
                                        </div>
                                    </td>

                                    {{-- KOLOM BUKTI --}}
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        @if ($item->bukti_foto)
                                            <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank"
                                                class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold hover:bg-blue-100 border border-blue-200 flex items-center gap-1 w-fit">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        @endif
                                    </td>

                                    {{-- KOLOM STATUS --}}
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        @if ($item->status == 'pending')
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                ⏳ Menunggu
                                            </span>
                                        @elseif($item->status == 'disetujui')
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded bg-green-100 text-green-800 border border-green-200">
                                                ✅ Disetujui
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded bg-red-100 text-red-800 border border-red-200">
                                                ❌ Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    {{-- KOLOM AKSI --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium align-middle">
                                        <div class="flex items-center gap-2">

                                            {{-- TOMBOL DETAIL --}}
                                            <a href="{{ route('ijin.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded font-bold text-xs border border-blue-200">
                                                Detail
                                            </a>

                                            {{-- ADMIN ACTIONS (ACC / TOLAK) --}}
                                            @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                <form action="{{ route('ijin.approve', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded font-bold text-xs shadow-sm transition">ACC</button>
                                                </form>
                                                <form action="{{ route('ijin.reject', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded font-bold text-xs shadow-sm transition">Tolak</button>
                                                </form>
                                            @endif

                                            {{-- DELETE (Bisa jika Admin ATAU Pemilik yg masih pending) --}}

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                                        Belum ada data riwayat ijin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM RAHASIA DI BAWAH (UNTUK HAPUS MASSAL) --}}
    <form id="bulk-delete-form" action="{{ route('ijin.bulk_delete') }}" method="POST">
        @csrf
        {{-- Input ID akan disisipkan via Javascript --}}
    </form>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // 1. Script Select All (Centang Semua)
        document.getElementById('select-all')?.addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // 2. Script Tombol Hapus dengan Konfirmasi Rapi
        document.getElementById('btn-hapus-massal')?.addEventListener('click', function() {
            // Ambil semua yang dicentang
            let checkboxes = document.querySelectorAll('.item-checkbox:checked');

            // KASUS 1: Belum ada yang dipilih
            if (checkboxes.length === 0) {
                alert('⚠️ Harap pilih minimal satu data untuk dihapus!');
                return;
            }

            // KASUS 2: Konfirmasi Penghapusan
            if (confirm('❓ Apakah Anda YAKIN ingin menghapus ' + checkboxes.length +
                    ' data terpilih? Data yang dihapus tidak bisa dikembalikan.')) {

                let form = document.getElementById('bulk-delete-form');

                // Masukkan ID yang dipilih ke dalam form rahasia
                checkboxes.forEach(chk => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = chk.value;
                    form.appendChild(input);
                });

                // Kirim Form
                form.submit();
            }
        });
    </script>
</x-app-layout>
