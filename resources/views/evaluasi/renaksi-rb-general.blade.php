@extends('layout.rubick')
@section('title', 'Evaluasi Renaksi RB General - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Evaluasi Renaksi  RB General
                @if (isset($instansi))
                    - {{ $instansi->name }}
                @endif
            </h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                <label for="indikator_id" class="form-label font-bold">Tahun</label>
                <select name="tahun" class="form-control" onchange="location.href='/evaluasi/renaksi-rb-general?tahun=' + this.value">
                    @for ($i=date('Y'); $i>2015; $i--)
                    <option value="{{ $i }}" {{ ($i==$tahun) ? 'selected':'' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="row">
                <a class="btn btn-danger" href="/evaluasi/data-lke-renaksi">Data LKE Renaksi</a> &nbsp; 
                <a class="btn btn-danger" href="/evaluasi/data-konversi-jawaban">Data Konversi Jawaban</a>
            </div>
            <div class="separator mt-5"></div>
            @if($kembali==false)
            <table id="table-instansi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Instansi</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $cc=>$jw)
                    <tr>
                        <td>{{ $cc+1 }}</td>
                        @if ($istpn)
                        <td><a href="?instansi={{ $jw->instansi_id }}" style="color:blue">{{ $jw->instansi->name }}</a></td>
                        @else 
                        <td><a href="?instansi={{ $jw->id }}" style="color:blue">{{ $jw->name }}</a></td>
                        @endif
                        <td>{{ $jw->skor }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <table id="table-instansi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>LKE Renaksi</th>
                        <th>Jawaban</th>
                        <th>Catatan</th>
                        <th>Rekomendasi</th>
                        @if($check==true)
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $cc=>$jw)
                    <tr>
                        <td>{{ $cc+1 }}</td>
                        <td>{{ $cc+1 }}</td>
                        <td>{{ $cc+1 }}</td>
                        <td>{{ $cc+1 }}</td>
                        <td>{{ $cc+1 }}</td>
                        @if($check==true)
                        <td>{{ $cc }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <div class="row mt-4">
                @if($kembali==true)
                <a href="/evaluasi/renaksi-rb-general" class="btn btn-warning">&lt; Kembali</a>
                @endif
                @if($check==true)
                &nbsp; <a href="/evaluasi/renaksi-rb-general" class="btn btn-danger">Tambah</a>
                @endif
            </div>
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
    window.dttable = new DataTable('#table-instansi');
});
</script>
@endpush
