<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jurnal Refleksi & Catatan Harian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">

                    {{-- NOTIFIKASI --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong>Peringatan!</strong> {{ session('error') }}
                        </div>
                    @endif

                    {{-- HEADER: JUDUL & TOMBOL --}}
                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-gray-900">Daftar Semua Catatan</h3>

                            {{-- TOMBOL HAPUS MASSAL (HANYA ADMIN) --}}
                            @if (Auth::user()->role == 'admin')
                                <button type="button" id="btn-hapus-massal"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200 border border-red-200 transition">
                                    Hapus Terpilih
                                </button>
                                <a href="{{ route('jurnal.export') }}"
                                    class="px-2 py-1 bg-green-600 text-white rounded font-bold text-sm hover:bg-green-700 transition">📥
                                    Excel</a>
                            @endif
                        </div>

                        {{-- TOMBOL TULIS JURNAL --}}
                        <a href="{{ route('jurnal.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 transition">
                            + Tulis Jurnal Baru
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

                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">
                                        Tanggal
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-48">
                                        Penulis
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">
                                        Topik / Konteks
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Judul & Isi Singkat
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jurnals as $item)
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
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                🕒 {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                            </div>
                                        </td>


                                        {{-- KOLOM PENULIS --}}
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <div class="text-sm font-bold text-gray-700 truncate max-w-[150px]">
                                                {{ $item->nama_guru }}
                                            </div>
                                        </td>
                                        {{-- KOLOM TOPIK --}}
                                        <td class="px-3 py-4  align-middle whitespace-nowrap">
                                            {{-- max-w-[150px] : Batas lebar badge --}}
                                            {{-- truncate : Potong teks jika kepanjangan --}}
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold  text-gray-800   max-w-[150px] truncate"
                                                title="{{ $item->kategori }}">
                                                {{ $item->kategori }}
                                            </span>
                                        </td>



                                        {{-- KOLOM JUDUL & ISI (WRAPPING) --}}
                                        <td class="px-6 py-4 align-middle">
                                            {{-- JUDUL: Batas lebar 250px (w-64), potong sisanya --}}
                                            <div class="w-64 truncate text-sm font-bold text-gray-900"
                                                title="{{ $item->judul_refleksi }}">
                                                {{ $item->judul_refleksi }}
                                            </div>

                                            {{-- ISI: Batas lebar sama, potong sisanya --}}
                                            <div class="w-64 truncate text-xs text-gray-500 mt-1"
                                                title="{{ strip_tags($item->isi_refleksi) }}">
                                                {{ strip_tags($item->isi_refleksi) }}
                                            </div>
                                        </td>

                                        {{-- KOLOM AKSI --}}
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <a href="{{ route('jurnal.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded border border-blue-200 transition hover:bg-blue-100 text-xs">
                                                Baca Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                            Belum ada catatan jurnal yang dibuat.
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
    <form id="bulk-delete-form" action="{{ route('jurnal.bulk_delete') }}" method="POST">
        @csrf
        {{-- Input ID akan disisipkan via Javascript --}}
    </form>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // 1. Script Select All
        document.getElementById('select-all')?.addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // 2. Script Tombol Hapus dengan Konfirmasi
        document.getElementById('btn-hapus-massal')?.addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox:checked');

            if (checkboxes.length === 0) {
                alert('⚠️ Harap pilih minimal satu data untuk dihapus!');
                return;
            }

            if (confirm('❓ Apakah Anda YAKIN ingin menghapus ' + checkboxes.length +
                    ' data terpilih? Data yang dihapus tidak bisa dikembalikan.')) {

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
