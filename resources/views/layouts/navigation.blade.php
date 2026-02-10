<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="font-extrabold text-2xl text-blue-900 tracking-tighter hover:text-blue-700 transition">
                        RUSMAN.ID
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">

                    <a href="{{ route('dashboard') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('dashboard') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Beranda
                    </a>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none transition {{ request()->routeIs('kesiswaan.*') ? 'text-gray-900 font-bold' : '' }}">
                            <span>Kesiswaan</span>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('kesiswaan.lomba.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kegiatan Lomba</a>
                                <a href="{{ route('kesiswaan.kegiatan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kegiatan
                                    Kesiswaan</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('kurikulum.kegiatan.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('kurikulum.*') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Kurikulum
                    </a>

                    <a href="{{ route('humas.kegiatan.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('humas.*') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Humas
                    </a>

                    <a href="{{ route('sarpras.kegiatan.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('sarpras.*') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Sarpras
                    </a>

                    <a href="{{ route('jurnal.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('jurnal.*') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Refleksi
                    </a>

                    <a href="{{ route('berita.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('berita.*') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Berita
                    </a>

                    <a href="{{ route('ijin.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition {{ request()->routeIs('ijin.*') ? 'text-gray-900 font-bold border-b-2 border-blue-500' : '' }}">
                        Ijin
                    </a>

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = ! open"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div class="flex flex-col text-right mr-2">
                            <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <div
                            class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>
                    <div x-show="open"
                        class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                        style="display: none;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
