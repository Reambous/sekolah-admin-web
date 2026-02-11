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

class LombaExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    public function collection()
    {
        return DB::table('kesiswaan_lomba')
            ->join('users', 'kesiswaan_lomba.user_id', '=', 'users.id')
            // Ambil data peserta dan gabungkan nama+kelas menjadi satu teks
            ->leftJoin('kesiswaan_lomba_peserta', 'kesiswaan_lomba.id', '=', 'kesiswaan_lomba_peserta.kesiswaan_lomba_id')
            ->select(
                'kesiswaan_lomba.*',
                'users.name as nama_guru',
                DB::raw("GROUP_CONCAT(CONCAT(nama_siswa, ' (', kelas, ')') SEPARATOR ', ') as daftar_peserta")
            )
            ->groupBy('kesiswaan_lomba.id') // Wajib ada grup by agar tidak duplikat
            ->orderBy('kesiswaan_lomba.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Guru Pembimbing',
            'Jenis Lomba',
            'Prestasi',
            'Nama Siswa & Kelas', // Kolom baru
            'Refleksi',
            'Status'
        ];
    }

    public function map($lomba): array
    {
        return [
            $lomba->id,
            \Carbon\Carbon::parse($lomba->tanggal)->translatedFormat('d M Y'),
            $lomba->nama_guru,
            $lomba->jenis_lomba,
            $lomba->prestasi,
            $lomba->daftar_peserta ?? '-', // Menampilkan nama siswa yang sudah digabung
            strip_tags($lomba->refleksi),
            strtoupper($lomba->status),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 12
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('F')->getAlignment()->setWrapText(false); // Peserta
        $sheet->getStyle('G')->getAlignment()->setWrapText(false); // Refleksi
        return [1 => ['font' => ['bold' => true]]];
    }

    public function title(): string
    {
        return 'Laporan Lomba Kesiswaan';
    }
}
