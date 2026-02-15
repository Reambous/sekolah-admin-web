<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50 font-sans">

    {{-- CONTAINER: Lebar 95% agar mepet kanan-kiri --}}
    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- NAVBAR HEIGHT: h-20 (80px) --}}
        <div class="flex justify-between h-15">

            {{-- BAGIAN KIRI (LOGO & MENU) --}}
            <div class="flex">
                {{-- LOGO --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="font-bold text-xl text-gray-900 tracking-widest uppercase hover:text-gray-700 transition">
                        RUSMAN.ID
                    </a>
                </div>

                {{-- DESKTOP MENU --}}
                <div class="hidden space-x-1 lg:space-x-4 sm:-my-px sm:ml-6 sm:flex">

                    {{-- MENU BERANDA --}}
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('dashboard') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Beranda
                    </a>
                    <a href="{{ route('berita.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('berita.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Berita
                    </a>
                    {{-- DROPDOWN KESISWAAN (FIX POSISI) --}}
                    {{-- Wrapper ini harus h-full dan relative agar dropdown patokannya benar --}}
                    <div class="relative h-full" x-data="{ open: false }" @click.away="open = false">

                        {{-- Tombol Trigger --}}
                        <button @click="open = ! open"
                            class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full focus:outline-none gap-1
                            {{ request()->routeIs('kesiswaan.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <span>Kesiswaan</span>
                            <svg :class="{ 'rotate-180': open }" class="h-3 w-3 transition-transform duration-200"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- ISI DROPDOWN --}}
                        {{-- top-full: Turun persis di bawah garis navbar --}}
                        {{-- mt-0: Menghapus jarak agar nempel --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            class="absolute top-full left-0 z-50 bg-white border border-gray-200 shadow-xl min-w-[200px] py-2"
                            style="display: none;">

                            <a href="{{ route('kesiswaan.lomba.index') }}"
                                class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-gray-100 hover:text-black uppercase tracking-wide">
                                - Kegiatan Lomba
                            </a>
                            <a href="{{ route('kesiswaan.kegiatan.index') }}"
                                class="block px-4 py-3 text-xs font-bold text-gray-600 hover:bg-gray-100 hover:text-black uppercase tracking-wide">
                                - Kegiatan Kesiswaan
                            </a>
                        </div>
                    </div>

                    {{-- MENU LAINNYA --}}
                    <a href="{{ route('kurikulum.kegiatan.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('kurikulum.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Kurikulum
                    </a>

                    <a href="{{ route('humas.kegiatan.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('humas.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Humas
                    </a>

                    <a href="{{ route('sarpras.kegiatan.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('sarpras.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Sarpras
                    </a>

                    <a href="{{ route('ijin.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('ijin.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Ijin Guru
                    </a>

                    <a href="{{ route('jurnal.index') }}"
                        class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                        {{ request()->routeIs('jurnal.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Refleksi
                    </a>



                    @if (Auth::user()->role == 'admin')
                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center px-3 pt-1 border-b-2 text-xs font-bold uppercase tracking-wide transition duration-150 ease-in-out h-full
                            {{ request()->routeIs('users.*') ? 'border-black text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Kelola Akun
                        </a>
                    @endif

                </div>
            </div>

            {{-- BAGIAN KANAN (PROFILE) --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6 ">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = ! open"
                        class="inline-flex items-center gap-3 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                        <div class="flex flex-col text-right">
                            <span
                                class="font-bold uppercase text-xs tracking-wide truncate max-w-xs">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-gray-400">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <div
                            class="h-8 w-8 rounded bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>
                    <div x-show="open"
                        class="absolute right-0 z-50 mt-2 w-48 rounded shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                        style="display: none;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-4 py-2 text-xs font-bold uppercase text-gray-600 hover:bg-gray-100 hover:text-black text-left">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TOMBOL HAMBURGER (HP) --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE (RESPONSIVE) --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('dashboard') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Beranda</a>

            <div x-data="{ kesiswaanOpen: false }">
                <button @click="kesiswaanOpen = !kesiswaanOpen"
                    class="w-full flex justify-between items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-xs font-bold uppercase tracking-wide text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300 transition focus:outline-none">
                    <span>Kesiswaan</span>
                    <svg :class="{ 'rotate-180': kesiswaanOpen }"
                        class="w-4 h-4 transform transition-transform duration-200 text-gray-400" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="kesiswaanOpen" class="pl-6 space-y-1 bg-gray-50">
                    <a href="{{ route('kesiswaan.lomba.index') }}"
                        class="block py-2 pr-4 text-xs font-semibold uppercase text-gray-500 hover:text-black">-
                        Kegiatan Lomba</a>
                    <a href="{{ route('kesiswaan.kegiatan.index') }}"
                        class="block py-2 pr-4 text-xs font-semibold uppercase text-gray-500 hover:text-black">-
                        Kegiatan Kesiswaan</a>
                </div>
            </div>

            <a href="{{ route('kurikulum.kegiatan.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('kurikulum.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Kurikulum</a>
            <a href="{{ route('humas.kegiatan.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('humas.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Humas</a>
            <a href="{{ route('sarpras.kegiatan.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('sarpras.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Sarpras</a>
            <a href="{{ route('ijin.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('ijin.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Ijin
                Guru</a>
            <a href="{{ route('jurnal.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('jurnal.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Refleksi</a>
            <a href="{{ route('berita.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('berita.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Berita</a>

            @if (Auth::user()->role == 'admin')
                <a href="{{ route('users.index') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 text-xs font-bold uppercase tracking-wide transition {{ request()->routeIs('users.*') ? 'border-black text-black bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300' }}">Kelola
                    Akun</a>
            @endif
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50">
            <div class="px-4 flex items-center">
                <div
                    class="h-10 w-10 rounded bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-sm uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="ml-3">
                    <div class="font-bold text-sm text-gray-800 uppercase">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block px-4 py-2 text-xs font-bold uppercase text-gray-500 hover:text-gray-900 hover:bg-gray-100">Log
                        Out</a>
                </form>
            </div>
        </div>
    </div>
</nav>
