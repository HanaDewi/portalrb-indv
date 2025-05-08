@extends('zi.admin.rubick')
@section('title', 'Rekap Data Pengusulan ZI - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> {{$title}} </h2>
        </div>
        <br />
        <div class="col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6  intro-y">
                <div class="box p-5 zoom-in">
                    <div class="flex items-center">
                        <div class="text-lg font-bold truncate">Tahun :
                            <select id="filter-tahun">
                                @for ($i =date('Y'); $i >= 2024; $i--)
                                <option value="{{$i}}" @if($i==$tahun ) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="separator mt-5"></div>
            <table id="rekap-zi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th rowspan=2>No</th>
                        <th rowspan=2>Instansi</th>
                        <th rowspan=2>Unit</th>
                        <th rowspan=2>WBK/WBBM</th>
                        <th colspan=5>Status</th>
                        <th rowspan=2>LKE Evaluator </th>
                        <th rowspan=2>Kondisi / Catatan</th>
                        <th rowspan=2>Rekomendasi</th>

                    </tr>
                    <tr>
                        <th>Administrasi dan sanggah</th>
                        <th>Analisis Dokumen</th>
                        <th>Wawancara</th>
                        <th>Verifikasi Lapangan</th>
                        <th>Panel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unit_ZIs as $index => $unit_ZI )
                    <tr>
                        <td>{{$index+1}}</td>
                        <td @if($unit_ZI->instansiZI->instansi_wbk_mandiri)
                            class="text-red-500"
                            @endif
                            >
                            {{$unit_ZI->instansiZI->klpd_instansi->name}}
                            @if($unit_ZI->instansiZI->instansi_wbk_mandiri)
                            (WBK Mandiri)
                            @else
                            <i style="opacity: 0;">(Non Mandiri) </i>
                            @endif
                        </td>
                        <td>{{$unit_ZI->nama}}</td>
                        <td class="text-center">
                            @if($unit_ZI->wbk)
                            WBK
                            @else
                            WBBM
                            @endif
                        </td>
                        <td class="text-center">

                            @if(optional($unit_ZI->seleksi_administrasi_unit)->status_final == 1)
                            <div style="visibility: hidden">1</div>
                            <i class="fa fa-check-circle" style="font-size: 2em; color:green"></i>
                            @elseif(optional($unit_ZI->sanggah_unit)->status_final ==1)
                            <div style="visibility: hidden">1</div>
                            <i class="fa fa-check-circle" style="font-size: 2em; color:green"></i>
                            @elseif(optional($unit_ZI->sanggah_unit)->status_final === 0)
                            <div style="visibility: hidden">0</div>
                            <i class="fa  fa-circle-xmark" style="font-size: 2em; color:red"></i>
                            @endif

                        </td>
                        <td class="text-center">
                            @if(optional($unit_ZI->analisis_dokumen)->status == 1)
                            <div style="visibility: hidden">1</div>
                            <i class="fa fa-check-circle" style="font-size: 2em; color:green"></i>
                            @elseif(optional($unit_ZI->analisis_dokumen)->status === 0)
                            <div style="visibility: hidden">0</div>
                            <i class="fa  fa-circle-xmark" style="font-size: 2em; color:red"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(optional($unit_ZI->wawancara)->status == 1)
                            <div style="visibility: hidden">1</div>
                            <i class="fa fa-check-circle" style="font-size: 2em; color:green"></i>
                            @elseif(optional($unit_ZI->wawancara)->status === 0)
                            <div style="visibility: hidden">0</div>
                            <i class="fa  fa-circle-xmark" style="font-size: 2em; color:red"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(optional($unit_ZI->verifikasi_lapangan)->status == 1)
                            <div style="visibility: hidden">1</div>
                            <i class="fa fa-check-circle" style="font-size: 2em; color:green"></i>
                            @elseif(optional($unit_ZI->verifikasi_lapangan)->status === 0)
                            <div style="visibility: hidden">0</div>
                            <i class="fa  fa-circle-xmark" style="font-size: 2em; color:red"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(optional($unit_ZI->panel)->status == 1)
                            <div style="visibility: hidden">1</div>
                            <i class="fa fa-check-circle" style="font-size: 2em; color:green"></i>
                            @elseif(optional($unit_ZI->panel)->status === 0)
                            <div style="visibility: hidden">0</div>
                            <i class="fa  fa-circle-xmark" style="font-size: 2em; color:red"></i>
                            @endif
                        </td>
                        <td>
                            @if($unit_ZI->analisis_dokumen)
                            <a href="{{$unit_ZI->analisis_dokumen->bukti_dukung}}" class="btn btn-primary"
                                target="_blank">Lihat</a>
                            @endif
                        </td>
                        <td>
                            <strong>Seleksi Administrasi :</strong><br />
                            @if(isset($unit_ZI->seleksi_administrasi_unit))
                            {{$unit_ZI->seleksi_administrasi_unit->catatan_lke}}
                            {{$unit_ZI->seleksi_administrasi_unit->catatan_tlhp}}
                            {{$unit_ZI->seleksi_administrasi_unit->catatan_survei_mandiri}}
                            {{$unit_ZI->seleksi_administrasi_unit->catatan_2wbk}}
                            @endif
                            <br /><br />@if(isset($unit_ZI->sanggah_unit))<br />
                            {{$unit_ZI->sanggah_unit->catatan_lke}}
                            {{$unit_ZI->sanggah_unit->catatan_tlhp}}
                            {{$unit_ZI->sanggah_unit->catatan_survei_mandiri}}
                            {{$unit_ZI->sanggah_unit->catatan_2wbk}}
                            @endif
                            <br /><br /><strong>Analisis Dokumen</strong><br />
                            @if(isset($unit_ZI->analisis_dokumen))
                            {{$unit_ZI->analisis_dokumen->kondisi}}
                            @endif
                            <br /><br /><strong>Wawancara</strong><br />
                            @if(isset($unit_ZI->wawancara))
                            {{$unit_ZI->wawancara->kondisi}}
                            @endif
                            <br /><br /><strong>Verifikasi Lapangan</strong><br />
                            @if(isset($unit_ZI->verifikasi_lapangan))
                            {{$unit_ZI->verifikasi_lapangan->kondisi}}
                            @endif
                            <br /><br /><strong>Panel</strong><br />
                            @if(isset($unit_ZI->panel))
                            {{$unit_ZI->panel->kondisi}}
                            @endif
                        </td>
                        <td>
                            <strong>Analisis Dokumen</strong><br />
                            @if(isset($unit_ZI->analisis_dokumen))
                            {{$unit_ZI->analisis_dokumen->rekomendasi}}
                            @endif
                            <br /><br /><strong>Wawancara</strong><br />
                            @if(isset($unit_ZI->wawancara))
                            {{$unit_ZI->wawancara->rekomendasi}}
                            @endif
                            <br /><br /><strong>Verifikasi Lapangan</strong><br />
                            @if(isset($unit_ZI->verifikasi_lapangan))
                            {{$unit_ZI->verifikasi_lapangan->rekomendasi}}
                            @endif
                            <br /><br /><strong>Panel</strong><br />
                            @if(isset($unit_ZI->panel))
                            {{$unit_ZI->panel->rekomendasi}}
                            @endif

                        </td>
                    </tr>
                    @endforeach

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
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function(){
        $('#filter-tahun').change(function() {
            var selectedValue = $(this).val();
            window.location.href = window.location.pathname + '?tahun=' + selectedValue;
        });
        var empDataTable = $('#rekap-zi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    title: 'Rekap Data Pengusulan ZI',
                    text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="https://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                            titleAttr: 'Download Excel'
                } 
            ],
            scrollX: true,
            // 'orderFixed': [0, 'asc'],
            autoWidth: false,
            paging: true,
            bInfo: false,
            ordering: true,
        });

        
        $('#unitMandiri').on('click', function () {
            empDataTable.search("uwbkm").draw();
        });

        $('#unitNonMandiri').on('click', function () {
            empDataTable.search("Non uwbkn", true, true, true).draw();
        });
        $('#unitWbbm').on('click', function () {
            empDataTable.search("uwbbm", true, true, true).draw();
        });
        $('#unitTotal').on('click', function () {
            empDataTable.search("").draw();
        });

        empDataTable.on( 'order.dt search.dt', function () {
            empDataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            });
        }).draw();
            
    });

    

</script>
@endpush