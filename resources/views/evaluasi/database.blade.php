@extends('layout.rubick')
@section('title', 'Database Indikator')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">Database Indikator</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="form-group mb-3">
                <label for="kegiatan_id" class="form-label mt-2">Kegiatan <span class="text-danger">*</span></label>
                {!! Form::select('kegiatan_id', kegiatan(), null, ['class' => 'w-full', 'id' => 'kegiatan_id', 'data-placeholder' => 'Pilih Kegiatan', 'onchange' => 'getData();']) !!}
            </div>
            <table id="lke_utama" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Komponen</th>
                        <th>Sub Komponen</th>
                        <th>Indikator</th>
                        <th>Sudah Terisi</th>
                        <th>Rata-rata Skor</th>
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

    var lke_utama = $('#lke_utama').DataTable( {
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
            { data: 'komponen' },
            { data: 'subkomponen' },
            { data: 'indikator' },
            { data: 'terisi' },
            { data: 'rata_rata_score' },
        ],
		columnDefs: [
			{
				targets: [5],
				render: $.fn.dataTable.render.number('.', ',', 2, '')
			}
		]
    }); 

    function getData() {
        lke_utama.ajax.url("{{url('evaluasi/database/getDatas')}}").load(null, false);
    }
</script>
@endpush