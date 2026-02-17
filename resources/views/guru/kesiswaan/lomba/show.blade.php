<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA --}}
            <div class="bg-white border-2 border-gray-200 p-8 md:p-10 shadow-sm relative">

                {{-- 1. HEADER BAGIAN ATAS (JUDUL & META) --}}
                <div class="border-b-4 border-gray-900 pb-6 mb-8">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="bg-blue-900 text-white text-xs font-bold px-3 py-1 uppercase tracking-widest break-words max-w-[70%]">
                            {{ $lomba->jenis_lomba }}
                        </span>

                        {{-- Tanggal Besar di Pojok Kanan --}}
                        <div class="text-right">
                            <div class="text-2xl font-black text-gray-900 leading-none">
                                {{ \Carbon\Carbon::parse($lomba->tanggal)->format('d') }}
                            </div>
                            <div class="text-xs font-bold text-gray-500 uppercase">
                                {{ \Carbon\Carbon::parse($lomba->tanggal)->translatedFormat('M Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Hasil / Prestasi (Judul Utama) --}}
                    <h1
                        class="text-3xl md:text-4xl font-black text-gray-900 mb-3 uppercase leading-tight tracking-tight break-words">
                        {{ $lomba->prestasi }}
                    </h1>

                    {{-- Meta Data Bar --}}
                    <div
                        class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-500 uppercase tracking-wide">
                        <span class="flex items-center gap-1">
                            <span class="text-blue-700">👤</span> Pelapor: {{ $lomba->nama_guru }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-1">
                            <span class="text-blue-700">🕒</span> Input:
                            {{ \Carbon\Carbon::parse($lomba->created_at)->format('H:i') }} WIB
                        </span>
                    </div>
                </div>

                {{-- 2. TABEL PESERTA --}}
                <div class="mb-10">
                    <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                        <span class="w-2 h-6 bg-yellow-400 inline-block"></span>
                        Daftar Anggota Tim
                    </h3>

                    <div class="overflow-hidden border border-gray-200">
                        <table class="w-full table-fixed border-collapse">
                            <thead class="bg-gray-900 text-white">
                                <tr>
                                    <th class="p-3 text-center text-xs font-black uppercase tracking-widest w-[10%]">No
                                    </th>
                                    <th class="p-3 text-left text-xs font-black uppercase tracking-widest w-[60%]">Nama
                                        Siswa</th>
                                    <th class="p-3 text-center text-xs font-black uppercase tracking-widest w-[30%]">
                                        Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($peserta as $index => $p)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="p-3 text-sm text-center font-bold text-gray-500 align-top">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="p-3 text-sm font-bold text-gray-900 align-top break-words uppercase">
                                            {{ $p->nama_siswa }}
                                        </td>
                                        <td
                                            class="p-3 text-sm text-center font-medium text-blue-700 align-top uppercase">
                                            {{ $p->kelas }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="p-6 text-sm text-gray-400 italic text-center bg-gray-50">
                                            Belum ada data peserta yang diinput.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. CATATAN TAMBAHAN --}}
                <div class="mb-10">
                    <h3 class="text-lg font-black text-gray-900 uppercase mb-4 flex items-center gap-2">
                        <span class="w-2 h-6 bg-gray-400 inline-block"></span>
                        Catatan Tambahan
                    </h3>
                    <div
                        class="bg-gray-50 p-6 border border-gray-200 text-gray-800 text-base leading-relaxed whitespace-pre-line text-justify font-serif break-words">
                        {{ $lomba->refleksi ?: 'Tidak ada catatan tambahan.' }}
                    </div>
                </div>

                {{-- 4. FOOTER / TOMBOL AKSI --}}
                <div
                    class="flex flex-col md:flex-row justify-between items-center pt-6 border-t-2 border-gray-100 gap-4 mt-8">

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('kesiswaan.lomba.index') }}"
                        class="text-xs font-bold text-gray-900 uppercase tracking-widest hover:text-blue-700 hover:underline transition">
                        &larr; Kembali ke Daftar
                    </a>

                    {{-- Grup Tombol Aksi --}}
                    <div class="flex gap-3 w-full md:w-auto">
                        @php
                            $isAdmin = Auth::user()->role == 'admin';
                            $isOwner = $lomba->user_id == Auth::id();
                            $isPending = $lomba->status == 'pending';
                            // Boleh Edit Jika: Admin ATAU (Pemilik & Pending)
                            $canEdit = $isAdmin || ($isOwner && $isPending);
                        @endphp

                        @if ($canEdit)
                            <a href="{{ route('kesiswaan.lomba.edit', $lomba->id) }}"
                                class="flex-1 md:flex-none text-center bg-yellow-400 text-black px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-yellow-500 transition shadow-sm">
                                Edit Data
                            </a>

                            <form action="{{ route('kesiswaan.lomba.destroy', $lomba->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data prestasi ini?')"
                                class="flex-1 md:flex-none">
                                @csrf @method('DELETE')
                                <button
                                    class="w-full md:w-auto bg-red-600 text-white px-5 py-3 text-xs font-black uppercase tracking-wider hover:bg-red-700 transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        @else
                            @if ($isOwner && !$isPending)
                                <span
                                    class="flex-1 md:flex-none text-center bg-gray-100 text-gray-400 border border-gray-300 px-5 py-3 text-xs font-black uppercase tracking-wider cursor-not-allowed">
                                    🔒 Data Terkunci
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
