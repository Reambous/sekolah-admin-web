<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths; // <-- Ganti ShouldAutoSize dengan ini
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class BeritaExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    /**
     * Ambil data dari database
     */
    public function collection()
    {
        return DB::table('berita')
            ->join('users', 'berita.user_id', '=', 'users.id')
            ->select('berita.*', 'users.name as nama_penulis')
            ->orderBy('berita.created_at', 'desc')
            ->get();
    }

    /**
     * Judul Kolom (Header)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Judul Berita',
            'Penulis',
            'Isi Berita', // <-- Teks Panjang
            'Link Gambar',
            'Link Dokumen',
        ];
    }

    /**
     * Mengatur Lebar Kolom Manual
     * (Agar Isi Berita tidak bikin lebar kolom meledak)
     */
    public function columnWidths(): array
    {
        return [
            'A' => 3,   // ID (Kecil)
            'B' => 15,  // Tanggal
            'C' => 15,  // Judul (Sedang)
            'D' => 15,  // Penulis
            'E' => 15,  // Isi Berita (Dibatasi 50, teks panjang akan terpotong tampilan)
            'F' => 15,  // Link Gambar
            'G' => 15,  // Link Dokumen
        ];
    }

    /**
     * Styling: Header Bold & Text Wrapping dimatikan
     */
    public function styles(Worksheet $sheet)
    {
        // Matikan text wrapping agar baris tidak jadi tinggi (gepeng)
        $sheet->getStyle('E')->getAlignment()->setWrapText(false);

        return [
            // Baris 1 (Header) Bold
            1    => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Mapping Data
     */
    public function map($berita): array
    {
        // Buat Link Gambar
        $linkGambar = $berita->gambar ? asset('storage/' . $berita->gambar) : '-';
        $linkLampiran = $berita->lampiran ? asset('storage/' . $berita->lampiran) : '-';

        return [
            $berita->id,
            \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y'),
            $berita->judul,
            $berita->nama_penulis,
            strip_tags($berita->isi), // Isi full (tanpa HTML tags), biarkan Excel memotong tampilannya
            $linkGambar,
            $linkLampiran,
        ];
    }
    public function title(): string
    {
        return 'Laporan Berita Sekolah'; // <--- Ganti sesuai nama file (Misal: 'Lomba', 'Kesiswaan')
    }
}
