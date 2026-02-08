<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Kegiatan Sarpras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $kegiatan->nama_kegiatan }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Dilaporkan oleh: <span
                                    class="font-semibold text-blue-600">{{ $kegiatan->nama_guru }}</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Tanggal: {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            @if ($kegiatan->status == 'pending')
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full border border-yellow-200">
                                    ⏳ Pending
                                </span>
                            @else
                                <span
                                    class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full border border-green-200">
                                    ✅ Disetujui
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-2">Isi Refleksi / Catatan:</h4>
                        <div
                            class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $kegiatan->refleksi }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-100">

                        <a href="{{ route('sarpras.kegiatan.index') }}"
                            class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                            &larr; Kembali ke Daftar
                        </a>

                        <div class="flex gap-3">

                            {{-- LOGIKA SEDERHANA: --}}
                            {{-- Tampilkan tombol JIKA: User adalah Admin ATAU (User adalah Pemilik DAN Status masih Pending) --}}

                            @if (Auth::user()->role == 'admin' || (Auth::id() == $kegiatan->user_id && $kegiatan->status == 'pending'))
                                <a href="{{ route('sarpras.kegiatan.edit', $kegiatan->id) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded shadow transition">
                                    Edit Data
                                </a>

                                <form action="{{ route('sarpras.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded shadow transition">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                {{-- JIKA TIDAK BOLEH EDIT (Berarti Guru membuka data yang sudah Disetujui) --}}
                                <span
                                    class="text-gray-400 font-semibold flex items-center gap-2 cursor-not-allowed bg-gray-100 px-4 py-2 rounded">
                                    🔒 Data Terkunci (Read Only)
                                </span>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
