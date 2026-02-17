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
                        Edit Akun Pengguna
                    </h2>
                    <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Perbarui profil, peran, atau reset password pengguna
                    </p>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ $user->name }}" required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                        @error('name')
                            <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-6">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Email (Login)
                        </label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3">
                        @error('email')
                            <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="mb-8">
                        <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                            Peran Pengguna (Role)
                        </label>
                        <select name="role"
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm font-medium focus:bg-white focus:border-blue-900 focus:ring-0 transition-colors rounded-none px-4 py-3 cursor-pointer">
                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru / Staf Tata Usaha
                            </option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator (Akses
                                Penuh)</option>
                        </select>
                    </div>

                    {{-- SECTION RESET PASSWORD --}}
                    <div class="mt-8 pt-8 border-t-2  bg-gray-50 p-6 border border-gray-200">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-1">
                            Reset Password
                        </h3>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-4">
                            ⚠ Biarkan kosong jika tidak ingin mengubah password.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                    Password Baru
                                </label>
                                <input type="password" name="password"
                                    class="w-full bg-white border-2 border-gray-200 text-gray-900 text-sm font-medium focus:border-red-600 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-300"
                                    placeholder="Minimal 3 karakter">
                                @error('password')
                                    <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-900 uppercase tracking-widest mb-2">
                                    Konfirmasi Password
                                </label>
                                <input type="password" name="password_confirmation"
                                    class="w-full bg-white border-2 border-gray-200 text-gray-900 text-sm font-medium focus:border-red-600 focus:ring-0 transition-colors rounded-none px-4 py-3 placeholder-gray-300"
                                    placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-between pt-6 mt-6 border-t-2 border-gray-100">
                        <a href="{{ route('users.index') }}"
                            class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-black hover:underline transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-900 text-white px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-blue-800 transition shadow-sm border-b-4 border-blue-950 hover:border-black transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
