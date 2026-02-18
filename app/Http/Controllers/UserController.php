<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // 1. DAFTAR USER
    public function index()
    {
        // Hanya Admin
        if (Auth::user()->role !== 'admin') abort(403);

        $users = User::orderBy('name', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. FORM TAMBAH USER
    public function create()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.users.create');
    }

    // 3. SIMPAN USER BARU
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:3'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), // Enkripsi Password
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 5. UPDATE USER (TERMASUK RESET PASSWORD)
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id], // Ignore current email
            'role' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', 'min:3'], // Nullable = Boleh kosong
        ]);

        // Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Jika Password Diisi (Reset Password), maka update. Jika kosong, biarkan lama.
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data akun diperbarui.');
    }

    // 6. HAPUS USER
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $user = User::findOrFail($id);

        // Mencegah Admin menghapus dirinya sendiri
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri yang sedang login!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') abort(403);
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:users,id']);
        \Illuminate\Support\Facades\DB::table('users')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'User terpilih dihapus.');
    }
}
