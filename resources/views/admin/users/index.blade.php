<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Akun Guru & Staf') }}
        </h2>
    </x-slot>

    {{-- CSS ANTI-GAGAL --}}
    <style>
        /* Default: Tampilkan HP, Sembunyikan Laptop */
        #tampilan-hp {
            display: block;
        }

        #tampilan-laptop {
            display: none;
        }

        /* Jika Layar LEBIH BESAR dari 768px (Laptop/Tablet), Tukar Posisi */
        @media (min-width: 768px) {
            #tampilan-hp {
                display: none !important;
            }

            #tampilan-laptop {
                display: block !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    {{-- NOTIFIKASI --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- HEADER: JUDUL & TOMBOL --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Pengguna Sistem</h3>

                        <a href="{{ route('users.create') }}"
                            class="w-full md:w-auto text-center px-4 py-2 bg-blue-900 text-white text-sm font-bold rounded-lg hover:bg-blue-800 transition shadow">
                            + Tambah Akun Baru
                        </a>
                    </div>

                    {{-- ======================================================== --}}
                    {{-- 1. TAMPILAN MOBILE (HP) - KARTU --}}
                    {{-- ======================================================== --}}
                    <div id="tampilan-hp" class="space-y-4">
                        @foreach ($users as $user)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 relative">

                                {{-- Badge Peran di Pojok --}}
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex flex-col">
                                        <span class="text-base font-bold text-gray-900">{{ $user->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                    </div>

                                    @if ($user->role == 'admin')
                                        <span
                                            class="px-2 py-1 text-[10px] font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-200">ADMIN</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-[10px] font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">GURU/STAF</span>
                                    @endif
                                </div>

                                {{-- Metadata --}}
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">
                                    📅 Bergabung: {{ $user->created_at->format('d M Y') }}
                                </div>

                                {{-- Action Buttons Mobile --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="flex-1 text-center bg-indigo-50 text-indigo-700 border border-indigo-200 px-3 py-2 rounded-lg font-bold text-xs">
                                        Edit / Reset
                                    </a>

                                    @if ($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="flex-1" onsubmit="return confirm('Hapus akun ini selamanya?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-full bg-red-50 text-red-600 border border-red-200 px-3 py-2 rounded-lg font-bold text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <div
                                            class="flex-1 text-center bg-gray-50 text-gray-400 border border-gray-200 px-3 py-2 rounded-lg font-bold text-[10px] flex items-center justify-center">
                                            Akun Anda
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- ======================================================== --}}
                    {{-- 2. TAMPILAN LAPTOP (DESKTOP) - TABEL --}}
                    {{-- ======================================================== --}}
                    <div id="tampilan-laptop"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Email (Login)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Peran (Role)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Bergabung</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900 truncate max-w-[200px]">
                                                {{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->role == 'admin')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800">Administrator</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">Guru
                                                    / Staf</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 font-bold text-xs bg-indigo-50 px-3 py-1 rounded border border-indigo-100 transition">Edit
                                                    / Reset</a>

                                                @if ($user->id !== Auth::id())
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus akun ini selamanya?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 font-bold text-xs bg-red-50 px-3 py-1 rounded border border-red-100 transition">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
