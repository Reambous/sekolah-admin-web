<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\KurikulumExport; // Pastikan ini ada!
use Maatwebsite\Excel\Facades\Excel;

class KurikulumKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = DB::table('kurikulum_kegiatan')
            ->join('users', 'kurikulum_kegiatan.user_id', '=', 'users.id')
            ->select('kurikulum_kegiatan.*', 'users.name as nama_guru')
            ->orderBy('created_at', 'desc')->get();
        return view('guru.kurikulum.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('guru.kurikulum.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['tanggal' => 'required|date', 'nama_kegiatan' => 'required', 'refleksi' => 'required']);
        DB::table('kurikulum_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('kurikulum.kegiatan.index')->with('success', 'Disimpan.');
    }

    public function show($id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')
            ->join('users', 'kurikulum_kegiatan.user_id', '=', 'users.id')
            ->select('kurikulum_kegiatan.*', 'users.name as nama_guru')
            ->where('kurikulum_kegiatan.id', $id)->first();
        if (!$kegiatan) {
            // 3. Jika KOSONG (sudah dihapus), lempar ke Dashboard dengan pesan error
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, link kegiatan tersebut sudah tidak tersedia atau telah dihapus.');
        }
        return view('guru.kurikulum.kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin' && ($kegiatan->user_id !== Auth::id() || $kegiatan->status !== 'pending')) abort(403);
        return view('guru.kurikulum.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')->where('id', $id)->first();

        // PENGAMAN UPDATE
        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') {
            return redirect()->route('kurikulum.kegiatan.index')->with('error', 'Gagal update! Data ini baru saja disetujui Admin.');
        }

        DB::table('kurikulum_kegiatan')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'updated_at' => now()
        ]);
        return redirect()->route('kurikulum.kegiatan.index')->with('success', 'Diperbarui.');
    }

    public function destroy($id)
    {
        $kegiatan = DB::table('kurikulum_kegiatan')->where('id', $id)->first();

        // PENGAMAN DELETE
        if ($kegiatan->status == 'disetujui') {
            return back()->with('error', 'Data Valid tidak bisa dihapus! Admin harus Batal ACC dulu.');
        }

        if (Auth::user()->role !== 'admin' && $kegiatan->user_id !== Auth::id()) abort(403);
        DB::table('kurikulum_kegiatan')->where('id', $id)->delete();
        return redirect()->route('kurikulum.kegiatan.index')->with('success', 'Dihapus.');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kurikulum_kegiatan')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Disetujui.');
    }

    public function unapprove($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kurikulum_kegiatan')->where('id', $id)->update(['status' => 'pending']);
        return back()->with('success', 'Batal ACC.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:kurikulum_kegiatan,id']);
        \Illuminate\Support\Facades\DB::table('kurikulum_kegiatan')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Data kurikulum terpilih dihapus.');
    }
    public function exportExcel()
    {
        return Excel::download(new KurikulumExport, 'rekap-kurikulum-kegiatan-' . now()->format('Y-m-d') . '.xlsx');
    }
}
