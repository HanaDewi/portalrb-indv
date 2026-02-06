<!DOCTYPE html>
<html lang="en">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('midone') }}/dist/images/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lembar Kerja Evaluasi">
    <meta name="keywords" content="Lembar Kerja Evaluasi">
    <meta name="author" content="MENPANRB">
    <title>@yield('title') - PORTALRB</title>
    <!-- BEGIN: CSS Assets-->
    <link href="{{ asset('ext') }}/sweetalert2/sweetalert2.css" rel="stylesheet">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('midone') }}/dist/css/app.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"
        integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- END: CSS Assets-->
    <!-----------------------------------------------------------
    -- animate.min.css by Daniel Eden (https://animate.style)
    -- is required for the animation of notifications and slide out panels
    -- you can ignore this step if you already have this file in your project
    --------------------------------------------------------------------------->
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<!-- END: Head -->

@php
    $menus = isset($akip) ? menus('akip') : menus();
@endphp

<body class="app">
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="{{ url('/') }}" class="flex mr-auto">
                <img alt="LKE RB" class="w-24" src="{{ asset('assets') }}/images/logo-portalrb.png">
            </a>
            <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
        </div>
        <ul class="border-t border-theme-24 py-5 hidden">
            @foreach ($menus as $menu)
                @if (in_array('devider', $menu['levels']))
                    <li class="menu__devider my-6"></li>
                @elseif (in_array(auth()->user()->level, $menu['levels']))
                    @php
                        $active =
                            request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'menu--active' : '';
                        $open = request()->is($menu['url']) || request()->is($menu['url'] . '/*') ? 'menu--open' : '';
                    @endphp
                    <li>
                        @php
                            $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                            $subicon = isset($menu['items'])
                                ? (request()->is($menu['url']) || request()->is($menu['url'] . '/*')
                                    ? '<div class="menu__sub-icon transform rotate-180"> <i data-feather="chevron-down"></i></div>'
                                    : '<div class="menu__sub-icon "> <i data-feather="chevron-down"></i> </div>')
                                : '';
                            $subopen =
                                request()->is($menu['url']) || request()->is($menu['url'] . '/*')
                                    ? 'menu__sub-open'
                                    : '';
                        @endphp
                        <a href="{{ $url }}" class="menu {{ $active }} {{ $open }}">
                            <div class="menu__icon"><i data-feather="{{ $menu['icon'] }}"></i></div>
                            <div class="menu__title">{{ $menu['title'] }} {!! $subicon !!}</div>
                        </a>
                        @isset($menu['items'])
                            <ul class="{{ $subopen }}">
                                @foreach ($menu['items'] as $item)
                                    @php
                                        $active =
                                            request()->is($item['url']) || request()->is($item['url'] . '/*')
                                                ? 'menu--active'
                                                : '';
                                        $open =
                                            request()->is($item['url']) || request()->is($item['url'] . '/*')
                                                ? 'menu__sub-open'
                                                : '';
                                        $itemUrl =
                                            isset($item['items']) && count($item['items']) > 0
                                                ? 'javascript:;'
                                                : url($item['url']);
                                    @endphp
                                    @if (in_array(auth()->user()->level, $item['levels']))
                                        <li>
                                            <a href="{{ $itemUrl }}" class="menu {{ $active }}">
                                                <div class="menu__icon"> <i data-feather="{{ $item['icon'] }}"></i> </div>
                                                <div class="menu__title"> {{ $item['title'] }} </div>
                                            </a>
                                            @isset($item['items'])
                                                <ul class="{{ $open }}">
                                                    @foreach ($item['items'] as $subitem)
                                                        @php
                                                            $active =
                                                                request()->is($subitem['url']) ||
                                                                request()->is($subitem['url'] . '/*')
                                                                    ? 'menu--active'
                                                                    : '';
                                                        @endphp
                                                        @if (in_array(auth()->user()->level, $subitem['levels']))
                                                            <li>
                                                                <a href="{{ url($subitem['url']) }}"
                                                                    class="menu {{ $active }}">
                                                                    <div class="menu__icon"> <i
                                                                            data-feather="{{ $subitem['icon'] }}"></i> </div>
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
                    <div class="menu__icon">
                        <i data-feather="log-out"></i>
                    </div>
                    <div class="menu__title"> Logout </div>
                </a>
            </li>
        </ul>
    </div>
    <!-- END: Mobile Menu -->
    <div class="flex">
        <!-- BEGIN: Side Menu -->
        <nav class="side-nav">
            <a href="{{ url('/') }}" class="intro-x flex items-center pl-2 pt-4">
                <img alt="LKE RB" src="{{ asset('assets') }}/images/logo-portalrb.png">
            </a>
            <div class="my-6"></div>
            <ul>
                @foreach ($menus as $menu)
                    @if (in_array('devider', $menu['levels']))
                        <div class="side-nav__devider my-6"></div>
                    @elseif (in_array(auth()->user()->level, $menu['levels']))
                        @php
                            $active =
                                request()->is($menu['url']) || request()->is($menu['url'] . '/*')
                                    ? 'side-menu--active'
                                    : '';
                            $open =
                                request()->is($menu['url']) || request()->is($menu['url'] . '/*')
                                    ? 'side-menu--open'
                                    : '';
                        @endphp
                        <li>
                            @php
                                $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                                $subicon = isset($menu['items'])
                                    ? (request()->is($menu['url']) || request()->is($menu['url'] . '/*')
                                        ? '<div class="side-menu__sub-icon transform rotate-180"> <i data-feather="chevron-down"></i></div>'
                                        : '<div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>')
                                    : '';
                                $subopen =
                                    request()->is($menu['url']) || request()->is($menu['url'] . '/*')
                                        ? 'side-menu__sub-open'
                                        : '';
                            @endphp
                            <a href="{{ $url }}" class="side-menu {{ $active }} {{ $open }}">
                                <div class="side-menu__icon"><i data-feather="{{ $menu['icon'] }}"></i></div>
                                <div class="side-menu__title">{{ $menu['title'] }} {!! $subicon !!}</div>
                            </a>
                            @isset($menu['items'])
                                <ul class="{{ $subopen }}">
                                    @foreach ($menu['items'] as $item)
                                        @php
                                            $active =
                                                request()->is($item['url']) || request()->is($item['url'] . '/*')
                                                    ? 'side-menu--active'
                                                    : '';
                                            $open =
                                                request()->is($item['url']) || request()->is($item['url'] . '/*')
                                                    ? 'side-menu__sub-open'
                                                    : '';
                                            $itemUrl =
                                                isset($item['items']) && count($item['items']) > 0
                                                    ? 'javascript:;'
                                                    : url($item['url']);
                                        @endphp
                                        @if (in_array(auth()->user()->level, $item['levels']))
                                            <li>
                                                <a href="{{ $itemUrl }}" class="side-menu {{ $active }}">
                                                    <div class="side-menu__icon"><i data-feather="{{ $item['icon'] }}"></i>
                                                    </div>
                                                    <div class="side-menu__title">{{ $item['title'] }}
                                                        {!! isset($item['items'])
                                                            ? '<div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>'
                                                            : '' !!}</div>
                                                </a>
                                                @isset($item['items'])
                                                    <ul class="{{ $open }}">
                                                        @foreach ($item['items'] as $subitem)
                                                            @php
                                                                $active =
                                                                    request()->is($subitem['url']) ||
                                                                    request()->is($subitem['url'] . '/*')
                                                                        ? 'side-menu--active'
                                                                        : '';
                                                            @endphp
                                                            @if (in_array(auth()->user()->level, $subitem['levels']))
                                                                <li>
                                                                    <a href="{{ url($subitem['url']) }}"
                                                                        class="side-menu {{ $active }}">
                                                                        <div class="side-menu__icon"><i
                                                                                data-feather="{{ $subitem['icon'] }}"></i>
                                                                        </div>
                                                                        <div class="side-menu__title">{{ $subitem['title'] }}
                                                                        </div>
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
                        <div class="side-menu__icon"><i data-feather="log-out"></i></div>
                        <div class="side-menu__title"> Logout </div>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- END: Side Menu -->
        <!-- BEGIN: Content -->
        <div class="content">
            <!-- BEGIN: Top Bar -->
            <div class="top-bar flex w-full justify-end">
                <!-- BEGIN: Breadcrumb -->
                <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                    <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="globe" data-lucide="globe"
                        class="lucide lucide-globe block mx-auto">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z">
                        </path>
                    </svg>
                    @if (isset($akip))
                        <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-extrabold"
                            style="font-weight: 600">EVALUASI </h1>
                        <h1 class="text-lg text-red-700 font-extrabold">SAKIP</h1>
                    @else
                        <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-extrabold"
                            style="font-weight: 600">SISTEM INFORMASI LEMBAR KERJA </h1>
                        <h1 class="text-lg text-danger font-extrabold"> EVALUASI RB</h1>
                    @endif
                </nav>
                <!-- END: Breadcrumb -->
                <div class="intro-x relative mr-3 sm:mr-6">
                    <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-bold"
                        style="font-weight: 600">Selamat datang, <a class="text-lg text-danger font-bold">
                            {{ auth()->user()->username }}</a>
                    </h1>
                    <div class="text-slate-500 text-xs mt-0.3 float-right">{{ auth()->user()->nama }} | {{ auth()->user()->level }}</div>
                </div>

                <!-- BladewindUI Account Menu -->
                <div class="intro-x relative mr-3 sm:mr-6">
                    <x-bladewind::dropmenu position="right">
                        <x-slot:trigger>
                            <div class="flex items-center space-x-3 cursor-pointer">
                                <div class="flex w-full">
                                    <x-bladewind::avatar
                                        image="{{ auth()->user()->foto ? asset('storage/user/' . auth()->user()->foto) : asset('template_lkerb/dist/images/favicon.png') }}"
                                        size="small"
                                        class="shadow-lg"
                                    />
                                </div>

                                <div class="hidden sm:block">
                                    <div class="font-medium text-sm">{{ auth()->user()->username }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->nama }}</div>
                                </div>

                                <x-bladewind::icon name="chevron-down" class="!h-4 !w-4 text-gray-400" />
                            </div>
                        </x-slot:trigger>

                        <!-- Header dengan info user -->
                        <x-bladewind::dropmenu.item header="true" class="bg-gray-50">
                            <div class="flex items-center space-x-3">
                                <x-bladewind::avatar
                                    image="{{ auth()->user()->foto ? asset('storage/user/' . auth()->user()->foto) : asset('template_lkerb/dist/images/favicon.png') }}"
                                    size="small"
                                />
                                <div>
                                    <div class="font-semibold text-gray-900">{{ auth()->user()->username }}</div>
                                    <div class="text-sm text-gray-500">{{ auth()->user()->nama }}</div>
                                    <div class="text-xs text-gray-400">{{ auth()->user()->level }}</div>
                                </div>
                            </div>
                        </x-bladewind::dropmenu.item>

                        <!-- Divider -->
                        {{-- <x-bladewind::dropmenu.item divider /> --}}

                        <!-- Menu Profile -->
                        <x-bladewind::dropmenu.item
                            icon="user"
                            onclick="window.location.href='{{ route('profil') }}'"
                        >
                            Profile
                        </x-bladewind::dropmenu.item>

                        <!-- Menu Settings -->
                        <x-bladewind::dropmenu.item
                            icon="cog-6-tooth"
                            onclick="window.location.href='{{ route('profil') }}'"
                        >
                            Pengaturan
                        </x-bladewind::dropmenu.item>

                        <!-- Divider -->
                        <x-bladewind::dropmenu.item divider />

                        <!-- Menu Logout -->
                        <x-bladewind::dropmenu.item
                            icon="arrow-right-on-rectangle"
                            onclick="event.preventDefault(); $('#logout').submit();"
                            class="text-red-600 hover:bg-red-50"
                        >
                            Logout
                        </x-bladewind::dropmenu.item>
                    </x-bladewind::dropmenu>

                    <!-- Authentication Form -->
                    <form method="POST" action="{{ route('logout') }}" id="logout" style="display: none;">
                        @csrf
                    </form>
                </div>
                <!-- END: Account Menu -->
            </div>
            <!-- END: Top Bar -->

            <div class="mt-4">
                @yield('content')
            </div>
        </div>
        <!-- END: Content -->
    </div>
    <!-- BEGIN: JS Assets-->
    <script src="{{ asset('ext') }}/jquery/jquery.js"></script>
    {{-- Sweetalert2 --}}
    <script src="{{ asset('ext') }}/sweetalert2/sweetalert2.js"></script>
    @stack('js_file')
    <script src="{{ asset('midone') }}/dist/js/app.js"></script>
    @stack('js')
    <!-- END: JS Assets-->
</body>

</html>
