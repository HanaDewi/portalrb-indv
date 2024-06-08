@extends('layout.rubick')
@section('title', 'Dashboard RB General')
@section('content')

    <div class="intro-y col-span-12 lg:col-span-12">
        @include('common.status')
        <h2 class="text-lg font-medium truncate mr-5">Dashboard RB Tematik - Penilaian Tema dan Sasaran Tematik, Permasalahan
            dan Sasaran, Rencana Aksi. </h2>
    </div>
    <div class="col-span-12 sm:col-span-6 lg:col-span-4">
        <div class="intro-y box p-5">
            <h2 class="text-lg font-medium truncate mr-5">
                Provinsi
            </h2>
            <div class="mt-3">
                <div class="h-[213px]">
                    <canvas id="pie-chart-provinsi" width="956" height="800"
                        style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
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
                    <canvas id="pie-chart-kementrian" width="956" height="800"
                        style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
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
                    <canvas id="pie-chart-pemerintah" width="956" height="800"
                        style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium truncate mr-5">Dashboard RB Tematik Berdasarkan Tematik Permasalahan</h2>
    </div>

    <div class="col-span-12 sm:col-span-6 lg:col-span-4">
        <div class="intro-y box p-5">
            <h2 class="text-lg font-medium truncate mr-5">
                Provinsi
            </h2>
            <div class="mt-3">
                <div class="h-[213px]">
                    <canvas id="pie-bar1" width="956" height="800"
                        style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 sm:col-span-6 lg:col-span-4">
        <div class="intro-y box p-5">
            <h2 class="text-lg font-medium truncate mr-5">
                Kementerian Lembaga
            </h2>
            <div class="mt-3">
                <div class="h-[213px]">
                    <canvas id="pie-bar2" width="956" height="800"
                        style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
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
                    <canvas id="pie-bar3" width="956" height="800"
                        style="display: block; box-sizing: border-box; height: 400px; width: 478px;"></canvas>
                </div>
            </div>
        </div>
    </div>



    <div class="intro-y col-span-12 lg:col-span-12" class="overflow-x-auto">
        <table class="table table-report -mt-2">
            <tbody>
                <tr class="intro-x">
                    <td class="text-center">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: #f1c40f"></div>
                            Tema 1: Pengentasan Kemiskinan
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-primary rounded-full mr-3"></div>
                            Tema 2: Realisasi Investasi
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: #b3611f"></div>
                            Tema 3: Digitalisasi Pemerintahan
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: #b39450"></div>
                            Tema 4: Penggunaan Produk Dalam Negeri
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: #a2a2a2"></div>
                            Tema 5: Pengendalian Inflasi
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>

    <div class="intro-y box col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data Semua Intansi Pemerintah - RB Tematik</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w200">Instansi Pemerintah</th>
                        <th> Group Instansi</th>
                        <th> Jumlah Tema</th>
                        <th>Tema dan Sasaran Tematik</th>
                        <th>Permasalahan dan Sasaran</th>
                        <th>Rencana Aksi</th>
                        <th>Semua</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 0;
                        $yes_kl = 0;
                        $no_kl = 0;
                        $yes_prov = 0;
                        $no_prov = 0;
                        $yes_kab = 0;
                        $no_kab = 0;
                        $tema_kl_counts = [
                            'tema1' => 0,
                            'tema2' => 0,
                            'tema3' => 0,
                            'tema4' => 0,
                            'tema5' => 0,
                        ];
                        $tema_prov_counts = [
                            'tema1' => 0,
                            'tema2' => 0,
                            'tema3' => 0,
                            'tema4' => 0,
                            'tema5' => 0,
                        ];
                        $tema_kab_counts = [
                            'tema1' => 0,
                            'tema2' => 0,
                            'tema3' => 0,
                            'tema4' => 0,
                            'tema5' => 0,
                        ];
                    @endphp
                    @foreach ($instansis as $instansi)
                        @php
                            $no++;
                            $instansi_id = $instansi->id;
                            $sql = "
            WITH baseline_check AS (
                SELECT
                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM tematik_sasaran_roadmap tsr
                            WHERE tsr.instansi_id = ?
                        ) THEN 'yes'
                        ELSE '---'
                    END as tematik,
                    (SELECT COUNT(DISTINCT tsr.tema_id)
                    FROM tematik_sasaran_roadmap tsr
                    WHERE tsr.instansi_id = ?)
            AS tema_id_count
            ),
            distinct_themes AS (
                SELECT DISTINCT tsr.tema_id,
                ROW_NUMBER() OVER (ORDER BY tsr.tema_id) AS row_num
                FROM tematik_sasaran_roadmap tsr
                WHERE tsr.instansi_id = ?
            ),
            permasalahan_check AS (
                SELECT
                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM tematik_sasaran_roadmap tsr
                            JOIN tematik_indikator_roadmap tir ON tir.tematik_sasaran_roadmap_id = tsr.id
                            JOIN tematik_permasalahan tp ON tp.tematik_indikator_roadmap_id = tir.id
                            WHERE tsr.instansi_id = ?
                        ) THEN 'yes'
                        ELSE '---'
                    END as permasalahan
            ),
            rencana_aksi_check AS (
                SELECT
                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM tematik_sasaran_roadmap tsr
                            JOIN tematik_indikator_roadmap tir ON tir.tematik_sasaran_roadmap_id = tsr.id
                            JOIN tematik_permasalahan tp ON tp.tematik_indikator_roadmap_id = tir.id
                            JOIN tematik_indikator_permasalahan tip ON tip.tematik_permasalahan_id = tp.id
                            JOIN tematik_rencana_aksi tra ON tra.tematik_indikator_permasalahan_id = tip.id
                            WHERE tsr.instansi_id = ?
                        ) THEN 'yes'
                        ELSE '---'
                    END as rencana_aksi
            )
            SELECT 
                baseline_check.tematik,
                baseline_check.tema_id_count,
                MAX(CASE WHEN distinct_themes.tema_id = 1 THEN 1 ELSE 0 END) AS tema1,
                MAX(CASE WHEN distinct_themes.tema_id = 2 THEN 1 ELSE 0 END) AS tema2,
                MAX(CASE WHEN distinct_themes.tema_id = 3 THEN 1 ELSE 0 END) AS tema3,
                MAX(CASE WHEN distinct_themes.tema_id = 4 THEN 1 ELSE 0 END) AS tema4,
                MAX(CASE WHEN distinct_themes.tema_id = 5 THEN 1 ELSE 0 END) AS tema5,
                permasalahan_check.permasalahan,
                rencana_aksi_check.rencana_aksi
            FROM baseline_check
            LEFT JOIN distinct_themes ON 1 = 1
            LEFT JOIN permasalahan_check ON 1 = 1
            LEFT JOIN rencana_aksi_check ON 1 = 1
            GROUP BY baseline_check.tematik, baseline_check.tema_id_count, permasalahan_check.permasalahan, rencana_aksi_check.rencana_aksi;
        ";

                            $exists = DB::select($sql, [$instansi_id, $instansi_id, $instansi_id, $instansi_id, $instansi_id]);
                            $tematik = $exists[0]->tematik ?? '---';
                            $permasalahan = $exists[0]->permasalahan ?? '---';
                            $rencana_aksi = $exists[0]->rencana_aksi ?? '---';
                            $tema_id_count = $exists[0]->tema_id_count ?? '---';
                            $semua = $tematik == 'yes' && $permasalahan == 'yes' && $rencana_aksi == 'yes' ? 'yes' : '---';

                            $temas = [
                                'tema1' => $exists[0]->tema1 ?? 0,
                                'tema2' => $exists[0]->tema2 ?? 0,
                                'tema3' => $exists[0]->tema3 ?? 0,
                                'tema4' => $exists[0]->tema4 ?? 0,
                                'tema5' => $exists[0]->tema5 ?? 0,
                            ];

                            foreach ($temas as $key => $tema) {
                                if ($tema) {
                                    if ($instansi->group == 'kl') {
                                        $tema_kl_counts[$key]++;
                                    } elseif ($instansi->group == 'kab') {
                                        $tema_kab_counts[$key]++;
                                    } else {
                                        $tema_prov_counts[$key]++;
                                    }
                                }
                            }

                            $tema_counts_kl_json = json_encode(array_values($tema_kl_counts));
                            $tema_counts_kab_json = json_encode(array_values($tema_kab_counts));
                            $tema_counts_prov_json = json_encode(array_values($tema_prov_counts));

                            if ($instansi->group == 'kl') {
                                if ($semua == 'yes') {
                                    $yes_kl++;
                                } else {
                                    $no_kl++;
                                }
                            } elseif ($instansi->group == 'prov') {
                                if ($semua == 'yes') {
                                    $yes_prov++;
                                } else {
                                    $no_prov++;
                                }
                            } elseif ($instansi->group == 'kab') {
                                if ($semua == 'yes') {
                                    $yes_kab++;
                                } else {
                                    $no_kab++;
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td><a class="tabel"
                                    href="{{ URL::to('/rencana_aksi/rb-tematik/rekap_data?instansi_id=' . $instansi->id) }}">{{ $instansi->name }}</a>
                            </td>
                            @php
                                $group = $instansi->group == 'kl' ? 'Kementerian' : ($instansi->group == 'prov' ? 'Provinsi' : ($instansi->group == 'kab' ? 'Kabupaten' : 'Lainnya'));
                            @endphp
                            <td> {{ $group }}</td>
                            <td> {{ $tema_id_count }}
                            </td>
                            <td>
                                @if ($tematik == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green"
                                        class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                        <path
                                            d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                @else
                                    {{ $tematik }}
                                @endif
                            </td>
                            <td>
                                @if ($permasalahan == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green"
                                        class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                        <path
                                            d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                @else
                                    {{ $permasalahan }}
                                @endif
                            </td>
                            <td>
                                @if ($rencana_aksi == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green"
                                        class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                        <path
                                            d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                @else
                                    {{ $rencana_aksi }}
                                @endif
                            </td>
                            <td>
                                @if ($semua == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        version="1.1" width="36" height="36" viewBox="0 0 256 256"
                                        xml:space="preserve">
                                        <defs>
                                        </defs>
                                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                                            transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                            <circle cx="45" cy="45" r="45"
                                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(40,201,55); fill-rule: nonzero; opacity: 1;"
                                                transform="  matrix(1 0 0 1 0 0) " />
                                            <path
                                                d="M 38.478 64.5 c -0.01 0 -0.02 0 -0.029 0 c -1.3 -0.009 -2.533 -0.579 -3.381 -1.563 L 21.59 47.284 c -1.622 -1.883 -1.41 -4.725 0.474 -6.347 c 1.884 -1.621 4.725 -1.409 6.347 0.474 l 10.112 11.744 L 61.629 27.02 c 1.645 -1.862 4.489 -2.037 6.352 -0.391 c 1.862 1.646 2.037 4.49 0.391 6.352 l -26.521 30 C 40.995 63.947 39.767 64.5 38.478 64.5 z"
                                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                                                transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                        </g>
                                    </svg>
                                @else
                                    {{ $semua }}
                                @endif
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
        if ($("#pie-chart-provinsi").length) {
            var ctxProvinsi = $("#pie-chart-provinsi")[0].getContext("2d");

            var myPieChartProvinsi = new Chart(ctxProvinsi, {
                type: "pie",
                data: {
                    labels: ["{{ $yes_prov }} Sudah", "{{ $no_prov }} Belum"],
                    datasets: [{
                        data: [{{ $yes_prov }}, {{ $no_prov }}],
                        backgroundColor: ["#f1c40f", "#b32d29"],
                        hoverBackgroundColor: ["#f39c12", "#85201d"],
                        borderWidth: 5,
                        borderColor: $("html").hasClass("dark") ? "#34495e" : "#ecf0f1"
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: "#000"
                            }
                        }
                    }
                }
            });
        }

        if ($("#pie-chart-kementrian").length) {
            var ctxKementrian = $("#pie-chart-kementrian")[0].getContext("2d");

            var myPieChartKementrian = new Chart(ctxKementrian, {
                type: "pie",
                data: {
                    labels: ["{{ $yes_kl }} Sudah", "{{ $no_kl }} Belum"],
                    datasets: [{
                        data: [{{ $yes_kl }}, {{ $no_kl }}],
                        backgroundColor: ["#f1c40f", "#b32d29"],
                        hoverBackgroundColor: ["#f39c12", "#85201d"],
                        borderWidth: 5,
                        borderColor: $("html").hasClass("dark") ? "#34495e" : "#ecf0f1"
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: "#000"
                            }
                        }
                    }
                }
            });
        }

        if ($("#pie-chart-pemerintah").length) {
            var ctxPemerintah = $("#pie-chart-pemerintah")[0].getContext("2d");

            var myPieChartPemerintah = new Chart(ctxPemerintah, {
                type: "pie",
                data: {
                    labels: ["{{ $yes_kab }} Sudah", "{{ $no_kab }} Belum"],
                    datasets: [{
                        data: [{{ $yes_kab }}, {{ $no_kab }}],
                        backgroundColor: ["#f1c40f", "#b32d29"],
                        hoverBackgroundColor: ["#f39c12", "#85201d"],
                        borderWidth: 5,
                        borderColor: $("html").hasClass("dark") ? "#34495e" : "#ecf0f1"
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: "#000"
                            }
                        }
                    }
                }
            });
        }


        var tema_prov_Counts = <?php echo $tema_counts_prov_json; ?>;

        if (document.getElementById("pie-bar1")) {
            var ctxProvinsis = document.getElementById("pie-bar1").getContext("2d");

            var myPieChartProvinsi = new Chart(ctxProvinsis, {
                type: "bar",
                data: {
                    labels: ["Tema 1", "Tema 2", "Tema 3", "Tema 4", "Tema 5"],
                    datasets: [{
                        data: tema_prov_Counts,
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


        var tema_kl_Counts = <?php echo $tema_counts_kl_json; ?>;
        if (document.getElementById("pie-bar2")) {
            var ctxProvinsis2 = document.getElementById("pie-bar2").getContext("2d");

            var myPieChartProvinsi2 = new Chart(ctxProvinsis2, {
                type: "bar",
                data: {
                    labels: ["Tema 1", "Tema 2", "Tema 3", "Tema 4", "Tema 5"],
                    datasets: [{
                        data: tema_kl_Counts,
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


        var tema_kab_Counts = <?php echo $tema_counts_kab_json; ?>;
        if (document.getElementById("pie-bar3")) {
            var ctxProvinsis3 = document.getElementById("pie-bar3").getContext("2d");

            var myPieChartProvinsi3 = new Chart(ctxProvinsis3, {
                type: "bar",
                data: {
                    labels: ["Tema 1", "Tema 2", "Tema 3", "Tema 4", "Tema 5"],
                    datasets: [{
                        data: tema_kab_Counts,
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



        $(document).ready(function() {
            var empDataTable = $('#perencanaan').DataTable({
                "scrollX": true
            });
        });
    </script>
@endpush
