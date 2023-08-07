<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8">
    <link href="{{ asset('template_lkerb') }}/dist/images/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lembar Kerja Evaluasi">
    <meta name="keywords" content="Lembar Kerja Evaluasi">
    <meta name="author" content="MENPANRB">
    <title>KEMENPANRB - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
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
                            $subicon = isset($menu['items']) ? (request()->is($menu['url']) || request()->is($menu['url'].'/*') ? '<div class="menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i>' : '<div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>') : '';
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
                                <a href="menu-light-categories.html" class="menu {{ $active }}">
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
                    <li class="side-menu__devider my-6"></li>
                    @elseif (in_array(auth()->user()->level, $menu['levels']))
                    @php
                    $active = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'side-menu--active' : '';
                    $open = request()->is($menu['url']) || request()->is($menu['url'].'/*') ? 'side-menu--open' : '';
                    @endphp
                    <li>
                        @php
                            $url = isset($menu['items']) ? 'javascript:;' : url($menu['url']);
                            $subicon = isset($menu['items']) ? (request()->is($menu['url']) || request()->is($menu['url'].'/*') ? '<div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i>' : '<div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>') : '';
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
                                <a href="{{ $item['url'] }}" class="side-menu {{ $active }}">
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
            </ul>
        </nav>
        <div class="content">
            <div class="top-bar">
                <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                    <i data-loading-icon="circles" class="w-8 h-8"></i>
                    <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-extrabold"
                        style="font-weight: 600">SISTEM INFORMASI LEMBAR KERJA </h1>
                    <h1 class="text-lg text-danger font-extrabold"> EVALUASI RB</h1>
                </nav>
                <div class="intro-x dropdown mr-auto sm:mr-6">
                    <div class="dropdown-toggle notification notification--bullet cursor-pointer" role="button"
                        aria-expanded="false" data-tw-toggle="dropdown">
                        <i data-lucide="bell" class="notification__icon dark:text-slate-500"></i>
                    </div>
                </div>
                <div class="intro-x dropdown w-8 h-8">
                    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in"
                        role="button" aria-expanded="false" data-tw-toggle="dropdown">
                        <img alt="{{ auth()->user()->username }}" src="{{ auth()->user()->foto ? asset('storage/user/'.auth()->user()->foto) : asset('template_lkerb/dist/images/user1.png') }}">
                    </div>

                    <div class="dropdown-menu w-56">
                            <ul class="dropdown-content bg-primary text-white">
                                <li class="p-2">
                                    <div class="font-medium">{{ auth()->user()->username }}</div>
                                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">{{ auth()->user()->nama }}</div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Pengaturan </a>
                                </li>
                            
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                                </li>
                            </ul>
                        </div>


                </div>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-6 flex flex-wrap sm:flex-nowrap items-center mt-2">
                </div>
                <div class="intro-y col-span-6 float-right mt-2">
                    <div class="float-right">
                        <h1 style="padding-left: 5px; padding-right: 5px;" class="mr-auto text-lg font-bold"
                            style="font-weight: 600">Selamat datang, <a class="text-lg text-danger font-bold"> {{ auth()->user()->username }}</a>
                        </h1>
                        <div class="text-slate-500 text-xs mt-0.3 float-right">{{ auth()->user()->nama }}</div>
                    </div>
                </div>


<div class="dropdown-menu w-56">
                            <ul class="dropdown-content bg-primary text-white">
                                <li class="p-2">
                                    <div class="font-medium">Kevin Spacey</div>
                                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">Software Engineer</div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Add Account </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Reset Password </a>
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="help-circle" class="w-4 h-4 mr-2"></i> Help </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                                </li>
                            </ul>
                        </div>





                <div class="intro-y col-span-12 lg:col-span-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('template_lkerb') }}/dist/js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="{{ asset('template_lkerb') }}/dist/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="http://datatables.net/download/build/nightly/jquery.dataTables.js"></script>
    <script src="{{ asset('template_lkerb') }}/dist/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('template_lkerb') }}/dist/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('template_lkerb') }}/dist/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('template_lkerb') }}/dist/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
</body>

</html>
