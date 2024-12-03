<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportLkeTemplate implements FromView, WithTitle, WithStyles, ShouldAutoSize
{
    public $parameter;
    public $datas;

    public function __construct($parameter, $datas)
    {
        $this->parameter = $parameter;
        $this->datas = $datas;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension('1')->setVisible(false);
        $sheet->getColumnDimension('A')->setVisible(false);
        $sheet->getColumnDimension('B')->setVisible(false);
    }

    public function view(): View
    {
        return view('evaluasi.lke_template', [
            'parameter' => $this->parameter,
            'datas' => $this->datas
        ]);
    }

    public function title(): string
    {
        return 'LKE Template - '.$this->parameter->nama;
    }
}
