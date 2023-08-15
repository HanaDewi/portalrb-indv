@extends('layout.rubick')
@section('title', 'RB General - Rekap Data')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data RB General - Rekap Data</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5" rowspan="2">No.</th>
                        <th class="w-10" rowspan="2">Kegiatan Utama</th>
                        <th class="w-10" rowspan="2">Indikator</th>
                        <th class="w-10" rowspan="2">Realisasi Indikator</th>
                        <th class="w-10" rowspan="2">Capaian Indikator</th>
                        <th class="w-10" rowspan="2">Catatan</th>
                        <th rowspan="2">Baseline</th>
                        <th class="w-5" rowspan="2">Tahun Target</th>
                        <th class="w-15" rowspan="2">Rencana Aksi</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Output</th>
                        <th class="w-5" colspan="5" style="text-align: center;">Target</th>
                        <th class="w-5" rowspan="2">Anggaran</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Unit Satuan Kerja Pelaksana</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Realisasi</th>
                        <th class="w-5" rowspan="2">Capaian Anggaran</th>
                    </tr>
                    <tr>
                        <th>Satuan</th>
                        <th>Indikator</th>
                        <th>TW1</th>
                        <th>TW2</th>
                        <th>TW3</th>
                        <th>TW4</th>
                        <th>Total</th>
                        <th>Koordinator</th>
                        <th>Pelaksana</th>
                        <th>Output</th>
                        <th>Anggaran</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($datas as $data)
                    <tr>
                        <td class="font-bold">{{ $no }}</td>
                        <td class="font-bold">{{ $data['perencanaan']->kegiatan_utama->nama }}</td>
                        <td>{{ $data['perencanaan']->indikator->nama }}</td>
                        <td>{{ $data['perencanaan']->realisasi_indikator }}</td>
                        <td>{{ $data['perencanaan']->capaian_indikator }}</td>
                        <td>{{ $data['perencanaan']->catatan }}</td>
                        <td>
                            <table class="table table-noborder w-full">
                                <tr>
                                    <td class="font-bold w-16">Tahun</td>
                                    <td>: {{ $data['perencanaan']->baseline_tahun }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Target</td>
                                    <td>: {{ $data['perencanaan']->baseline_target }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Realisasi</td>
                                    <td>: {{ $data['perencanaan']->baseline_realisasi }}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            @if ($data['target']->target)
                            <div class="flex items-center"><i data-lucide="bar-chart" class="w-4 h-4 mr-1"></i><span class="font-bold mr-1"> {{ $data['target']->tahun }}: </span> {{ $data['target']->target }}</div>
                            @endif
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->rencana_aksi }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->satuan_output }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->indikator_output }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->target_tw1 }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->target_tw2 }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->target_tw3 }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->target_tw4 }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->target_total }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->anggaran }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->koordinator }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->pelaksana }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->realisasi_output }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->realisasi_anggaran }}
                        </td>
                        <td>
                            {{ $data['rencana_aksi']->capaian_anggaran }}
                        </td>
                    </tr>
                    @php
                        $no++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script>
    $(function() {
        $("#perencanaan").DataTable({
            'scrollX': true,
            'orderFixed': [0, 'asc'],
            'autoWidth': false,
            'rowsGroup': [1],
        });
    });
</script>
@endpush
