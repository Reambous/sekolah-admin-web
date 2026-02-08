<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kegiatan Kurikulum') }}
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

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Kegiatan</h3>

                        {{-- Admin dan Guru boleh tambah data --}}
                        <a href="{{ route('kurikulum.kegiatan.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Input Kegiatan Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->tanggal }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                            {{ $item->nama_guru }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->nama_kegiatan }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 italic truncate max-w-xs">
                                            {{ Str::limit($item->refleksi, 40) }}</td>

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
                                                <a href="{{ route('kurikulum.kegiatan.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded-md transition hover:bg-blue-100">
                                                    Lihat Detail
                                                </a>

                                                {{-- 2. TOMBOL VALIDASI (Hanya Admin & Jika Pending) --}}
                                                {{-- Admin tetap butuh akses cepat ACC tanpa harus masuk detail --}}
                                                @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                    <form action="{{ route('kurikulum.kegiatan.approve', $item->id) }}"
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
                                                        action="{{ route('kurikulum.kegiatan.unapprove', $item->id) }}"
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
</x-app-layout>
