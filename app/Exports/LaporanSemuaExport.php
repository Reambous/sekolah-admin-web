<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanSemuaExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new HumasExport(),
            new LombaExport(),
            new KesiswaanExport(),
            new KurikulumExport(),
            new SarprasExport(),
            new BeritaExport(),
            new IjinExport(),
            new JurnalExport()
        ];
    }
}
