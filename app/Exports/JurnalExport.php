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

class JurnalExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    public function collection()
    {
        // Mengambil data jurnal dan join ke users untuk nama guru
        return DB::table('jurnal_refleksi')
            ->join('users', 'jurnal_refleksi.user_id', '=', 'users.id')
            ->select('jurnal_refleksi.*', 'users.name as nama_guru')
            ->orderBy('jurnal_refleksi.created_at', 'desc')
            ->get();
    }

    /**
     * Header disesuaikan dengan isi tabelmu
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Kegiatan',
            'Nama Guru',
            'Kategori',
            'Judul Refleksi',
            'Isi Refleksi',
        ];
    }

    /**
     * Lebar kolom manual agar tidak berantakan
     */
    public function columnWidths(): array
    {
        return [
            'A' => 3,   // ID
            'B' => 15,  // Tanggal
            'C' => 15,  // Nama Guru
            'D' => 15,  // Kategori
            'E' => 15,  // Judul
            'F' => 15,  // Isi Refleksi (Panjang)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Matikan wrap text agar baris tidak tinggi/gepeng
        $sheet->getStyle('F')->getAlignment()->setWrapText(false);

        return [
            // Baris 1 (Header) Bold
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function map($jurnal): array
    {
        return [
            $jurnal->id,
            \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y'),
            $jurnal->nama_guru,
            $jurnal->kategori,
            $jurnal->judul_refleksi,
            strip_tags($jurnal->isi_refleksi), // Bersihkan tag HTML jika ada
        ];
    }

    public function title(): string
    {
        return 'Laporan Refleksi'; // <--- Ganti sesuai nama file (Misal: 'Lomba', 'Kesiswaan')
    }
}
