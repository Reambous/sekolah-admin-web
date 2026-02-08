<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JurnalRefleksiController extends Controller
{
    // 1. INDEX (Lihat Semua - Filter Kategori)
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $query = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->orderBy('tanggal', 'desc');

        if ($kategori) {
            $query->where('jurnal_refleksi.kategori', $kategori);
        }

        $jurnals = $query->get();

        return view('guru.jurnal.index', compact('jurnals', 'kategori'));
    }

    // 2. CREATE (Tangkap Kategori dari URL)
    public function create(Request $request)
    {
        $kategori = $request->query('kategori');
        if (!$kategori) $kategori = 'umum'; // Default
        return view('guru.jurnal.create', compact('kategori'));
    }

    // 3. STORE (Simpan Baru)
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

        return redirect()->route('jurnal.index', ['kategori' => $request->kategori])
            ->with('success', 'Catatan jurnal berhasil disimpan.');
    }

    // 4. SHOW (Detail)
    public function show($id)
    {
        $jurnal = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->where('jurnal_refleksi.id', $id)
            ->first();

        if (!$jurnal) abort(404);

        // Tidak ada logika abort(403) agar semua guru bisa saling membaca (Sharing Session)

        return view('guru.jurnal.show', compact('jurnal'));
    }

    // 5. EDIT (Form)
    public function edit($id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        // Cek Keberadaan Data
        if (!$jurnal) return redirect()->route('jurnal.index')->with('error', 'Data tidak ditemukan.');

        // Cek Izin: Hanya Admin atau Pemilik
        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) abort(403);

        return view('guru.jurnal.edit', compact('jurnal'));
    }

    // 6. UPDATE (Simpan Perubahan - DENGAN PENGAMAN)
    public function update(Request $request, $id)
    {
        // 1. Cek Data Lama
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        // 2. PENGAMAN: JIKA DATA HILANG (Dihapus Admin/Orang lain)
        if (!$jurnal) {
            // KITA LANGSUNG REDIRECT KE INDEX TANPA PARAMETER KATEGORI
            // (Karena datanya hilang, kita tidak tahu kategorinya apa, jadi kembalikan ke index utama)
            return redirect()->route('jurnal.index')
                ->with('error', 'Gagal menyimpan! Jurnal ini baru saja dihapus oleh admin.');
        }

        // 3. PENGAMAN: Cek Kepemilikan (Kecuali Admin)
        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) {
            abort(403);
        }

        // 4. Lakukan Update
        DB::table('jurnal_refleksi')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'judul_refleksi' => $request->judul_refleksi,
            'isi_refleksi' => $request->isi_refleksi,
            'updated_at' => now(),
        ]);

        return redirect()->route('jurnal.index', ['kategori' => $jurnal->kategori])
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    // 7. DESTROY (Hapus)
    public function destroy($id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        // Cek Keberadaan
        if (!$jurnal) return back()->with('error', 'Data sudah tidak ada.');

        // Cek Izin
        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) abort(403);

        DB::table('jurnal_refleksi')->where('id', $id)->delete();

        // Redirect kembali ke index dengan kategori yang sesuai
        return redirect()->route('jurnal.index', ['kategori' => $jurnal->kategori])
            ->with('success', 'Catatan jurnal dihapus.');
    }
}
