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
        return ['ID', 'Tanggal Pengajuan', 'Nama Pemohon', 'Mulai Ijin', 'Selesai Ijin', 'Alasan', 'Status', 'Link Bukti'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15, // Alasan (Panjang)
            'G' => 15,
            'H' => 15 // Link Bukti
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

        return [
            $ijin->id,
            \Carbon\Carbon::parse($ijin->created_at)->translatedFormat('d M Y'),
            $ijin->nama_pemohon,
            \Carbon\Carbon::parse($ijin->mulai)->translatedFormat('d M Y'),
            \Carbon\Carbon::parse($ijin->selesai)->translatedFormat('d M Y'),
            $ijin->alasan,
            strtoupper($ijin->status), // PENDING/DISETUJUI
            $linkBukti,
        ];
    }

    public function title(): string
    {
        return 'Laporan Ijin Guru/Staf';
    }
}
