<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IndeksSimpleExport implements FromArray, WithHeadings
{
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Kode Instansi 1data Lama',
            'Tahun',
            'Kode Indeks',
            'Nilai',
            'Nama Indeks',
            'Instansi',
            'Kode Instansi 1data Baru',
            'Kode Instansi portalrb'
        ];
    }
}
