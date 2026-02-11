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

class KesiswaanExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    public function collection()
    {
        return DB::table('kesiswaan_kegiatan') // Pastikan nama tabel benar
            ->join('users', 'kesiswaan_kegiatan.user_id', '=', 'users.id')
            ->select('kesiswaan_kegiatan.*', 'users.name as nama_user')
            ->orderBy('kesiswaan_kegiatan.created_at', 'desc')
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

    public function map($data): array
    {
        return [
            $data->id,
            \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d M Y'),
            $data->nama_user,
            $data->nama_kegiatan,
            strip_tags($data->refleksi),
            strtoupper($data->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('E')->getAlignment()->setWrapText(false);
        return [1 => ['font' => ['bold' => true]]];
    }

    public function title(): string
    {
        return 'Laporan Kesiswaan'; // <--- Ganti sesuai nama file (Misal: 'Lomba', 'Kesiswaan')
    }
}
