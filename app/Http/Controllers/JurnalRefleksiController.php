<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JurnalRefleksiController extends Controller
{
    // 1. DAFTAR JURNAL
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        // LOGIKA BARU: Tampilkan SEMUA data
        $query = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->orderBy('tanggal', 'desc');

        // HAPUS filter "where user_id", biarkan semua guru melihat semua catatan
        // Filter Kategori tetap jalan
        if ($kategori) {
            $query->where('jurnal_refleksi.kategori', $kategori);
        }

        $jurnals = $query->get();

        return view('guru.jurnal.index', compact('jurnals', 'kategori'));
    }

    // 2. FORM TAMBAH
    // Terima parameter request untuk menangkap ?kategori=humas
    public function create(Request $request)
    {
        $kategori = $request->query('kategori'); // Ambil nilai kategori

        // Jika user nakal mencoba masuk tanpa kategori, default ke 'umum' atau tolak
        if (!$kategori) $kategori = 'umum';

        return view('guru.jurnal.create', compact('kategori'));
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

        // HAPUS LOGIKA ABORT(403) DI SINI.

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
