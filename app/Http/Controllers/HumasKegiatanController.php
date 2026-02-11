<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\HumasExport; // Pastikan ini ada!
use Maatwebsite\Excel\Facades\Excel;

class HumasKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = DB::table('humas_kegiatan')
            ->join('users', 'humas_kegiatan.user_id', '=', 'users.id')
            ->select('humas_kegiatan.*', 'users.name as nama_guru')
            ->orderBy('created_at', 'desc')->get();
        return view('guru.humas.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('guru.humas.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string',

            // UBAH JADI REQUIRED (Wajib)
            'refleksi' => 'required|string',
        ]);

        DB::table('humas_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,

            // HAPUS tanda tanya ganda (?? '-'), ambil langsung datanya
            'refleksi' => $request->refleksi,

            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('humas.kegiatan.index')->with('success', 'Laporan Humas disimpan.');
    }

    public function show($id)
    {
        $kegiatan = DB::table('humas_kegiatan')
            ->join('users', 'humas_kegiatan.user_id', '=', 'users.id')
            ->select('humas_kegiatan.*', 'users.name as nama_guru')
            ->where('humas_kegiatan.id', $id)->first();
        if (!$kegiatan) abort(404);
        return view('guru.humas.kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        $kegiatan = DB::table('humas_kegiatan')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin' && ($kegiatan->user_id !== Auth::id() || $kegiatan->status !== 'pending')) abort(403);
        return view('guru.humas.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = DB::table('humas_kegiatan')->where('id', $id)->first();

        // Pengaman Update (Kode standar)
        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') {
            return redirect()->route('humas.kegiatan.index')->with('error', 'Gagal update! Data ini baru saja disetujui Admin.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string',

            // UBAH JADI REQUIRED (Wajib)
            'refleksi' => 'required|string',
        ]);

        DB::table('humas_kegiatan')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,

            // HAPUS tanda tanya ganda (?? '-'), ambil langsung datanya
            'refleksi' => $request->refleksi,

            'updated_at' => now()
        ]);

        return redirect()->route('humas.kegiatan.index')->with('success', 'Data diupdate.');
    }

    public function destroy($id)
    {
        $kegiatan = DB::table('humas_kegiatan')->where('id', $id)->first();

        if ($kegiatan->status == 'disetujui') {
            return back()->with('error', 'Data Valid tidak bisa dihapus! Admin harus Batal ACC dulu.');
        }

        if (Auth::user()->role !== 'admin' && $kegiatan->user_id !== Auth::id()) abort(403);
        DB::table('humas_kegiatan')->where('id', $id)->delete();
        return redirect()->route('humas.kegiatan.index')->with('success', 'Dihapus.');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('humas_kegiatan')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Disetujui.');
    }

    public function unapprove($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('humas_kegiatan')->where('id', $id)->update(['status' => 'pending']);
        return back()->with('success', 'Batal ACC.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:humas_kegiatan,id']);
        \Illuminate\Support\Facades\DB::table('humas_kegiatan')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Data humas terpilih dihapus.');
    }
    public function exportExcel()
    {
        return Excel::download(new HumasExport, 'rekap-humas-kegiatan-' . now()->format('Y-m-d') . '.xlsx');
    }
}
