<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KesiswaanLombaController extends Controller
{
    // 1. INDEX (DAFTAR DATA)
    public function index()
    {
        // Query: Tampilkan SEMUA data, urutkan dari tanggal terbaru
        $lombas = DB::table('kesiswaan_lomba')
            ->join('users', 'kesiswaan_lomba.user_id', '=', 'users.id')
            ->select('kesiswaan_lomba.*', 'users.name as nama_guru') // Ambil nama guru penginput
            ->orderBy('tanggal', 'desc')
            ->get();

        // Load data peserta manual
        foreach ($lombas as $lomba) {
            $lomba->peserta = DB::table('kesiswaan_lomba_peserta')
                ->where('kesiswaan_lomba_id', $lomba->id)
                ->get();
        }

        return view('guru.kesiswaan.lomba.index', compact('lombas'));
    }

    // 2. CREATE (FORM)
    public function create()
    {
        return view('guru.kesiswaan.lomba.create');
    }

    // 3. STORE (SIMPAN)
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'tanggal'     => 'required|date',
            'jenis_lomba' => 'required|string',
            'prestasi'    => 'required|string',
            'peserta'     => 'required|array',
            'peserta.*.kelas' => 'required|string',
            'peserta.*.nama' => 'required|string', // Isinya bisa "Budi, Siti, Ahmad"
        ]);

        // 1. Simpan Data Induk (Lombanya)
        $lombaId = DB::table('kesiswaan_lomba')->insertGetId([
            'user_id'     => Auth::id(),
            'tanggal'     => $request->tanggal,
            'jenis_lomba' => $request->jenis_lomba,
            'prestasi'    => $request->prestasi,
            'refleksi'    => $request->refleksi,
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 2. LOGIKA CERDAS: Pecah Nama Berdasarkan Koma
        $pesertaData = [];

        foreach ($request->peserta as $row) {
            // Pecah string nama berdasarkan koma
            // Contoh: "Budi, Siti " -> Menjadi ["Budi", " Siti "]
            $nama_list = explode(',', $row['nama']);

            foreach ($nama_list as $nama) {
                // Bersihkan spasi di awal/akhir nama (trim)
                $nama_bersih = trim($nama);

                if (!empty($nama_bersih)) {
                    $pesertaData[] = [
                        'kesiswaan_lomba_id' => $lombaId,
                        'nama_siswa' => $nama_bersih,
                        'kelas'      => strtoupper($row['kelas']), // Otomatis Huruf Besar
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Simpan semua sekaligus
        if (count($pesertaData) > 0) {
            DB::table('kesiswaan_lomba_peserta')->insert($pesertaData);
        }

        return redirect()->route('kesiswaan.lomba.index')->with('success', 'Data tim berhasil disimpan! Sistem otomatis memisahkan nama.');
    }

    // 4. SHOW (DETAIL)
    public function show($id)
    {
        $lomba = DB::table('kesiswaan_lomba')
            ->join('users', 'kesiswaan_lomba.user_id', '=', 'users.id')
            ->select('kesiswaan_lomba.*', 'users.name as nama_guru')
            ->where('kesiswaan_lomba.id', $id)
            ->first();

        if (!$lomba) abort(404);

        // AMBIL DATA PESERTA
        $peserta = DB::table('kesiswaan_lomba_peserta')
            ->where('kesiswaan_lomba_id', $id)
            ->get();

        // HAPUS LOGIKA ABORT(403) DI SINI.

        return view('guru.kesiswaan.lomba.show', compact('lomba', 'peserta'));
    }

    // 5. EDIT
    public function edit($id)
    {
        // 1. Ambil Data Lomba
        $lomba = DB::table('kesiswaan_lomba')->where('id', $id)->first();

        // Cek Akses
        if (Auth::user()->role !== 'admin') {
            if ($lomba->user_id !== Auth::id()) abort(403);
            if ($lomba->status !== 'pending') abort(403);
        }

        // 2. Ambil Peserta
        $rawPeserta = DB::table('kesiswaan_lomba_peserta')
            ->where('kesiswaan_lomba_id', $id)
            ->get();

        // 3. LOGIKA PENGGABUNGAN (Grouping)
        // Kita ubah data database menjadi format yang dimengerti AlpineJS di view
        // Format Target: [{kelas: 'XII RPL', nama: 'Budi, Siti'}, {kelas: 'X TKJ', nama: 'Dedi'}]

        $grouped = [];
        foreach ($rawPeserta as $p) {
            $kelas = $p->kelas;
            if (!isset($grouped[$kelas])) {
                $grouped[$kelas] = [];
            }
            $grouped[$kelas][] = $p->nama_siswa;
        }

        $formattedPeserta = [];
        foreach ($grouped as $kelas => $namaList) {
            $formattedPeserta[] = [
                'kelas' => $kelas,
                'nama' => implode(', ', $namaList) // Gabungkan nama pakai koma
            ];
        }

        // Jika kosong (jaga-jaga), kasih 1 baris kosong
        if (empty($formattedPeserta)) {
            $formattedPeserta[] = ['kelas' => '', 'nama' => ''];
        }

        return view('guru.kesiswaan.lomba.edit', compact('lomba', 'formattedPeserta'));
    }

    // 6. UPDATE
    public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'tanggal'     => 'required|date',
            'jenis_lomba' => 'required|string',
            'prestasi'    => 'required|string',
            'peserta'     => 'required|array',
            'peserta.*.kelas' => 'required|string',
            'peserta.*.nama' => 'required|string',
        ]);

        // Cek Status
        $lomba = DB::table('kesiswaan_lomba')->where('id', $id)->first();
        if (Auth::user()->role !== 'admin' && $lomba->status !== 'pending') abort(403);

        // 2. Update Data Induk
        DB::table('kesiswaan_lomba')->where('id', $id)->update([
            'tanggal'     => $request->tanggal,
            'jenis_lomba' => $request->jenis_lomba,
            'prestasi'    => $request->prestasi,
            'refleksi'    => $request->refleksi,
            'updated_at'  => now(),
        ]);

        // 3. LOGIKA UPDATE PESERTA (RESET TOTAL)
        // Cara paling aman: Hapus semua peserta lama, lalu masukkan yang baru
        DB::table('kesiswaan_lomba_peserta')->where('kesiswaan_lomba_id', $id)->delete();

        // 4. Masukkan Peserta Baru (Sama seperti logika Store)
        $pesertaData = [];
        foreach ($request->peserta as $row) {
            $nama_list = explode(',', $row['nama']); // Pecah koma
            foreach ($nama_list as $nama) {
                $nama_bersih = trim($nama);
                if (!empty($nama_bersih)) {
                    $pesertaData[] = [
                        'kesiswaan_lomba_id' => $id,
                        'nama_siswa' => $nama_bersih,
                        'kelas'      => strtoupper($row['kelas']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (count($pesertaData) > 0) {
            DB::table('kesiswaan_lomba_peserta')->insert($pesertaData);
        }

        return redirect()->route('kesiswaan.lomba.index')->with('success', 'Data lomba berhasil diperbarui.');
    }

    // 7. DESTROY
    public function destroy($id)
    {
        $lomba = DB::table('kesiswaan_lomba')->where('id', $id)->first();

        if (Auth::user()->role !== 'admin' && $lomba->status !== 'pending') abort(403);

        DB::table('kesiswaan_lomba')->where('id', $id)->delete();
        return redirect()->route('kesiswaan.lomba.index')->with('success', 'Data dihapus.');
    }

    // 8. APPROVE (KHUSUS ADMIN)
    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        DB::table('kesiswaan_lomba')->where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Data lomba divalidasi.');
    }
}
