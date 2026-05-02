<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class IjinExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    public function collection()
    {
        return DB::table('ijin')
            ->join('users', 'ijin.user_id', '=', 'users.id')
            ->select('ijin.*', 'users.name as nama_pemohon')
            ->orderBy('ijin.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        // Judul kolom disesuaikan dengan format baru
        return ['ID', 'Tanggal Pengajuan', 'Nama Pemohon', 'Tanggal Ijin', 'Waktu Ijin', 'Alasan', 'Status', 'Link Bukti'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // ID
            'B' => 18, // Tgl Pengajuan
            'C' => 25, // Nama
            'D' => 18, // Tgl Ijin
            'E' => 20, // Waktu Ijin
            'F' => 40, // Alasan (Diperlebar sedikit agar lebih rapi)
            'G' => 15, // Status
            'H' => 30  // Link Bukti
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('F')->getAlignment()->setWrapText(false); // Matikan text wrap
        return [1 => ['font' => ['bold' => true]]];
    }

    public function map($ijin): array
    {
        $linkBukti = $ijin->bukti_foto ? asset('storage/' . $ijin->bukti_foto) : '-';

        // Logika Waktu: Jika jam ada, format "08:00 - 12:00 WIB", jika kosong tulis "1 HARI PENUH"
        $waktu = ($ijin->jam_mulai && $ijin->jam_selesai)
            ? \Carbon\Carbon::parse($ijin->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($ijin->jam_selesai)->format('H:i') . ' WIB'
            : '1 HARI PENUH';

        return [
            $ijin->id,
            \Carbon\Carbon::parse($ijin->created_at)->translatedFormat('d M Y'),
            $ijin->nama_pemohon,
            \Carbon\Carbon::parse($ijin->tanggal)->translatedFormat('d M Y'), // Menggunakan kolom 'tanggal' yang baru
            $waktu, // Menggunakan variabel $waktu
            $ijin->alasan,
            strtoupper($ijin->status),
            $linkBukti,
        ];
    }

    public function title(): string
    {
        return 'Laporan Ijin Guru-Staf';
    }
}
