<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesiswaanKegiatan; // Pastikan Model ini ada (nanti kita buat otomatis lewat Controller ini jika belum)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KesiswaanKegiatanController extends Controller
{
    // 1. TAMPILKAN DATA (INDEX)
    public function index()
    {
        // LOGIKA BARU: Tampilkan SEMUA data untuk SEMUA role
        $kegiatan = DB::table('kesiswaan_kegiatan')
            ->join('users', 'kesiswaan_kegiatan.user_id', '=', 'users.id')
            ->select('kesiswaan_kegiatan.*', 'users.name as nama_guru') // Ambil nama guru
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.kesiswaan.kegiatan.index', compact('kegiatan'));
    }

    // 8. TAMPILKAN DETAIL DATA
    public function show($id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')
            ->join('users', 'kesiswaan_kegiatan.user_id', '=', 'users.id')
            ->select('kesiswaan_kegiatan.*', 'users.name as nama_guru')
            ->where('kesiswaan_kegiatan.id', $id)
            ->first();

        if (!$kegiatan) abort(404);

        // HAPUS BAGIAN "if ($user->role !== 'admin' ... abort(403))"
        // Biarkan semua guru bisa melihat detail ini.

        return view('guru.kesiswaan.kegiatan.show', compact('kegiatan'));
    }

    // 2. FORM TAMBAH DATA (CREATE)
    public function create()
    {
        return view('guru.kesiswaan.kegiatan.create');
    }

    // 3. SIMPAN DATA KE DATABASE (STORE)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'refleksi' => 'required|string',
        ]);

        DB::table('kesiswaan_kegiatan')->insert([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refleksi' => $request->refleksi,
            'status' => 'pending', // Default Pending
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kesiswaan.kegiatan.index')
            ->with('success', 'Terima kasih Bapak/Ibu! Laporan kegiatan berhasil diajukan. Status saat ini: Pending.');
    }

    // 4. ADMIN MENYETUJUI DATA (Validasi)
    public function approve($id)
    {
        // Pastikan yang akses adalah Admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        // Cari data berdasarkan ID
        $kegiatan = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();

        // Jika data ketemu, ubah statusnya
        if ($kegiatan) {
            DB::table('kesiswaan_kegiatan')
                ->where('id', $id)
                ->update([
                    'status' => 'disetujui',
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Laporan berhasil divalidasi dan kini statusnya Resmi (Terkunci).');
        }

        return back()->with('error', 'Data tidak ditemukan.');
    }

    // 5. TAMPILKAN FORM EDIT (Hanya Jika Pending)
    public function edit($id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();
        $user = Auth::user();

        // Cek 1: Data ada?
        if (!$kegiatan) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        // LOGIKA BARU:
        // Jika User BUKAN Admin, maka harus cek kepemilikan & status
        if ($user->role !== 'admin') {
            // Cek milik sendiri
            if ($kegiatan->user_id !== $user->id) abort(403, 'Bukan data Anda.');
            // Cek status pending
            if ($kegiatan->status !== 'pending') abort(403, 'Data sudah disetujui, tidak bisa diedit.');
        }
        // Jika Admin, otomatis lolos (skip pengecekan di atas)

        return view('guru.kesiswaan.kegiatan.edit', compact('kegiatan'));
    }

    // 6. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'refleksi' => 'required|string',
        ]);

        // LOGIKA BARU:
        // Hanya cek status pending jika user ADALAH GURU
        // Admin bebas update kapan saja
        if (Auth::user()->role == 'guru') {
            $cek = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();
            if ($cek->status !== 'pending') abort(403);
        }

        DB::table('kesiswaan_kegiatan')
            ->where('id', $id)
            ->update([
                'tanggal' => $request->tanggal,
                'nama_kegiatan' => $request->nama_kegiatan,
                'refleksi' => $request->refleksi,
                'updated_at' => now(),
            ]);

        return redirect()->route('kesiswaan.kegiatan.index')->with('success', 'Data berhasil diperbarui!');
    }

    // 7. HAPUS DATA
    public function destroy($id)
    {
        $kegiatan = DB::table('kesiswaan_kegiatan')->where('id', $id)->first();

        // LOGIKA BARU:
        // Guru dilarang hapus jika status bukan pending.
        // Admin BOLEH hapus status apapun.
        if (Auth::user()->role !== 'admin' && $kegiatan->status !== 'pending') {
            abort(403, 'Data terkunci.');
        }

        DB::table('kesiswaan_kegiatan')->where('id', $id)->delete();

        return redirect()->route('kesiswaan.kegiatan.index')->with('success', 'Data berhasil dihapus.');
    }

    // 9. BATALKAN ACC (UNAPPROVE)
    // 9. BATALKAN ACC (UNAPPROVE)
    public function unapprove($id)
    {
        // Hanya Admin yang boleh
        if (Auth::user()->role !== 'admin') abort(403);

        // Update status jadi pending
        DB::table('kesiswaan_kegiatan')
            ->where('id', $id)
            ->update([
                'status' => 'pending',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Status berhasil dikembalikan ke Pending.');
    }
}
