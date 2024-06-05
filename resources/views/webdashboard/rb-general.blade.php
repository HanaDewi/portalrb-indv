@extends('layout.rubick')
@section('title', 'Dashboard RB General')
@section('content')

                    <div class="intro-y col-span-12 lg:col-span-12">
                        @include('common.status')
                        <h2 class="text-lg font-medium truncate mr-5">Dashboard Rencana Aksi RB General - Tahun 2024</h2>
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
                                <h2 class="font-bold text-base mr-auto"> Hasil Semua Intansi Pemerintah - Dashboard RB General</h2>
                            </div>
                            <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
                                <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                                    <thead class="table-dark font-bold">
                                        <tr>
                                            <th class="w-5">No.</th>
                                            <th class="w200">Instansi Pemerintah</th>
                                            <th> Group Instansi</th>
                                            <th >Baseline</th>
                                            <th >Tahun Target</th>
                                            <th >Target</th>
                                            <th >Rencana Aksi</th>
                                            <th >Semua</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    @php
                        $tahun_berjalan = 2024;
                        $no = 0;
                        $yes_kl = 0;
                        $no_kl = 0;
                        $yes_prov = 0;
                        $no_prov = 0;
                        $yes_kab = 0;
                        $no_kab = 0;
                    @endphp

                    @foreach ($instansis as $instansi)
                        @php
                            $no++;
                            $w = '';
                            if ($instansi->group == 'kl') {
                                $w = 'i.kl';
                            } elseif ($instansi->group == 'prov') {
                                $w = 'i.provinsi';
                            } elseif ($instansi->group == 'kab') {
                                $w = 'i.kabupaten';
                            } else {
                                $w = 'i.id';
                            }
                            $sql = "
                                WITH baseline_check AS (
                                    SELECT 
                                        CASE 
                                            WHEN NOT EXISTS (
                                                SELECT 1
                                                FROM indikator i
                                                WHERE $w = 1 
                                                AND NOT EXISTS (
                                                    SELECT 1 
                                                    FROM general_perencanaan gp 
                                                    WHERE gp.indikator_id = i.id 
                                                        AND gp.instansi_id = ? AND gp.baseline_tahun = ($tahun_berjalan - 1)
                                                )
                                            ) THEN 'yes'
                                            ELSE '---'
                                        END AS baseline
                                ),
                                target_check AS (
                                    SELECT 
                                        baseline,
                                        CASE 
                                            WHEN baseline = 'yes' AND NOT EXISTS (
                                                SELECT 1
                                                FROM general_perencanaan gp
                                                WHERE gp.instansi_id = ?
                                                AND NOT EXISTS (
                                                    SELECT 1
                                                    FROM general_perencanaan_target gpt
                                                    WHERE gpt.general_perencanaan_id = gp.id
                                                )
                                            ) THEN 'yes'
                                            ELSE '---'
                                        END AS target,
                                        (SELECT gpt.tahun FROM general_perencanaan_target gpt
                                            JOIN general_perencanaan gp ON gpt.general_perencanaan_id = gp.id
                                            WHERE gp.instansi_id = ? LIMIT 1) AS tahun
                                    FROM baseline_check
                                )
                                SELECT 
                                    baseline,
                                    target,
                                    tahun,
                                    CASE
                                        WHEN target = 'yes' AND EXISTS (
                                            SELECT 1
                                            FROM general_perencanaan_target gpt
                                            WHERE  EXISTS (
                                                SELECT 1
                                                FROM general_perencanaan gp
                                                WHERE gp.instansi_id = ?
                                                AND gp.id = gpt.general_perencanaan_id
                                            )
                                            AND NOT EXISTS (
                                                SELECT 1
                                                FROM general_rencana_aksi gra
                                                WHERE gra.general_perencanaan_target_id = gpt.id
                                            )
                                        ) THEN 'yes'
                                        ELSE '---'
                                    END AS rencana_aksi
                                FROM target_check
                            ";
                            $exists = DB::select($sql, [$instansi->id, $instansi->id, $instansi->id, $instansi->id]);
                            $baseline = $exists[0]->baseline;
                            $target = $exists[0]->target;
                            $tahun = $exists[0]->tahun;
                            $rencana_aksi = $exists[0]->rencana_aksi;
                            $semua = ($baseline == 'yes' && $target == 'yes' && $rencana_aksi == 'yes') ? 'yes' : '---';
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
                                <td><a class="tabel" href="{{ URL::to('/rencana_aksi/rb-general/rekap_data?instansi_id=' . $instansi->id) }}">{{ $instansi->name }}</a></td>
                                                            @php
                                    $group = $instansi->group == 'kl' ? 'Kementerian' :
                                            ($instansi->group == 'prov' ? 'Provinsi' :
                                            ($instansi->group == 'kab' ? 'Kabupaten' : 'Lainnya'));
                                @endphp

                                <td>{{ $group }}</td>
                                <td>@if ($baseline == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                                    </svg>
                                    @else {{ $baseline }} @endif
                                </td>
                                <td>{{ $tahun ?? '---' }}</td>
                                <td>@if ($target == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                                    </svg>
                                    @else {{ $target }} @endif
                                </td>
                                <td>@if ($rencana_aksi == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="green" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                                    </svg>
                                    @else {{ $rencana_aksi }} @endif
                                </td>
                                <td>@if ($semua == 'yes')
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="36" height="36" viewBox="0 0 256 256" xml:space="preserve">
                                        <defs>
                                        </defs>
                                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                            <circle cx="45" cy="45" r="45" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(40,201,55); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
                                            <path d="M 38.478 64.5 c -0.01 0 -0.02 0 -0.029 0 c -1.3 -0.009 -2.533 -0.579 -3.381 -1.563 L 21.59 47.284 c -1.622 -1.883 -1.41 -4.725 0.474 -6.347 c 1.884 -1.621 4.725 -1.409 6.347 0.474 l 10.112 11.744 L 61.629 27.02 c 1.645 -1.862 4.489 -2.037 6.352 -0.391 c 1.862 1.646 2.037 4.49 0.391 6.352 l -26.521 30 C 40.995 63.947 39.767 64.5 38.478 64.5 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                        </g>
                                        </svg>
                                    @else {{ $semua }} @endif
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
        var ctxKementrian  = $("#pie-chart-kementrian")[0].getContext("2d");

        var myPieChartKementrian  = new Chart(ctxKementrian , {
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
        var ctxPemerintah  = $("#pie-chart-pemerintah")[0].getContext("2d");

        var myPieChartPemerintah  = new Chart(ctxPemerintah , {
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

    $(document).ready(function(){
            var empDataTable = $('#perencanaan').DataTable({
                "scrollX": true
            });
        });

    </script>

@endpush