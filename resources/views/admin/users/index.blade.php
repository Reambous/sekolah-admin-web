<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    {{-- CSS ANTI-GAGAL --}}
    <style>
        #tampilan-hp {
            display: block;
        }

        #tampilan-laptop {
            display: none;
        }

        @media (min-width: 768px) {
            #tampilan-hp {
                display: none !important;
            }

            #tampilan-laptop {
                display: block !important;
            }
        }
    </style>

    <div class="py-8 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-[95%] mx-auto">

            {{-- JUDUL HALAMAN (GAYA PORTAL) --}}
            <div
                class="border-b-4 border-gray-900 mb-8 pb-4 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Kelola Akun Pengguna
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Manajemen akses login Guru dan Staf Tata Usaha
                    </p>
                </div>

                {{-- TOMBOL AKSI UTAMA --}}
                <div>
                    <a href="{{ route('users.create') }}"
                        class="inline-block bg-gray-900 text-white px-5 py-2 text-xs font-bold uppercase tracking-wider hover:bg-yellow-500 hover:text-black transition shadow-lg transform hover:-translate-y-0.5">
                        + Tambah Akun Baru
                    </a>
                </div>
            </div>

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border-l-4 border-green-600 p-4 text-green-800 text-sm font-bold flex items-center gap-2 shadow-sm">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-6 bg-red-50 border-l-4 border-red-600 p-4 text-red-800 text-sm font-bold flex items-center gap-2 shadow-sm">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            {{-- 1. TAMPILAN MOBILE (HP) --}}
            <div id="tampilan-hp" class="space-y-4">
                @foreach ($users as $user)
                    <div
                        class="bg-white border-2 border-gray-100 p-5 shadow-sm relative group hover:border-blue-200 transition">

                        {{-- Header Kartu (Nama & Role) --}}
                        <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-2">
                            <div class="flex flex-col">
                                <span class="text-lg font-black text-gray-900 leading-tight">
                                    {{ $user->name }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mt-1">
                                    Bergabung: {{ $user->created_at->format('d M Y') }}
                                </span>
                            </div>

                            @if ($user->role == 'admin')
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase tracking-wide border bg-purple-50 text-purple-700 border-purple-200">
                                    ADMIN
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 text-[10px] font-bold uppercase tracking-wide border bg-blue-50 text-blue-700 border-blue-200">
                                    GURU/STAF
                                </span>
                            @endif
                        </div>

                        {{-- Isi Kartu (Email) --}}
                        <div class="mb-4">
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600 font-medium bg-gray-50 p-2 border border-gray-100">
                                <span class="text-xs">✉️</span> {{ $user->email }}
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="flex-1 text-center text-xs bg-gray-900 text-white px-3 py-2 font-bold uppercase tracking-wider hover:bg-blue-700 transition">
                                Edit / Reset
                            </a>

                            @if ($user->id !== Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Hapus akun ini selamanya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full text-center text-xs bg-red-600 text-white px-3 py-2 font-bold uppercase tracking-wider hover:bg-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <div
                                    class="flex-1 text-center text-[10px] font-bold text-gray-400 bg-gray-100 px-3 py-2 border border-gray-200 uppercase cursor-not-allowed">
                                    Akun Anda
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 2. TAMPILAN LAPTOP (DESKTOP) --}}
            <div id="tampilan-laptop" class="overflow-hidden border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-64">Nama
                                Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest">Email (Login)
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest w-40">Peran
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest w-40">Bergabung
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest w-48">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-blue-50 transition duration-150 group">

                                {{-- Nama --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle ">
                                    <div class="text-sm font-bold text-gray-900 truncate max-w-lg">
                                        {{ $user->name }}
                                    </div>
                                    @if ($user->id === Auth::id())
                                        <span
                                            class="text-[10px] font-bold text-green-600 uppercase tracking-wide">(Sedang
                                            Login)</span>
                                    @endif
                                </td>

                                {{-- Email --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle">
                                    <div class="text-sm font-medium text-gray-600 font-mono truncate max-w-xs">
                                        {{ $user->email }}
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td class="px-6 py-4 align-middle text-center">
                                    @if ($user->role == 'admin')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-none border border-purple-200 bg-purple-50 text-[10px] font-bold text-purple-700 uppercase tracking-wide">
                                            Administrator
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-none border border-blue-200 bg-blue-50 text-[10px] font-bold text-blue-700 uppercase tracking-wide">
                                            Guru / Staf
                                        </span>
                                    @endif
                                </td>

                                {{-- Tanggal --}}
                                <td
                                    class="px-6 py-4 whitespace-nowrap align-middle text-xs font-bold text-gray-500 uppercase">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-xs font-bold text-indigo-600 border-b-2 border-transparent hover:border-indigo-600 hover:text-black transition uppercase tracking-wide">
                                            Edit
                                        </a>

                                        @if ($user->id !== Auth::id())
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Hapus akun ini selamanya?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs font-bold text-red-600 border-b-2 border-transparent hover:border-red-600 hover:text-black transition uppercase tracking-wide">
                                                    Hapus
                                                </button>
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
</x-app-layout>
