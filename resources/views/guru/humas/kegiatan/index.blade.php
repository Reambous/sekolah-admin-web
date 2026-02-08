<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Laporan Kegiatan Humas') }}</h2>
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

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong>Peringatan!</strong> {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Kegiatan Hubungan Masyarakat</h3>
                        <a href="{{ route('humas.kegiatan.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Input Kegiatan
                        </a>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kegiatan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Guru</th>
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
                                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                            {{ $item->nama_kegiatan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                            {{ $item->nama_guru }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded border border-yellow-200">
                                                    ⏳ Pending
                                                </span>
                                            @else
                                                <span
                                                    class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded border border-green-200">
                                                    ✅ Disetujui
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">

                                                {{-- 1. TOMBOL DETAIL (Semua Bisa Lihat) --}}
                                                <a href="{{ route('humas.kegiatan.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded transition hover:bg-blue-100">
                                                    Lihat Detail
                                                </a>

                                                {{-- 2. TOMBOL ACC (Khusus Admin & Pending) --}}
                                                @if (Auth::user()->role == 'admin' && $item->status == 'pending')
                                                    <form action="{{ route('humas.kegiatan.approve', $item->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button
                                                            class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs ml-2 font-bold shadow transition">
                                                            ✓ ACC
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- 3. TOMBOL BATAL ACC (Khusus Admin & Disetujui) --}}
                                                @if (Auth::user()->role == 'admin' && $item->status == 'disetujui')
                                                    <form action="{{ route('humas.kegiatan.unapprove', $item->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Batalkan validasi data ini?')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs ml-2 font-bold shadow transition">
                                                            X Batal
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">Belum ada
                                            laporan kegiatan Humas.</td>
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
