<x-app-layout>
    <x-slot name="header">
        {{-- Header kosong agar tampilan menyatu --}}
    </x-slot>

    <div class=" bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-full mx-auto">

            {{-- 1. HERO SLIDER (CAROUSEL) --}}
            {{-- x-data: Ini adalah logic Javascript sederhana (Alpine.js) --}}
            <div x-data="{
                activeSlide: 0,
                slides: [
                    'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1600&auto=format&fit=crop', // Gambar 1 (Gedung Sekolah)
                    'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600&auto=format&fit=crop', // Gambar 2 (Siswa Belajar)
                    'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=1600&auto=format&fit=crop', // Gambar 3 (Perpustakaan)
                    'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1600&auto=format&fit=crop' // Gambar 4 (Guru Mengajar)
                ],
                loop() {
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
                    }, 5000); // 5000ms = 5 Detik ganti gambar
                }
            }" x-init="loop()"
                class="relative h-[800px] bg-gray-900 mb-8 overflow-hidden group border-b-4 border-blue-900 shadow-lg">

                {{-- LOOPING GAMBAR --}}
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0 transform scale-105"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-1000"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-100" class="absolute inset-0 w-full h-full">

                        {{-- Gambar --}}
                        <img :src="slide" class="absolute inset-0 w-full h-full object-cover opacity-50">
                    </div>
                </template>

                {{-- OVERLAY TEXT (TEKS SAMBUTAN TETAP DI DEPAN) --}}
                <div class="relative z-10 h-full flex flex-col justify-center px-8 md:px-16">
                    <div class="animate-fade-in-up">
                        <span
                            class="bg-blue-800 text-white text-xs font-bold px-3 py-1 uppercase tracking-widest inline-block mb-3 shadow-md">
                            Portal Resmi Sekolah
                        </span>
                        <h1
                            class="text-4xl md:text-6xl font-black text-white leading-tight mb-4 drop-shadow-lg uppercase tracking-tight">
                            Selamat Datang, <br>
                            <span class="text-yellow-400">{{ Auth::user()->name }}</span>
                        </h1>
                        <p
                            class="text-gray-100 text-lg md:text-xl font-light max-w-2xl border-l-4 border-yellow-400 pl-4 bg-black/30 p-2 backdrop-blur-sm">
                            Sistem Informasi Manajemen Terpadu. Kelola administrasi akademik dan kepegawaian dengan
                            profesional.
                        </p>
                    </div>
                </div>

                {{-- NAVIGASI DOTS (TITIK-TITIK DI BAWAH) --}}
                <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 z-20 flex space-x-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index"
                            :class="{
                                'bg-yellow-400 w-8': activeSlide ===
                                    index,
                                'bg-white/50 w-2 hover:bg-white': activeSlide !== index
                            }"
                            class="h-1 rounded-full transition-all duration-300"></button>
                    </template>
                </div>

            </div>


            <div class="text-center pl-28 pr-28 pb-8">Dashboard Guru SD Negeri 1 [Nama Kota]. Platform ini
                dirancang
                untuk mempermudah
                administrasi agar Bapak/Ibu bisa lebih fokus pada hal yang paling penting: Mengajar. Jangan lupa untuk
                selalu memberikan pelajaran yang baik. Mari wujudkan sekolah yang disiplin,
                bersih, dan berprestasi.</div>
            {{-- 2. STATS TICKER (BARIS DATA SINGKAT) --}}
            {{-- 3. SECTION BERITA, JUARA, & REFLEKSI --}}
            <div class="px-5 mb-10">

                {{-- BARIS 1: PRESTASI, KUTIPAN, REFLEKSI (GRID 3 KOLOM) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                    {{-- KOLOM 1: PRESTASI TERBARU --}}
                    <div class="bg-gray-900 text-white p-6 shadow-md h-full flex flex-col justify-center">
                        <div class="border-b border-gray-700 mb-4 pb-2 flex justify-between items-end">
                            <h4 class="text-sm font-black uppercase tracking-widest text-yellow-400">
                                Prestasi Terbaru
                            </h4>
                            <a href="{{ route('kesiswaan.lomba.index') }}"
                                class="text-[10px] font-bold text-yellow-400 hover:underline uppercase">
                                Lihat Semua
                            </a>
                        </div>
                        <ul class="space-y-4">
                            @forelse($juara_terbaru as $juara)
                                <li class="border-l-2 border-yellow-500 pl-3">
                                    <p class="text-xs font-bold text-gray-400 uppercase truncate"
                                        title="{{ $juara->jenis_lomba }}">
                                        {{ $juara->jenis_lomba }}
                                    </p>
                                    <p class="text-sm font-black italic text-white uppercase">
                                        {{ $juara->peringkat }}
                                    </p>
                                </li>
                            @empty
                                <li class="text-xs text-gray-500 italic">Belum ada data prestasi.</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- KOLOM 2: KUTIPAN (TENGAH) --}}
                    <div
                        class="bg-blue-50 border-l-4 border-blue-800 p-6 h-full flex flex-col justify-center items-center text-center shadow-sm">
                        <p class="text-sm text-gray-700 leading-relaxed font-medium italic">
                            "Setiap hari adalah kesempatan baru untuk membentuk masa depan. Ingatlah bahwa di tangan
                            Bapak/Ibu Guru, terdapat harapan dan mimpi ratusan siswa. Mari kita terus bersinergi
                            menciptakan lingkungan belajar yang menyentuh hati."
                        </p>
                    </div>

                    {{-- KOLOM 3: REFLEKSI GURU --}}
                    <div class="bg-white border border-gray-200 p-6  h-full flex flex-col justify-center">
                        <div class="border-b-2 border-blue-900 mb-4 pb-2 flex justify-between items-end">
                            <h4 class="text-sm font-black uppercase tracking-widest text-blue-900">
                                Refleksi Guru
                            </h4>
                            <a href="{{ route('jurnal.index') }}"
                                class="text-[10px] font-bold text-blue-600 hover:underline uppercase">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($refleksi_terbaru->take(3) as $key => $refleksi)
                                <div class="flex gap-4 group cursor-default">
                                    <span
                                        class="text-3xl font-black text-gray-200 group-hover:text-blue-900 transition select-none">
                                        {{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-700 transition truncate"
                                            title="{{ $refleksi->judul_refleksi }}">
                                            {{ $refleksi->judul_refleksi }}
                                        </p>
                                        <div
                                            class="flex items-center gap-1 mt-1 text-[10px] text-gray-400 uppercase font-bold">
                                            <span class="text-blue-600">{{ $refleksi->user->name ?? 'Guru' }}</span>
                                            <span>•</span>
                                            <span>{{ \Carbon\Carbon::parse($refleksi->tanggal)->format('d M') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic text-center">Belum ada catatan refleksi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- BARIS 2: BERITA UTAMA (FULL LEBAR) --}}
                <div class="bg-white border border-gray-200 p-8 shadow-sm">

                    {{-- HEADER BERITA --}}
                    <div class="border-b-2 border-black mb-6 pb-2 flex justify-between items-end">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Berita Utama</h3>
                        <a href="{{ route('berita.index') }}"
                            class="text-xs font-bold text-blue-800 hover:bg-blue-800 hover:text-white px-3 py-1 rounded transition uppercase tracking-wide mb-1">
                            Baca Semua Berita &rarr;
                        </a>
                    </div>

                    {{-- LIST BERITA (GRID 3 KOLOM AGAR RAPI KE SAMPING) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse($berita_terbaru->take(3) as $news)
                            <article
                                class="flex flex-col h-full border-b md:border-b-0 md:border-r border-gray-200 last:border-0 pr-0 md:pr-6 group">

                                {{-- GAMBAR --}}
                                @if ($news->gambar)
                                    <div class="mb-4 overflow-hidden border border-gray-100 h-48">
                                        <a href="{{ route('berita.show', $news->id) }}">
                                            <img src="{{ asset('storage/' . $news->gambar) }}"
                                                class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 ease-in-out"
                                                alt="Gambar Berita">
                                        </a>
                                    </div>
                                @else
                                    <div
                                        class="mb-4 h-48 bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-bold uppercase">
                                        Tanpa Gambar
                                    </div>
                                @endif

                                {{-- KONTEN --}}
                                <div class="flex-1 flex flex-col">
                                    <div class="text-xs font-bold text-blue-600 uppercase mb-2">
                                        {{ \Carbon\Carbon::parse($news->created_at)->format('d M Y') }}
                                    </div>

                                    <h2 class="text-lg font-bold text-gray-900 leading-tight mb-3 flex-1 truncate">
                                        <a href="{{ route('berita.show', $news->id) }}"
                                            class="hover:text-blue-800 transition">
                                            {{ $news->judul }}
                                        </a>
                                    </h2>

                                    <div class="text-gray-600 text-sm line-clamp-3 mb-3 truncate"
                                        title="{{ strip_tags($news->isi) }}">
                                        {{ Str::limit(strip_tags($news->isi), 100) }}
                                    </div>

                                    <a href="{{ route('berita.show', $news->id) }}"
                                        class="text-xs font-bold text-gray-900 uppercase border-b border-gray-900 w-max hover:text-blue-700 hover:border-blue-700 pb-0.5">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-3 py-10 text-center text-gray-400 italic">
                                Belum ada konten berita untuk ditampilkan.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
