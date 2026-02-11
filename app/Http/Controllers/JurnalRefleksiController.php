<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\JurnalExport;
use Maatwebsite\Excel\Facades\Excel;

class JurnalRefleksiController extends Controller
{
    // 1. INDEX (Tampilkan Semua Jurnal)
    public function index()
    {
        // Ambil semua data, urutkan dari yang terbaru
        $jurnals = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.jurnal.index', compact('jurnals'));
    }

    // 2. CREATE (Form Tambah)
    public function create()
    {
        // Tidak perlu menangkap kategori dari URL lagi
        return view('guru.jurnal.create');
    }

    // 3. STORE (Simpan)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string', // User isi manual (Topik)
            'judul_refleksi' => 'required|string|max:255',
            'isi_refleksi' => 'required|string',
        ]);

        DB::table('jurnal_refleksi')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori, // Simpan text manual user
            'judul_refleksi' => $request->judul_refleksi,
            'isi_refleksi' => $request->isi_refleksi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Catatan jurnal berhasil disimpan.');
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

        return view('guru.jurnal.show', compact('jurnal'));
    }

    // 5. EDIT (Form Edit)
    public function edit($id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        if (!$jurnal) return redirect()->route('jurnal.index')->with('error', 'Data tidak ditemukan.');

        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) abort(403);

        return view('guru.jurnal.edit', compact('jurnal'));
    }

    // 6. UPDATE (Simpan Perubahan)
    public function update(Request $request, $id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();

        if (!$jurnal) {
            return redirect()->route('jurnal.index')->with('error', 'Gagal update! Data hilang.');
        }

        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) abort(403);

        DB::table('jurnal_refleksi')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori, // Update topik manual
            'judul_refleksi' => $request->judul_refleksi,
            'isi_refleksi' => $request->isi_refleksi,
            'updated_at' => now(),
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    // 7. DESTROY (Hapus)
    public function destroy($id)
    {
        $jurnal = DB::table('jurnal_refleksi')->where('id', $id)->first();
        if (!$jurnal) return back()->with('error', 'Data tidak ada.');

        if (Auth::user()->role !== 'admin' && $jurnal->user_id !== Auth::id()) abort(403);

        DB::table('jurnal_refleksi')->where('id', $id)->delete();

        return redirect()->route('jurnal.index')->with('success', 'Catatan dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') abort(403);
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:jurnal_refleksi,id']);
        \Illuminate\Support\Facades\DB::table('jurnal_refleksi')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Jurnal terpilih dihapus.');
    }

    public function exportExcel()
    {
        return Excel::download(new JurnalExport, 'rekap-jurnal-' . now()->format('Y-m-d') . '.xlsx');
    }
}
