@extends('layout.rubick')
@section('title', 'Hasil Evaluasi')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">Hasil Evaluasi</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="form-group mb-3">
                <label for="kegiatan_id" class="form-label mt-2">Kegiatan <span class="text-danger">*</span></label>
                {!! Form::select('kegiatan_id', kegiatan(), null, ['class' => 'w-full', 'id' => 'kegiatan_id', 'data-placeholder' => 'Pilih Kegiatan', 'onchange' => 'getData();']) !!}
            </div>
            <table id="hasil_evaluasi" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w-32">Kelompok Instansi</th>
                        <th class="w200">Nama Instansi</th>
                        <th class="w-5">RB General Awal</th>
                        <th class="w-5">Koefisien</th>
                        <th class="w-5">RB General</th>
                        <th class="w-5">Bobot RB General</th>
                        <th class="w-5">Bobot RB General Penyesuaian</th>
                        <th class="w-5">RB General Penyesuaian</th>
                        <th class="w-5">RB Tematik</th>
                        <th class="w-5">Index RB</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        getData();
    });

    var hasil_evaluasi = $('#hasil_evaluasi').DataTable( {
        responsive: true,
        processing: true,
        ordering: false,
        ajax: {
            url: "{{url('emptyDT')}}",
            data: function(d) {
                d.kegiatan_id = $('#kegiatan_id').val();
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
            { data: 'group_instansi' },
            { data: 'nama_instansi' },
            { data: 'rb_general' },
            { data: 'koefisien' },
            { data: 'rb_general_koefisien' },
            { data: 'bobot_rb_general' },
            { data: 'bobot_rb_general_penyesuaian' },
            { data: 'rb_general_penyesuaian' },
            { data: 'rb_tematik' },
            { data: 'index_rb' },
        ],
		columnDefs: [
			{
				targets: [3,4,5,6,7,8,9,10],
				render: $.fn.dataTable.render.number('.', ',', 2, '')
			}
		]
    }); 

    function getData() {
        hasil_evaluasi.ajax.url("{{url('evaluasi/hasil-evaluasi/getDatas')}}").load(null, false);
    }
</script>
@endpush