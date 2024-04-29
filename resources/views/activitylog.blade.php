@extends('layout.rubick')
@section('title', 'Log Aktifitas')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
            <h2 class="font-bold text-base mr-auto">
                Log Aktifitas
            </h2>
        </div>
        <div class="grid grid-cols-12 gap-6 p-5">
            <div class="intro-y col-span-12 lg:col-span-12">
                <table class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%" id="activitylog-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Pelaku</th>
                            <th>Model</th>
                            <th>Even</th>
                            <th>Instansi</th>
                            <th>Propertis</th>
                            <th>Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        getActivitylogData();
    });

    var activitylog = $('#activitylog-table').DataTable( {
        responsive: true,
        processing: true,
        scrollX: true,
        ajax: "",
        columns: [
            {
                data: null,
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'pelaku' },
            { data: 'subject_type' },
            { data: 'event' },
            { data: 'instansi' },
            { data: 'pretty' },
            { data: 'pada' },
        ],
        ordering: false,
    });

    function getActivitylogData() {
        activitylog.ajax.url("{{url('activitylog/getData')}}").load(null, false);
    }
</script>
@endpush