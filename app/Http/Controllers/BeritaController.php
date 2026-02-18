<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\BeritaExport;
use Maatwebsite\Excel\Facades\Excel;

class BeritaController extends Controller
{
    // 1. INDEX (Tampilan Luar)
    public function index()
    {
        // Ambil berita + hitung jumlah komentar
        $berita = DB::table('berita')
            ->join('users', 'berita.user_id', '=', 'users.id')
            ->leftJoin('berita_komentar', 'berita.id', '=', 'berita_komentar.berita_id') // Join ke komentar
            ->select(
                'berita.*',
                'users.name as penulis',
                DB::raw('COUNT(berita_komentar.id) as total_komentar') // Hitung komentar
            )
            ->groupBy('berita.id', 'users.name') // Grouping wajib
            ->orderBy('berita.created_at', 'desc')
            ->get();

        return view('berita.index', compact('berita'));
    }

    // 2. CREATE (Admin Only)
    public function create()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('berita.create');
    }

    // 3. STORE (Simpan Baru)
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'lampiran' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        // 1. Gambar
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('berita_images', 'public');
        }

        // 2. Lampiran (Logic Baru)
        $lampiranPath = null;
        $namaFileAsli = null; // Default null

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $namaFileAsli = $file->getClientOriginalName(); // Ambil nama asli (misal: SK.pdf)
            $lampiranPath = $file->store('berita_docs', 'public'); // Simpan file fisik
        }

        DB::table('berita')->insert([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'gambar' => $gambarPath,
            'lampiran' => $lampiranPath,
            'nama_file_asli' => $namaFileAsli, // Simpan nama asli
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('berita.index')->with('success', 'Berita diterbitkan.');
    }

    // 4. SHOW (Detail)
    public function show($id)
    {
        $berita = DB::table('berita')
            ->join('users', 'berita.user_id', '=', 'users.id')
            ->select('berita.*', 'users.name as penulis')
            ->where('berita.id', $id)->first();

        if (!$berita) {
            // 3. Jika KOSONG (sudah dihapus), lempar ke Dashboard dengan pesan error
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, link kegiatan tersebut sudah tidak tersedia atau telah dihapus.');
        }

        $komentar = DB::table('berita_komentar')
            ->join('users', 'berita_komentar.user_id', '=', 'users.id')
            ->select('berita_komentar.*', 'users.name as nama_user')
            ->where('berita_id', $id)
            ->orderBy('created_at', 'desc')->get();

        return view('berita.show', compact('berita', 'komentar'));
    }

    // 5. EDIT BERITA (Baru - Admin Only)
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $berita = DB::table('berita')->where('id', $id)->first();
        return view('berita.edit', compact('berita'));
    }

    // 6. UPDATE BERITA
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $berita = DB::table('berita')->where('id', $id)->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'lampiran' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        // Logic Gambar
        $gambarPath = $berita->gambar;
        if ($request->hasFile('gambar')) {
            if ($berita->gambar) Storage::disk('public')->delete($berita->gambar);
            $gambarPath = $request->file('gambar')->store('berita_images', 'public');
        }

        // Logic Lampiran
        $lampiranPath = $berita->lampiran;
        $namaFileAsli = $berita->nama_file_asli;

        if ($request->hasFile('lampiran')) {
            // Hapus file lama
            if ($berita->lampiran) Storage::disk('public')->delete($berita->lampiran);

            // Simpan file baru
            $file = $request->file('lampiran');
            $namaFileAsli = $file->getClientOriginalName();
            $lampiranPath = $file->store('berita_docs', 'public');
        }

        DB::table('berita')->where('id', $id)->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'gambar' => $gambarPath,
            'lampiran' => $lampiranPath,
            'nama_file_asli' => $namaFileAsli,
            'updated_at' => now(),
        ]);

        $berita = DB::table('berita')->where('id', $id)->first();

        // 1. LOGIKA HAPUS GAMBAR LAMA (Jika dicentang)
        if ($request->has('hapus_gambar') && $berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
            DB::table('berita')->where('id', $id)->update(['gambar' => null]);
        }

        // 2. LOGIKA HAPUS LAMPIRAN LAMA (Jika dicentang)
        if ($request->has('hapus_lampiran') && $berita->lampiran) {
            Storage::disk('public')->delete($berita->lampiran);
            DB::table('berita')->where('id', $id)->update(['lampiran' => null]);
        }
        return redirect()->route('berita.show', $id)->with('success', 'Berita diperbarui.');
    }

    // 7. HAPUS BERITA
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $berita = DB::table('berita')->where('id', $id)->first();

        // Hapus file fisik
        if ($berita->gambar) Storage::disk('public')->delete($berita->gambar);
        if ($berita->lampiran) Storage::disk('public')->delete($berita->lampiran); // Hapus doc

        DB::table('berita')->where('id', $id)->delete();
        return redirect()->route('berita.index')->with('success', 'Berita dihapus.');
    }

    // --- LOGIKA KOMENTAR ---

    public function postComment(Request $request, $id)
    {
        $request->validate(['isi_komentar' => 'required']);
        DB::table('berita_komentar')->insert([
            'berita_id' => $id,
            'user_id' => Auth::id(),
            'isi_komentar' => $request->isi_komentar,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Komentar terkirim.');
    }

    public function deleteComment($id)
    {
        $k = DB::table('berita_komentar')->where('id', $id)->first();
        // Cek: Admin ATAU Pemilik Komentar
        if (Auth::user()->role !== 'admin' && $k->user_id !== Auth::id()) abort(403);

        DB::table('berita_komentar')->where('id', $id)->delete();
        return back()->with('success', 'Komentar dihapus.');
    }

    // EDIT KOMENTAR (View Form)
    public function editComment($id)
    {
        $komentar = DB::table('berita_komentar')->where('id', $id)->first();

        // PENGAMAN: Hanya PEMILIK yang boleh edit. Admin TIDAK BOLEH edit punya orang.
        if ($komentar->user_id !== Auth::id()) {
            abort(403, 'Hanya penulis komentar yang boleh mengedit.');
        }

        return view('berita.edit_comment', compact('komentar'));
    }

    // UPDATE KOMENTAR (Action)
    public function updateComment(Request $request, $id)
    {
        $k = DB::table('berita_komentar')->where('id', $id)->first();

        // PENGAMAN: Hanya PEMILIK
        if ($k->user_id !== Auth::id()) abort(403);

        DB::table('berita_komentar')->where('id', $id)->update([
            'isi_komentar' => $request->isi_komentar,
            'updated_at' => now()
        ]);

        return redirect()->route('berita.show', $k->berita_id)->with('success', 'Komentar berhasil diedit.');
    }

    public function bulkDestroy(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') abort(403);
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:berita,id']);
        \Illuminate\Support\Facades\DB::table('berita')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Berita terpilih dihapus.');
    }

    // 2. TAMBAHKAN FUNGSI INI DI DALAM CLASS BeritaController
    public function exportExcel()
    {
        // Nama file saat didownload: laporan-berita-(tanggal).xlsx
        $namaFile = 'laporan-berita-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new BeritaExport, $namaFile);
    }
}
