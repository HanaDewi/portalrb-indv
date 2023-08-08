@extends('layout.rubick')
@section('title', 'Dashboard')

@section('content')
    <div class="col-span-12 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 md:col-span-12 lg:col-span-12 xl:col-span-12">
            <div class="box">
                <div class="p-5">
                    <div class="rounded-md">
                        <img width="100%" alt="menpanrb" class="rounded-md" src="{{ asset('template_lkerb') }}/dist/images/bannerpanrb.jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="rencanaaksiuser.html">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-medium truncate">Data Rencana Aksi</div>
                            <div class="text-slate-500 mt-1">13 Data Rencana Aksi</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-1" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                40%
                            </div>
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
                            <div class="text-lg font-medium truncate">Data Rencana Tematik</div>
                            <div class="text-slate-500 mt-1">10 Data Rencana Tematik</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                                <canvas id="report-donut-chart-2" width="90" height="90" style="display: block; box-sizing: border-box; height: 90px; width: 90px;"></canvas>
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                20%
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="inputrencanaaksi.html">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-medium truncate">Input Rencana Aksi</div>
                            <div class="text-slate-500 mt-1">Masukan Rencana Aksi RB 2023</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                    <button class="btn btn-warning mr-1 mb-2"> <i data-lucide="edit" class="w-10 h-10"></i></button>
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
                            <div class="text-lg font-medium truncate">Input Rencana Tematik</div>
                            <div class="text-slate-500 mt-1">Masukan Rencana Tematik RB 2023</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                    <button class="btn btn-pending mr-1 mb-2"> <i data-lucide="file-text" class="w-10 h-10"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <div class="flex items-center">
                    <div class="w-2/4 flex-none">
                        <div class="text-lg font-medium truncate">Pengaturan</div>
                        <div class="text-slate-500 mt-1">Pengaturan Profil dan Akun</div>
                    </div>
                    <div class="flex-none ml-auto relative">
                        <div class="w-[90px] h-[90px]">
                        </div>
                        <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <button class="btn btn-dark mr-1 mb-2"> <i data-lucide="settings" class="w-10 h-10"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 intro-y">
            <div class="box p-5 zoom-in">
                <a href="login.html">
                    <div class="flex items-center">
                        <div class="w-2/4 flex-none">
                            <div class="text-lg font-medium truncate">Logout</div>
                            <div class="text-slate-500 mt-1">Keluar Dari Sistem</div>
                        </div>
                        <div class="flex-none ml-auto relative">
                            <div class="w-[90px] h-[90px]">
                            </div>
                            <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                <div class="font-medium absolute w-full h-full flex items-center justify-center top-0 left-0">
                                    <button class="btn btn-danger mr-1 mb-2"> <i data-lucide="log-out" class="w-10 h-10"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
