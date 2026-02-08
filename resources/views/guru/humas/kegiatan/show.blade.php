<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Kegiatan Humas') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="border-b pb-4 mb-6">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase tracking-wide">
                            HUMAS
                        </span>
                        <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $kegiatan->nama_kegiatan }}</h1>

                        <div class="flex flex-col sm:flex-row gap-4 mt-3 text-sm text-gray-600">
                            <span>📅
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }}</span>
                            <span>👤 Guru: <span
                                    class="font-semibold text-blue-600">{{ $kegiatan->nama_guru }}</span></span>
                        </div>

                        <div class="mt-4">
                            @if ($kegiatan->status == 'disetujui')
                                <span
                                    class="text-green-700 font-bold text-sm bg-green-100 px-3 py-1 rounded border border-green-200">
                                    ✅ Disetujui / Valid
                                </span>
                            @else
                                <span
                                    class="text-yellow-700 font-bold text-sm bg-yellow-100 px-3 py-1 rounded border border-yellow-200">
                                    ⏳ Menunggu Validasi (Pending)
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="font-bold text-gray-700 mb-2">Refleksi / Keterangan:</h4>
                        <div class="bg-gray-50 p-5 rounded-lg border text-gray-800 leading-relaxed whitespace-pre-line">
                            {{ $kegiatan->refleksi ?: '-' }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-100 mt-4">
                        <a href="{{ route('humas.kegiatan.index') }}"
                            class="text-gray-600 font-medium hover:text-gray-900 px-4 py-2 rounded hover:bg-gray-100 transition">
                            &larr; Kembali
                        </a>

                        <div class="flex gap-3">
                            @php
                                $isAdmin = Auth::user()->role == 'admin';
                                $isOwner = $kegiatan->user_id == Auth::id();
                                $isPending = $kegiatan->status == 'pending';
                                $isValid = $kegiatan->status == 'disetujui';
                            @endphp

                            {{-- TOMBOL EDIT (Admin ATAU Pemilik yg Pending) --}}
                            @if ($isAdmin || ($isOwner && $isPending))
                                <a href="{{ route('humas.kegiatan.edit', $kegiatan->id) }}"
                                    class="bg-indigo-600 text-white px-5 py-2 rounded font-bold hover:bg-indigo-700 shadow transition">
                                    Edit
                                </a>
                            @endif

                            {{-- TOMBOL HAPUS (HANYA JIKA PENDING) --}}
                            @if ($isPending && ($isAdmin || $isOwner))
                                <form action="{{ route('humas.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-600 text-white px-5 py-2 rounded font-bold hover:bg-red-700 shadow transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif

                            {{-- TOMBOL BATAL ACC (KHUSUS ADMIN JIKA VALID) --}}
                            @if ($isAdmin && $isValid)
                                <form action="{{ route('humas.kegiatan.unapprove', $kegiatan->id) }}" method="POST"
                                    onsubmit="return confirm('Kembalikan status ke Pending agar bisa diedit/dihapus?')">
                                    @csrf @method('PATCH')
                                    <button
                                        class="bg-orange-500 text-white px-5 py-2 rounded font-bold hover:bg-orange-600 shadow transition">
                                        Buka Gembok (Batal ACC)
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
