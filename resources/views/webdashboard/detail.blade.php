@extends('layout.rubick')
@section('title', 'Rincian Hasil Evaluasi RB')

@section('content')
@php
    $fmt = fn($v, $dec=2) => is_numeric($v) ? number_format((float)$v, $dec, ',', '.') : ($v ?? '-');
    
    // Urutan predikat dari terendah ke tertinggi untuk logika Naik/Turun
    $gradeOrder = ['D', 'C', 'CC', 'B', 'BB', 'A-', 'A', 'AA'];

    // Fungsi khusus untuk menentukan status Naik/Turun pada teks Predikat
    $getPredikatStatus = function($now, $prev) use ($gradeOrder) {
        if (!$now || !$prev || $now == '-' || $prev == '-') {
            return '<td class="text-center">-</td><td class="text-center">-</td>';
        }

        $idxNow = array_search(strtoupper($now), $gradeOrder);
        $idxPrev = array_search(strtoupper($prev), $gradeOrder);

        if ($idxNow === false || $idxPrev === false) {
            return '<td class="text-center">-</td><td class="text-center">-</td>';
        }

        if ($idxNow > $idxPrev) {
            $color = '065f46'; $bg = 'a7f3d0'; $label = '(Naik)';
        } elseif ($idxNow < $idxPrev) {
            $color = '991b1b'; $bg = 'fecaca'; $label = '(Turun)';
        } else {
            $color = '6b7280'; $bg = ''; $label = '(-)';
        }

        $style = "color:#$color; font-weight:800;" . ($bg ? " background-color:#$bg;" : "");
        return '<td class="text-center" data-bg="'.$bg.'" data-color="'.$color.'" data-bold="true" style="'.$style.'">-</td>
                <td class="text-center" data-bg="'.$bg.'" data-color="'.$color.'" data-bold="true" style="'.$style.'">'.$label.'</td>';
    };

    // Fungsi selisih untuk angka (Indeks RB, dll)
    $selisihHtml = function($now, $prev) use ($fmt) {
        if (!is_numeric($now) || !is_numeric($prev)) {
            return '<td class="text-center">-</td><td class="text-center">-</td>';
        }
        $s = round((float)$now - (float)$prev, 2);
        
        if ($s > 0) {
            $textColor = '065f46'; // Hijau Gelap
            $bgColor = 'a7f3d0';   // Hijau Muda (Transparansi 50%)
            $label = '(Naik)';
            $s_str = '+' . $fmt($s);
        } elseif ($s < 0) {
            $textColor = '991b1b'; // Merah Gelap
            $bgColor = 'fecaca';   // Merah Muda (Transparansi 50%)
            $label = '(Turun)';
            $s_str = $fmt($s);
        } else {
            $textColor = '6b7280';
            $bgColor = '';
            $label = '(-)';
            $s_str = $fmt($s);
        }

        $bgAttr = $bgColor ? 'data-bg="'.$bgColor.'"' : '';
        $colAttr = 'data-color="'.$textColor.'"';
        $style = 'color:#'.$textColor.';'.($bgColor ? ' background-color:#'.$bgColor.';' : '').' font-weight:800;';

        return '<td class="text-center" '.$bgAttr.' '.$colAttr.' data-bold="true" style="'.$style.'">'.$s_str.'</td>
                <td class="text-center" '.$bgAttr.' '.$colAttr.' data-bold="true" style="'.$style.'">'.$label.'</td>';
    };

    $nowRbGeneral  = $evalNow?->rb_general_penyesuaian ?? null;
    $prevRbGeneral = $evalPrev?->rb_general_penyesuaian ?? null;
    $nowRbTematik  = $evalNow?->rb_tematik ?? null;
    $prevRbTematik = $evalPrev?->rb_tematik ?? null;
    $nowIndexRb    = $evalNow?->index_rb ?? null;
    $prevIndexRb   = $evalPrev?->index_rb ?? null;
@endphp

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-5 gap-3 px-8">
        <a href="{{ url('webdashboard') }}" 
           style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; font-weight:600; text-decoration:none; cursor:pointer;"
           onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e293b';" 
           onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569';">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="intro-y box shadow-sm rounded-md overflow-hidden">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-center justify-between px-8 py-6 border-b border-slate-200/60 bg-slate-50/50 rounded-t-md">
            <div class="text-left mb-4 sm:mb-0 mr-auto">
                <h2 class="text-lg font-bold text-slate-800 m-0 uppercase leading-tight">HASIL EVALUASI REFORMASI BIROKRASI</h2>
                <div class="text-base text-blue-600 font-semibold mt-1.5">{{ $instansi->name }}</div>
                <div class="text-sm text-slate-500 mt-0.5 font-medium tracking-wide">TAHUN {{ $tahun }}</div>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <button onclick="exportToExcel()"
                    style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#059669; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Unduh Excel
                </button>

                <button onclick="exportToPDF()"
                    style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2v14a2 2 0 002 2z"/></svg>
                    Unduh PDF
                </button>
            </div>
        </div>

        <div class="p-8" id="print-area">

            <h3 class="font-bold text-slate-700 text-sm mb-3 border-l-4 border-blue-600 pl-3">Tabel Summary RB</h3>
            <div class="overflow-x-auto mb-8">
                <table class="table-detail w-full sm:w-3/4 min-w-[500px]">
                    <thead>
                        <tr>
                            <th style="width:50%;"></th>
                            <th class="text-center">TAHUN {{ $tahun }}</th>
                            <th class="text-center">TAHUN {{ $tahun - 1 }}</th>
                            <th class="text-center" colspan="2">Ket.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-semibold bg-slate-50">
                            <td>Nilai RB General Awal</td>
                            <td class="text-center">{{ $fmt($evalNow?->rb_general) }}</td>
                            <td class="text-center">{{ $fmt($evalPrev?->rb_general) }}</td>
                            {!! $selisihHtml($evalNow?->rb_general, $evalPrev?->rb_general) !!}
                        </tr>
                        <tr>
                            <td class="pl-8 text-slate-500">- Penyesuaian Bobot</td>
                            <td class="text-center">{{ $fmt($evalNow?->bobot_rb_general_penyesuaian) }}</td>
                            <td class="text-center">{{ $fmt($evalPrev?->bobot_rb_general_penyesuaian) }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td>
                        </tr>
                        <tr class="font-semibold bg-slate-50">
                            <td>Nilai RB General Penyesuaian</td>
                            <td class="text-center">{{ $fmt($nowRbGeneral) }}</td>
                            <td class="text-center">{{ $fmt($prevRbGeneral) }}</td>
                            {!! $selisihHtml($nowRbGeneral, $prevRbGeneral) !!}
                        </tr>
                        <tr>
                            <td class="pl-8 text-slate-500">- Koefisien</td>
                            <td class="text-center">{{ $fmt($evalNow?->koefisien) }}</td>
                            <td class="text-center">{{ $fmt($evalPrev?->koefisien) }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td>
                        </tr>
                        <tr class="font-semibold">
                            <td>Nilai RB General</td>
                            <td class="text-center">{{ $fmt($evalNow?->rb_general) }}</td>
                            <td class="text-center">{{ $fmt($evalPrev?->rb_general) }}</td>
                            {!! $selisihHtml($evalNow?->rb_general, $evalPrev?->rb_general) !!}
                        </tr>
                        <tr class="font-semibold">
                            <td>Nilai RB Tematik</td>
                            <td class="text-center">{{ $fmt($nowRbTematik) }}</td>
                            <td class="text-center">{{ $fmt($prevRbTematik) }}</td>
                            {!! $selisihHtml($nowRbTematik, $prevRbTematik) !!}
                        </tr>
                        
                        {{-- Baris Indeks RB (Sangat Menonjol) --}}
                        <tr data-bg="dbeafe" data-color="1e40af" data-bold="true" data-size="12" style="background-color: #dbeafe; color: #1e40af; font-size: 15px; font-weight: 800;">
                            <td style="padding: 12px 10px;">Indeks RB</td>
                            <td class="text-center" style="padding: 12px 10px;">{{ $fmt($nowIndexRb) }}</td>
                            <td class="text-center" style="padding: 12px 10px;">{{ $fmt($prevIndexRb) }}</td>
                            {!! $selisihHtml($nowIndexRb, $prevIndexRb) !!}
                        </tr>
                        
                        {{-- Baris Predikat (Menonjol & Logika Ket. Diperbaiki) --}}
                        <tr data-bg="fee2e2" data-color="991b1b" data-bold="true" data-size="12" style="background-color: #fee2e2; color: #991b1b; font-size: 15px; font-weight: 800;">
                            <td style="padding: 12px 10px;">Predikat</td>
                            <td class="text-center">{{ $predikat }}</td>
                            <td class="text-center">{{ $predikatPrev }}</td>
                            {!! $getPredikatStatus($predikat, $predikatPrev) !!}
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="font-bold text-slate-700 text-sm mb-3 border-l-4 border-purple-600 pl-3">Tabel Hasil Evaluasi RB</h3>
            <div class="overflow-x-auto mb-8">
                <table class="table-detail w-full sm:w-3/4 min-w-[500px]">
                    <thead>
                        <tr>
                            <th style="width:50%;"></th>
                            <th class="text-center">TAHUN {{ $tahun }}</th>
                            <th class="text-center">TAHUN {{ $tahun - 1 }}</th>
                            <th class="text-center" colspan="2">Ket.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="section-row">
                            <td colspan="5" style="background:#1e3a5f; color:white; font-weight:700;">Indeks RB</td>
                        </tr>
                        <tr class="font-bold bg-slate-100">
                            <td>Nilai RB General</td>
                            <td class="text-center">{{ $fmt($evalNow?->rb_general) }}</td>
                            <td class="text-center">{{ $fmt($evalPrev?->rb_general) }}</td>
                            {!! $selisihHtml($evalNow?->rb_general, $evalPrev?->rb_general) !!}
                        </tr>
                        <tr>
                            <td class="pl-8">- Strategi Pelaksanaan RB</td>
                            <td class="text-center">{{ $fmt($grupNow['strategi']) }}</td>
                            <td class="text-center">{{ $fmt($grupPrev['strategi']) }}</td>
                            {!! $selisihHtml($grupNow['strategi'], $grupPrev['strategi']) !!}
                        </tr>
                        <tr>
                            <td class="pl-8">- Capaian Pelaksanaan Kebijakan Reformasi Birokrasi</td>
                            <td class="text-center">{{ $fmt($grupNow['kebijakan']) }}</td>
                            <td class="text-center">{{ $fmt($grupPrev['kebijakan']) }}</td>
                            {!! $selisihHtml($grupNow['kebijakan'], $grupPrev['kebijakan']) !!}
                        </tr>
                        <tr>
                            <td class="pl-8">- Capaian Sasaran Strategis Reformasi Birokrasi</td>
                            <td class="text-center">{{ $fmt($grupNow['sasaran']) }}</td>
                            <td class="text-center">{{ $fmt($grupPrev['sasaran']) }}</td>
                            {!! $selisihHtml($grupNow['sasaran'], $grupPrev['sasaran']) !!}
                        </tr>
                        
                        <tr data-bg="dbeafe" data-color="1e40af" data-bold="true" data-size="12" style="background-color: #dbeafe; color: #1e40af; font-size: 15px; font-weight: 800;">
                            <td style="padding: 12px 10px;">Nilai RB Tematik</td>
                            <td class="text-center" style="padding: 12px 10px;">{{ $fmt($nowRbTematik) }}</td>
                            <td class="text-center" style="padding: 12px 10px;">{{ $fmt($prevRbTematik) }}</td>
                            {!! $selisihHtml($nowRbTematik, $prevRbTematik) !!}
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="font-bold text-slate-700 text-sm mb-3 border-l-4 border-emerald-500 pl-3">
                Tabel Rincian Indikator Penilaian Hasil Evaluasi RB TAHUN {{ $tahun }}
            </h3>
            <div class="overflow-x-auto pb-5">
                <table class="table-detail w-full min-w-[1100px]">
                    <thead>
                        <tr>
                            <th style="width:28%;">Indikator / Komponen</th>
                            <th class="text-center">Bobot</th>
                            <th class="text-center">Target Baik</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Ket.</th>
                            <th class="text-center">Skor Index</th>
                            <th class="text-center">Capaian Index (%)</th>
                            <th class="text-center">Skor N-1</th>
                            <th class="text-center">Skor Index N-1</th>
                            <th class="text-center">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $getRow = function($keyword) use ($rincian) {
                            return $rincian->first(fn($r) => stripos($r->indikator, $keyword) !== false)
                                ?? (object)[
                                    'bobot'=>'-','target_baik'=>'-','score'=>'-','get'=>'-',
                                    'ketColor'=>'text-slate-500','skor_index'=>'-',
                                    'capaian_pct'=>'-','skor_prev'=>'-','skor_index_prev'=>'-',
                                    'selisih'=>null,'ket_selisih'=>'-','ket'=>'-'
                                ];
                        };
                        $renderRow = function($label, $row) use ($fmt) {
                            $selisihStyle = '';
                            $selisihLabel = '-';
                            $bgAttr = ''; $colAttr = '';
                            
                            if ($row->selisih !== null) {
                                if($row->selisih > 0) {
                                    $selisihStyle = 'color:#065f46; background-color:#a7f3d0; font-weight:800;';
                                    $bgAttr = 'data-bg="a7f3d0"'; $colAttr = 'data-color="065f46"';
                                    $selisihLabel = '+' . number_format($row->selisih, 2, ',', '.') . ' (' . $row->ket_selisih . ')';
                                } elseif($row->selisih < 0) {
                                    $selisihStyle = 'color:#991b1b; background-color:#fecaca; font-weight:800;';
                                    $bgAttr = 'data-bg="fecaca"'; $colAttr = 'data-color="991b1b"';
                                    $selisihLabel = number_format($row->selisih, 2, ',', '.') . ' (' . $row->ket_selisih . ')';
                                }
                            }
                            return '
                            <tr>
                                <td class="pl-6">'.$label.'</td>
                                <td class="text-center">'.$fmt($row->bobot).'</td>
                                <td class="text-center">'.$fmt($row->target_baik).'</td>
                                <td class="text-center font-bold text-blue-600">'.$fmt($row->score).'</td>
                                <td class="text-center '.$row->ketColor.'">'.$row->ket.'</td>
                                <td class="text-center">'.$fmt($row->skor_index).'</td>
                                <td class="text-center">'.$fmt($row->capaian_pct).'</td>
                                <td class="text-center">'.$fmt($row->skor_prev).'</td>
                                <td class="text-center">'.$fmt($row->skor_index_prev).'</td>
                                <td class="text-center" '.$bgAttr.' '.$colAttr.' data-bold="true" style="'.$selisihStyle.'">'.$selisihLabel.'</td>
                            </tr>';
                        };
                        @endphp

                        <tr class="section-row"><td colspan="10">RB GENERAL</td></tr>
                        <tr class="subsection-row"><td colspan="10">A. Strategi Pelaksanaan RB General</td></tr>
                        {!! $renderRow('1. Rencana Aksi Pembangunan RB General', $getRow('Rencana Aksi Pembangunan')) !!}
                        {!! $renderRow('2. Tingkat Implementasi Rencana Aksi RB General', $getRow('Tingkat Implementasi Rencana Aksi')) !!}

                        <tr class="subsection-row"><td colspan="10">B. Capaian Pelaksanaan Kebijakan Reformasi Birokrasi</td></tr>
                        {!! $renderRow('1. Persentase Penyederhanaan Struktur Organisasi', $getRow('Penyederhanaan Struktur')) !!}
                        {!! $renderRow('2. Tingkat Capaian Sistem Kerja untuk Penyederhanaan Birokrasi', $getRow('Tingkat Capaian Sistem Kerja')) !!}
                        {!! $renderRow('3. Tingkat Maturitas SPIP', $getRow('Tingkat Maturitas SPIP')) !!}
                        {!! $renderRow('4. Tingkat Keberhasilan Pembangunan ZI', $getRow('Keberhasilan Pembangunan ZI')) !!}
                        {!! $renderRow('5. Nilai SAKIP', $getRow('Nilai SAKIP')) !!}
                        {!! $renderRow('6. Indeks Perencanaan Pembangunan', $getRow('Indeks Perencanaan Pembangunan')) !!}
                        {!! $renderRow('7. Tingkat implementasi Kebijakan Arsitektur SPBE', $getRow('Kebijakan Arsitektur SPBE')) !!}
                        {!! $renderRow('8. Tingkat Digitalisasi Arsip', $getRow('Digitalisasi Arsip')) !!}
                        {!! $renderRow('9. Indikator Kinerja Pelaksanaan Anggaran', $getRow('Kinerja Pelaksanaan Anggaran')) !!}
                        {!! $renderRow('10. Indeks Pengelolaan Aset', $getRow('Indeks Pengelolaan Aset')) !!}
                        {!! $renderRow('11. Tingkat tindak lanjut pengaduan masyarakat (LAPOR)', $getRow('pengaduan masyarakat')) !!}
                        {!! $renderRow('12. Indeks Kualitas Kebijakan', $getRow('Indeks Kualitas Kebijakan')) !!}
                        {!! $renderRow('13. Indeks Reformasi Hukum', $getRow('Indeks Reformasi Hukum')) !!}
                        {!! $renderRow('14. Indeks Pembangunan Statistik', $getRow('Indeks Pembangunan Statistik')) !!}
                        {!! $renderRow('15. Indeks Tata Kelola Pengadaan', $getRow('Tata Kelola Pengadaan')) !!}
                        {!! $renderRow('16. Indeks Sistem Merit', $getRow('Sistem Merit')) !!}
                        {!! $renderRow('17. Indeks Pelayanan Publik', $getRow('Indeks Pelayanan Publik')) !!}
                        {!! $renderRow('18. Tingkat Kepatuhan Standar Pelayanan Publik', $getRow('Standar Pelayanan Publik')) !!}

                        <tr class="subsection-row"><td colspan="10">C. Capaian Sasaran Strategis Reformasi Birokrasi</td></tr>
                        {!! $renderRow('1. Indeks SPBE', $getRow('Indeks SPBE')) !!}
                        {!! $renderRow('2. Opini BPK', $getRow('Opini BPK')) !!}
                        {!! $renderRow('3. Tindak Lanjut Rekomendasi', $getRow('Tindak Lanjut Rekomendasi')) !!}
                        {!! $renderRow('4. Indeks BerAkhlak', $getRow('Indeks BerAkhlak')) !!}
                        {!! $renderRow('5. Survei Penilaian Integritas', $getRow('Survei Penilaian Integritas')) !!}
                        {!! $renderRow('6. Survei Kepuasan Masyarakat', $getRow('Survei Kepuasan Masyarakat')) !!}
                        {!! $renderRow('7. Capaian Prioritas Nasional', $getRow('Prioritas Nasional')) !!}
                        {!! $renderRow('8. Capaian IKU Kementerian Lembaga', $getRow('IKU Kementerian')) !!}
                        {!! $renderRow('9. Capaian IKU Makro', $getRow('IKU Makro')) !!}
                        {!! $renderRow('10. Capaian IKU Non Makro', $getRow('IKU Non Makro')) !!}

                        <tr class="section-row thematic"><td colspan="10">RB TEMATIK</td></tr>
                        <tr class="subsection-row"><td colspan="10">A. Strategi Pembangunan</td></tr>
                        {!! $renderRow('- Pengentasan Kemiskinan', $getRow('Pengentasan Kemiskinan (Strategi')) !!}
                        {!! $renderRow('- Realisasi Investasi', $getRow('Realisasi Investasi (Strategi')) !!}
                        {!! $renderRow('- Digitalisasi Adm. Pemerintah - Penanganan Stunting', $getRow('Penanganan Stunting (Strategi')) !!}
                        {!! $renderRow('- Penggunaan Produk Dalam Negeri', $getRow('Produk Dalam Negeri (Strategi')) !!}
                        {!! $renderRow('- Pengendalian Inflasi', $getRow('Pengendalian Inflasi (Strategi')) !!}

                        <tr class="subsection-row"><td colspan="10">B. Capaian Indikator Dampak</td></tr>
                        {!! $renderRow('- Pengentasan Kemiskinan', $getRow('Pengentasan Kemiskinan (Capaian')) !!}
                        {!! $renderRow('- Realisasi Investasi', $getRow('Realisasi Investasi (Capaian')) !!}
                        {!! $renderRow('- Digitalisasi Adm. Pemerintah - Penanganan Stunting', $getRow('Penanganan Stunting (Capaian')) !!}
                        {!! $renderRow('- Penggunaan Produk Dalam Negeri', $getRow('Produk Dalam Negeri (Capaian')) !!}
                        {!! $renderRow('- Pengendalian Inflasi', $getRow('Pengendalian Inflasi (Capaian')) !!}
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('css')
<style>
    .table-detail { border-collapse: collapse; width: 100%; font-size: 13px; }
    .table-detail th { background-color: #1e293b; color: white; border: 1px solid #334155; padding: 10px 12px; font-weight: 600; white-space: nowrap; text-align: center; }
    .table-detail td { border: 1px solid #cbd5e1; padding: 8px 12px; color: #334155; }
    .table-detail tr:nth-child(even):not(.section-row):not(.subsection-row) { background-color: #f8fafc; }
    .table-detail tr:hover:not(.section-row):not(.subsection-row) { background-color: #f1f5f9; }
    .table-detail .section-row td { background-color: #1e40af; color: white; font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 10px 12px; }
    .table-detail .section-row.thematic td { background-color: #065f46; }
    .table-detail .subsection-row td { background-color: #e2e8f0; font-weight: 700; color: #0f172a; }

    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .table-detail { font-size: 9px; }
        .table-detail th { background-color: #e2e8f0 !important; color: black !important; -webkit-print-color-adjust: exact; }
        .table-detail .section-row td { background-color: #c7d2fe !important; -webkit-print-color-adjust: exact; }
        .table-detail .section-row.thematic td { background-color: #a7f3d0 !important; -webkit-print-color-adjust: exact; }
        .btn { display: none !important; }
        a.btn { display: none !important; }
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.min.js"></script>
<script>
    var instansiName = @json($instansi->name);
    var tahun = @json($tahun);

    function exportToPDF() {
        var content = [];
        content.push({ text: 'HASIL EVALUASI REFORMASI BIROKRASI', style: 'header' });
        content.push({ text: instansiName, style: 'subheader' });
        content.push({ text: 'TAHUN ' + tahun, style: 'subheader', margin: [0, 0, 0, 15] });

        function buildPdfTable(tableEl, isRincian) {
            var body = [];
            var rows = tableEl.querySelectorAll('tr');
            rows.forEach(function(tr, rowIndex) {
                var rowData = [];
                var cells = tr.querySelectorAll('th, td');
                var isHeader = tr.closest('thead') !== null;
                var isSection = tr.classList.contains('section-row');
                var isSubSection = tr.classList.contains('subsection-row');

                var trBg = tr.getAttribute('data-bg');
                var trColor = tr.getAttribute('data-color');
                var trBold = tr.getAttribute('data-bold') === 'true';
                var trSize = tr.getAttribute('data-size');

                cells.forEach(function(cell) {
                    var text = cell.innerText.trim();
                    var colSpan = cell.getAttribute('colspan') ? parseInt(cell.getAttribute('colspan')) : 1;
                    var bg = cell.getAttribute('data-bg') || trBg;
                    var color = cell.getAttribute('data-color') || trColor;
                    var bold = cell.getAttribute('data-bold') === 'true' || trBold || isHeader || isSection || isSubSection;
                    var size = cell.getAttribute('data-size') || trSize || (isRincian ? 8 : 10);

                    var alignment = (cell.classList.contains('text-center') || isHeader) ? 'center' : 'left';
                    if (isSection || isSubSection) alignment = 'left';

                    if (isHeader && !bg) { bg = "1E293B"; color = "FFFFFF"; }
                    if (isSection && !bg) { bg = tr.classList.contains('thematic') ? "065F46" : "1E40AF"; color = "FFFFFF"; }
                    if (isSubSection && !bg) { bg = "E2E8F0"; color = "0F172A"; }
                    if (!bg && rowIndex % 2 !== 0 && !isRincian && !isHeader) bg = "F8FAFC";
                    if (!color) color = "334155";

                    rowData.push({
                        text: text, colSpan: colSpan, alignment: alignment, bold: bold, fontSize: parseInt(size),
                        color: color.startsWith('#') ? color : '#' + color,
                        fillColor: bg ? (bg.startsWith('#') ? bg : '#' + bg) : null,
                        margin: [4, 4, 4, 4]
                    });
                    for (var i = 1; i < colSpan; i++) rowData.push({});
                });
                if (rowData.length > 0) body.push(rowData);
            });
            return body;
        }

        var tables = document.querySelectorAll('#print-area .table-detail');
        var titles = ['Tabel Summary RB', 'Tabel Hasil Evaluasi RB', 'Tabel Rincian Indikator Penilaian'];
        tables.forEach(function(tbl, index) {
            content.push({ text: titles[index], style: 'tableTitle' });
            var isRincian = index === 2;
            var widths = isRincian ? ['*', '6%', '8%', '7%', '8%', '8%', '10%', '8%', '9%', '12%'] : ['*', '15%', '15%', '10%', '10%'];
            content.push({
                table: { headerRows: 1, widths: widths, body: buildPdfTable(tbl, isRincian) },
                layout: { hLineWidth: ()=>0.5, vLineWidth: ()=>0.5, hLineColor: ()=>'#cbd5e1', vLineColor: ()=>'#cbd5e1' },
                margin: [0, 0, 0, 20]
            });
        });

        pdfMake.createPdf({
            pageOrientation: 'landscape', pageMargins: [20, 30, 20, 30], content: content,
            styles: {
                header: { fontSize: 14, bold: true, color: '#1e293b' },
                subheader: { fontSize: 11, bold: true, color: '#2563eb' },
                tableTitle: { fontSize: 12, bold: true, color: '#334155', margin: [0, 0, 0, 8] }
            },
            defaultStyle: { fontSize: 9, color: '#334155' }
        }).download('Evaluasi_RB_' + instansiName.replace(/\s+/g, '_') + '_' + tahun + '.pdf');
    }

    function exportToExcel() {
        var wb = XLSX.utils.book_new();
        var aoa = []; 
        aoa.push([{ v: 'HASIL EVALUASI REFORMASI BIROKRASI', s: { font: { bold: true, sz: 14 } } }]);
        aoa.push([{ v: 'Instansi:', s: { font: { bold: true } } }, { v: instansiName }]);
        aoa.push([{ v: 'Tahun:', s: { font: { bold: true } } }, { v: tahun }]);
        aoa.push([]); 

        var tables = document.querySelectorAll('#print-area .table-detail');
        var titles = ['TABEL SUMMARY RB', 'TABEL HASIL EVALUASI RB', 'TABEL RINCIAN INDIKATOR PENILAIAN'];

        tables.forEach(function(tbl, index) {
            aoa.push([{ v: titles[index], s: { font: { bold: true, sz: 12, color: { rgb: "FFFFFF" } }, fill: { fgColor: { rgb: "334155" } } } }]);
            tbl.querySelectorAll('tr').forEach(function(tr) {
                var rowData = [];
                var trBg = tr.getAttribute('data-bg'), trColor = tr.getAttribute('data-color'), trBold = tr.getAttribute('data-bold') === 'true', trSize = tr.getAttribute('data-size');
                tr.querySelectorAll('th, td').forEach(function(cell) {
                    var text = cell.innerText.trim(), isHeader = cell.tagName.toLowerCase() === 'th', isSection = tr.classList.contains('section-row'), isSubSection = tr.classList.contains('subsection-row');
                    var bg = cell.getAttribute('data-bg') || trBg, color = cell.getAttribute('data-color') || trColor, bold = cell.getAttribute('data-bold') === 'true' || trBold || isHeader || isSection || isSubSection, size = cell.getAttribute('data-size') || trSize || (isHeader ? 11 : 10);
                    var alignment = (cell.classList.contains('text-center') || isHeader) ? 'center' : 'left';
                    if(isSection || isSubSection) alignment = 'left';

                    if (isHeader && !bg) { bg = "1E293B"; color = "FFFFFF"; }
                    if (isSection && !bg) { bg = tr.classList.contains('thematic') ? "065F46" : "1E40AF"; color = "FFFFFF"; }
                    if (isSubSection && !bg) { bg = "E2E8F0"; color = "0F172A"; }

                    var style = { font: { name: "Arial", sz: parseInt(size), bold: bold }, alignment: { vertical: "center", horizontal: alignment } };
                    if (color) style.font.color = { rgb: color.replace('#', '').toUpperCase() };
                    if (bg) style.fill = { fgColor: { rgb: bg.replace('#', '').toUpperCase() } };

                    rowData.push({ v: text, s: style });
                    var colspan = cell.getAttribute('colspan');
                    if (colspan) for (var i = 1; i < parseInt(colspan); i++) rowData.push({ v: '', s: style }); 
                });
                if(rowData.length > 0) aoa.push(rowData);
            });
            aoa.push([]); 
        });

        var ws = XLSX.utils.aoa_to_sheet(aoa);
        ws['!cols'] = [ {wch: 48}, {wch: 12}, {wch: 12}, {wch: 10}, {wch: 15}, {wch: 12}, {wch: 16}, {wch: 12}, {wch: 14}, {wch: 25} ];
        XLSX.utils.book_append_sheet(wb, ws, 'Hasil Evaluasi');
        XLSX.writeFile(wb, 'Evaluasi_RB_' + instansiName.replace(/\s+/g, '_') + '_' + tahun + '.xlsx');
    }
</script>
@endpush