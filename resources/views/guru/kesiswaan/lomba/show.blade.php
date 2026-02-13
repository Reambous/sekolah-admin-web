<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Prestasi & Lomba') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="border-b pb-4 mb-6">
                        <span
                            class=" text-blue-800 text-xs font-bold  py-1 rounded uppercase tracking-wide break-words whitespace-normal">
                            {{ $lomba->jenis_lomba }}
                        </span>

                        {{-- GANTI BAGIAN INI: Tambahkan break-words dan leading-tight --}}
                        <h1 class="text-2xl font-bold text-gray-900 mt-2 break-words leading-tight">
                            {{ $lomba->prestasi }}
                        </h1>

                        {{-- Sisa kode tanggal & pembimbing biarkan sama --}}
                        <div class="flex flex-col sm:flex-row gap-4 mt-2 text-sm text-gray-500">
                            <span>📅
                                {{ \Carbon\Carbon::parse($lomba->tanggal)->translatedFormat('l, d F Y') }}</span>

                            {{-- Jam (Ambil dari waktu input/created_at) --}}
                            <span class="text-xs text-gray-500 mt-1">
                                🕒 {{ \Carbon\Carbon::parse($lomba->created_at)->format('H:i') }} WIB
                            </span>
                            <span>👤 {{ $lomba->nama_guru }}</span>
                        </div>
                        {{-- ... dst ... --}}
                    </div>
                    <div class="mb-8">
                        <h4 class="font-bold text-gray-700 mb-3">Daftar Anggota Tim / Peserta:</h4>

                        <div class="border rounded-lg shadow-sm bg-white">
                            {{-- HAPUS overflow-x-auto agar tabel dipaksa wrap ke bawah, bukan scroll ke samping --}}
                            <table class="w-full table-fixed border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        {{-- 1. KOLOM NO (Kunci 5%) --}}
                                        <th
                                            class="p-3 text-left text-xs font-bold text-gray-500 uppercase border-b w-[5%]">
                                            No
                                        </th>

                                        {{-- 2. KOLOM NAMA (Ambil 75% - Sisanya) --}}
                                        <th
                                            class="p-3 text-left text-xs font-bold text-gray-500 uppercase border-b w-[75%]">
                                            Nama Siswa
                                        </th>

                                        {{-- 3. KOLOM KELAS (Kunci 20% - Agar selalu terlihat) --}}
                                        <th
                                            class="p-3 text-left text-xs font-bold text-gray-500 uppercase border-b w-[20%]">
                                            Kelas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($peserta as $index => $p)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-3 text-sm text-gray-500 align-top border-b">
                                                {{ $index + 1 }}
                                            </td>

                                            {{-- NAMA: Paksa Text Wrapping --}}
                                            <td
                                                class="p-3 text-sm font-medium text-gray-900 align-top border-b break-words whitespace-normal">
                                                {{ $p->nama_siswa }}
                                            </td>

                                            <td class="p-3 text-sm text-gray-600 align-top border-b">
                                                {{ $p->kelas }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-6 text-sm text-red-500 italic text-center">
                                                Data peserta belum diinput.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-bold text-gray-700 mb-2">Catatan Tambahan:</h4>

                        {{-- GANTI DIV INI --}}
                        {{-- whitespace-pre-line: Menjaga enter/paragraf --}}
                        {{-- break-words: Memaksa kata panjang turun --}}
                        <div
                            class="bg-gray-50 p-4 rounded border text-gray-800 text-sm leading-relaxed whitespace-pre-line break-words text-justify">
                            {{ $lomba->refleksi ?: 'Tidak ada catatan tambahan.' }}
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-100 mt-4">

                        {{-- TOMBOL KEMBALI: Di HP jadi lebar penuh, di Laptop otomatis --}}
                        <a href="{{ route('kesiswaan.lomba.index') }}"
                            class="w-full sm:w-auto text-center text-gray-600 font-medium hover:text-gray-900 px-4 py-2 rounded hover:bg-gray-100 transition border border-gray-200 sm:border-transparent">
                            &larr; Kembali ke Daftar
                        </a>

                        {{-- GRUP TOMBOL AKSI --}}
                        <div class="flex flex-row gap-3 w-full sm:w-auto justify-center">

                            @php
                                $isAdmin = Auth::user()->role == 'admin';
                                $isOwner = $lomba->user_id == Auth::id();
                                $isPending = $lomba->status == 'pending';

                                // Boleh Edit Jika: Admin ATAU (Pemilik & Pending)
                                $canEdit = $isAdmin || ($isOwner && $isPending);
                            @endphp

                            @if ($canEdit)
                                {{-- Tombol Edit: flex-1 agar di HP bagi rata lebarnya --}}
                                <a href="{{ route('kesiswaan.lomba.edit', $lomba->id) }}"
                                    class="flex-1 sm:flex-none text-center bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow transition text-sm">
                                    Edit Data
                                </a>

                                {{-- Tombol Hapus: flex-1 agar di HP bagi rata lebarnya --}}
                                <form action="{{ route('kesiswaan.lomba.destroy', $lomba->id) }}" method="POST"
                                    class="flex-1 sm:flex-none" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-700 shadow transition text-sm">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                @if ($isOwner && !$isPending)
                                    <span
                                        class="w-full sm:w-auto flex items-center justify-center gap-2 text-gray-400 bg-gray-50 px-5 py-2 rounded-lg border border-gray-200 cursor-not-allowed text-xs font-semibold">
                                        🔒 Data Terkunci (Read Only)
                                    </span>
                                @endif
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
