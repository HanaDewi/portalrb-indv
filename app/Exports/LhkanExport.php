<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LhkanExport implements FromArray, WithHeadings
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
            'Nama Instansi',
            'Periode',
            'Status',
            'Total Aparatur',
            'Wajib LHKPN',
            'Tidak Wajib LHKPN',
            'Realisasi LHKPN',
            'Realisasi SPT Non LHKPN',
            'Belum SPT Non LHKPN',
            'Total Belum LHKAN',
            'PIC',
            'Link Rekap',
            'Catatan',
            'Tanggal Submit',
        ];
    }
}