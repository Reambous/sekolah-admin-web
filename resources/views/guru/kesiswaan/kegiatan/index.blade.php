<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kegiatan Kesiswaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    {{-- PESAN ERROR (MERAH) --}}
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Peringatan!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif



                    {{-- 2. HEADER: JUDUL & TOMBOL HAPUS NEMPEL --}}
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-gray-900">Daftar Kegiatan</h3>

                            {{-- Tombol Hapus: Merah, Kecil, Nempel Judul --}}
                            @if (Auth::user()->role == 'admin')
                                {{-- GANTI BUTTON JADI SEPERTI INI --}}
                                <button type="button" id="btn-hapus-massal"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                    Hapus Terpilih
                                </button>
                                <a href="{{ route('kesiswaan.kegiatan.export') }}"
                                    class="px-2 py-1 bg-green-600 text-white rounded font-bold text-sm hover:bg-green-700 transition">📥
                                    Excel</a>
                            @endif
                        </div>

                        {{-- Tombol Tambah (Tetap di Kanan) --}}
                        <a href="{{ route('kesiswaan.kegiatan.create') }}"
                            class="px-4 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 transition">
                            + Input Kegiatan
                        </a>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- TAMBAHKAN INI (CHECKBOX ALL - ADMIN ONLY) --}}
                                    {{-- 3. CHECKBOX ALL (ADMIN ONLY) --}}
                                    @if (Auth::user()->role == 'admin')
                                        <th class="px-4 py-3 text-left w-10">
                                            <input type="checkbox" id="select-all"
                                                class="rounded border-gray-300 text-black focus:ring-black">
                                        </th>
                                    @endif

                                    {{-- ... lanjutkan th Tanggal dst ... --}}
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    {{-- Kolom Guru hanya muncul untuk Admin --}}
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
                                        {{-- TAMBAHKAN INI (CHECKBOX ITEM - ADMIN ONLY) --}}
                                        {{-- 4. CHECKBOX ITEM (ADMIN ONLY) --}}
                                        @if (Auth::user()->role == 'admin')
                                            <td class="px-4 py-3">
                                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                    class="item-checkbox rounded border-gray-300 text-black focus:ring-black">
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{-- Tanggal --}}
                                            <div class="font-bold">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>

                                            {{-- Jam (Ambil dari waktu input/created_at) --}}
                                            <div class="text-xs text-gray-500 mt-1">
                                                🕒 {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                            </div>
                                        </td>

                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 truncate max-w-[150px]">
                                            {{ $item->nama_guru }}
                                        </td>

                                        <td class="px-4 py-3 align-middle">
                                            {{-- w-40 : Membatasi lebar (sekitar 160px) --}}
                                            {{-- truncate : Memotong teks yang lewat (titik-titik...) --}}
                                            <div class="w-64 truncate font-bold text-gray-900"
                                                title="{{ $item->nama_kegiatan }}">
                                                {{ $item->nama_kegiatan }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 align-middle">
                                            {{-- w-64 : Membatasi lebar lebih panjang dikit (sekitar 250px) --}}
                                            <div class="w-40 truncate text-sm text-gray-500 italic"
                                                title="{{ $item->refleksi ?? $item->keterangan }}">
                                                {{ $item->refleksi ?? $item->keterangan }}
                                            </div>
                                        </td>

                                        {{-- Status Badge --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                            @endif
                                        </td>

                                        {{-- KOLOM AKSI (LOGIKA TOMBOL) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">

                                                {{-- 1. TOMBOL DETAIL (Semua Bisa Lihat) --}}
                                                <a href="{{ route('kesiswaan.kegiatan.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded-md transition hover:bg-blue-100">
                                                    Lihat Detail
                                                </a>

                                                {{-- 2. TOMBOL VALIDASI (Hanya Admin & Jika Pending) --}}
                                                {{-- Admin tetap butuh akses cepat ACC tanpa harus masuk detail --}}
                                                @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                    <form action="{{ route('kesiswaan.kegiatan.approve', $item->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded shadow ml-2 transition">
                                                            ✓ ACC
                                                        </button>
                                                    </form>
                                                @endif
                                                {{-- TOMBOL BATAL ACC --}}
                                                @if (Auth::user()->role == 'admin' && $item->status == 'disetujui')
                                                    <form
                                                        action="{{ route('kesiswaan.kegiatan.unapprove', $item->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Batalkan validasi ini?')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded shadow ml-2 transition">
                                                            X Batal
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data kegiatan.
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
    <form id="bulk-delete-form" action="{{ route('kesiswaan.kegiatan.bulk_delete') }}" method="POST"
        onsubmit="return confirm('Yakin hapus data terpilih?')">
        @csrf
        {{-- Input ID akan disisipkan via Javascript --}}
    </form>

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
