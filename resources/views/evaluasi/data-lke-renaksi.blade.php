@extends('layout.rubick')
@section('title', 'Data LKE Renaksi - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data LKE Renaksi - {{ auth()->user()->nama }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                <label for="indikator_id" class="form-label font-bold">Tahun</label>
                <select name="tahun" class="form-control" onchange="location.href='/evaluasi/data-lke-renaksi?tahun=' + this.value">
                    @for ($i=date('Y'); $i>2015; $i--)
                    <option value="{{ $i }}" {{ ($i==$tahun) ? 'selected':'' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="separator mt-5"></div>
            <table id="data-lke" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Kriteria</th>
                        <th>Parent</th>
                        <th>Info</th>
                        <th class="w-5">Tahun</th>
                        @if ($isadmin==true)
                        <th class="w-5">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datalke as $nn=>$de)
                    <tr>
                        <th>{{ $nn+1 }}</th>
                        <th>{{ $de->kriteria }}</th>
                        <th>{{ $de->parent_id }}</th>
                        <th>{{ $de->info }}</th>
                        <th>{{ $de->tahun }}</th>
                        @if ($isadmin==true)
                        <th></th>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br/>
            <a class="btn btn-danger">Tambah Data</a>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function(){
    $('#data-lke').dataTable();
});
</script>
@endpush
