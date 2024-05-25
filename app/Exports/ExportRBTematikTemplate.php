<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportRBTematikTemplate implements FromView, WithStyles, WithTitle
{
    public function view(): View
    {
        return view('rb-tematik.rbTematik_template');
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension('1')->setVisible(false);
    }

    public function title(): string
    {
        return 'RB Tematik - Rencana Aksi';
    }
}
