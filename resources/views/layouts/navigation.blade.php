<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl text-blue-800 tracking-tighter">
                        RUSMAN.ID
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">

                    <a href="{{ route('dashboard') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">
                        Beranda
                    </a>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none transition">
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
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Kegiatan Lomba
                                </a> <a href="{{ route('kesiswaan.kegiatan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kegiatan
                                    Kesiswaan</a>
                                <a href="{{ route('jurnal.index', ['kategori' => 'kesiswaan']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Refleksi Kesiswaan
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none transition">
                            <span>Kurikulum</span>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('kurikulum.kegiatan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Kegiatan Kurikulum
                                </a>
                                <a href="{{ route('jurnal.index', ['kategori' => 'kurikulum']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Refleksi Kurikulum
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none transition">
                            <span>Humas</span>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('humas.kegiatan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Kegiatan Humas
                                </a> <a href="{{ route('jurnal.index', ['kategori' => 'humas']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Refleksi Humas
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none transition">
                            <span>Sarpras</span>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('sarpras.kegiatan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Kegiatan Sarpras
                                </a>
                                <a href="{{ route('jurnal.index', ['kategori' => 'sarpras']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Refleksi Sarpras
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">
                        Umum
                    </a>

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = ! open"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
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
