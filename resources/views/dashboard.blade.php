@extends('layout.rubick')
@section('title', 'Dashboard')

@section('content')
    <div class="col-span-12 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12 xl:col-span-12">
            <div class="box">
                <div class="p-5">
                    <div class="rounded-md">
                        <img width="100%" alt="menpanrb" class="rounded-md" src="{{ asset('template_lkerb') }}/dist/images/banner_dashboard.jpg">
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="rencanaaksiuser.html">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Data Rekap RB General</div>
                            <div class="text-slate-500 mt-1">Data Perencanaan Aksi RB General</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-1" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                 <i data-lucide="pie-chart" class="w-10 h-10"> </i>                           </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="tematik.html">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Data Rekap RB Tematik</div>
                            <div class="text-slate-500 mt-1">Data Perencanaan Aksi RB Tematik</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-2" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                 <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-bar-chart"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-4"/><path d="M8 18v-2"/><path d="M16 18v-6"/></svg> </span>       
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="rb-general/perencanaan">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Input Rencana Aksi</div>
                            <div class="text-slate-500 mt-1">Masukan Rencana Aksi RB 2023</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 radius10">
                                    <button class="btn btn-warning mr-1 mb-2 radius10" style="border-radius: 15px;"> <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text"><path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4"/><path d="M19 17V5a2 2 0 0 0-2-2H4"/><path d="M15 8h-5"/><path d="M15 12h-5"/></svg></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="inputrencanatematik.html">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Input Rencana Tematik</div>
                            <div class="text-slate-500 mt-1">Masukan Rencana Tematik RB General 2023</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0 radius10">
                                    <button class="btn btn-pending mr-1 mb-2" style="border-radius: 15px;"> <i data-lucide="file-text" class="w-10 h-10"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div> --}}
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="{{ url('hasil') }}">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-bold truncate">Hasil Evaluasi</div>
                            <div class="text-slate-500 mt-1">Hasil Evaluasi Reformasi Birokrasi Tahun 2023</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-2" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-bar-chart"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-4"/><path d="M8 18v-2"/><path d="M16 18v-6"/></svg> </span>       
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
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
