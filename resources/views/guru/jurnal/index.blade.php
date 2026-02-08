<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $kategori ? 'Jurnal Refleksi ' . ucfirst($kategori) : 'Semua Jurnal Refleksi' }}
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

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $kategori ? 'Daftar Catatan ' . ucfirst($kategori) : 'Semua Catatan Harian' }}
                        </h3>

                        <a href="{{ route('jurnal.create', ['kategori' => $kategori]) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Tulis Jurnal
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
                                        Kategori</th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Penulis</th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jurnals as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ ucfirst($item->kategori) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                            {{ $item->nama_guru }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                            {{ $item->judul_refleksi }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('jurnal.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-3 py-1 rounded-md transition hover:bg-blue-100">
                                                Baca Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                                            Belum ada catatan jurnal.
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
