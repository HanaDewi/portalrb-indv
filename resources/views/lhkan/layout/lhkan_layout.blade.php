<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8">
    <link href="{{ asset('template_lkerb') }}/dist/images/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lembar Kerja Evaluasi">
    <meta name="keywords" content="Lembar Kerja Evaluasi">
    <meta name="author" content="MENPANRB">
    <title>@yield('title') - LHKAN</title>

    <!-- External CDN Stylesheets -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,100italic,300italic,400italic,500italic,500,700,700italic,900,900italic"
        type="text/css">
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"
        type="text/css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"
        integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer">

    <!-- Local Stylesheets -->
    <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css?v2" />
    <link rel="stylesheet" href="{{ asset('ext') }}/sweetalert2/sweetalert2.css">
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/datatables.css" />
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/buttons.dataTables.min.css" />

    <!-- Dynamic CSS Stack -->
    @stack('css')

</head>

<body class="py-5">
    <!-- Mobile Navigation -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="{{ url('/') }}" class="flex mr-auto relative">
                <img alt="LKE RB" class="w-24" src="{{ asset('assets') }}/images/logo-portalrb.png">
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
                @foreach (menus() as $menu)
                    @include('lhkan.components.menu-item', ['menu' => $menu, 'type' => 'mobile'])
                @endforeach
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
        <!-- Side Navigation -->
        <nav class="side-nav">
            <a href="{{ url('/') }}" class="intro-x flex items-center pl-2 pt-4">
                <img alt="LKE RB" src="{{ asset('assets') }}/images/logo-portalrb.png">
            </a>
            <div class="side-nav__devider my-6"></div>
            <ul>
                @foreach (menus() as $menu)
                    @include('lhkan.components.menu-item', ['menu' => $menu, 'type' => 'side'])
                @endforeach
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();"
                        class="side-menu">
                        <div class="side-menu__icon"><i data-lucide="log-out"></i></div>
                        <div class="side-menu__title"> Logout </div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <!-- Top Bar -->
            <div class="top-bar">
                <nav aria-label="breadcrumb" class="-intro-x mr-auto sm:flex">
                    <i data-lucide="globe" class="w-6 h-6 mr-2"></i>
                    <h1 class="mr-auto text-lg font-extrabold px-[5px]" style="font-weight: 600">
                        @yield('title')
                    </h1>
                </nav>
                <div class="intro-x dropdown ml-auto sm:mr-6 relative">
                    <h1 class="mr-auto text-lg font-bold px-[5px]" style="font-weight: 600">
                        Selamat datang, <a class="text-lg text-info font-bold">
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
                        <ul class="dropdown-content bg-primary text-black">
                            <li class="p-2">
                                <div class="font-medium">{{ auth()->user()->username }}</div>
                                <div class="text-xs text-black/70 mt-0.5 dark:text-slate-200">
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

            <!-- Page Content -->
            <div>
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Core Framework Scripts -->
    <script src="{{ asset('template_lkerb') }}/dist/js/app.js"></script>
    <script src="{{ asset('ext') }}/jquery/jquery.js"></script>

    <!-- UI Library Scripts -->
    {{-- Sweetalert2 --}}
    <script src="{{ asset('ext') }}/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('ext') }}/datatables/datatables.js"></script>

    <!-- Dynamic JS Stack -->
    @stack('js')
</body>

</html>