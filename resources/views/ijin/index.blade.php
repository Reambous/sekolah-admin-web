<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Riwayat Perizinan') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}</div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar Pengajuan</h3>
                    <p class="text-sm text-gray-500">
                        {{-- Bedakan teks untuk Admin dan Guru --}}
                        @if (Auth::user()->role == 'admin')
                            Data seluruh perizinan guru.
                        @else
                            Riwayat pengajuan ijin Anda.
                        @endif
                    </p>
                </div>
                <a href="{{ route('ijin.create') }}"
                    class="px-4 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 transition shadow">
                    + Ajukan Ijin
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Nama Guru</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->mulai)->translatedFormat('d M Y') }}
                                        </div>
                                        @if ($item->mulai != $item->selesai)
                                            <div class="text-xs text-gray-500">
                                                s.d
                                                {{ \Carbon\Carbon::parse($item->selesai)->translatedFormat('d M Y') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_guru }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p
                                            class="text-sm text-gray-700 font-medium break-words whitespace-normal max-w-xs">
                                            {{ $item->alasan }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
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

                                    <td class="px-6 py-4 whitespace-nowrap">
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

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            {{-- TOMBOL DETAIL (BARU) --}}
                                            <a href="{{ route('ijin.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded font-bold text-xs border border-blue-200">
                                                Detail
                                            </a>
                                            {{-- ADMIN ACTIONS --}}
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
                                            @if (Auth::user()->role == 'admin' || ($item->user_id == Auth::id() && $item->status == 'pending'))
                                                <form action="{{ route('ijin.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Batalkan pengajuan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="text-gray-400 hover:text-red-600 transition"
                                                        title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
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
</x-app-layout>
