<!doctype html>
<html class="no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Ruang Belajar - Reformasi Birokrasi</title>
    <meta name="author" content="Ruang Belajar">
    <meta name="description" content="Ruang Belajar">
    <meta name="keywords" content="Ruang Belajar">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ URL::to('/ruangbelajar/') }}/assets/img/favicons/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Jost' rel='stylesheet'>
    <link rel="stylesheet" href="{{ URL::to('/ruangbelajar/') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::to('/ruangbelajar/') }}/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ URL::to('/ruangbelajar/') }}/assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="{{ URL::to('/ruangbelajar/') }}/assets/css/slick.min.css">
    <link rel="stylesheet" href="{{ URL::to('/ruangbelajar/') }}/assets/css/nice-select.min.css">
    <link rel="stylesheet" href="{{ URL::to('/ruangbelajar/') }}/assets/css/style.css">
    @yield('css_jquery')
</head>

<body>
    <div class="th-menu-wrapper">
        <div class="th-menu-area text-center">
            <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <a href="{{ URL::to('/ruangbelajar/') }}/"materi-page.html"><img
                        src="{{ URL::to('/ruangbelajar/') }}/assets/img/logoruangbelajar.jpg" alt="Ruang Belajar"></a>
            </div>
            <div class="th-mobile-menu">
                <ul>
                    <li class="menu-item-has-children">
                    <li><a href="{{ URL::to('/') }}">Beranda</a></li>
                    <li class="menu-item-has-children"><a href="{{ URL::to('/') }}">Pusat Pengetahuan</a>
                        <ul class="sub-menu">
                            <li><a href="#">Praktik Baik RB</a></li>
                            <li><a href="#">Pembangunan RB</a></li>
                            <li><a href="#">Hasil Penelitian RB</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Pusat Pelatihannnn</a></li>
                    <li><a href="#">F.A.Q</a></li>
                    <li><a href="#">Portal RB</a></li>
                </ul>
            </div>
        </div>
    </div>

    <header class="th-header header-layout1 onepage-nav">
        <div class="header-top">
            <div class="container">
                <div class="row justify-content-center justify-content-lg-between align-items-center gy-2">
                    <div class="col-auto d-none d-lg-block">
                        <div class="header-links">
                            <ul>
                                <li>Deputi Bidang Reformasi Birokrasi, Akuntabilitas Aparatur dan Pengawasan,
                                    KemenPANRB</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="header-links header-right">
                            <ul>
                                <li>
                                    <div class="header-social">
                                        <span class="social-title">Follow Us:</span>
                                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://www.twitter.com/"><i class="fab fa-instagram"></i></a>
                                        <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-wrapper">
            @include('ruang-belajar.layouts.menu')
        </div>
    </header>

    @yield('content')

    <div class="footertop-area">
        <div class="container">
            <img src="{{ URL::to('/ruangbelajar/') }}/assets/img/bg1.png">
        </div>
    </div>
    <footer class="footer-wrapper footer-layout1">
        <div class="footer-wrap">
            <div class="widget-area">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-md-6 col-xxl-3 col-xl-3">
                            <div class="widget footer-widget">
                                <div class="th-widget-about">
                                    <div class="about-logo">
                                        <a href="#"><img
                                                src="{{ URL::to('/ruangbelajar/') }}/assets/img/logowhite.png"
                                                alt="Ruang Belajar"></a>
                                    </div>
                                    <p class="about-text">Deputi Bidang Reformasi Birokrasi, Akuntabilitas Aparatus
                                        dan
                                        Pengawasan. Kementerian Pendayagunaan Aparatur Negara dan Reformasi
                                        Birokrasi
                                        Republik Indonesia</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-auto">
                            <div class="widget widget_nav_menu footer-widget">
                                <h3 class="widget_title">Tentang Kami</h3>
                                <div class="menu-all-pages-container">
                                    <p class="about-text"> Email: rbkunwas@menpan.go.id </p>
                                    <p class="about-text"> Telp: (+6221) 7398380-89 </p>
                                    <p class="about-text"> Jl. Jend. Sudirman Kav. 69 Jakarta Selatan - 12190
                                        Indonesia
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-auto">
                            <div class="widget widget_nav_menu footer-widget">
                                <h3 class="widget_title">Menu</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">
                                        <li><a href="{{ URL::to('/') }}">Beranda</a></li>
                                        <li><a href="#">Evaluasi RB</a></li>
                                        <li><a href="#">Data RB</a></li>
                                        <li><a href="{{ URL::to('/') }}/login"">Login</a></li>
                                        <li><a href="#">wishlist</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-auto">
                            <div class="widget widget_nav_menu footer-widget">
                                <h3 class="widget_title">Referensi</h3>
                                <div class="menu-all-pages-container">
                                    <ul class="menu">
                                        <li><a href="#">KemenpanRB</a></li>
                                        <li><a href="#">F.A.Q</a></li>
                                        <li><a href="#">Video</a></li>
                                        <li><a href="#">Dokumentasi</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright-wrap">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12">
                            <p class="copyright-text">Copyright © 2023 <a href="#">Deputi Bidang
                                    Reformasi
                                    Birokrasi, Akuntabilitas Aparatur dan Pengawasan.</a> All Rights
                                Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/slick.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/bootstrap.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/jquery.counterup.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/jquery-ui.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/isotope.pkgd.min.js"></script>
    <script src="{{ URL::to('/ruangbelajar/') }}/assets/js/main.js"></script>
</body>

@yield('css_jquery')

</html>
