@extends('zi.admin.rubick')
@section('title', 'Rekap Data Pengusulan ZI - ' .auth()->user()->nama)
@push('css')
<style>
    * {
        box-sizing: border-box;
    }

    h1 {
        font-size: 10px;
    }

    /* The actual timeline (the vertical ruler) */
    .timeline {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* The actual timeline (the vertical ruler) */
    .timeline::after {
        content: '';
        position: absolute;
        width: 6px;
        background-color: black;
        top: 0;
        bottom: 0;
        left: 5%;
        margin-left: -6px;
    }

    /* Container around content */
    .container {
        padding: 10px 40px;
        position: relative;
        background-color: inherit;
        width: 100%;
    }

    /* The circles on the timeline */
    .container::after {
        content: '';
        position: absolute;
        width: 25px;
        height: 25px;
        right: -17px;
        background-color: white;
        border: 4px solid #FF9F55;
        top: 15px;
        border-radius: 50%;
        z-index: 1;
    }

    /* Place the container to the left */
    .left {
        left: 0;
    }

    /* Place the container to the right */
    .right {
        left: 5%;
    }

    /* Add arrows to the left container (pointing right) */
    .left::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 22px;
        width: 0;
        z-index: 1;
        right: 30px;
        border: medium solid white;
        border-width: 10px 0 10px 10px;
        border-color: transparent transparent transparent white;
    }

    /* Add arrows to the right container (pointing left) */
    .right::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 22px;
        width: 0;
        z-index: 1;
        left: 30px;
        border: medium solid white;
        border-width: 10px 10px 10px 0;
        border-color: transparent white transparent transparent;
    }

    /* Fix the circle for containers on the right side */
    .right::after {
        left: -16px;
    }

    /* The actual content */
    .timeline-content {
        padding: 20px 30px;
        background-color: white;
        position: relative;
        border-radius: 6px;
    }

    /* Media queries - Responsive timeline on screens less than 600px wide */
    @media screen and (max-width: 600px) {

        /* Place the timelime to the left */
        .timeline::after {
            left: 31px;
        }

        /* Full-width containers */
        .container {
            width: 100%;
            padding-left: 70px;
            padding-right: 25px;
        }

        /* Make sure that all arrows are pointing leftwards */
        .container::before {
            left: 60px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent white transparent transparent;
        }

        /* Make sure all circles are at the same spot */
        .left::after,
        .right::after {
            left: 15px;
        }

        /* Make all right containers behave like the left ones */
        .right {
            left: 0%;
        }
    }
</style>

@endpush
@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Rekap Data Total ZI - {{ auth()->user()->nama }}</h2>
        </div>
        <br />
        <div class="col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6  intro-y">
                <div class="box p-5 zoom-in">
                    <a href="{{route('rekap_unit')}}">
                        <div class="flex items-center">
                            <div class="w-2/4 flex-none">
                                <div class="text-lg font-bold truncate">Tahap Pengusulan</div>
                                <div class="text-gray-800 mt-2 text-xl">

                                    {{$total_instansi}} <sup style="font-size: 0.5em">Total Instansi</sup>
                                    <br />
                                    {{$total_wbk}} <sup style="font-size: 0.5em">Unit WBK</sup>
                                    |
                                    {{$total_wbbm}} <sup style="font-size: 0.5em">Unit WBBM</sup>|
                                    <b>{{$total_wbk +
                                        $total_wbbm}}
                                        <sup style="font-size: 0.5em">Jumlah Unit Total</sup></b>
                                </div>
                            </div>
                            <div class="flex-none ml-auto relative">
                                <div class="w-[90px] h-[90px]">
                                    <canvas id="report-donut-chart-2" width="90" height="90"
                                        style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                                </div>
                                <div
                                    class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-bar-chart">
                                            <path
                                                d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                            <polyline points="14 2 14 8 20 8" />
                                            <path d="M12 18v-4" />
                                            <path d="M8 18v-2" />
                                            <path d="M16 18v-6" />
                                        </svg> </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6 intro-y">
                <a href="{{route('rekap_final')}}">
                    <div class="box p-5 zoom-in">
                        <div class="flex items-center">
                            <div class="w-3/4 flex-none">
                                <div class="text-lg font-bold truncate">Lulus Final</div>
                                <div class="text-gray-800 mt-2 text-xl">

                                    {{$total_instansi_final}} <sup style="font-size: 0.5em">Total Instansi</sup>
                                    <br />
                                    {{$total_wbk_final}} <sup style="font-size: 0.5em">Unit WBK</sup>
                                    |
                                    {{$total_wbbm_final}} <sup style="font-size: 0.5em">Unit WBBM</sup>|
                                    <b>{{$total_wbk_final +
                                        $total_wbbm_final}} <sup style="font-size: 0.5em">Jumlah Unit
                                            Total</sup></b>
                                    < </div>
                                </div>
                                <div class="flex-none ml-auto relative">
                                    <div class="w-[90px] h-[90px]">
                                        <canvas id="report-donut-chart-2" width="90" height="90"
                                            style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                                    </div>
                                    <div
                                        class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                        <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-file-bar-chart">
                                                <path
                                                    d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                                <polyline points="14 2 14 8 20 8" />
                                                <path d="M12 18v-4" />
                                                <path d="M8 18v-2" />
                                                <path d="M16 18v-6" />
                                            </svg> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br /><br />

            <!-- Timeline -->
            <div class="timeline">
                <div class="container right">
                    <a href="{{ route('seleksi_administrasi') }}">
                        <div class="timeline-content">
                            <h1>Seleksi Administrasi</h1>
                            <div class="col-md-6">

                                <a href="#c" id="instansiTotal"><b> {{$total_instansi_administrasi}} <sup
                                            style="font-size: 0.5em">Total Instansi</sup></b></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('rekap_unit')}}">
                                    {{$total_wbk_administrasi}} <sup style="font-size: 0.5em">WBK</sup>
                                    |
                                    ? <sup style="font-size: 0.5em">WBK Mandiri</sup> |
                                    {{$total_wbbm_administrasi}} <sup style="font-size: 0.5em">WBBM</sup> |
                                    <b> {{$total_wbk_administrasi + $total_wbbm_administrasi}} <sup
                                            style="font-size: 0.5em">Total Unit</sup></b>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="container right">
                    <a href="{{ route('sanggah') }}">
                        <div class="timeline-content">
                            <h1>Proses Sanggah</h1>
                            <div class="col-md-6">
                                <a href="#c" id="instansiTotal"><b> {{$total_instansi_sanggah}} <sup
                                            style="font-size: 0.5em">Total Instansi</sup></b></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('rekap_unit')}}">
                                    {{$total_wbk_sanggah}} <sup style="font-size: 0.5em">WBK</sup>
                                    |
                                    ? <sup style="font-size: 0.5em">WBK Mandiri</sup> |
                                    {{$total_wbbm_sanggah}} <sup style="font-size: 0.5em">WBBM</sup> |
                                    <b> {{$total_wbk_sanggah + $total_wbbm_sanggah}} <sup style="font-size: 0.5em">Total
                                            Unit</sup></b>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="container right">
                    <a href="{{ route('seleksi_dokumen') }}">
                        <div class="timeline-content">
                            <h1>Analisis Dokumen</h1>
                            <div class="col-md-6">
                                <a href="#c" id="instansiTotal"><b> {{$total_instansi_analisis_dokumen}} <sup
                                            style="font-size: 0.5em">Total Instansi</sup></b></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('rekap_unit')}}">
                                    {{$total_wbk_analisis_dokumen}} <sup style="font-size: 0.5em">WBK</sup>
                                    |
                                    ? <sup style="font-size: 0.5em">WBK Mandiri</sup> |
                                    {{$total_wbbm_analisis_dokumen}} <sup style="font-size: 0.5em">WBBM</sup> |
                                    <b> {{$total_wbk_analisis_dokumen + $total_wbbm_analisis_dokumen}} <sup
                                            style="font-size: 0.5em">Total Unit</sup></b>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="container right">
                    <a href="{{ route('seleksi_wawancara') }}?">
                        <div class="timeline-content">
                            <h1>Wawancara</h1>
                            <div class="col-md-6">

                                <a href="#c" id="instansiTotal"><b> {{$total_instansi_seleksi_wawancara}} <sup
                                            style="font-size: 0.5em">Total Instansi</sup></b></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('rekap_unit')}}">
                                    {{$total_wbk_seleksi_wawancara}} <sup style="font-size: 0.5em">WBK</sup>
                                    |
                                    ? <sup style="font-size: 0.5em">WBK Mandiri</sup> |
                                    {{$total_wbbm_seleksi_wawancara}} <sup style="font-size: 0.5em">WBBM</sup> |
                                    <b> {{$total_wbk_seleksi_wawancara + $total_wbbm_seleksi_wawancara}} <sup
                                            style="font-size: 0.5em">Total Unit</sup></b>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="container right">
                    <a href="{{ route('verifikasi_lapangan') }}">
                        <div class="timeline-content">
                            <h1>Observasi Lapangan</h1>
                            <div class="col-md-6">
                                <a href="#c" id="instansiTotal"><b> {{$total_instansi_verifikasi_lapangan}} <sup
                                            style="font-size: 0.5em">Total Instansi</sup></b></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('rekap_unit')}}">
                                    {{$total_wbk_verifikasi_lapangan}} <sup style="font-size: 0.5em">WBK</sup>
                                    |
                                    ? <sup style="font-size: 0.5em">WBK Mandiri</sup> |
                                    {{$total_wbbm_verifikasi_lapangan}} <sup style="font-size: 0.5em">WBBM</sup> |
                                    <b> {{$total_wbk_verifikasi_lapangan + $total_wbbm_verifikasi_lapangan}} <sup
                                            style="font-size: 0.5em">Total Unit</sup></b>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="container right">
                    <a href="{{ route('panel') }}">
                        <div class="timeline-content">
                            <h1>Panel</h1>
                            <div class="col-md-6">
                                <a href="#c" id="instansiTotal"><b> {{$total_instansi_panel}} <sup
                                            style="font-size: 0.5em">Total Instansi</sup></b></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('rekap_unit')}}">
                                    {{$total_wbk_panel}} <sup style="font-size: 0.5em">WBK</sup>
                                    |
                                    ? <sup style="font-size: 0.5em">WBK Mandiri</sup> |
                                    {{$total_wbbm_panel}} <sup style="font-size: 0.5em">WBBM</sup> |
                                    <b> {{$total_wbk_panel + $total_wbbm_panel}} <sup style="font-size: 0.5em">Total
                                            Unit</sup></b>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
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

        
        $('#instansiMandiri').on('click', function () {
            empDataTable.search("WBK Mandiri").draw();
        });

        $('#instansiNonMandiri').on('click', function () {
            empDataTable.search("Non Mandiri", true, true, true).draw();
        });

        $('#instansiTotal').on('click', function () {
            empDataTable.search("").draw();
        });

        empDataTable.on('order.dt search.dt', function () {
            empDataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        })
        }).draw();
    
    });

    

    </script>
    @endpush