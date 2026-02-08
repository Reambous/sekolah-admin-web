<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesiswaanKegiatan; // Pastikan Model ini ada (nanti kita buat otomatis lewat Controller ini jika belum)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KesiswaanKegiatanController extends Controller
{
    // 1. TAMPILKAN DATA (INDEX)
    public function index()
    {
        // Jika Guru: Lihat data sendiri. Jika Admin: Lihat semua.
        $user = Auth::user();

        if ($user->role == 'guru') {
            $kegiatan = DB::table('kesiswaan_kegiatan')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $kegiatan = DB::table('kesiswaan_kegiatan')
                ->join('users', 'kesiswaan_kegiatan.user_id', '=', 'users.id')
                ->select('kesiswaan_kegiatan.*', 'users.name as nama_guru')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('guru.kesiswaan.kegiatan.index', compact('kegiatan'));
    }

    // 2. FORM TAMBAH DATA (CREATE)
    public function create()
    {
        return view('guru.kesiswaan.kegiatan.create');
    }

    // 3. SIMPAN DATA KE DATABASE (STORE)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'refleksi' => 'required|string',
        ]);

        DB::table('kesiswaan_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'status' => 'pending', // Default Pending
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kesiswaan.kegiatan.index')->with('success', 'Data berhasil disimpan!');
    }
}
