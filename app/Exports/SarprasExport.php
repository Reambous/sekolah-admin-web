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

class SarprasExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    public function collection()
    {
        return DB::table('sarpras_kegiatan')
            ->join('users', 'sarpras_kegiatan.user_id', '=', 'users.id')
            ->select('sarpras_kegiatan.*', 'users.name as nama_user')
            ->orderBy('sarpras_kegiatan.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Kegiatan',
            'Nama Guru/Pelapor',
            'Nama Kegiatan',
            'Refleksi / Keterangan',
            'Status Approval',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,   // ID
            'B' => 15,  // Tanggal
            'C' => 15,  // Nama Guru
            'D' => 15,  // Nama Kegiatan
            'E' => 15,  // Refleksi (Panjang)
            'F' => 15,  // Status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Matikan wrap text agar baris tetap rapi (tidak tinggi-tinggi)
        $sheet->getStyle('E')->getAlignment()->setWrapText(false);

        return [
            // Baris 1 (Header) Bold
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function map($kegiatan): array
    {
        return [
            $kegiatan->id,
            \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d M Y'),
            $kegiatan->nama_user, // Mengambil hasil join dari tabel users
            $kegiatan->nama_kegiatan,
            strip_tags($kegiatan->refleksi), // Bersihkan tag HTML
            strtoupper($kegiatan->status),   // PENDING / DISETUJUI
        ];
    }
    public function title(): string
    {
        return 'Laporan Sarpras'; // <--- Ganti sesuai nama file (Misal: 'Lomba', 'Kesiswaan')
    }
}
