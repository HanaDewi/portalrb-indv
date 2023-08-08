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
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/datatables.css" />
    <!-- End plugin css for this page -->
    <link href="{{ asset('ext') }}/sweetalert2/sweetalert2.css" rel="stylesheet">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css" />
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
                @foreach (menus() as $menu)
                    @if ($menu['levels'] == 'devider')
                    <li class="menu__devider my-6"></li>
                    @elseif (in_array(auth()->user()->level, $menu['levels']))
                    @php
                    $active = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'menu--active' : '';
                    $open = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'menu--open' : '';
                    @endphp
                    <li>
                        @php
                            $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                            $subicon = isset($menu['items']) ? (request()->is($menu['url']) || request()->is($menu['url'].'/*') ? '<div class="menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i></div>' : '<div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>') : '';
                            $subopen = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'menu__sub-open' : '';
                        @endphp
                        <a href="{{ $url }}" class="menu {{ $active }} {{ $open }}">
                            <div class="menu__icon"><i data-lucide="{{ $menu['icon'] }}"></i></div>
                            <div class="menu__title">{{ $menu['title'] }} {!! $subicon !!}</div>
                        </a>
                        @isset($menu['items'])
                        <ul class="{{ $subopen }}">
                            @foreach ($menu['items'] as $item)
                            @php
                            $active = request()->is($item['url']) || request()->is($item['url'].'/*') ? 'menu--active' : '';
                            @endphp
                            <li>
                                <a href="{{ url($item['url']) }}" class="menu {{ $active }}">
                                    <div class="menu__icon"> <i data-lucide="{{ $item['icon'] }}"></i> </div>
                                    <div class="menu__title"> {{ $item['title'] }} </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endisset
                    </li>
                    @endif
                @endforeach
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();" class="side-menu">
                        <div class="menu__icon">
                            <i data-lucide="log-out"></i>
                        </div>
                        <div class="menu__title"> Logout </div>
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
                @foreach (menus() as $menu)
                    @if ($menu['levels'] == 'devider')
                    <div class="side-nav__devider my-6"></div>
                    @elseif (in_array(auth()->user()->level, $menu['levels']))
                    @php
                    $active = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'side-menu--active' : '';
                    $open = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'side-menu--open' : '';
                    @endphp
                    <li>
                        @php
                            $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                            $subicon = isset($menu['items']) ? (request()->is($menu['url']) || request()->is($menu['url'].'/*') ? '<div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i></div>' : '<div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>') : '';
                            $subopen = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'side-menu__sub-open' : '';
                        @endphp
                        <a href="{{ $url }}" class="side-menu {{ $active }} {{ $open }}">
                            <div class="side-menu__icon"><i data-lucide="{{ $menu['icon'] }}"></i></div>
                            <div class="side-menu__title">{{ $menu['title'] }} {!! $subicon !!}</div>
                        </a>
                        @isset($menu['items'])
                        <ul class="{{ $subopen }}">
                            @foreach ($menu['items'] as $item)
                            @php
                            $active = request()->is($item['url']) || request()->is($item['url'].'/*') ? 'side-menu--active' : '';
                            @endphp
                            <li>
                                <a href="{{ url($item['url']) }}" class="side-menu {{ $active }}">
                                    <div class="side-menu__icon"> <i data-lucide="{{ $item['icon'] }}"></i> </div>
                                    <div class="side-menu__title"> {{ $item['title'] }} </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endisset
                    </li>
                    @endif
                @endforeach
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="log-out"></i>
                        </div>
                        <div class="side-menu__title"> Logout </div>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="content">
            <div class="top-bar">
                <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                    <i data-loading-icon="circles" class="w-8 h-8"></i>
                    <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-extrabold" style="font-weight: 600">SISTEM INFORMASI LEMBAR KERJA </h1>
                    <h1 class="text-lg text-danger font-extrabold"> EVALUASI RB</h1>
                </nav>
                <div class="intro-x dropdown mr-auto sm:mr-6">
                    <div class="dropdown-toggle notification notification--bullet cursor-pointer" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                        <i data-lucide="bell" class="notification__icon dark:text-slate-500"></i>
                    </div>
                </div>
                <div class="intro-x dropdown w-8 h-8">
                    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                        <img alt="{{ auth()->user()->username }}" src="{{ auth()->user()->foto ? asset('storage/user/'.auth()->user()->foto) : asset('template_lkerb/dist/images/favicon.png') }}">
                    </div>
                    <div class="dropdown-menu w-56">
                        <ul class="dropdown-content bg-primary text-white">
                            <li class="p-2">
                                <div class="font-medium">{{ auth()->user()->username }}</div>
                                <div class="text-xs text-white/70 mt-0.5 dark:text-slate-200">{{ auth()->user()->nama }}</div>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                            <li>
                                <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white/[0.08]">
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout').submit();" class="dropdown-item hover:bg-white/5"> <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" id="logout">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-6 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    @yield('button')
                </div>
                <div class="intro-y col-span-6 float-right mt-2">
                    <div class="float-right">
                        <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-bold"
                            style="font-weight: 600">Selamat datang, <a class="text-lg text-danger font-bold"> {{ auth()->user()->username }}</a>
                        </h1>
                        <div class="text-slate-500 text-xs mt-0.3 float-right">{{ auth()->user()->nama }}</div>
                    </div>
                </div>
                <div class="intro-y col-span-12 lg:col-span-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('template_lkerb') }}/dist/js/app.js"></script>
    <script src="{{ asset('ext') }}/jquery/jquery.js"></script>
    {{-- Sweetalert2 --}}
    <script src="{{ asset('ext') }}/sweetalert2/sweetalert2.js"></script>
    <script src="{{asset('ext')}}/datatables/datatables.js"></script>
    @stack('js')
    <script>
        $.extend( $.fn.dataTable.defaults, {
            language: {
                sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan _MENU_ entri",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                sInfoPostFix: "",
                sSearch: "Cari:",
                sUrl: "",
                oPaginate: {
                    sFirst: "|<",
                    sPrevious: "<<",
                    sNext: ">>",
                    sLast: ">|"
                }
            }
        });
    </script>
</body>
</html>
