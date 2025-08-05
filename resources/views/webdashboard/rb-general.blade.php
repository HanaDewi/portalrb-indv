@extends('layout.rubick')
@section('title', 'Dashboard RB General')

@section('content')
    <div class="intro-y col-span-12 lg:col-span-12 p-5">
        @include('common.status')
    </div>
    
    <div class="intro-y box col-span-12 lg:col-span-12 mb-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="text-lg font-medium truncate mr-5">Dashboard Rencana Aksi RB General</h2>
        </div>
        <form method="GET" action="{{ url()->current() }}">
            <div class="flex items-center gap-3 p-5">
                <label for="tahun" class="font-medium">Tahun:</label>
                <select name="tahun" id="tahun" class="form-select w-40" onchange="this.form.submit()">
                    @foreach ([2024, 2025] as $th)
                        <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="col-span-12 grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 intro-y box p-5 mb-5">
            <h2 class="text-lg font-medium truncate mr-5">
                Pemerintah Kabupaten/Kota
            </h2>
            <div class="mt-3">
                <div class="h-[213px]">
                    <canvas id="pie-chart-pemerintah" class="h-[300px] w-full"></canvas>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 intro-y box p-5 mb-5">
            <h2 class="text-lg font-medium truncate mr-5">
                Provinsi
            </h2>
            <div class="mt-3">
                <div class="h-[213px]">
                    <canvas id="pie-chart-provinsi" class="h-[300px] w-full"></canvas>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 intro-y box p-5 mb-5">
            <h2 class="text-lg font-medium truncate mr-5">
                Kementrian Lembaga
            </h2>
            <div class="mt-3">
                <div class="h-[213px]">
                    <canvas id="pie-chart-kementrian" class="h-[300px] w-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y box col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto">Hasil Semua Instansi Pemerintah - Dashboard RB General</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w200">Instansi Pemerintah</th>
                        <th>Group Instansi</th>
                        <th>Baseline</th>
                        <th>Tahun Target</th>
                        <th>Target</th>
                        <th>Rencana Aksi</th>
                        <th>Semua</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><a class="tabel" href="{{ url('/rencana_aksi/rb-general/rekap_data?instansi_id%5B%5D=' . $data['instansi']->id) }}">{{ $data['instansi']->name }}</a></td>
                            <td>{{ $data['group'] }}</td>
                            <td>{!! $data['baseline'] ? '<span style="font-weight: bolder; font-size:28px;">✔</span>' : '---' !!}</td>
                            <td>{{ $data['tahun_target'] }}</td>
                            <td>{!! $data['target'] ? '<span style="font-weight: bolder; font-size:28px;">✔</span>' : '---' !!}</td>
                            <td>{!! $data['rencana_aksi'] ? '<span style="font-weight: bolder; font-size:28px;">✔</span>' : '---' !!}</td>
                            <td>{!! $data['semua'] ? '<span style="font-weight: bolder; font-size:28px; color: #30d15b;">✔</span>' : '---' !!}</td>
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

        $(document).ready(function() {
            var empDataTable = $('#perencanaan').DataTable({
                "scrollX": true,
                dom: 'Blfrtip',
                pageLength: 50,
                lengthMenu: [50, 100, 150, 'All'],
                buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        orientation: 'landscape',
                        pageSize: 'A4',
                        text: '<button class="btn btn-danger w-32 mr-2 mb-2"><svg fill="#ffffff" height="24px" width="24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 482.14 482.14" xml:space="preserve" stroke="#ffffff"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <g> <path d="M142.024,310.194c0-8.007-5.556-12.782-15.359-12.782c-4.003,0-6.714,0.395-8.132,0.773v25.69 c1.679,0.378,3.743,0.504,6.588,0.504C135.57,324.379,142.024,319.1,142.024,310.194z"/> <path d="M202.709,297.681c-4.39,0-7.227,0.379-8.905,0.772v56.896c1.679,0.394,4.39,0.394,6.841,0.394 c17.809,0.126,29.424-9.677,29.424-30.449C230.195,307.231,219.611,297.681,202.709,297.681z"/> <path d="M315.458,0H121.811c-28.29,0-51.315,23.041-51.315,51.315v189.754h-5.012c-11.418,0-20.678,9.251-20.678,20.679v125.404 c0,11.427,9.259,20.677,20.678,20.677h5.012v22.995c0,28.305,23.025,51.315,51.315,51.315h264.223 c28.272,0,51.3-23.011,51.3-51.315V121.449L315.458,0z M99.053,284.379c6.06-1.024,14.578-1.796,26.579-1.796 c12.128,0,20.772,2.315,26.58,6.965c5.548,4.382,9.292,11.615,9.292,20.127c0,8.51-2.837,15.745-7.999,20.646 c-6.714,6.32-16.643,9.157-28.258,9.157c-2.585,0-4.902-0.128-6.714-0.379v31.096H99.053V284.379z M386.034,450.713H121.811 c-10.954,0-19.874-8.92-19.874-19.889v-22.995h246.31c11.42,0,20.679-9.25,20.679-20.677V261.748 c0-11.428-9.259-20.679-20.679-20.679h-246.31V51.315c0-10.938,8.921-19.858,19.874-19.858l181.89-0.19v67.233 c0,19.638,15.934,35.587,35.587,35.587l65.862-0.189l0.741,296.925C405.891,441.793,396.987,450.713,386.034,450.713z M174.065,369.801v-85.422c7.225-1.15,16.642-1.796,26.58-1.796c16.516,0,27.226,2.963,35.618,9.282 c9.031,6.714,14.704,17.416,14.704,32.781c0,16.643-6.06,28.133-14.453,35.224c-9.157,7.612-23.096,11.222-40.125,11.222 C186.191,371.092,178.966,370.446,174.065,369.801z M314.892,319.226v15.996h-31.23v34.973h-19.74v-86.966h53.16v16.122h-33.42 v19.875H314.892z"/> </g> </g> </svg> &nbsp;PDF </button>',
                        titleAttr: 'Download PDF',
                        customize: function(doc) {
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    if (cell.text === '✔' || cell.text === '✓') {
                                        cell.text = 'YA';
                                    }
                                });
                            });
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<button class="btn btn-warning w-32 mr-2 mb-2"> <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="24px" height="24px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                        titleAttr: 'Download Excel'
                    }
                ]
            });
        });
    </script>
@endpush
