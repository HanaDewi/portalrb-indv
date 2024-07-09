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

</head>

<body class="py-5">
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
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
            <div class="side-nav__devider my-6"></div>
            <ul>
                <li>
                    <a href="{{ route('rekap_pengusulan') }}" class="side-menu">
                        <div class="side-menu__icon"><i data-lucide="inbox"></i></div>
                        <div class="side-menu__title"> Rekap Pengusulan </div>
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