<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KesiswaanLombaController extends Controller
{
    public function index()
    {
        $lombas = DB::table('kesiswaan_lomba')
            ->join('users', 'kesiswaan_lomba.user_id', '=', 'users.id')
            ->select('kesiswaan_lomba.*', 'users.name as nama_guru')
            ->orderBy('tanggal', 'desc')->get();

        foreach ($lombas as $lomba) {
            $lomba->peserta = DB::table('kesiswaan_lomba_peserta')->where('kesiswaan_lomba_id', $lomba->id)->get();
        }
        return view('guru.kesiswaan.lomba.index', compact('lombas'));
    }

    public function create()
    {
        return view('guru.kesiswaan.lomba.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_lomba' => 'required',
            'prestasi' => 'required',
            'peserta' => 'required|array',
            'peserta.*.nama' => 'required',
            'peserta.*.kelas' => 'required',
            'refleksi' => 'nullable|string',
        ]);

        $lombaId = DB::table('kesiswaan_lomba')->insertGetId([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'jenis_lomba' => $request->jenis_lomba,
            'prestasi' => $request->prestasi,
            'refleksi' => $request->refleksi ?? '-',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $pesertaData = [];
        foreach ($request->peserta as $row) {
            $nama_list = explode(',', $row['nama']);
            foreach ($nama_list as $nama) {
                if (trim($nama)) {
                    $pesertaData[] = [
                        'kesiswaan_lomba_id' => $lombaId,
                        'nama_siswa' => trim($nama),
                        'kelas' => strtoupper($row['kelas']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }
        if ($pesertaData) DB::table('kesiswaan_lomba_peserta')->insert($pesertaData);

        return redirect()->route('kesiswaan.lomba.index')->with('success', 'Data Lomba tersimpan.');
    }

    public function show($id)
    {
        $lomba = DB::table('kesiswaan_lomba')
            ->join('users', 'kesiswaan_lomba.user_id', '=', 'users.id')
            ->select('kesiswaan_lomba.*', 'users.name as nama_guru')
            ->where('kesiswaan_lomba.id', $id)->first();

        if (!$lomba) abort(404);
        $peserta = DB::table('kesiswaan_lomba_peserta')->where('kesiswaan_lomba_id', $id)->get();

        return view('guru.kesiswaan.lomba.show', compact('lomba', 'peserta'));
    }

    public function edit($id)
    {
        $lomba = DB::table('kesiswaan_lomba')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin') {
            if ($lomba->user_id !== Auth::id() || $lomba->status !== 'pending') abort(403);
        }

        // Logic Grouping Peserta untuk Edit
        $rawPeserta = DB::table('kesiswaan_lomba_peserta')->where('kesiswaan_lomba_id', $id)->get();
        $grouped = [];
        foreach ($rawPeserta as $p) {
            $grouped[$p->kelas][] = $p->nama_siswa;
        }
        $formattedPeserta = [];
        foreach ($grouped as $kelas => $names) {
            $formattedPeserta[] = ['kelas' => $kelas, 'nama' => implode(', ', $names)];
        }
        if (empty($formattedPeserta)) $formattedPeserta[] = ['kelas' => '', 'nama' => ''];

        return view('guru.kesiswaan.lomba.edit', compact('lomba', 'formattedPeserta'));
    }

    public function update(Request $request, $id)
    {
        $lomba = DB::table('kesiswaan_lomba')->where('id', $id)->first();

        // PENGAMAN UPDATE
        if (Auth::user()->role !== 'admin' && $lomba->status !== 'pending') {
            return redirect()->route('kesiswaan.lomba.index')->with('error', 'Gagal update! Data ini baru saja disetujui Admin.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jenis_lomba' => 'required',
            'prestasi' => 'required',
            'peserta' => 'required|array',
            'peserta.*.nama' => 'required',
            'peserta.*.kelas' => 'required',
            'refleksi' => 'nullable|string',
        ]);

        DB::table('kesiswaan_lomba')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'jenis_lomba' => $request->jenis_lomba,
            'prestasi' => $request->prestasi,
            'refleksi' => $request->refleksi ?? '-',
            'updated_at' => now()
        ]);

        DB::table('kesiswaan_lomba_peserta')->where('kesiswaan_lomba_id', $id)->delete();

        $pesertaData = [];
        foreach ($request->peserta as $row) {
            $nama_list = explode(',', $row['nama']);
            foreach ($nama_list as $nama) {
                if (trim($nama)) {
                    $pesertaData[] = [
                        'kesiswaan_lomba_id' => $id,
                        'nama_siswa' => trim($nama),
                        'kelas' => strtoupper($row['kelas']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }
        if ($pesertaData) DB::table('kesiswaan_lomba_peserta')->insert($pesertaData);

        return redirect()->route('kesiswaan.lomba.index')->with('success', 'Data Lomba diperbarui.');
    }

    public function destroy($id)
    {
        $lomba = DB::table('kesiswaan_lomba')->where('id', $id)->first();

        // PENGAMAN DELETE
        if ($lomba->status == 'disetujui') {
            return back()->with('error', 'Data Valid tidak bisa dihapus! Admin harus Batal ACC dulu.');
        }

        if (Auth::user()->role !== 'admin' && $lomba->user_id !== Auth::id()) abort(403);

        DB::table('kesiswaan_lomba')->where('id', $id)->delete();
        return redirect()->route('kesiswaan.lomba.index')->with('success', 'Data dihapus.');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kesiswaan_lomba')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Lomba disetujui.');
    }

    public function unapprove($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('kesiswaan_lomba')->where('id', $id)->update(['status' => 'pending']);
        return back()->with('success', 'Status lomba kembali Pending.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:kesiswaan_lomba,id']);
        \Illuminate\Support\Facades\DB::table('kesiswaan_lomba')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Data lomba terpilih dihapus.');
    }
}
