@extends('home-template.template')
@section('cssJsHere')
<link rel="stylesheet" href="{{ asset('assets/css/timelinezi.css') }}" />
<style>
    select,
    input {
        width: auto;
    }

    .dataTables_length label {
        margin-left: 20px;
    }
</style>
@endsection

@section('content')
<section class="features-area pt-50 pb-85 rel z-1"
    style="background-image: url({{ URL::to('/') }}/assets/images/bg2.jpg); background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="section-title text-center pb-35 ">
                <h2 style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;">
                    Hasil Akhir</h2>
            </div>
            @include('zi.evaluatan.progress')
            <hr />
            <div class="col-lg-12 col-md-12">
                <div class="feature-item" style="background-color: white; border-radius: 25px; padding: 20px 80px">
                    <div class="content">
                        <h5>{{$title}} <br /> {{ $instansiZI->klpd_instansi->name}}</h5>
                        <hr />
                        <div class="row">
                        </div>
                        <hr />
                        <div class="row">
                            <div class="card">
                                <div class="card-header" style="text-align: left; background: #b42b2d; color:#F2F9FF">
                                    Pengumuman Akhir Zona Integritas
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header" style=" background: #B1F0F7; color:black">
                                                    Pengusulan & Hasil Akhir
                                                </div>
                                                <div class="card-body" style="text-align: left; font-size:12px">
                                                    <table class="table ">
                                                        <thead>
                                                            <tr>
                                                                <td>Unit</td>
                                                                <td class="text-center">Pengusulan</td>
                                                                <td class="text-center">Hasil Akhir</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>WBK</td>
                                                                <td class="text-center">{{$instansiZI->jml_wbk}}</td>
                                                                <td class="text-center" </td>
                                                            </tr>
                                                            <tr>
                                                                <td>WBBM</td>
                                                                <td class="text-center">{{$instansiZI->jml_wbbm}}</td>
                                                                <td class="text-center"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total</td>
                                                                <td class="text-center">{{$instansiZI->jml_wbk +
                                                                    $instansiZI->jml_wbbm}}
                                                                </td>
                                                                <td class="text-center"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header" style=" background: #81BFDA; color:black">
                                                    Surat Undangan
                                                </div>
                                                <div class="card-body">
                                                    @if ($instansiZI->surat_undangan)

                                                    <br />
                                                    <a href="{{asset('storage/uploads/SuratUndangan2024/'.$instansiZI->surat_undangan)}}"
                                                        target="_blank"><img src="{{asset('images/pdf.png')}}"
                                                            width="30%"></a>
                                                    <br /><br />
                                                    <p>Surat Undangan {{$instansiZI->klpd_instansi->name}}</p>

                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-header" style=" background: #F5F0CD; color:black">
                                                    LHE
                                                </div>
                                                <div class="card-body">
                                                    @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" )
                                                    @if ($instansiZI->lhe)

                                                    <br />
                                                    <a href="{{asset('storage/uploads/LHEZI2024/'.$instansiZI->lhe)}}"
                                                        target="_blank"><img src="{{asset('images/pdf.png')}}"
                                                            width="30%"></a>
                                                    <br /><br />
                                                    <p>LHE {{$instansiZI->klpd_instansi->name}}</p>

                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br /><br /><br /><br />
                            <hr />
                            <div class="row">
                                <div class="col-12">
                                    <table id="rekap-zi" class="table table-striped">
                                        <thead class="table-dark font-bold">
                                            <tr>
                                                <th>No</th>
                                                <th>Unit</th>
                                                <th>Status </th>
                                                <th>Catatan</th>
                                                <th>Rekomendasi</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" )
                                            @php
                                            $i=0;
                                            $wbk=0;
                                            $wbbm=0;
                                            @endphp
                                            @foreach ( $units as $unit )
                                            @if(!$instansiZI->instansi_wbk_mandiri OR ($instansiZI->instansi_wbk_mandiri
                                            AND
                                            $unit->wbbm ))
                                            <tr class="text-left" style="text-align:left">
                                                <td>{{++$i}}</td>
                                                <td class=" text-left">@if($unit->wbk) (WBK {{++$wbk}}) @else
                                                    (WBBM {{++$wbbm}}) @endif
                                                    {{$unit->nama}}
                                                </td>
                                                <td>
                                                    @if(optional($unit->panel)->status==1)
                                                    <p style="color:#066188">Lulus</p>
                                                    @else
                                                    <p style="color:#b42b2d">Tidak Lulus</p>
                                                    @endif
                                                </td>
                                                <td>{{optional($unit->final)->kondisi}}</td>
                                                <td>{{optional($unit->final)->rekomendasi}}</td>

                                            </tr>
                                            @endif
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan=5 style="color:rgb(200, 45, 45)">Hasil Akan ditampilkan
                                                    tanggal
                                                    11 Desember
                                                    Saat Acara
                                                    Penyerahan
                                                    diselenggarakan</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('jsHere')
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
    var empDataTable = $('#rekap-zi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    title: 'Rekap Data Pengusulan ZI',
                    text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="https://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Download Excel </button>',
                            titleAttr: 'Download Excel'
                } ,
                {
                     extend: 'pdfHtml5',
                     download: 'open',
                     text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <i class="fa fa-file-pdf" style="font-size:18px"></i>  &nbsp;Download PDF </button>',
                            titleAttr: 'Download PDF'
                }
            ],
            scrollX: true,
            autoWidth: false,
            paging: true,
            bInfo: false,
            ordering: true,
        });

        empDataTable.on( 'order.dt search.dt', function () {
            empDataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            });
        }).draw();
    });
</script>
@endsection