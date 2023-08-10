@extends('layout.rubick')
@section('title', 'RB General - Rencana Aksi')

@section('button')
<button class="btn btn-danger shadow-md mr-2" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-kegiatan_utama">Tambah Rencana Aksi</button>
<a href="{{ url('rb-general/perencanaan') }}" class="btn btn-warning shadow-md mr-2">Kembali</a>
@endsection
@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> RB General - Rencana Aksi</h2>
            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0"></div>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <td class="font-bold align-top"">Kegiatan Utama</td>
                    <td>{{ $target->perencanaan->kegiatan_utama->nama }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top"">Indikator</td>
                    <td>{{ $target->perencanaan->indikator->nama }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Baseline</td>
                    <td>
                        <table class="table table-noborder">
                            <tr>
                                <td class="font-bold w-16">Tahun</td>
                                <td>: {{ $target->perencanaan->baseline_tahun }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Target</td>
                                <td>: {{ $target->perencanaan->baseline_target }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Realisasi</td>
                                <td>: {{ $target->perencanaan->baseline_realisasi }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold align-top"">Tahun</td>
                    <td>{{ $target->tahun }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top"">Target</td>
                    <td>{{ $target->target }}</td>
                </tr>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Data Rencana Aksi</h2>
            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0"></div>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped table-hover" id="rencana_aksi-table">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Rencana Aksi</th>
                        <th rowspan="2">Satuan Output</th>
                        <th rowspan="2">Indikator Output</th>
                        <th colspan="5">Target</th>
                        <th rowspan="2">Anggaran</th>
                        <th rowspan="2">Pelaksana</th>
                        <th rowspan="2">Koordinator</th>
                        <th rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th>TW1</th>
                        <th>TW2</th>
                        <th>TW3</th>
                        <th>TW4</th>
                        <th>Total</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function() {
        getData();
    });

    var rencana_aksi = $('#rencana_aksi-table').DataTable( {
        responsive: true,
        processing: true,
        ordering: false,
        ajax: {
            url: "{{url('emptyDT')}}",
        },
        columns: [
            {
                data: null,
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'rencana_aksi' },
            { data: 'satuan_output' },
            { data: 'indikator_output' },
            { data: 'target_tw1' },
            { data: 'target_tw2' },
            { data: 'target_tw3' },
            { data: 'target_tw4' },
            { data: 'target_total' },
            { data: 'anggaran' },
            { data: 'pelaksana' },
            { data: 'koordinator' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.id+');" class="btn btn-warning btn-sm w-10">Edit</button><button onclick="hapus('+row.id+');" class="btn btn-danger btn-sm w-10">Hapus</button>';
                },
            },
        ],
    }); 

    function getData() {
        rencana_aksi.ajax.url("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/getData')}}").load(null, false);
    }
</script>
@endpush
