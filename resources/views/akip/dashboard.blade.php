@extends('layout.midone', ['akip' => true])
@section('title', 'Dashboard')

@section('content')
    <div class="col-span-12 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12 xl:col-span-12">
            <div class="box">
                <div class="p-5">
                    <div class="rounded-md">
                        <img width="100%" alt="menpanrb" class="rounded-md" src="{{ URL::to('/') }}/assets/images/banner_dashboard.jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ url('akip/evaluasi/sakip') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Evaluasi Akip</div>
                            <div class="text-slate-500 mt-1">Hasil Evaluasi Sakip</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-2" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-bar-chart">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
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
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('profil') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Pengaturan</div>
                            <div class="text-slate-500 mt-1">Pengaturan Profil dan Akun</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 radius10">
                                    <button class="btn btn-dark mr-1 mb-2" style="border-radius: 15px;"> <i data-lucide="settings" class="w-10 h-10"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Logout</div>
                            <div class="text-slate-500 mt-1">Keluar Dari Sistem</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 ">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 ">
                                    <button class="btn btn-danger mr-1 mb-2 radius10" style="border-radius: 15px;"> <i data-lucide="log-out" class="w-10 h-10"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <h1 class="text-2xl font-semibold mb-6">
            Progress Pengisian Evaluasi AKIP Pemerintah Daerah Tahun <span id="tahun-display">{{ $selectedYear }}</span> TW <span id="tw-display">{{ $selectedPeriode }}</span> 
        </h1>
        <div class="flex gap-4 mb-6">
            <div class="form-group">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun:</label>
                <select id="tahun" name="tahun" class="form-control px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @for ($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">Triwulan:</label>
                <select id="periode" name="periode" class="form-control px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @foreach ([1 => 'TW 1 (Jan–Mar)', 2 => 'TW 2 (Apr–Jun)', 3 => 'TW 3 (Jul–Sep)', 4 => 'TW 4 (Okt–Des)'] as $key => $label)
                        <option value="{{ $key }}" {{ $key == $selectedPeriode ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button id="filterBtn" class="btn btn-primary px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Tim</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 flex items-center justify-center">
                            Jumlah Pemda yang Dikelola</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jumlah Pemda yang Telah Diisi
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Progress Pengisian</th>
                    </tr>
                </thead>
                <tbody id="table-pda">
                    @foreach ($tims as $tim)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $tim->nama }} ({{ $tim->keterangan }})</td>
                            <td class="px-4 py-2 text-sm text-gray-800 flex items-center justify-center">
                                {{ $tim->total_instansi }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $tim->total_instansi_filled }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 text-center">
                                @if ($tim->total_instansi > 0)
                                    {{ number_format(($tim->total_instansi_filled / $tim->total_instansi) * 100, 2) }} %
                                @else
                                    0 %
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <h1 class="text-2xl font-semibold mb-6">
            Progress Pengisian Evaluasi AKIP Kementerian/Lembaga Tahun <span id="tahun-displayK">{{ $selectedYearK }}</span> 
        </h1>
        <div class="flex gap-4 mb-6">
            <div class="form-group">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun:</label>
                <select id="tahunK" name="tahunK" class="form-control px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @for ($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ $year == $selectedYearK ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end">
                <button id="filterBtnK" class="btn btn-primary px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Tim</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 flex items-center justify-center">
                            Jumlah K/L yang Dikelola</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jumlah K/L yang Telah Diisi
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Progress Pengisian</th>
                    </tr>
                </thead>
                <tbody id="table-kl">
                    @foreach ($tims_kl as $tim)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $tim->nama }} ({{ $tim->keterangan }})</td>
                            <td class="px-4 py-2 text-sm text-gray-800 flex items-center justify-center">
                                {{ $tim->total_instansi }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $tim->total_instansi_filled }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 text-center">
                                @if ($tim->total_instansi > 0)
                                    {{ number_format(($tim->total_instansi_filled / $tim->total_instansi) * 100, 2) }} %
                                @else
                                    0 %
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
        <script>
            $(function() {
                $('#filterBtn').on('click', filterData);
                $('#filterBtnK').on('click', filterDataK);

                function filterDataK() {
                    const tahunK = $('#tahunK').val();

                    showLoadingK();

                    $.get('{{ route('akip.dashboard.filter.kl') }}', {
                            tahunK,
                        })
                        .done(response => {
                            $('#tahun-displayK').text(tahunK);
                            updateTableK(response.tims_kl);
                            hideLoading();
                        })
                }

                function filterData() {
                    const tahun = $('#tahun').val();
                    const periode = $('#periode').val();

                    showLoading();

                    $.get('{{ route('akip.dashboard.filter') }}', {
                            tahun,
                            periode
                        })
                        .done(response => {
                            $('#tahun-display').text(tahun);
                            $('#tw-display').text(periode);
                            updateTable(response.tims);
                            hideLoading();
                        })
                        .fail(xhr => {
                            alert('Error loading data. Please try again.');
                            hideLoading();
                            console.error(xhr);
                        });
                }

                function updateTable(tims) {
                    const tbody = $('#table-pda').empty();
                    if (!tims.length) {
                        tbody.append(`
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada data untuk periode yang dipilih</td>
                    </tr>
                `);
                        return;
                    }
                    tims.forEach(tim => {
                        const progress = tim.total_instansi > 0 ?
                            ((tim.total_instansi_filled / tim.total_instansi) * 100).toFixed(2) :
                            0;
                        tbody.append(`
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm text-gray-800">${tim.nama} (${tim.keterangan})</td>
                        <td class="px-4 py-2 text-sm text-gray-800 text-center">${tim.total_instansi}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">${tim.total_instansi_filled}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">${progress} %</td>
                    </tr>
                `);
                    });
                }

                function updateTableK(tims) {
                    const tbody = $('#table-kl').empty();
                    if (!tims.length) {
                        tbody.append(`
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada data untuk periode yang dipilih</td>
                    </tr>
                `);
                        return;
                    }
                    tims.forEach(tim => {
                        const progress = tim.total_instansi > 0 ?
                            ((tim.total_instansi_filled / tim.total_instansi) * 100).toFixed(2) :
                            0;
                        tbody.append(`
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm text-gray-800">${tim.nama} (${tim.keterangan})</td>
                        <td class="px-4 py-2 text-sm text-gray-800 text-center">${tim.total_instansi}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">${tim.total_instansi_filled}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">${progress} %</td>
                    </tr>
                `);
                    });
                }

                function showLoadingK() {
                    $('#table-kl').html(`
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center">
                        <div class="flex justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                        </div>
                        <p class="mt-2 text-gray-500">Loading...</p>
                    </td>
                </tr>
            `);
                }


                function showLoading() {
                    $('#table-pda').html(`
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center">
                        <div class="flex justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                        </div>
                        <p class="mt-2 text-gray-500">Loading...</p>
                    </td>
                </tr>
            `);
                }

                function hideLoading() {
                    // no-op; table will be re-rendered by updateTable
                }
            });
        </script>
    @endpush
