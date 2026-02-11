<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 1. WELCOME BANNER --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-blue-500">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-gray-500 mt-1">
                        Selamat beraktivitas! Berikut adalah ringkasan kegiatan Anda hari ini.
                    </p>
                </div>
            </div>

            {{-- 2. STATS GRID (KARTU RINGKASAN) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                {{-- KARTU 1: STATUS IJIN --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase">Pending Approval</p>
                        <h4 class="text-2xl font-bold text-yellow-600">{{ $stats['ijin_pending'] }}</h4>
                        <p class="text-xs text-gray-400">Pengajuan Ijin</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                        ⏳
                    </div>
                </div>

                {{-- KARTU 2: JURNAL SAYA --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase">Jurnal Saya</p>
                        <h4 class="text-2xl font-bold text-blue-600">{{ $stats['jurnal_saya'] }}</h4>
                        <p class="text-xs text-gray-400">Total Catatan</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        📖
                    </div>
                </div>

                {{-- KARTU 3: KEGIATAN SARPRAS (ADMIN ONLY/UMUM) --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase">Sarpras Pending</p>
                        <h4 class="text-2xl font-bold text-purple-600">{{ $stats['kegiatan_sarpras'] }}</h4>
                        <p class="text-xs text-gray-400">Laporan Kegiatan</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                        🏢
                    </div>
                </div>

                {{-- KARTU 4: TOTAL BERITA --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase">Informasi</p>
                        <h4 class="text-2xl font-bold text-green-600">{{ $stats['berita_total'] }}</h4>
                        <p class="text-xs text-gray-400">Berita Sekolah</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        📢
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- 3. KOLOM KIRI (BERITA TERBARU & SHORTCUT) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- SHORTCUT BUTTONS --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            🚀 Akses Cepat
                        </h4>


                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('ijin.create') }}"
                                class="px-2 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded font-bold text-sm hover:bg-yellow-100 transition">
                                + Ajukan Ijin
                            </a>
                            <a href="{{ route('jurnal.create') }}"
                                class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded font-bold text-sm hover:bg-blue-100 transition">
                                + Tulis Refleksi
                            </a>
                            <a href="{{ route('sarpras.kegiatan.create') }}"
                                class="px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded font-bold text-sm hover:bg-purple-100 transition">
                                + Tulis Sarpras
                            </a>
                            @if (Auth::user()->role == 'admin')
                                <a href="{{ route('berita.create') }}"
                                    class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded font-bold text-sm hover:bg-green-100 transition">
                                    + Buat Berita
                                </a>
                                <a href="{{ route('download.semua') }}"
                                    class="px-2 py-1 bg-red-50 text-red-700 border border-red-200 rounded font-bold text-sm hover:bg-red-100 transition">
                                    📊 Download semuaLaporan (Excel)
                                </a>
                                {{-- Tombol Export Excel --}}
                            @endif


                        </div>
                    </div>

                    {{-- BERITA TERBARU --}}
                    {{-- BERITA TERBARU --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-800">📢 Papan Pengumuman Terbaru</h4>
                            <a href="{{ route('berita.index') }}"
                                class="text-xs text-blue-600 hover:underline font-bold">Lihat Semua</a>
                        </div>

                        <div class="space-y-4">
                            @forelse($berita_terbaru as $news)
                                <div
                                    class="flex gap-4 p-3 hover:bg-gray-50 rounded border border-transparent hover:border-gray-100 transition overflow-hidden">

                                    {{-- Tanggal Box (Diberi shrink-0 agar tidak gepeng) --}}
                                    <div
                                        class="hidden sm:flex flex-col items-center justify-center bg-gray-100 rounded p-2 w-16 h-16 text-center shrink-0">
                                        <span
                                            class="text-xs font-bold text-gray-500">{{ \Carbon\Carbon::parse($news->created_at)->format('M') }}</span>
                                        <span
                                            class="text-xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($news->created_at)->format('d') }}</span>
                                    </div>

                                    {{-- Wrapper Teks (Diberi overflow-hidden) --}}
                                    <div class="overflow-hidden w-full">

                                        {{-- JUDUL: truncate (Maks 1 Baris) --}}
                                        <h5 class="font-bold text-gray-900 text-sm truncate"
                                            title="{{ $news->judul }}">
                                            <a href="{{ route('berita.show', $news->id) }}"
                                                class="hover:text-blue-600">
                                                {{ $news->judul }}
                                            </a>
                                        </h5>

                                        {{-- ISI: line-clamp-2 (Maks 2 Baris) --}}
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                            {{ Str::limit(strip_tags($news->isi), 100) }}
                                        </p>

                                        <div class="mt-2 text-[10px] text-gray-400 uppercase font-bold flex gap-2">
                                            <span>✍️ {{ $news->penulis ?? 'Admin' }}</span>
                                            @if ($news->gambar)
                                                <span>📷 Ada Gambar</span>
                                            @endif
                                            @if ($news->lampiran)
                                                <span>📎 Ada Dokumen</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 italic text-sm">Belum ada berita terbaru.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- 4. KOLOM KANAN (RIWAYAT PRIBADI) --}}
                <div class="space-y-6">

                    {{-- RIWAYAT IJIN TERAKHIR --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-800 text-sm">⏳ Ijin Terakhir Saya</h4>
                            <a href="{{ route('ijin.index') }}" class="text-xs text-blue-600 hover:underline">Semua</a>
                        </div>
                        <ul class="space-y-3">
                            @forelse($ijin_terakhir as $ijin)
                                <li class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs font-bold text-gray-700">
                                                {{ \Carbon\Carbon::parse($ijin->mulai)->format('d M') }}
                                                - {{ \Carbon\Carbon::parse($ijin->selesai)->format('d M') }}
                                            </p>
                                            <p class="text-xs text-gray-500 w-32 mt-0.5 truncate max-w-[250px]">
                                                {{ $ijin->alasan }}
                                            </p>
                                        </div>
                                        <div>
                                            @if ($ijin->status == 'pending')
                                                <span
                                                    class="px-2 py-0.5 text-[10px] bg-yellow-100 text-yellow-700 rounded-full font-bold">Pending</span>
                                            @elseif($ijin->status == 'disetujui')
                                                <span
                                                    class="px-2 py-0.5 text-[10px] bg-green-100 text-green-700 rounded-full font-bold">Disetujui</span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 text-[10px] bg-red-100 text-red-700 rounded-full font-bold">Ditolak</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-xs text-gray-400 italic text-center">Belum ada pengajuan ijin.</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- RIWAYAT JURNAL TERAKHIR --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-800 text-sm">📖 Jurnal Terakhir Saya</h4>
                            <a href="{{ route('jurnal.index') }}"
                                class="text-xs text-blue-600 hover:underline">Semua</a>
                        </div>
                        <ul class="space-y-3">
                            @forelse($jurnal_terakhir as $jurnal)
                                <li class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                                    <a href="{{ route('jurnal.show', $jurnal->id) }}" class="block group">
                                        <p
                                            class="text-xs font-bold text-gray-700 group-hover:text-blue-600 transition truncate max-w-[250px]">
                                            {{ $jurnal->judul_refleksi }}
                                        </p>
                                        <div
                                            class="flex justify-between mt-1 text-[10px] text-gray-400 uppercase truncate max-w-[250px]">
                                            <span>{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}
                                            </span>


                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="text-xs text-gray-400 italic text-center">Belum ada jurnal.</li>
                            @endforelse
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
