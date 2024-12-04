@extends('zi.admin.rubick')
@section('title', $title)

@push('css')
<style>
    .glowing-border {
        border: 2px solid #b01133;
        border-radius: 7px;
    }

    .link-wrap {
        word-break: break-all;

    }

    select:has(option[value="1"]:checked) {
        background-color: green !important;
        color: white;
    }

    select:has(option[value="0"]:checked) {
        background-color: red !important;
        color: white;
    }

    td select {
        min-width: 120px
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    /*#rekap-zi tbody tr:nth-child(3n+1) {
        background-color: rgb(215, 213, 213);
        /* Light gray for the first row in each group 
    }
    */
</style>
@if($status !="Berhak")
<style>
    .form-control,
    .glowing-border {
        pointer-events: none;
    }


    #tombol-kirim,
    .kirim-file {
        display: none
    }
</style>

@endif
@endpush

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> {{$title}} - Unit {{$unit_zi->nama}} -
                {{$unit_zi->instansiZI->klpd_instansi->name}}
            </h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped">
                <thead class="table-dark font-bold">
                    <tr>
                        <th width="20%">Tahapan Seleksi</th>
                        <th width="45%">Kondisi / Catatan</th>
                        <th width="45%">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-left">Seleksi Administrasi dan sanggah</th>
                        <td> @if(isset($unit_zi->seleksi_administrasi_unit))
                            {!!$unit_zi->seleksi_administrasi_unit->catatan_lke!!}
                            {!!$unit_zi->seleksi_administrasi_unit->catatan_tlhp!!}
                            {!!$unit_zi->seleksi_administrasi_unit->catatan_survei_mandiri!!}
                            {!!$unit_zi->seleksi_administrasi_unit->catatan_2wbk!!}
                            @endif
                            @if(isset($unit_zi->sanggah_unit))
                            {!!$unit_zi->sanggah_unit->catatan_lke!!}
                            {!!$unit_zi->sanggah_unit->catatan_tlhp!!}
                            {!!$unit_zi->sanggah_unit->catatan_survei_mandiri!!}
                            {!!$unit_zi->sanggah_unit->catatan_2wbk!!}
                            @endif</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-left">Analisis Dokumen</th>
                        <td>
                            @if(isset($unit_zi->analisis_dokumen))
                            {!!$unit_zi->analisis_dokumen->kondisi!!}
                            @endif</td>
                        <td>{!!$unit_zi->analisis_dokumen->rekomendasi!!}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Wawancara</th>
                        <td> @if(isset($unit_zi->wawancara))
                            {!!$unit_zi->wawancara->kondisi!!}
                            @endif</td>
                        <td> @if(isset($unit_zi->wawancara))
                            {!!$unit_zi->wawancara->rekomendasi!!}
                            @endif</td>
                    </tr>
                    <tr>
                        <th class="text-left">Verifikasi Lapangan</th>
                        <td>
                            @if(isset($unit_zi->verifikasi_lapangan))
                            {!!$unit_zi->verifikasi_lapangan->kondisi!!}
                            @endif
                        </td>
                        <td>@if(isset($unit_zi->verifikasi_lapangan))
                            {!!$unit_zi->verifikasi_lapangan->rekomendasi!!}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left"></th>
                        <td>@if(isset($unit_zi->panel))
                            {!!$unit_zi->panel->kondisi!!}
                            @endif
                        </td>
                        <td>
                            @if(isset($unit_zi->panel))
                            {!!$unit_zi->panel->rekomendasi!!}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>






        </div>

        <form action="{{ route('proses_final_unit_simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="unit_id" value="{{$unit_zi->id}}">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6 intro-y">
                <div class="box p-5 zoom-in">
                    <div class="flex items-center">
                        <div class="w-3/4 flex-none">
                            <div class="text-lg font-bold truncate">Kondisi / Catatan</div>
                            <textarea id="content" name="catatan" rows="20"
                                class="form-control glowing-border">{{optional($unit_zi->final)->kondisi}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6 intro-y">
                <div class="box p-5 zoom-in">
                    <div class="flex items-center">
                        <div class="w-3/4 flex-none">
                            <div class="text-lg font-bold truncate">Rekomendasi</div>
                            <textarea id="content" name="rekomendasi" rows="20"
                                class="form-control glowing-border">{{optional($unit_zi->final)->rekomendasi}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6 intro-y">
                <div class="box p-5 zoom-in">
                    <div class="flex items-center">
                        <div class="w-3/4 flex-none">
                            <button type="submit" class="btn btn-primary">Simpan </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
</div>


@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="{{ asset('AdminLTE-3.2.0') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js">
</script>

@endpush