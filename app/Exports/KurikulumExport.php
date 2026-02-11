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
// NAMA CLASS HARUS PERSIS: KurikulumExport
class KurikulumExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    public function collection()
    {
        return DB::table('kurikulum_kegiatan') // Pastikan nama tabel benar
            ->join('users', 'kurikulum_kegiatan.user_id', '=', 'users.id')
            ->select('kurikulum_kegiatan.*', 'users.name as nama_guru')
            ->orderBy('kurikulum_kegiatan.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Tanggal', 'Nama Guru', 'Nama Kegiatan', 'Refleksi', 'Status'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('E')->getAlignment()->setWrapText(false);
        return [1 => ['font' => ['bold' => true]]];
    }

    public function map($data): array
    {
        return [
            $data->id,
            \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d M Y'),
            $data->nama_guru,
            $data->nama_kegiatan,
            strip_tags($data->refleksi),
            strtoupper($data->status)
        ];
    }

    public function title(): string
    {
        return 'Laporan Kurikulum'; // <--- Ganti sesuai nama file (Misal: 'Lomba', 'Kesiswaan')
    }
}
