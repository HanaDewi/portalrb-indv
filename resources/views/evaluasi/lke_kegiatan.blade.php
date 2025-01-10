@extends('layout.rubick')
@section('title', 'LKE Utama')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">Lembar Kerja Evaluasi</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="lke_kegiatan" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Kegiatan</th>
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

    var lke_kegiatan = $('#lke_kegiatan').DataTable( {
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
            { data: 'nama_kegiatan' },
        ]
    }); 

    function getData() {
        lke_kegiatan.ajax.url("{{url('evaluasi/hasil-evaluasi/getKegiatan')}}").load(null, false);
    }
</script>
@endpush
