<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8">
    <link href="{{ asset('template_lkerb') }}/dist/images/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lembar Kerja Evaluasi">
    <meta name="keywords" content="Lembar Kerja Evaluasi">
    <meta name="author" content="MENPANRB">
    <title>@yield('title') - LKE KEMENPANRB</title>
    <link rel="stylesheet"
        href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,100italic,300italic,400italic,500italic,500,700,700italic,900,900italic'
        rel='stylesheet' type='text/css'>
    <!-- End plugin css for this page -->
    <link href="{{ asset('ext') }}/sweetalert2/sweetalert2.css" rel="stylesheet">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css" />
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/datatables.css" />
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/buttons.dataTables.min.css" />
    <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"
        integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>


</head>

<body class="py-5">
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="{{ route('dashboard_zi')}}" class="flex mr-auto">
                <img alt="LKE RB" class="w-24" src="{{ asset('template_lkerb') }}/dist/images/logo.jpg">
            </a>
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
        </div>
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
            <ul class="scrollable__content py-2">
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();"
                        class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="log-out"></i>
                        </div>
                        <div class="side-menu__title"> Logout </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="flex mt-[4.7rem] md:mt-0">
        <nav class="side-nav">
            <a href="" class="intro-x flex items-center pl-2 pt-4">
                <img alt="LKE RB" src="{{ asset('template_lkerb') }}/dist/images/logo.jpg">
            </a>
            <ul>
                <li>
                    <a href="{{ route('rekap_total') }}" class="side-menu
                    @if($title == 'Rekap Total')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="pie-chart"></i></div>
                        <div class="side-menu__title"> Rekap Total </div>
                    </a>
                </li>


                <li>
                    <a href="#" class="side-menu
                    @if(in_array($title, ['Rekap Pengusulan Instansi', 'Rekap Pengusulan Unit', 'Rekap Administrasi Unit','Rekap Sanggah Unit', 'Rekap Analisis Dokumen'  ])) 
                        class side-menu--active side-menu--open
                    @endif
                    ">
                        <!-- class side-menu--active side-menu--open-->
                        <div class="side-menu__icon"><i data-lucide="clipboard-list"></i></div>
                        <div class="side-menu__title">
                            Rekap
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="
                        @if(in_array($title, ['Rekap Pengusulan Instansi', 'Rekap Pengusulan Unit', 'Rekap Administrasi Unit','Rekap Sanggah Unit', 'Rekap Analisis Dokumen'  ])) 
                            side-menu__sub-open
                        @else
                            side-menu__sub-close
                        @endif
                        ">
                        <li>
                            <a href="{{ route('rekap_pengusulan') }}" class="side-menu 
                            @if($title == 'Rekap Pengusulan Instansi')
                                side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Pengusulan Instansi
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekap_unit') }}" class="side-menu 
                            @if($title == 'Rekap Pengusulan Unit')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Pengusulan Unit
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekap_administrasi') }}" class="side-menu 
                            @if($title == 'Rekap Administrasi Unit')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Seleksi Administrasi
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekap_sanggah') }}" class="side-menu 
                            @if($title == 'Rekap Sanggah Unit')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Sanggah
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekap_dokumen') }}" class="side-menu 
                            @if($title == 'Rekap Analisis Dokumen')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Analisis Dokumen
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekap_wawancara') }}" class="side-menu 
                            @if($title == 'Rekap Wawancara')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Wawancara
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekap_verlap') }}" class="side-menu 
                            @if($title == 'Rekap Verlap')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide=""></i></div>
                                <div class="side-menu__title">
                                    Rekap Verlap
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('seleksi_administrasi') }}" class="side-menu
                    @if($title == 'Seleksi Administrasi')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="clipboard"></i></div>
                        <div class="side-menu__title"> Seleksi Administrasi </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sanggah') }}" class="side-menu
                    @if($title == 'Sanggah')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="check-circle"></i></div>
                        <div class="side-menu__title"> Proses Sanggah </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seleksi_dokumen') }}" class="side-menu
                    @if($title == 'Analisis Dokumen')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="file-text"></i></div>
                        <div class="side-menu__title"> Analisis Dokumen</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seleksi_wawancara') }}" class="side-menu
                    @if($title == 'Wawancara')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="activity"></i></div>
                        <div class="side-menu__title"> Wawancara</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('verifikasi_lapangan') }}" class="side-menu
                    @if($title == 'Verifikasi Lapangan')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="globe"></i></div>
                        <div class="side-menu__title"> Verifikasi Lapangan</div>
                    </a>
                </li>
                <!--<li>
                    <a href=" route('seleksi_warlap') " class="side-menu
                    @if($title == 'Wawancara dan Verifikasi Lapangan')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="clipboard"></i></div>
                        <div class="side-menu__title"> Wawancara dan Verifikasi Lapangan</div>
                    </a>
                </li>
                -->
                <li>
                    <a href="{{ route('panel') }}" class="side-menu
                    @if($title == 'Panel')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="users"></i></div>
                        <div class="side-menu__title"> Panel </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('final') }}" class="side-menu
                    @if($title == 'Final')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="target"></i></div>
                        <div class="side-menu__title"> Final </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('template_lke_evaluator') }}" class="side-menu
                    @if($title == 'Template LKE Evaluator')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="bookmark"></i></div>
                        <div class="side-menu__title">Template LKE </div>
                    </a>
                </li>
                @if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060,10209]) )
                <li>
                    <a href="{{ route('lke_evaluator') }}" class="side-menu
                    @if($title == 'LKE Evaluator')
                                side-menu--active 
                    @endif
                    ">
                        <div class="side-menu__icon"><i data-lucide="bookmark"></i></div>
                        <div class="side-menu__title">Generate LKE</div>
                    </a>
                </li>
                @endif
                @if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060, 10048, 10059, 10046, 10053,
                10056, 10052]) )
                <li>
                    <a href="#" class="side-menu
                    @if(in_array($title, ['Kelola Tim', 'Kelola Anggota Tim' ])) 
                        class side-menu--active side-menu--open
                    @endif
                    ">
                        <!-- class side-menu--active side-menu--open-->
                        <div class="side-menu__icon"><i data-lucide="clipboard-list"></i></div>
                        <div class="side-menu__title">
                            Kelola Tim
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="
                        @if(in_array($title, ['Kelola Tim', 'Kelola Anggota Tim', 'Kelola Unit Tim' ])) 
                            side-menu__sub-open
                        @else
                            side-menu__sub-close
                        @endif
                        ">
                        <li>
                            <a href="{{route('kelola_tim_zi')}}" class="side-menu 
                            @if($title == 'Kelola Tim')
                                side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide="user"></i></div>
                                <div class="side-menu__title">
                                    Tim
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('kelola_anggota_tim_zi')}}" class="side-menu 
                            @if($title == 'Kelola Anggota Tim')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide="user-plus"></i></div>
                                <div class="side-menu__title">
                                    Anggota Tim
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('kelola_unit_tim_zi')}}" class="side-menu 
                            @if($title == 'Kelola Unit Tim')
                            side-menu--active 
                            @endif
                            ">
                                <div class="side-menu__icon"><i data-lucide="user-plus"></i></div>
                                <div class="side-menu__title">
                                    Unit Tim
                                    <div class="side-menu__sub-icon "> </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li>
                    <a href="https://docs.google.com/spreadsheets/d/1ns2C87_sw2uXyIqKqutjAZRiLdFN32S7kGPhTjkRhPk/edit?usp=sharing"
                        target="_blank" class="side-menu">
                        <div class="side-menu__icon"><i data-lucide="database"></i></div>
                        <div class="side-menu__title"> ZI 2014-2023 </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();"
                        class="side-menu">
                        <div class="side-menu__icon"><i data-lucide="log-out"></i></div>
                        <div class="side-menu__title"> Logout </div>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="content">
            <div class="top-bar">
                <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                    <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        icon-name="globe" data-lucide="globe" class="lucide lucide-globe block mx-auto">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z">
                        </path>
                    </svg>
                    <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-extrabold"
                        style="font-weight: 600">Evaluasi </h1>
                    <h1 class="text-lg text-danger font-extrabold"> Zona Integritas</h1>
                </nav>
                <div class="intro-x dropdown mr-auto sm:mr-6">
                    <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-bold"
                        style="font-weight: 600">Selamat datang, <a class="text-lg text-danger font-bold">
                            {{ auth()->user()->username }}</a>
                    </h1>
                    <div class="text-slate-500 text-xs mt-0.3 float-right">{{ auth()->user()->nama }}</div>
                </div>
                <div class="intro-x dropdown w-8 h-8">
                    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in"
                        role="button" aria-expanded="false" data-tw-toggle="dropdown">
                        <img alt="{{ auth()->user()->username }}"
                            src="{{ auth()->user()->foto ? asset('storage/user/' . auth()->user()->foto) : asset('template_lkerb/dist/images/favicon.png') }}">
                    </div>
                    <div class="dropdown-menu w-56">
                        <ul class="dropdown-content bg-primary text-white">
                            <li class="p-2">
                                <div class="font-medium">{{ auth()->user()->username }}</div>
                                <div class="text-xs text-white/70 mt-0.5 dark:text-slate-200">
                                    {{ auth()->user()->nama }} - {{ auth()->user()->level }}</div>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                            <li>
                                <a href="{{ route('profil') }}" class="dropdown-item hover:bg-white/5"> <i
                                        data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();"
                                    class="dropdown-item hover:bg-white/5">
                                    <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout
                                </a>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" id="logout">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-span-12 grid grid-cols-12 gap-6">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('template_lkerb') }}/dist/js/app.js"></script>
    <script src="{{ asset('ext') }}/jquery/jquery.js"></script>
    {{-- Sweetalert2 --}}
    <script src="{{ asset('ext') }}/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('ext') }}/datatables/datatables.js"></script>
    @stack('js')
</body>

</html>