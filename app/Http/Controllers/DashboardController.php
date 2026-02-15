<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\LaporanSemuaExport; // Pastikan ini ada!
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $role = Auth::user()->role;

        // 1. STATISTIK RINGKAS
        $stats = [
            'ijin_pending' => DB::table('ijin')->where('status', 'pending')->count(),
            'jurnal_saya' => DB::table('jurnal_refleksi')->where('user_id', $userId)->count(),
            'berita_total' => DB::table('berita')->count(),
            'kegiatan_sarpras' => DB::table('sarpras_kegiatan')->where('status', 'pending')->count(),
        ];

        // 2. DATA TERBARU (LIMIT 3 - 5 ITEM)

        // PERBAIKAN DI SINI: Tambahkan JOIN ke tabel users agar properti 'penulis' ada
        $berita_terbaru = DB::table('berita')
            ->join('users', 'berita.user_id', '=', 'users.id') // Hubungkan user_id berita ke id users
            ->select('berita.*', 'users.name as penulis')      // Ambil nama user sebagai 'penulis'
            ->orderBy('berita.created_at', 'desc')
            ->limit(4)
            ->get();

        // Ijin Terakhir Saya (Pribadi)
        $ijin_terakhir = DB::table('ijin')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Jurnal Terakhir Saya (Pribadi)
        $jurnal_terakhir = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id') // Optional: Jika perlu nama di jurnal juga
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->where('jurnal_refleksi.user_id', $userId)
            ->orderBy('jurnal_refleksi.created_at', 'desc')
            ->limit(3)
            ->get();

        // 5. REFLEKSI TERBARU (GLOBAL / SEMUA ORANG)
        // Tidak ada filter user_id, jadi semua bisa lihat punya semua orang
        $refleksi_terbaru = DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id') // Gabungkan dengan tabel user
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')     // Ambil nama gurunya
            ->orderBy('jurnal_refleksi.created_at', 'desc')
            ->limit(3)
            ->get();

        $juara_terbaru = DB::table('kesiswaan_lomba')
            ->select('jenis_lomba', 'prestasi as peringkat') // Alias 'juara' jadi 'peringkat'
            ->whereNotNull('prestasi') // Hanya ambil yang sudah ada prestasinya
            ->where('prestasi', '!=', '') // Jaga-jaga jika string kosong
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard', compact('stats', 'berita_terbaru', 'ijin_terakhir',  'jurnal_terakhir', 'refleksi_terbaru', 'juara_terbaru'));
    }

    public function downloadSemua()
    {
        return Excel::download(new LaporanSemuaExport, 'rekap-Semua-kegiatan-' . now()->format('Y-m-d') . '.xlsx');
    }
}
