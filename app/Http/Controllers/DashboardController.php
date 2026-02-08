<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Wajib ada untuk cek user login

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cek Role
        if ($user->role === 'admin') {
            return view('admin.dashboard'); // Arahkan ke folder admin
        } elseif ($user->role === 'guru') {
            return view('guru.dashboard'); // Arahkan ke folder guru
        }

        // Jaga-jaga jika ada role lain (misal user biasa)
        return abort(403, 'Anda tidak memiliki akses.');
    }
}
