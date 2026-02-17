<x-app-layout>
    <x-slot name="header">
        {{-- Header dikosongkan agar menyatu dengan body --}}
    </x-slot>

    <div class="py-12 bg-white min-h-screen font-sans text-gray-900">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- KARTU UTAMA --}}
            <div class="bg-white border-2 border-gray-200 p-8 shadow-sm relative">

                {{-- HEADER HALAMAN --}}
                <div class="border-b-4 border-gray-900 pb-4 mb-8">
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-1">
                        Tambah Akun Baru
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Buat akses baru untuk guru atau staf sekolah
                    </p>
                </div>

                {{-- NOTIFIKASI ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <strong class="font-black text-red-800 uppercase text-xs tracking-wide">Gagal
                                Menyimpan!</strong>
                        </div>
                        <ul class="list-disc list-inside text-red-700 text-sm font-medium mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400"
                            placeholder="Contoh: Budi Santoso, S.Pd">
                    </div>

                    {{-- Email --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Email (Untuk Login)
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-400"
                            placeholder="nama@sekolah.sch.id">
                    </div>

                    {{-- Role --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Peran Pengguna (Role)
                        </label>
                        <select name="role"
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 cursor-pointer">
                            <option value="guru">Guru / Staf Tata Usaha</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                    </div>

                    {{-- Password Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Password
                            </label>
                            <input type="password" name="password" required
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3"
                                placeholder="******">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-wide">* Minimal 3
                                Karakter</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3"
                                placeholder="******">
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <a href="{{ route('users.index') }}"
                            class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-gray-900 text-white px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-blue-900 transition shadow-sm border-b-4 border-black hover:border-blue-950 transform hover:-translate-y-0.5">
                            Simpan Akun
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
