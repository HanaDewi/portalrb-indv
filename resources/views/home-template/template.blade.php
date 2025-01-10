<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal Reformasi Birokrasi Nasional PANRB</title>
    <link rel="shortcut icon" href="{{ URL::to('/') }}/assets/images/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/datatables.css" />
    <link rel="stylesheet" href="{{ asset('ext') }}/datatables/buttons.dataTables.min.css" />
    <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('cssJsHere')


</head>

<body>
    <div class="page-wrapper">
        <header class="main-header header-one bgc-yellow">
            <div class="header-top-wrap rel z-5 pt-15 pb-10 rpt-10 rpb-5">
                <div class="container">
                    <div class="header-top">
                        <ul>
                            <li>Deputi Bidang Reformasi Birokrasi, Akuntabilitas Aparatur dan Pengawasan PANRB </li>
                        </ul>
                        <ul>
                            <li>
                                <a href="https://www.instagram.com/rbkunwas/" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/rbkunwas" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/kempanrb/" target="_blank">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/channel/UCbgLGOpvsj8Si0Bs6getx1g" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-upper">
                <div class="container container-1335 clearfix">
                    <div class="header-inner rel d-flex">
                        <div class="logo-outer align-self-center">
                            <div class="logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('/assets/images/logoportalreformasibirokrasinasional.png') }}"
                                        alt="Logo">
                                </a>
                            </div>
                        </div>
                        <div class="nav-outer ms-lg-auto clearfix">
                            <nav class="main-menu navbar-expand-lg">
                                <div class="navbar-header py-10">
                                    <div class="mobile-logo">
                                        <a href="{{ route('home') }}">
                                            <img src="{{ asset('/assets/images/logoportalreformasibirokrasinasional.png')}}"
                                                alt="Logo">
                                        </a>
                                    </div>
                                    <button type="button" class="navbar-toggle" data-bs-toggle="collapse"
                                        data-bs-target=".navbar-collapse">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                                <div class="navbar-collapse collapse clearfix">
                                    <ul class="navigation clearfix">
                                        <li>
                                            <a href="{{ route('home') }}">Beranda</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard') }}">Evaluasi</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('ruang-belajar.home') }}">Ruang Belajar</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home_zi') }}">Zona Integritas</a>
                                        </li>
                                        <li>
                                            @if(Auth::User())
                                            <a href="{{ url('logout') }}"
                                                onclick="event.preventDefault(); $('#logout').submit();">
                                                <form method="POST" action="{{ route('logout') }}" id="logout">
                                                    @csrf
                                                </form>
                                                @else
                                                <a href="{{ url('login') }}">
                                                    @endif
                                                    <span class="xbtn">
                                                        <i class="fas fa-arrow-right"></i> {{
                                                        (Auth::User())?"Logout":"Login" }} </span>
                                                </a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @yield('content')
        <footer class="main-footer pt-20  bgs-cover footer-white" style="background: #151516;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-sm-6">
                        <div class="footer-widget widget_about wow fadeInUp delay-0-3s">
                            <div class="footer-logo mb-25">
                                <a href="{{ url('/') }}">
                                    <img src="{{ URL::to('/') }}/assets/images/rbkunwas.jpg" alt="Logo">
                                </a>
                            </div>
                            <p>
                                <i class="fas fa-building redw"></i> Deputi Bidang Reformasi Birokrasi, Akuntabilitas
                                Aparatur dan Pengawasan
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="footer-widget">
                            <h4 class="footer-title">Tentang Kami</h4>
                            <ul>
                                <li>
                                    <i class="fas fa-envelope redw"></i> E-mail: rbkunwas@gmail.com
                                </li>
                                <li>
                                    <i class="fas fa-phone redw"></i> Telp: (+6221) 7398381 - 89
                                </li>
                                <li>
                                    <i class="fas fa-map-marker-alt redw"></i> Jl. Jend. Sudirman Kav. 69 Jakarta
                                    Selatan - 12190 Indonesia
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="footer-widget widget_menu wow fadeInUp delay-0-5s">
                            <h4 class="footer-title">Tautan Portal</h4>
                            <ul>
                                <li>
                                    <a href="#">Beranda</a>
                                </li>
                                <li>
                                    <a href="#">Evaluasi</a>
                                </li>
                                <li>
                                    <a href="#">Survey</a>
                                </li>
                                <li>
                                    <a href="#">Ruang Belajar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="footer-bottom text-center py-30" style="background: #151516;">
            <div class="container">
                <div class="copyright-text">
                    <p>© Copyright 2023. Deputi Bidang Reformasi Birokrasi, Akuntabilitas Aparatur dan Pengawasan PANRB.
                        All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>


</body>
@yield('jsHere')

</html>