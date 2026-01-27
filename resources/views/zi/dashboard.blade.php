@extends('zi.admin.rubick')
@section('title', 'Dashboard')

@section('content')
    <div class="col-span-12 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12 xl:col-span-12">
            <div class="box">
                <div class="p-5">
                    <div class="rounded-md">
                        <img width="100%" alt="menpanrb" class="rounded-md" src="{{ URL::to('/') }}/assets/images/newportal.jpg">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('rekap_pengusulan') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Rekap Pengusulan ZI</div>
                            <div class="text-slate-500 mt-1">Rekap Pengusulan ZI setiap instansi</div>
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
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('seleksi_administrasi') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Seleksi Administrasi</div>
                            <div class="text-slate-500 mt-1">Seleksi untuk mengecek kelengkapan administrasi</div>
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
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('sanggah') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Seleksi Sanggah</div>
                            <div class="text-slate-500 mt-1">Seleksi untuk memeriksa hasil sanggah dari instansi</div>
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

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('seleksi_dokumen') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Analisis Dokumen</div>
                            <div class="text-slate-500 mt-1">Analisis Dokumen </div>
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

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('seleksi_wawancara') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Wawancara </div>
                            <div class="text-slate-500 mt-1">Wawancara </div>
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

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('verifikasi_lapangan') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Verifikasi Lapangan </div>
                            <div class="text-slate-500 mt-1">Verifikasi Lapangan </div>
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

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ route('panel') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Seleksi Panel </div>
                            <div class="text-slate-500 mt-1">Seleksi Panel </div>
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

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
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
@endsection
