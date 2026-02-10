<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Akun Baru') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8">

                    {{-- CEK GLOBAL ERROR --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                            <p class="font-bold">Gagal Menyimpan!</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email (Untuk Login)</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Peran (Role)</label>
                            <select name="role"
                                class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="guru">Guru / Staf</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="mb-6 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                                <input type="password" name="password"
                                    class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <p class="text-[10px] text-gray-400 mt-1">Minimal 3 karakter.</p>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <p class="text-[10px] text-gray-400 mt-1">Harus sama dengan password.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('users.index') }}"
                                class="px-5 py-2 bg-gray-100 rounded text-gray-700 font-bold text-sm hover:bg-gray-200">Batal</a>
                            <button type="submit"
                                class="px-5 py-2 bg-blue-900 text-white rounded font-bold text-sm hover:bg-blue-800">Simpan
                                Akun</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
