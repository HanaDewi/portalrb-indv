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
            <a href="{{ url('/') }}" class="flex mr-auto">
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
                @if (in_array('devider', $menu['levels']))
                <li class="menu__devider my-6"></li>
                @elseif (in_array(auth()->user()->level, $menu['levels']))
                @php
                $active = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'menu--active' : '';
                $open = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'menu--open' : '';
                @endphp
                <li>
                    @php
                    $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                    $subicon = isset($menu['items']) ? (request()->is($menu['url']) || request()->is($menu['url'] .
                    '/*') ? '<div class="menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i></div>'
                    : '<div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>') : '';
                    $subopen = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'menu__sub-open' :
                    '';
                    @endphp
                    <a href="{{ $url }}" class="menu {{ $active }} {{ $open }}">
                        <div class="menu__icon"><i data-lucide="{{ $menu['icon'] }}"></i></div>
                        <div class="menu__title">{{ $menu['title'] }} {!! $subicon !!}</div>
                    </a>
                    @isset($menu['items'])
                    <ul class="{{ $subopen }}">
                        @foreach ($menu['items'] as $item)
                        @php
                        $active = request()->is($item['url']) || request()->is($item['url'] . '/*') ? 'menu--active' :
                        '';
                        $open = request()->is($item['url']) || request()->is($item['url'] . '/*') ? 'menu__sub-open' :
                        '';
                        $itemUrl = isset($item['items']) && count($item['items']) > 0 ? 'javascript:;' :
                        url($item['url']);
                        @endphp
                        @if (in_array(auth()->user()->level, $item['levels']))
                        <li>
                            <a href="{{ $itemUrl }}" class="menu {{ $active }}">
                                <div class="menu__icon"> <i data-lucide="{{ $item['icon'] }}"></i> </div>
                                <div class="menu__title"> {{ $item['title'] }} </div>
                            </a>
                            @isset($item['items'])
                            <ul class="{{ $open }}">
                                @foreach ($item['items'] as $subitem)
                                @php
                                $active = request()->is($subitem['url']) || request()->is($subitem['url'] . '/*') ?
                                'menu--active' : '';
                                @endphp
                                @if (in_array(auth()->user()->level, $subitem['levels']))
                                <li>
                                    <a href="{{ url($subitem['url']) }}" class="menu {{ $active }}">
                                        <div class="menu__icon"> <i data-lucide="{{ $subitem['icon'] }}"></i> </div>
                                        <div class="menu__title"> {{ $subitem['title'] }} </div>
                                    </a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                            @endisset
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    @endisset
                </li>
                @endif
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
        <nav class="side-nav">
            <a href="{{ url('/') }}" class="intro-x flex items-center pl-2 pt-4">
                <img alt="LKE RB" src="{{ asset('assets') }}/images/logo-portalrb.png">
            </a>
            <div class="side-nav__devider my-6"></div>
            <ul>
                @foreach (menus() as $menu)
                @if (in_array('devider', $menu['levels']))
                <div class="side-nav__devider my-6"></div>
                @elseif (in_array(auth()->user()->level, $menu['levels']))
                @php
                $active = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'side-menu--active' : '';
                $open = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'side-menu--open' : '';
                @endphp
                <li>
                    @php
                    $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                    $subicon = isset($menu['items']) ? (request()->is($menu['url']) || request()->is($menu['url'] .
                    '/*') ? '<div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i>
                    </div>' : '<div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>') : '';
                    $subopen = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'side-menu__sub-open'
                    : '';
                    @endphp
                    <a href="{{ $url }}" class="side-menu {{ $active }} {{ $open }}">
                        <div class="side-menu__icon"><i data-lucide="{{ $menu['icon'] }}"></i></div>
                        <div class="side-menu__title">{{ $menu['title'] }} {!! $subicon !!}</div>
                    </a>
                    @isset($menu['items'])
                    <ul class="{{ $subopen }}">
                        @foreach ($menu['items'] as $item)
                        @php
                        $active = request()->is($item['url']) || request()->is($item['url'] . '/*') ?
                        'side-menu--active' : '';
                        $open = request()->is($item['url']) || request()->is($item['url'] . '/*') ?
                        'side-menu__sub-open' : '';
                        $itemUrl = isset($item['items']) && count($item['items']) > 0 ? 'javascript:;' :
                        url($item['url']);
                        @endphp
                        @if (in_array(auth()->user()->level, $item['levels']))
                        <li>
                            <a href="{{ $itemUrl }}" class="side-menu {{ $active }}">
                                <div class="side-menu__icon"><i data-lucide="{{ $item['icon'] }}"></i></div>
                                <div class="side-menu__title">{{ $item['title'] }} {!! isset($item['items']) ? '<div
                                        class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>' : ''
                                    !!}</div>
                            </a>
                            @isset($item['items'])
                            <ul class="{{ $open }}">
                                @foreach ($item['items'] as $subitem)
                                @php
                                $active = request()->is($subitem['url']) || request()->is($subitem['url'] . '/*') ?
                                'side-menu--active' : '';
                                @endphp
                                @if (in_array(auth()->user()->level, $subitem['levels']))
                                <li>
                                    <a href="{{ url($subitem['url']) }}" class="side-menu {{ $active }}">
                                        <div class="side-menu__icon"><i data-lucide="{{ $subitem['icon'] }}"></i></div>
                                        <div class="side-menu__title">{{ $subitem['title'] }}</div>
                                    </a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                            @endisset
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    @endisset
                </li>
                @endif
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
                        style="font-weight: 600">SISTEM INFORMASI LEMBAR KERJA </h1>
                    <h1 class="text-lg text-primary font-extrabold"> EVALUASI RB</h1>
                </nav>
                <div class="intro-x dropdown mr-auto sm:mr-6">
                    <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-bold"
                        style="font-weight: 600">Selamat datang, <a class="text-lg text-primary font-bold">
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

            <div>
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