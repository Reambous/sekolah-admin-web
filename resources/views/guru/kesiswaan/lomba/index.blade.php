<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Prestasi & Lomba') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">

                    {{-- NOTIFIKASI SUKSES --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- HEADER: JUDUL & TOMBOL HAPUS --}}
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-gray-900">Daftar Lomba</h3>

                            {{-- Tombol Hapus Massal (Hanya Admin) --}}
                            @if (Auth::user()->role == 'admin')
                                <button type="button" id="btn-hapus-massal"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                    Hapus Terpilih
                                </button>
                            @endif
                        </div>

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('kesiswaan.lomba.create') }}"
                            class="px-4 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 transition">
                            + Input Lomba
                        </a>
                    </div>

                    {{-- TABEL DATA --}}
                    <div class="overflow-x-auto border rounded-lg">
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

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-32">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">
                                        Nama Lomba
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-48">
                                        Juara / Hasil
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-24">
                                        Status
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-32">
                                        Pelapor
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-24">
                                        Aksi
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($lombas as $item)
                                    <tr class="hover:bg-gray-50 transition">

                                        {{-- CHECKBOX ROW (ADMIN ONLY) --}}
                                        @if (Auth::user()->role == 'admin')
                                            <td class="px-4 py-3">
                                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                    class="item-checkbox rounded border-gray-300 text-black focus:ring-black">
                                            </td>
                                        @endif

                                        {{-- KOLOM TANGGAL & WAKTU --}}
                                        <td class="px-4 py-3 whitespace-nowrap align-middle">
                                            <div class="font-bold text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                🕒 {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                            </div>
                                        </td>



                                        {{-- KOLOM NAMA LOMBA (DIPOTONG/TRUNCATE) --}}
                                        <td class="px-4 py-3 align-middle">
                                            {{-- Ubah jadi jenis_lomba sesuai controller --}}
                                            <div class="w-64 truncate font-bold text-gray-900"
                                                title="{{ $item->jenis_lomba }}">
                                                {{ $item->jenis_lomba }}
                                            </div>
                                        </td>

                                        {{-- KOLOM JUARA (DIPOTONG/TRUNCATE) --}}
                                        <td class="px-4 py-3 align-middle">
                                            {{-- Ubah jadi prestasi sesuai controller --}}
                                            <div class="w-40 truncate text-sm text-gray-600"
                                                title="{{ $item->prestasi }}">
                                                {{ $item->prestasi }}
                                            </div>
                                        </td>
                                        {{-- KOLOM STATUS --}}
                                        <td class="px-4 py-3 whitespace-nowrap align-middle">
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @endif
                                        </td>
                                        {{-- KOLOM PELAPOR --}}
                                        <td class="px-4 py-3 whitespace-nowrap align-middle">
                                            <span class="text-xs font-bold bg-gray-100 px-2 py-1 rounded border">
                                                {{ substr($item->nama_guru, 0, 10) }}..
                                            </span>
                                        </td>

                                        {{-- KOLOM AKSI --}}
                                        <td class="px-4 py-3 whitespace-nowrap align-middle">
                                            <div class="flex items-center gap-2">

                                                {{-- TOMBOL DETAIL --}}
                                                <a href="{{ route('kesiswaan.lomba.show', $item->id) }}"
                                                    class="text-blue-600 font-bold hover:underline text-xs bg-blue-50 px-3 py-1 rounded border border-blue-200">
                                                    Detail
                                                </a>

                                                {{-- LOGIKA ADMIN (ACC / BATAL) --}}
                                                @if (Auth::user()->role == 'admin')
                                                    {{-- Jika Pending -> Tampilkan Tombol ACC --}}
                                                    @if ($item->status == 'pending')
                                                        <form
                                                            action="{{ route('kesiswaan.lomba.approve', $item->id) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-2 py-1 rounded shadow ml-1">
                                                                ✓ ACC
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Jika Disetujui -> Tampilkan Tombol BATAL --}}
                                                    @if ($item->status == 'disetujui')
                                                        <form
                                                            action="{{ route('kesiswaan.lomba.unapprove', $item->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Batalkan validasi ini?')">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow ml-1">
                                                                X Batal
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                            Belum ada data lomba.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM RAHASIA DI BAWAH (UNTUK HAPUS MASSAL) --}}
    <form id="bulk-delete-form" action="{{ route('kesiswaan.lomba.bulk_delete') }}" method="POST">
        @csrf
        {{-- Input ID akan disisipkan via Javascript --}}
    </form>

    {{-- SCRIPT JAVA SCRIPT --}}
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
