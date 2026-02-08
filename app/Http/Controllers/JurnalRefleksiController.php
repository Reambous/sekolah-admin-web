<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JurnalRefleksiController extends Controller
{
    // 1. DAFTAR JURNAL
    // 1. DAFTAR JURNAL (Bisa Filter Kategori)
    public function index(Request $request)
    {
        $user = Auth::user();
        $kategori = $request->query('kategori'); // Ambil data dari link (misal: ?kategori=humas)

        // Mulai Query Dasar
        $query = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->orderBy('tanggal', 'desc');

        // Filter 1: Jika Guru, hanya lihat punya sendiri. Admin lihat semua.
        if ($user->role !== 'admin') {
            $query->where('jurnal_refleksi.user_id', $user->id);
        }

        // Filter 2: Jika ada kategori spesifik yang diminta
        if ($kategori) {
            $query->where('jurnal_refleksi.kategori', $kategori);
        }

        $jurnals = $query->get();

        // Kirim data jurnal & info kategori yang sedang aktif ke View
        return view('guru.jurnal.index', compact('jurnals', 'kategori'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('guru.jurnal.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'judul_refleksi' => 'required|string|max:255',
            'isi_refleksi' => 'required|string',
        ]);

        DB::table('jurnal_refleksi')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'judul_refleksi' => $request->judul_refleksi,
            'isi_refleksi' => $request->isi_refleksi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // PESAN BARU:
        return redirect()->route('jurnal.index')->with('success', 'Terima kasih Bapak/Ibu! Catatan jurnal berhasil disimpan.');
    }

    // 4. DETAIL
    public function show($id)
    {
        $jurnal = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->where('jurnal_refleksi.id', $id)
            ->first();

        if (!$jurnal) abort(404);

        // Cek Akses (Admin boleh, Pemilik boleh)
        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.jurnal.show', compact('jurnal'));
    }

    // 5. FORM EDIT
    public function edit($id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        if ($jurnal->user_id !== Auth::id()) abort(403); // Hanya pemilik yg bisa edit

        return view('guru.jurnal.edit', compact('jurnal'));
    }

    // 6. UPDATE
    public function update(Request $request, $id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();
        if ($jurnal->user_id !== Auth::id()) abort(403);

        DB::table('jurnal_refleksi')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'judul_refleksi' => $request->judul_refleksi,
            'isi_refleksi' => $request->isi_refleksi,
            'updated_at' => now(),
        ]);

        // PESAN BARU:
        return redirect()->route('jurnal.index')->with('success', 'Berhasil! Jurnal refleksi telah diperbarui.');
    }

    // 7. HAPUS
    public function destroy($id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) abort(403);

        DB::table('jurnal_refleksi')->where('id', $id)->delete();

        // PESAN BARU:
        return redirect()->route('jurnal.index')->with('success', 'Data jurnal berhasil dihapus permanen.');
    }
}
