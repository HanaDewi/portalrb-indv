@extends('layout.rubick')
@section('title', 'Dashboard Hasil Evaluasi')

@section('content')

<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
<h2 class="text-lg font-medium truncate mr-5">Dashboard Hasil Evaluasi</h2>
</div>
                            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                                <div class="intro-y box p-5">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Provinsi
                                    </h2>
                                    <div class="mt-3">
                                        <div class="h-[213px]">
                                            <canvas id="pie-chart-provinsi" width="956" height="800" style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                                <div class="intro-y box p-5">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Kementrian Lembaga
                                    </h2>
                                    <div class="mt-3">
                                        <div class="h-[213px]">
                                            <canvas id="pie-chart-kementrian" width="956" height="800" style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                                <div class="intro-y box p-5">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Pemerintah Kabupaten/Kota
                                    </h2>
                                    <div class="mt-3">
                                        <div class="h-[213px]">
                                            <canvas id="pie-chart-pemerintah" width="956" height="800" style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
    <div class="intro-y box col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Hasil Evaluasi Semua Kementrian / Lembaga Pemerintah</h2>
        </div>
    
    <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
    <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w200">Instansi Pemerintah</th>
                        <th> Group Instansi</th>
                        <th >RB General</th>
                        <th >RB Tematik</th>
                        <th >Index RB</th>
                        <th >Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 0;
                    $predikat_kl_counts = [
                            'AA' => 0,
                            'A' => 0,
                            'BB' => 0,
                            'B' => 0,
                            'CC' => 0,
                            'C' => 0,
                            'D' => 0,
                        ];
                    $predikat_prov_counts = [
                            'AA' => 0,
                            'A' => 0,
                            'BB' => 0,
                            'B' => 0,
                            'CC' => 0,
                            'C' => 0,
                            'D' => 0,
                        ];
                    $predikat_kab_counts = [
                            'AA' => 0,
                            'A' => 0,
                            'BB' => 0,
                            'B' => 0,
                            'CC' => 0,
                            'C' => 0,
                            'D' => 0,
                        ];
                    @endphp
                    @foreach ($instansis as $instansi)
                        @php
                            $no++;
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td><a class="tabel" href="{{ URL::to('/hasil/' . $instansi->id) }}">{{ $instansi->name }}</class=></td>
                            <td> {{ $instansi->group }}</td>
                            <td>
                                {{ isset($instansi->lke_test_tp) ? round($instansi->lke_test_tp->rb_general, 2) : '---' }}
                            </td>
                            <td>
                                {{ isset($instansi->lke_test_tp) ? round($instansi->lke_test_tp->rb_tematik, 2) : '---' }}
                            </td>
                            <td>
                                {{ isset($instansi->lke_test_tp) ? round($instansi->lke_test_tp->index_rb, 2) : '---' }}
                            </td>
                            <td>
                                @php
                                $score = isset($instansi->lke_test_tp) ? round($instansi->lke_test_tp->index_rb, 2) : null;                                
                                $predikat = 'D';
                                if ($score !== null) {
                                    if ($score == 100) {
                                        $predikat = 'AA';
                                    } elseif ($score >= 80 && $score <= 99.99) {
                                        $predikat = 'A';
                                    } elseif ($score >= 70 && $score <= 79.99) {
                                        $predikat = 'BB';
                                    } elseif ($score >= 60 && $score <= 69.99) {
                                        $predikat = 'B';
                                    } elseif ($score >= 50 && $score <= 59.99) {
                                        $predikat = 'CC';
                                    } elseif ($score >= 30 && $score <= 49.99) {
                                        $predikat = 'C';
                                    } else {
                                        $predikat = 'D';
                                    }
                                if($instansi->group=="kl"){
                                $predikat_kl_counts[$predikat]++;}
                                else if($instansi->group=="kab"){
                                $predikat_kab_counts[$predikat]++;}
                                else{
                                $predikat_prov_counts[$predikat]++;}
                                }
                                $predikat_counts_kl_json = json_encode(array_values($predikat_kl_counts));
                                $predikat_counts_kab_json = json_encode(array_values($predikat_kab_counts));
                                $predikat_counts_prov_json = json_encode(array_values($predikat_prov_counts));
                                @endphp
                                {{ $predikat }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>

    </div>




@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
var predikat_prov_Counts = <?php echo $predikat_counts_prov_json; ?>;
if ($("#pie-chart-provinsi").length) {
    var ctxProvinsi = $("#pie-chart-provinsi")[0].getContext("2d");

    var myPieChartProvinsi = new Chart(ctxProvinsi, {
        type: "bar",
        data: {
            labels: ["AA", "A", "BB", "B", "CC", "C", "D"],
            datasets: [{
                    data: predikat_prov_Counts,
                backgroundColor: [
                    "#f1c40f", "#b32d29", "#b3611f", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                ],
                hoverBackgroundColor: [
                    "#f1c40f", "#b32d29", "#b3611f", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                ],
                borderWidth: 1,
                borderColor: [
                    "#f1c40f", "#b32d29", "#f1bf52", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false, 
                    position: 'bottom',
                    labels: {
                        color: "#000"
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

var predikat_kl_Counts = <?php echo $predikat_counts_kl_json; ?>;

    if (document.getElementById("pie-chart-kementrian")) {
        var ctxProvinsi = document.getElementById("pie-chart-kementrian").getContext("2d");

        var myPieChartProvinsi = new Chart(ctxProvinsi, {
            type: "bar",
            data: {
                labels: ["AA", "A", "BB", "B", "CC", "C", "D"],
                datasets: [{
                    data: predikat_kl_Counts,
                    backgroundColor: [
                        "#f1c40f", "#b32d29", "#b3611f", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                    ],
                    hoverBackgroundColor: [
                        "#f1c40f", "#b32d29", "#b3611f", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                    ],
                    borderWidth: 1,
                    borderColor: [
                        "#f1c40f", "#b32d29", "#f1bf52", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                        labels: {
                            color: "#000"
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

var predikat_kab_Counts = <?php echo $predikat_counts_kab_json; ?>;
if ($("#pie-chart-pemerintah").length) {
    var ctxPemerintah  = $("#pie-chart-pemerintah")[0].getContext("2d");

    var myPieChartPemerintah  = new Chart(ctxPemerintah , {
        type: "bar",
        data: {
            labels: ["AA", "A", "BB", "B", "CC", "C", "D"],
            datasets: [{
                    data: predikat_kab_Counts,
                backgroundColor: [
                    "#f1c40f", "#b32d29", "#b3611f", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                ],
                hoverBackgroundColor: [
                    "#f1c40f", "#b32d29", "#b3611f", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                ],
                borderWidth: 1,
                borderColor: [
                    "#f1c40f", "#b32d29", "#f1bf52", "#b39450", "#a2a2a2", "#000", "#e74a0c"
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false, 
                    position: 'bottom',
                    labels: {
                        color: "#000"
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


$(document).ready(function(){
        var empDataTable = $('#perencanaan').DataTable({
            "scrollX": true
        });
    });


</script>

@endpush