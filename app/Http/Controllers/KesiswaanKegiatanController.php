<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KesiswaanKegiatanController extends Controller
{
    // 1. INDEX (Lihat Semua)
    public function index()
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')
            ->join('users', 'kesiswaan_kegiatan.user_id', '=', 'users.id')
            ->select('kesiswaan_kegiatan.*', 'users.name as nama_guru')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.kesiswaan.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('guru.kesiswaan.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['tanggal' => 'required|date', 'nama_kegiatan' => 'required', 'refleksi' => 'required']);

        DB::table('kesiswaan_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('kesiswaan.kegiatan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show($id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')
            ->join('users', 'kesiswaan_kegiatan.user_id', '=', 'users.id')
            ->select('kesiswaan_kegiatan.*', 'users.name as nama_guru')
            ->where('kesiswaan_kegiatan.id', $id)->first();

        if (!$kegiatan) abort(404);
        return view('guru.kesiswaan.kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();

        // Cek Izin Edit
        if (Auth::user()->role !== 'admin') {
            if ($kegiatan->user_id !== Auth::id()) abort(403);
            if ($kegiatan->status !== 'pending') abort(403);
        }
        return view('guru.kesiswaan.kegiatan.edit', compact('kegiatan'));
    }

    // UPDATE DENGAN PENGAMAN
    public function update(Request $request, $id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();

        // PENGAMAN: Jika status sudah tidak pending (sudah di-ACC), tolak edit.
        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') {
            return redirect()->route('kesiswaan.kegiatan.index')->with('error', 'Gagal update! Data ini baru saja disetujui Admin.');
        }

        DB::table('kesiswaan_kegiatan')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'updated_at' => now()
        ]);

        return redirect()->route('kesiswaan.kegiatan.index')->with('success', 'Data berhasil diperbarui.');
    }

    // DESTROY DENGAN PENGAMAN
    public function destroy($id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();

        // PENGAMAN: Jika status sudah Valid, tidak boleh dihapus
        if ($kegiatan->status == 'disetujui') {
            return back()->with('error', 'Data Valid tidak bisa dihapus! Admin harus Batal ACC dulu.');
        }

        if (Auth::user()->role !== 'admin' && $kegiatan->user_id !== Auth::id()) abort(403);

        DB::table('kesiswaan_kegiatan')->where('id', $id)->delete();
        return redirect()->route('kesiswaan.kegiatan.index')->with('success', 'Data dihapus.');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kesiswaan_kegiatan')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Data disetujui.');
    }

    public function unapprove($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kesiswaan_kegiatan')->where('id', $id)->update(['status' => 'pending']);
        return back()->with('success', 'Status dikembalikan ke Pending.');
    }
}
