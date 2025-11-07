@extends('home-template.template')

@section('cssJsHere')
<style>
    #owl-demo .item img,
    .slick-slider .item img {
        display: block;
        width: 100%;
        height: auto;
    }

    @media only screen and (max-width: 991px) {
        .demo {
            display: none;
        }

        #judul-bawah {
            display: none;
        }
    }
</style>
<script>
    $(document).ready(function() {
        // Ganti owlCarousel dengan Slick
        $("#owl-demo").slick({
            autoplay: true,
            autoplaySpeed: 2000,
            dots: false,
            arrows: false,
            fade: true,
            speed: 500,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1
        });
    });
</script>
@endsection

@section('content')
<div class="demo">
    <div id="owl-demo" class="owl-carousel">
        <!-- Slide 1: Portal RB -->
        <div class="item">
            <section class="hero-area bgs-cover pt-30 pb-15 rpt-130"
                style="background-image: url({{ URL::to('/') }}/assets/images/bgportal.jpg)">
                <div class="container container-1000">
                    <div class="row gap-80 align-items-center">
                        <div class="col-lg-12">
                            <img class="one wow fadeInRight delay-0-2s"
                                src="{{ URL::to('/') }}/assets/images/portalrbslider.png" alt="Portal RB">
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Slide 2: Presiden -->
        <div class="item">
            <section class="hero-area bgs-cover pt-30 pb-15 rpt-130"
                style="background-image: url({{ URL::to('/') }}/assets/images/bgslide1.jpg)">
                <div class="container container-1000">
                    <div class="row gap-80 align-items-center">
                        <div class="col-lg-12">
                            <img class="one wow fadeInRight delay-0-2s" src="{{ URL::to('/') }}/assets/images/banner_ZI_1.png" alt="zi1" style="width: 1000px; height: 500px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Slide 3: Menteri -->
        <div class="item">
            <section class="hero-area bgs-cover pt-30 pb-15 rpt-130"
                style="background-image: url({{ URL::to('/') }}/assets/images/bgslide2.jpg)">
                <div class="container container-1000">
                    <div class="row gap-80 align-items-center">
                        <div class="col-lg-12">
                            <img class="one wow fadeInRight delay-0-2s" src="{{ URL::to('/') }}/assets/images/banner_ZI_2.png" alt="zi2" style="width: 1000px; height: 440px; object-fit: contain;">
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
                <h2 id="judul-bawah">Portal Reformasi Birokrasi Nasional</h2>
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
                <img class="fulw" src="{{ URL::to('/') }}/assets/images/banner1.jpg">
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
                <img class="fulw" src="{{ URL::to('/') }}/assets/images/banner2.jpg">
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
                <img class="fulw" src="{{ URL::to('/') }}/assets/images/banner3.jpg">
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
@endsection