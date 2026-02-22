<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\SarprasExport;
use Maatwebsite\Excel\Facades\Excel;


class SarprasKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = DB::table('sarpras_kegiatan')
            ->join('users', 'sarpras_kegiatan.user_id', '=', 'users.id')
            ->select('sarpras_kegiatan.*', 'users.name as nama_guru')
            ->orderBy('created_at', 'desc')->get();
        return view('guru.sarpras.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('guru.sarpras.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['tanggal' => 'required|date', 'nama_kegiatan' => 'required', 'refleksi' => 'required']);
        DB::table('sarpras_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('sarpras.kegiatan.index')->with('success', 'Disimpan.');
    }

    public function show($id)
    {
        $kegiatan = DB::table('sarpras_kegiatan')
            ->join('users', 'sarpras_kegiatan.user_id', '=', 'users.id')
            ->select('sarpras_kegiatan.*', 'users.name as nama_guru')
            ->where('sarpras_kegiatan.id', $id)->first();
        if (!$kegiatan) {
            // 3. Jika KOSONG (sudah dihapus), lempar ke Dashboard dengan pesan error
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, link kegiatan tersebut sudah tidak tersedia atau telah dihapus.');
        }
        return view('guru.sarpras.kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        $kegiatan = DB::table('sarpras_kegiatan')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin' && ($kegiatan->user_id !== Auth::id() || $kegiatan->status !== 'pending')) abort(403);
        return view('guru.sarpras.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = DB::table('sarpras_kegiatan')->where('id', $id)->first();

        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') {
            return redirect()->route('sarpras.kegiatan.index')->with('error', 'Gagal update! Data ini baru saja disetujui Admin.');
        }

        DB::table('sarpras_kegiatan')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'updated_at' => now()
        ]);
        return redirect()->route('sarpras.kegiatan.index')->with('success', 'Diperbarui.');
    }

    public function destroy($id)
    {
        $kegiatan = DB::table('sarpras_kegiatan')->where('id', $id)->first();

        if ($kegiatan->status == 'disetujui') {
            return back()->with('error', 'Data Valid tidak bisa dihapus! Admin harus Batal ACC dulu.');
        }

        if (Auth::user()->role !== 'admin' && $kegiatan->user_id !== Auth::id()) abort(403);
        DB::table('sarpras_kegiatan')->where('id', $id)->delete();
        return redirect()->route('sarpras.kegiatan.index')->with('success', 'Dihapus.');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Halaman tersebut khusus untuk Admin.');
        }
        DB::table('sarpras_kegiatan')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Disetujui.');
    }

    public function unapprove($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Halaman tersebut khusus untuk Admin.');
        }
        DB::table('sarpras_kegiatan')->where('id', $id)->update(['status' => 'pending']);
        return back()->with('success', 'Batal ACC.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:sarpras_kegiatan,id']);
        \Illuminate\Support\Facades\DB::table('sarpras_kegiatan')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Data sarpras terpilih dihapus.');
    }

    public function exportExcel()
    {
        return Excel::download(new SarprasExport, 'rekap-sarpras-' . now()->format('Y-m-d') . '.xlsx');
    }
}
