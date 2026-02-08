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
                            class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase tracking-wide">
                            {{ $lomba->jenis_lomba }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $lomba->prestasi }}</h1>
                        <div class="flex flex-col sm:flex-row gap-4 mt-2 text-sm text-gray-500">
                            <span>📅 {{ \Carbon\Carbon::parse($lomba->tanggal)->translatedFormat('l, d F Y') }}</span>
                            <span>👤 Pembimbing: <span
                                    class="font-semibold text-blue-600">{{ $lomba->nama_guru }}</span></span>
                        </div>

                        <div class="mt-3">
                            @if ($lomba->status == 'disetujui')
                                <span
                                    class="text-green-600 font-bold text-sm bg-green-50 px-3 py-1 rounded border border-green-200">
                                    ✅ Data Valid / Disetujui
                                </span>
                            @else
                                <span
                                    class="text-yellow-600 font-bold text-sm bg-yellow-50 px-3 py-1 rounded border border-yellow-200">
                                    ⏳ Menunggu Validasi
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="font-bold text-gray-700 mb-3">Daftar Anggota Tim / Peserta:</h4>
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase w-10">
                                            No</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Nama
                                            Siswa</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Kelas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($peserta as $index => $p)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ $index + 1 }}
                                            </td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $p->nama_siswa }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ $p->kelas }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                class="px-4 py-2 text-sm text-red-500 italic text-center">Data peserta
                                                belum diinput.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="font-bold text-gray-700 mb-2">Catatan Tambahan:</h4>
                        <div class="bg-gray-50 p-4 rounded border text-gray-700 text-sm italic leading-relaxed">
                            "{{ $lomba->refleksi ?: 'Tidak ada catatan tambahan.' }}"
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-100 mt-4">

                        <a href="{{ route('kesiswaan.lomba.index') }}"
                            class="text-gray-600 font-medium hover:text-gray-900 px-4 py-2 rounded hover:bg-gray-100 transition">
                            &larr; Kembali ke Daftar
                        </a>

                        <div class="flex gap-3">

                            @php
                                $isAdmin = Auth::user()->role == 'admin';
                                $isOwner = $lomba->user_id == Auth::id();
                                $isPending = $lomba->status == 'pending';

                                // Boleh Edit Jika: Admin ATAU (Pemilik & Pending)
                                $canEdit = $isAdmin || ($isOwner && $isPending);
                            @endphp

                            @if ($canEdit)
                                <a href="{{ route('kesiswaan.lomba.edit', $lomba->id) }}"
                                    class="bg-indigo-600 text-white px-5 py-2 rounded font-bold hover:bg-indigo-700 shadow transition">
                                    Edit Data
                                </a>

                                <form action="{{ route('kesiswaan.lomba.destroy', $lomba->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-600 text-white px-5 py-2 rounded font-bold hover:bg-red-700 shadow transition">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                {{-- Jika User adalah Pemilik tapi data sudah Valid --}}
                                @if ($isOwner && !$isPending)
                                    <span
                                        class="flex items-center gap-2 text-gray-400 bg-gray-100 px-4 py-2 rounded border border-gray-200 cursor-not-allowed">
                                        🔒 Data Terkunci (Read Only)
                                    </span>
                                @endif
                                {{-- Jika Guru Lain melihat data orang lain -> Kosong (Tidak ada tombol) --}}
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
