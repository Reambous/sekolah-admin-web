<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IjinController extends Controller
{
    // 1. INDEX (Riwayat Ijin)
    public function index()
    {
        $query = DB::table('ijin')
            ->join('users', 'ijin.user_id', '=', 'users.id')
            ->select('ijin.*', 'users.name as nama_guru')
            ->orderBy('created_at', 'desc');

        // LOGIKA: Admin lihat semua, Guru cuma lihat punya sendiri
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $data_ijin = $query->get();

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
        // VALIDASI
        $request->validate([
            'mulai' => 'required|date',
            // VALIDASI PENTING: Tanggal selesai harus SETELAH atau SAMA dengan tanggal mulai
            'selesai' => 'required|date|after_or_equal:mulai',
            'alasan' => 'required|string',
            // UPDATE: Izinkan file dokumen
            'bukti_foto' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], [
            // PESAN ERROR CUSTOM
            'selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai! Harap cek kembali tanggal anda.',
            'alasan.required' => 'Keterangan/Alasan wajib diisi.',
            'bukti_foto.mimes' => 'File harus berupa Foto (JPG/PNG) atau Dokumen (PDF/Word).',
        ]);

        $path = null;
        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_ijin', 'public');
        }

        DB::table('ijin')->insert([
            'user_id' => Auth::id(),
            // 'jenis_ijin' => DIHAPUS,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'alasan' => $request->alasan,
            'bukti_foto' => $path,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
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
    public function show($id)
    {
        $ijin = DB::table('ijin')
            ->join('users', 'ijin.user_id', '=', 'users.id')
            ->select('ijin.*', 'users.name as nama_guru')
            ->where('ijin.id', $id)
            ->first();

        // Cek Hak Akses (Admin atau Pemilik)
        if (Auth::user()->role !== 'admin' && $ijin->user_id !== Auth::id()) abort(403);

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

        // Validasi Keamanan
        if ($ijin->user_id !== Auth::id()) abort(403);
        if ($ijin->status !== 'pending') abort(403, 'Sudah diproses tidak bisa edit');

        $request->validate([
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
            'alasan' => 'required|string',
            // PERBAIKAN DI SINI: Validasi Update harus sama dengan Store (Bisa PDF/Word)
            'bukti_foto' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], [
            'selesai.after_or_equal' => 'Tanggal selesai salah! Tidak boleh sebelum tanggal mulai.',
            'bukti_foto.mimes' => 'File harus berupa Foto (JPG/PNG) atau Dokumen (PDF/Word).',
        ]);

        // Logic Foto
        $path = $ijin->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            if ($ijin->bukti_foto) Storage::disk('public')->delete($ijin->bukti_foto);
            $path = $request->file('bukti_foto')->store('bukti_ijin', 'public');
        }

        DB::table('ijin')->where('id', $id)->update([
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'alasan' => $request->alasan,
            'bukti_foto' => $path,
            'updated_at' => now()
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

    // ... fungsi approve, reject, destroy ada di bawah ...
}
