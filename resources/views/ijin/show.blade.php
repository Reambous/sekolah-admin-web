<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Pengajuan Ijin') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Pengajuan Ijin</h1>
                        <p class="text-sm text-gray-500">Diajukan pada:
                            {{ \Carbon\Carbon::parse($ijin->created_at)->translatedFormat('l, d F Y, H:i') }}</p>
                    </div>

                    {{-- STATUS BADGE --}}
                    @if ($ijin->status == 'pending')
                        <span
                            class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold border border-yellow-200 shadow-sm">
                            ⏳ Menunggu Konfirmasi
                        </span>
                    @elseif($ijin->status == 'disetujui')
                        <span
                            class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold border border-green-200 shadow-sm">
                            ✅ Disetujui
                        </span>
                    @else
                        <span
                            class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold border border-red-200 shadow-sm">
                            ❌ Ditolak
                        </span>
                    @endif
                </div>

                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Guru</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $ijin->nama_guru }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Ijin</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ \Carbon\Carbon::parse($ijin->mulai)->translatedFormat('d M Y') }}
                                @if ($ijin->mulai != $ijin->selesai)
                                    <span class="text-gray-400 mx-1">-</span>
                                    {{ \Carbon\Carbon::parse($ijin->selesai)->translatedFormat('d M Y') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan / Alasan</p>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-800 leading-relaxed">
                            {{ $ijin->alasan }}
                        </div>
                    </div>

                    @if ($ijin->bukti_foto)
                        <div class="mb-8">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bukti Lampiran</p>
                            <div class="inline-block border p-1 rounded bg-white shadow-sm">
                                <a href="{{ asset('storage/' . $ijin->bukti_foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $ijin->bukti_foto) }}"
                                        class="max-h-64 rounded cursor-zoom-in hover:opacity-90 transition">
                                </a>
                            </div>
                        </div>
                    @else
                        <div
                            class="mb-8 p-4 bg-gray-50 border border-dashed border-gray-300 rounded text-center text-gray-400 italic text-sm">
                            Tidak ada bukti foto yang dilampirkan.
                        </div>
                    @endif

                    <div class="flex justify-between items-center pt-6 border-t border-gray-100">
                        <a href="{{ route('ijin.index') }}" class="text-gray-600 font-bold text-sm hover:text-gray-900">
                            &larr; Kembali
                        </a>

                        <div class="flex gap-3">
                            {{-- TOMBOL EDIT (Hanya Pemilik & Pending) --}}
                            @if ($ijin->user_id == Auth::id() && $ijin->status == 'pending')
                                <a href="{{ route('ijin.edit', $ijin->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded font-bold text-sm shadow transition">
                                    Edit Pengajuan
                                </a>
                            @endif

                            {{-- TOMBOL ADMIN (ACC / TOLAK) --}}
                            @if (Auth::user()->role == 'admin' && $ijin->status == 'pending')
                                <form action="{{ route('ijin.approve', $ijin->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button
                                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded font-bold text-sm shadow transition">Setujui
                                        (ACC)</button>
                                </form>
                                <form action="{{ route('ijin.reject', $ijin->id) }}" method="POST"
                                    onsubmit="return confirm('Tolak pengajuan ini?')">
                                    @csrf @method('PATCH')
                                    <button
                                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded font-bold text-sm shadow transition">Tolak</button>
                                </form>
                            @endif

                            {{-- TOMBOL HAPUS (Pending Only) --}}
                            @if ($ijin->status == 'pending' && (Auth::user()->role == 'admin' || $ijin->user_id == Auth::id()))
                                <form action="{{ route('ijin.destroy', $ijin->id) }}" method="POST"
                                    onsubmit="return confirm('Batalkan pengajuan ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="text-red-500 font-bold text-sm hover:underline px-3 py-2">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
