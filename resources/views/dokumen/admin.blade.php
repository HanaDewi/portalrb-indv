@extends('layout.rubick')
@section('title', 'Dokumen')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Dokumen</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <label for="instansi_id" class="form-label mt-2 font-bold">Instansi</label>
            {!! Form::select('instansi_id', instansis(), null, ['class' => 'w-full mb-5', 'id' => 'instansi_id', 'data-placeholder' => 'Semua Instansi',  'multiple' => 'multiple']) !!}
            <div class="grid grid-cols-8">
                <div class="col-span-2">
                    <label for="tahun" class="form-label mt-2 font-bold">Tahun</label>
                    {!! Form::select('tahun', tahun(), null, ['class' => 'w-full', 'id' => 'tahun', 'data-placeholder' => 'Semua Tahun',  'multiple' => 'multiple']) !!}
                </div>
                <div class="col-span-5">
                    <label for="tahun" class="form-label mt-2 font-bold">Kategori</label>
                    {!! Form::select('kategori_id', dokumen_kategori(), null, ['class' => 'w-full', 'id' => 'kategori_id', 'data-placeholder' => 'Semua Kategori',  'multiple' => 'multiple']) !!}
                </div>
                <div class="ml-5">
                    <button type="submit" class="btn btn-success saveButton mt-8 mb-10" onclick="getData();">Lihat Data</button>
                </div>
            </div>
            <table id="tabel-dokumen" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Instansi</th>
                        <th class="w-5">Tahun</th>
                        <th>Nama Kategori Dokumen</th>
                        <th>File Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#instansi_id').select2();
        $('#tahun').select2();
        $('#kategori_id').select2();
        getData();
    });

    var dokumen_tabel = $('#tabel-dokumen').DataTable( {
        responsive: true,
        processing: true,
        searching: false,
        ajax: {
            url: "{{url('emptyDT')}}",
            data: function(d) {
                d.instansi_id = $('#instansi_id').val();
                d.tahun = $('#tahun').val();
                d.kategori_id = $('#kategori_id').val();
            }
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
            { data: 'nama_instansi' },
            { data: 'tahun' },
            { data: 'nama_kategori' },
            { data: 'filenya' },
        ],
    }); 

    function getData() {
        dokumen_tabel.ajax.url("{{url('dokumen/getDatas')}}").load(null, false);
    }
</script>
@endpush
