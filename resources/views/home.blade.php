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
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    <style>
        #owl-demo .item img {
            display: block;
            width: 100%;
            height: auto;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#owl-demo").owlCarousel({
                pagination: false,
                autoPlay: 5000,
                singleItem: true
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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
                                <a href="#">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
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
                                <a href="{{ url('/') }}">
                                    <img src="{{ URL::to('/') }}/assets/images/logoportalreformasibirokrasinasional.png"
                                        alt="Logo">
                                </a>
                            </div>
                        </div>
                        <div class="nav-outer ms-lg-auto clearfix">
                            <nav class="main-menu navbar-expand-lg">
                                <div class="navbar-header py-10">
                                    <div class="mobile-logo">
                                        <a href="{{ url('/') }}">
                                            <img src="{{ URL::to('/') }}/assets/images/logoportalreformasibirokrasinasional.png"
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
                                            <a href="#">Beranda</a>
                                        </li>
                                        <li>
                                            <a href="#">Evaluasi</a>
                                        </li>
                                      
                                        <li>
                                            <a href="#">Ruang Belajar</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('login') }}">
                                                <span class="xbtn">
                                                    <i class="fas fa-arrow-right"></i> Login </span>
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
        <div class="demo">
            <div id="owl-demo" class="owl-carousel">
                <div class="item">
                    <section class="hero-area bgs-cover pt-70 pb-15 rpt-130"
                        style="background-image: url({{ URL::to('/') }}/assets/images/bgslide1.jpg)">
                        <div class="container container-1000">
                            <div class="row gap-80 align-items-center">
                                <div class="col-lg-7 order-lg-2">
                                    <img class="one wow fadeInRight delay-0-2s"
                                        src="{{ URL::to('/') }}/assets/images/slide1.png" alt="Hero">
                                </div>
                                <div class="col-lg-5">
                                    <div class="hero-images">
                                        <img width="50%" class="one wow fadeInRight delay-0-2s"
                                            src="{{ URL::to('/') }}/assets/images/presiden2.png" alt="Hero">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="item">
                    <section class="hero-area bgs-cover pt-70 pb-15 rpt-130"
                        style="background-image: url({{ URL::to('/') }}/assets/images/bgslide2.jpg)">
                        <div class="container container-1000">
                            <div class="row gap-80 align-items-center">
                                <div class="col-lg-7">
                                    <img class="one wow fadeInRight delay-0-2s"
                                        src="{{ URL::to('/') }}/assets/images/slide2.png
" alt="Hero">
                                </div>
                                <div class="col-lg-5">
                                    <div class="hero-images">
                                        <img width="50%" class="one wow fadeInRight delay-0-2s"
                                            src="{{ URL::to('/') }}/assets/images/menteri.png" alt="Hero">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="item">
                    <section class="hero-area bgs-cover pt-70 pb-15 rpt-130"
                        style="background-image: url({{ URL::to('/') }}/assets/images/bgslide3.jpg)">
                        <div class="container container-1000">
                            <div class="row gap-80 align-items-center">
                                <div class="col-lg-7 order-lg-2">
                                    <img class="one wow fadeInRight delay-0-2s"
                                        src="{{ URL::to('/') }}/assets/images/slide3.png" alt="Hero">
                                </div>
                                <div class="col-lg-5">
                                    <div class="hero-images">
                                        <img width="50%" class="one wow fadeInRight delay-0-2s"
                                            src="{{ URL::to('/') }}/assets/images/presiden.png"
                                            alt="Hero">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="item">
                    <section class="hero-area bgs-cover pt-70 pb-15 rpt-130"
                        style="background-image: url({{ URL::to('/') }}/assets/images/bgslide4.jpg)">
                        <div class="container container-1000">
                            <div class="row gap-80 align-items-center">
                                <div class="col-lg-12 ">
                                    <img class="one wow fadeInRight delay-0-2s"
                                        src="{{ URL::to('/') }}/assets/images/roadmap.png" alt="Hero">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <section class="donate-area rel z-1">
            <div class="container">
                <div class="row no-gap">
                    <img src="{{ URL::to('/') }}/assets/images/bg1.png">
                </div>
            </div>
        </section>
        <section class="features-area pt-50 pb-85 rel z-1"
            style="background-image: url({{ URL::to('/') }}/assets/images/bg2.jpg); background-size: cover;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="section-title text-center pb-35 ">
                        <h2>Portal Reformasi Birokrasi Nasional</h2>
                        <span class="line"></span>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-item">
                            <div class="content">
                                <div class="icon">
                                    <img src="{{ URL::to('/') }}/assets/images/icon1.png" alt="Icon">
                                </div>
                                <h5 class="redt">
                                    <a href="#">Dashboard</a>
                                </h5>
                                <p>Dashboard Perkembangan Reformasi Birokrasi Nasional</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-item">
                            <div class="content">
                                <div class="icon">
                                    <img src="{{ URL::to('/') }}/assets/images/icon2.png" alt="Icon">
                                </div>
                                <h5 class="redt">
                                    <a href="#">Sistem Informasi Evaluasi</a>
                                </h5>
                                <p>Sistem Informasi Evaluasi Pelaksanaan Reformasi Birokrasi Nasional</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-item">
                            <div class="content">
                                <div class="icon">
                                    <img src="{{ URL::to('/') }}/assets/images/icon3.png" alt="Icon">
                                </div>
                                <h5 class="redt">
                                    <a href="#">Ruang Belajar</a>
                                </h5>
                                <p>Knowledge Management System Reformasi Birokrasi Nasional</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features Area end -->
        <!-- Meter Area start -->
        <section class="meter-area py-80 rel z-1"
            style="background-image: url({{ URL::to('/') }}/assets/images/bg3.jpg); background-size: cover;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="section-title text-center pb-35 ">
                        <h2 class="text-white">Praktik Baik Birokrasi Nasional</h2>
                        <span class="line"></span>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <img class="fulw" src="{{ URL::to('/') }}/assets/images/post1.jpg">
                        <div class="feature-item">
                            <div class="content">
                                <h5 class="redt">
                                    <a href="#">Pemangkasan Proses Bisnis Layanan Kepegawaian</a>
                                </h5>
                                <p>Melakukan percepatan Pemutakhiran Data Mandiri (PDM) untuk peningkatan layanan
                                    manajemen kepegawaian ASN</p>
                                <a href="#">
                                    <span class="btnpan"> Selengkapnya <i class="fas fa-chevron-right ylw"></i>
                                    </span>
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <img class="fulw" src="{{ URL::to('/') }}/assets/images/post2.jpg">
                        <div class="feature-item">
                            <div class="content">
                                <h5 class="redt">
                                    <a href="#">Transformasi Profesionalisme ASN Berbasis Digital</a>
                                </h5>
                                <p>Transformasi manajemen aparatur sipil negara (ASN) perlu dilakukan secara menyeluruh
                                    atau holistik.</p>
                                <a href="#">
                                    <span class="btnpan"> Selengkapnya <i class="fas fa-chevron-right ylw"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <img class="fulw" src="{{ URL::to('/') }}/assets/images/post3.jpg">
                        <div class="feature-item">
                            <div class="content">
                                <h5 class="redt">
                                    <a href="#">Penerapan Reformasi Birokrasi Tematik</a>
                                </h5>
                                <p>Instansi pemerintah dapat lebih fokus untuk menyelesaikan permasalahan tata kelola
                                    yang terkait harapan kinerja</p>
                                <a href="#">
                                    <span class="btnpan"> Selengkapnya <i class="fas fa-chevron-right ylw"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="priorities-area pt-70 pb-75 rel z-1"
            style="background-image: url({{ URL::to('/') }}/assets/images/bg4.jpg); background-size: cover;">
            <div class="container">
                <div class="section-title text-center pb-35 ">
                    <h2> Data dan Statistik Birokrasi Nasional</h2>
                    <span class="line"></span>
                </div>


                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-6 col-sm-6">
                        <h3 class="redt"> Inovasi Pelayanan Publik </h3>
                        <h5> di Lingkungan Kementrian/Lembaga, Pemerintah Daerah, BUMN, dan BUMD Tahun 2023 </h5>

                        <h5>
                            <i class="fas fa-genderless redw"></i> Kelompok Umum
                        </h5>
                        <h5>
                            <i class="fas fa-genderless redq"></i> Kelompok Replikasi
                        </h5>
                        <h5>
                            <i class="fas fa-genderless yl"></i> Kelompok Khusus
                        </h5>
                        <h5>
                            <i class="fas fa-genderless greya"></i> Lainnya
                        </h5>
                        <br><br>

                        <a href="#">
                            <span class="btnpan"> Selengkapnya <i class="fas fa-chevron-right ylw"></i>
                            </span>
                        </a>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-sm-5">
                        <img src="{{ URL::to('/') }}/assets/images/grafik.png">
                    </div>
                </div>
            </div>
        </section>
        <section class="pt-20"></section>
        <section class="donate-area rel z-1">
            <div class="container">
                <div class="row no-gap">
                    <img src="assets/images/gambar/bg1.png">
                </div>
            </div>
        </section>
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

</html>
