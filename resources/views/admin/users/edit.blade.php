<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Akun') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8">

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}"
                                class="w-full rounded border-gray-300 text-sm" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}"
                                class="w-full rounded border-gray-300 text-sm" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Peran (Role)</label>
                            <select name="role" class="w-full rounded border-gray-300 text-sm">
                                <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru / Staf
                                </option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator
                                </option>
                            </select>
                        </div>

                        <div class="border-t border-gray-200 my-6 pt-6">
                            <h3 class="text-md font-bold text-gray-900 mb-1">Reset Password</h3>
                            <p class="text-xs text-gray-500 mb-4">Biarkan kosong jika tidak ingin mengganti password
                                user.</p>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password"
                                        class="w-full rounded border-gray-300 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi
                                        Password</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full rounded border-gray-300 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('users.index') }}"
                                class="px-5 py-2 bg-gray-100 rounded text-gray-700 font-bold text-sm">Batal</a>
                            <button type="submit"
                                class="px-5 py-2 bg-blue-900 text-white rounded font-bold text-sm hover:bg-blue-800">Simpan
                                Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
