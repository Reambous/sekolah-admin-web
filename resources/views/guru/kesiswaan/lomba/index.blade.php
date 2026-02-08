<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Data Prestasi & Lomba') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}</div>
                    @endif

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Juara & Lomba</h3>

                        {{-- Tombol Input muncul untuk semua (Admin & Guru) --}}
                        <a href="{{ route('kesiswaan.lomba.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Input Lomba
                        </a>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Perwakilan Siswa</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lomba & Prestasi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pembimbing</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($lombas as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-bold">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                        </td>

                                        {{-- KOLOM SISWA --}}
                                        <td class="px-6 py-4">
                                            @php
                                                $firstPeserta = $item->peserta->first();
                                                $totalPeserta = $item->peserta->count();
                                            @endphp

                                            @if ($firstPeserta)
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $firstPeserta->nama_siswa }}</div>
                                                <div class="text-xs text-gray-500">{{ $firstPeserta->kelas }}</div>

                                                @if ($totalPeserta > 1)
                                                    <div class="text-xs text-blue-600 font-semibold mt-1">
                                                        + {{ $totalPeserta - 1 }} Anggota Lainnya
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-red-500 text-xs italic">Data peserta kosong</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-medium">{{ $item->jenis_lomba }}
                                            </div>
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded border border-yellow-200 mt-1 inline-block">
                                                {{ $item->prestasi }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->nama_guru }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded">⏳
                                                    Pending</span>
                                            @else
                                                <span
                                                    class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded">✅
                                                    Valid</span>
                                            @endif
                                        </td>

                                        {{-- KOLOM AKSI (LOGIKA PENTING DI SINI) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">

                                                {{-- 1. TOMBOL DETAIL (Semua Bisa Lihat) --}}
                                                <a href="{{ route('kesiswaan.lomba.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded transition hover:bg-blue-100">
                                                    Lihat Detail
                                                </a>

                                                {{-- 2. TOMBOL ACC (Khusus Admin biar kerja cepat) --}}
                                                @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                    <form action="{{ route('kesiswaan.lomba.approve', $item->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button
                                                            class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs ml-2 font-bold shadow transition">
                                                            ✓ ACC
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            lomba.</td>
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
