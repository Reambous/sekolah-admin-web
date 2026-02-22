<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\IjinExport;
use Maatwebsite\Excel\Facades\Excel;

class IjinController extends Controller
{
    // 1. INDEX (Riwayat Ijin)
    public function index()
    {
        $query = DB::table('ijin')
            ->join('users', 'ijin.user_id', '=', 'users.id')
            ->select('ijin.*', 'users.name as nama_guru', 'users.role as role_pembuat')
            ->orderBy('created_at', 'desc');

        // LOGIKA: Admin lihat semua, Guru cuma lihat punya sendiri
        // Jika user yang login BUKAN ADMIN (Guru) -> Jalankan filter khusus
        if (Auth::user()->role !== 'admin') {
            $query->where(function ($q) {
                $q->where('ijin.user_id', Auth::id())   // 1. Lihat punya sendiri
                    ->orWhere('users.role', 'admin');     // 2. ATAU lihat punya user yang role-nya admin
            });
        }

        // Ambil datanya urut dari yang terbaru
        $data_ijin = $query->orderBy('ijin.created_at', 'desc')->get();

        return view('ijin.index', compact('data_ijin'));
    }

    // 2. CREATE (Form Pengajuan)
    public function create()
    {
        return view('ijin.create');
    }

    // 3. STORE (Simpan Pengajuan)
    // 3. STORE (Simpan Pengajuan)
    public function store(Request $request)
    {
        // 1. Validasi Input (Tanggal & Jam)
        $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai', // Jam selesai harus setelah jam mulai
            'alasan'      => 'required|string',
            'bukti_foto'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], [
            'jam_selesai.after' => 'Jam selesai/kembali tidak boleh sebelum atau sama dengan jam keluar!',
            'alasan.required'   => 'Keterangan/Alasan wajib diisi.',
            'bukti_foto.mimes'  => 'File harus berupa Foto (JPG/PNG) atau Dokumen (PDF/Word).',
        ]);

        // 2. Upload File (Jika ada)
        $path = null;
        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_ijin', 'public');
        }

        // 3. Simpan ke Database
        DB::table('ijin')->insert([
            'user_id'     => Auth::id(),
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'alasan'      => $request->alasan,
            'bukti_foto'  => $path,
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        return redirect()->route('ijin.index')->with('success', 'Pengajuan ijin berhasil dikirim.');
    }

    // 4. APPROVE (Admin Only)
    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('ijin')->where('id', $id)->update(['status' => 'disetujui', 'updated_at' => now()]);
        return back()->with('success', 'Ijin disetujui.');
    }

    // 5. REJECT (Admin Only)
    public function reject($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('ijin')->where('id', $id)->update(['status' => 'ditolak', 'updated_at' => now()]);
        return back()->with('success', 'Ijin ditolak.');
    }

    // 6. DELETE (Hanya jika masih Pending)
    public function destroy($id)
    {
        $ijin = DB::table('ijin')->where('id', $id)->first();

        // Cek Hak Akses
        if (Auth::user()->role !== 'admin' && $ijin->user_id !== Auth::id()) abort(403);

        // Hanya boleh hapus jika Pending (atau Admin maksa hapus)
        if (Auth::user()->role !== 'admin' && $ijin->status !== 'pending') {
            return back()->with('error', 'Ijin yang sudah diproses tidak bisa dihapus.');
        }

        if ($ijin->bukti_foto) Storage::disk('public')->delete($ijin->bukti_foto);

        DB::table('ijin')->where('id', $id)->delete();
        return back()->with('success', 'Data ijin dihapus.');
    }

    // ... fungsi index, create, store ada di atas ...

    // 4. SHOW (Lihat Detail)
    // 4. SHOW (Lihat Detail)
    public function show($id)
    {
        $ijin = DB::table('ijin')
            ->join('users', 'ijin.user_id', '=', 'users.id')
            // TAMBAHAN: ambil 'users.role' sebagai 'role_pemilik'
            ->select('ijin.*', 'users.name as nama_guru', 'users.role as role_pemilik')
            ->where('ijin.id', $id)
            ->first();

        if (!$ijin) {
            // 3. Jika KOSONG (sudah dihapus), lempar ke Dashboard dengan pesan error
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, link kegiatan tersebut sudah tidak tersedia atau telah dihapus.');
        }

        // LOGIKA HAK AKSES BARU:
        // Boleh lihat jika: 
        // 1. Yang login adalah Admin
        // 2. ATAU Yang login adalah Pemilik Ijin
        // 3. ATAU Ijin tersebut milik seorang Admin (Transparansi)

        $isAuthorized = (Auth::user()->role === 'admin') ||
            ($ijin->user_id === Auth::id()) ||
            ($ijin->role_pemilik === 'admin');

        if (!$isAuthorized) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail ijin guru lain.');
        }

        return view('ijin.show', compact('ijin'));
    }

    // 5. EDIT (Form Edit - Hanya jika Pending)
    public function edit($id)
    {
        $ijin = DB::table('ijin')->where('id', $id)->first();

        // Cek Hak Akses
        if ($ijin->user_id !== Auth::id()) abort(403);

        // Cek Status (Hanya Pending yang boleh diedit)
        if ($ijin->status !== 'pending') {
            return redirect()->route('ijin.index')->with('error', 'Ijin yang sudah diproses tidak bisa diedit.');
        }

        return view('ijin.edit', compact('ijin'));
    }

    // 6. UPDATE (Simpan Perubahan)
    public function update(Request $request, $id)
    {
        $ijin = DB::table('ijin')->where('id', $id)->first();

        // 1. Validasi Keamanan (Hanya pemilik & masih pending)
        if ($ijin->user_id !== Auth::id()) abort(403);
        if ($ijin->status !== 'pending') abort(403, 'Sudah diproses tidak bisa edit');

        // 2. Validasi Input (Tanggal & Jam)
        $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'alasan'      => 'required|string',
            'bukti_foto'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], [
            'jam_selesai.after' => 'Jam selesai salah! Tidak boleh sebelum jam keluar.',
            'alasan.required'   => 'Keterangan/Alasan wajib diisi.',
            'bukti_foto.mimes'  => 'File harus berupa Foto (JPG/PNG) atau Dokumen (PDF/Word).',
        ]);

        // 3. Logic Ganti Foto/File
        $path = $ijin->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            if ($ijin->bukti_foto) Storage::disk('public')->delete($ijin->bukti_foto);
            $path = $request->file('bukti_foto')->store('bukti_ijin', 'public');
        }

        // 4. Update Database
        DB::table('ijin')->where('id', $id)->update([
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'alasan'      => $request->alasan,
            'bukti_foto'  => $path,
            'updated_at'  => now()
        ]);

        return redirect()->route('ijin.show', $id)->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function bulkDestroy(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') abort(403);
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:ijin,id']);
        \Illuminate\Support\Facades\DB::table('ijin')->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Data ijin terpilih dihapus.');
    }
    public function exportExcel()
    {
        return Excel::download(new IjinExport, 'rekap-ijin-' . now()->format('Y-m-d') . '.xlsx');
    }
    // ... fungsi approve, reject, destroy ada di bawah ...
}
