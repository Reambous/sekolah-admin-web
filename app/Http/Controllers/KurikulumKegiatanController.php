<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KurikulumKegiatanController extends Controller
{
    // 1. INDEX (LIHAT SEMUA)
    public function index()
    {
        $kegiatan = DB::table('kurikulum_kegiatan')
            ->join('users', 'kurikulum_kegiatan.user_id', '=', 'users.id')
            ->select('kurikulum_kegiatan.*', 'users.name as nama_guru')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.kurikulum.kegiatan.index', compact('kegiatan'));
    }

    // 2. CREATE (FORM)
    public function create()
    {
        return view('guru.kurikulum.kegiatan.create');
    }

    // 3. STORE (SIMPAN)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'refleksi' => 'required|string',
        ]);

        DB::table('kurikulum_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kurikulum.kegiatan.index')->with('success', 'Laporan kurikulum berhasil disimpan.');
    }

    // 4. SHOW (DETAIL)
    public function show($id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')
            ->join('users', 'kurikulum_kegiatan.user_id', '=', 'users.id')
            ->select('kurikulum_kegiatan.*', 'users.name as nama_guru')
            ->where('kurikulum_kegiatan.id', $id)
            ->first();

        if (!$kegiatan) abort(404);

        return view('guru.kurikulum.kegiatan.show', compact('kegiatan'));
    }

    // 5. EDIT
    public function edit($id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')->where('id', $id)->first();

        // Cek Izin: Admin ATAU (Pemilik & Pending)
        if (Auth::user()->role !== 'admin') {
            if ($kegiatan->user_id !== Auth::id()) abort(403);
            if ($kegiatan->status !== 'pending') abort(403);
        }

        return view('guru.kurikulum.kegiatan.edit', compact('kegiatan'));
    }

    // 6. UPDATE
    public function update(Request $request, $id)
    {
        // Cek Izin
        $kegiatan = DB::table('kurikulum_kegiatan')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') abort(403);

        DB::table('kurikulum_kegiatan')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'updated_at' => now(),
        ]);

        return redirect()->route('kurikulum.kegiatan.index')->with('success', 'Laporan diperbarui.');
    }

    // 7. DESTROY
    public function destroy($id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') abort(403);

        DB::table('kurikulum_kegiatan')->where('id', $id)->delete();
        return redirect()->route('kurikulum.kegiatan.index')->with('success', 'Data dihapus.');
    }

    // 8. APPROVE
    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kurikulum_kegiatan')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Laporan disetujui.');
    }

    // 9. BATALKAN ACC (UNAPPROVE)
    public function unapprove($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        DB::table('kurikulum_kegiatan')
            ->where('id', $id)
            ->update(['status' => 'pending', 'updated_at' => now()]);

        return back()->with('success', 'Status dikembalikan ke Pending.');
    }
}
