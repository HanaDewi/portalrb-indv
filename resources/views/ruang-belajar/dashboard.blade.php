@extends('ruang-belajar.layouts.template')
@section('css_jquery')
@endsection


@section('content')
    <div class="th-hero-wrapper hero-1" id="hero">
        <div class="hero-slider-1 th-carousel" data-slide-show="1" data-md-slide-show="1" data-dots="true" data-ml-dots="true">
            @foreach ($sliders as $slider)
                @if ($slider->home_top_bar_slider_status == 1)
                    <div class="th-hero-slide">
                        <div class="container">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-12 text-lg-end text-center">
                                    <div class="hero-img1">
                                        <img src="{{ URL::to('/ruangbelajar/') }}/{{ $slider->home_top_bar_slider }}"
                                            alt="Ruang Belajar Reformasi Birokrasi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="space overflow-hidden" id="about-sec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6">
                    <div class="img-box1 mb-xl-0" style="padding-bottom: 0">
                        <div class="img1">
                            <img class="tilt-active" src="{{ URL::to('/ruangbelajar/') }}/assets/img/banner/banner1.jpg"
                                alt="About">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="title-area">
                        <span class="sub-title"><i class="fal fa-book me-2"></i> Tentang Ruang
                            Belajar</span>
                        <h2 class="sec-title">Selamat Datang di Ruang Belajar Reformasi Birokrasi</h2>
                    </div>
                    <p class="mt-n2 mb-25">Ruang Belajar MENPANRB merupakan media pembelajaran online yang membahas
                        berbagai materi tentang Pengelolaan Pendayaan Aparatur yang dapat diakses oleh seluruh pegawai
                        Kementerian Pendayaan Aparatur Negara dan Reformasi Birokrasi. Ruang Belajar berfungsi untuk
                        mendukung proses pendidikan dan pelatihan yang diselenggarakan di lingkungan Kementerian
                        Pendayaan Aparatur Negara dan Reformasi Birokrasi. </p>
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="checklist">
                                <ul>
                                    <li>Pusat Pengetahuan Kementrian PANRB</li>
                                    <li>Pusat Pelatihan Kementrian PANRB</li>
                                    <li>Referensi Acuan Praktik Baik Kementrian PANRB</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-50">
        <div class="container">
            <div class="shape-mockup category-shape-arrow d-xl-block d-none">
                <img src="{{ URL::to('/ruangbelajar/') }}/assets/img/banner/arrow.svg" alt="img">
            </div>
            <div class="category-sec-wrap">
                <div class="row">
                    <div class="col-xl-4">
                        <div class="title-area mb-25 mb-lg-0 text-xl-start text-center">
                            <span class="sub-title"><i class="fal fa-book me-2"></i> Pilih Kategori Ruang
                                Belajar</span>
                            <h2 class="sec-title">Kategori Ruang Belajar</h2>
                            <a href="materi-page.html" class="th-btn">Lihat Semua Kategori<i
                                    class="fa-regular fa-arrow-right ms-2 ylwcolor"></i></a>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row slider-shadow th-carousel category-slider" data-slide-show="4"
                            data-ml-slide-show="3" data-md-slide-show="3" data-sm-slide-show="2" data-arrows="true"
                            data-xl-arrows="true" data-ml-arrows="true" data-xs-arrows="true">
                            @foreach ($categories as $category)
                                <div class="col-md-6 col-xl-4">
                                    <div class="category-card">
                                        <div class="category-card_icon">
                                            <img src="{{ URL::to('/' . $category->icon) }}" alt="image">
                                        </div>
                                        <div class="category-card_content">
                                            <h3 class="category-card_title"><a
                                                    href="materi-page.html">{{ $category->name }}</a></h3>
                                            <p class="category-card_text"> </p>
                                            <a href="materi-page.html" class="th-btn">Lihat Semua<i
                                                    class="fa-solid fa-arrow-right ms-1 ylwcolor"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="space" data-bg-src="{{ URL::to('/ruangbelajar/') }}/assets/img/bgslider.jpg" id="course-sec">
        <div class="container">
            <div class="mb-35 text-center text-md-start">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-8">
                        <div class="title-area mb-md-0">
                            <span class="sub-title2"><i class="fal fa-book me-2"></i>Materi Terpopular</span>
                            <h2 class="sec-title2">Ruang Belajar Paling Popular</h2>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <a href="materi-page.html" class="th-btn">Lihat Semua<i
                                class="fa-solid fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="row slider-shadow th-carousel course-slider-1" data-slide-show="4" data-ml-slide-show="3"
                data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="1" data-arrows="true">

                @foreach ($popularArticles as $popularArticle)
                    <div class="col-md-6 col-lg-4">
                        <div class="course-box style2">
                            <div class="course-img">
                                <img src="{{ URL::to('/' . $popularArticle->image) }}" alt="img">
                                <span class="tag"><i class="fas fa-book"></i> PPT</span>
                                <span class="tags"></span>
                            </div>
                            <div class="course-content">

                                <h3 class="course-title"><a
                                        href="{{ route('ruang-belajar.praktek-details', $popularArticle->slug) }}">

                                         {{$popularArticle->title}} 

                                    </a></h3>
                                <div class="course-meta">
                                    <span><i class="fal fa-file"></i>
                                        @php
                                            if (isset($popularArticle->category)) {
                                                echo $popularArticle->category->name;
                                            }
                                        @endphp
                                    </span>

                                    <span><i class="fal fa-user"></i>1.223 Views</span>
                                </div>
                                <div class="course-author">
                                    <a href="{{ route('ruang-belajar.praktek-details', $popularArticle->slug) }}"
                                        class="th-btn">Selengkapnya <i
                                            class="fa-solid fa-arrow-right ms-1 ylwcolor"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <div class="container mt-50">
            <div class="mt-150 mb-35 text-center text-md-start">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-8">
                        <div class="title-area mb-md-0">
                            <span class="sub-title2"><i class="fal fa-book me-2"></i>Materi Terbaru</span>
                            <h2 class="sec-title2">Ruang Belajar Terkini</h2>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <a href="materi-page.html" class="th-btn">Lihat Semua<i
                                class="fa-solid fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="row slider-shadow th-carousel course-slider-1" data-slide-show="4" data-ml-slide-show="3"
                data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="1" data-arrows="true">
                @foreach ($recentArticles as $recentArticle)
                    <div class="col-md-6 col-lg-4">
                        <div class="course-box style2">
                            <div class="course-img">
                                <img src="{{ URL::to('/' . $recentArticle->image) }}" alt="img">
                                <span class="tag"><i class="fas fa-video"></i> PPT</span>
                                <span class="tags"></span>
                            </div>
                            <div class="course-content">

                                <h3 class="course-title"><a
                                        href="{{ route('ruang-belajar.praktek-details', $recentArticle->slug) }}">

                                         {{$recentArticle->title }}

                                    </a></h3>
                                <div class="course-meta">
                                    <span><i class="fal fa-file"></i>
                                        @php
                                            if (isset($recentArticle->category)) {
                                                echo $recentArticle->category->name;
                                            }
                                        @endphp
                                    </span>
                                    <span><i class="fal fa-user"></i>1.223 Views</span>
                                </div>
                                <div class="course-author">
                                    <a href="{{ route('ruang-belajar.praktek-details', $recentArticle->slug) }}"
                                        class="th-btn">Selengkapnya <i
                                            class="fa-solid fa-arrow-right ms-1 ylwcolor"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <section class="testi-area-1 overflow-hidden space-bottom bggrey">
        <div class="container">
            <div class="title-area text-center mb-50">
                <span class="sub-title"><i class="fal fa-book me-2"></i> Testimoni Pengguna</span>
                <h3 class="sec-title">Testimoni Pengguna Ruang Belajar</h3>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="th-carousel testi-slider1 dot-style2 row slick-initialized slick-slider slick-dotted"
                        id="testimonial-slider1" data-slide-show="2" data-ml-slide-show="2" data-lg-slide-show="2"
                        data-md-slide-show="2" data-ml-slide-show="2" data-dots="true" data-arrows="false">
                        <div class="slick-list draggable">
                            <div>
                                <div class="col-lg-6 slick-slide slick-cloned" tabindex="-1" role="tabpanel"
                                    id="" aria-describedby="slick-slide-control42" data-slick-index="-2"
                                    aria-hidden="true">
                                    <div class="testi-box">
                                        <div class="testi-box-bg-shape">
                                            <svg width="150" height="137" viewBox="0 0 150 137" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M0 9.99951C0 4.47666 4.47715 -0.000488281 10 -0.000488281H140C145.523 -0.000488281 150 4.47666 150 9.99951V10.5803C150 13.3951 148.814 16.0796 146.732 17.9747L18.8619 134.394C17.0205 136.07 14.6199 137 12.1297 137H10C4.47715 137 0 132.522 0 127V9.99951Z"
                                                    fill="materi-page.htmlb32b2d"></path>
                                            </svg>
                                        </div>
                                        <div class="testi-box_content">
                                            <div class="testi-box_img">
                                                <img src="{{ URL::to('/ruangbelajar/') }}/assets/img/kursus/user1.jpg"
                                                    alt="Avater">
                                            </div>
                                            <p class="testi-box_text">Penggunaan sistem pembelajaran sangat bermanfaat,
                                                saya jadi lebih mengetahui tentang informasi dan pengetahuan tentang
                                                kebijakan dan peraturan negara, sebelumnya tidak tahu sama sekali,
                                                terima kasih!</p>
                                        </div>
                                        <div class="testi-box_bottom">
                                            <div>
                                                <h3 class="testi-box_name">Ahmad Subagyo</h3>
                                                <span class="testi-box_desig">Karyawan Swasta</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 slick-slide slick-cloned" tabindex="-1" role="tabpanel"
                                    id="" aria-describedby="slick-slide-control42" data-slick-index="-2"
                                    aria-hidden="true">
                                    <div class="testi-box">
                                        <div class="testi-box-bg-shape">
                                            <svg width="150" height="137" viewBox="0 0 150 137" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M0 9.99951C0 4.47666 4.47715 -0.000488281 10 -0.000488281H140C145.523 -0.000488281 150 4.47666 150 9.99951V10.5803C150 13.3951 148.814 16.0796 146.732 17.9747L18.8619 134.394C17.0205 136.07 14.6199 137 12.1297 137H10C4.47715 137 0 132.522 0 127V9.99951Z"
                                                    fill="materi-page.htmlb32b2d"></path>
                                            </svg>
                                        </div>
                                        <div class="testi-box_content">
                                            <div class="testi-box_img">
                                                <img src="{{ URL::to('/ruangbelajar/') }}/assets/img/kursus/user2.jpg"
                                                    alt="Avater">
                                            </div>
                                            <p class="testi-box_text">Penggunaan sistem pembelajaran sangat bermanfaat,
                                                saya jadi lebih mengetahui tentang informasi dan pengetahuan tentang
                                                kebijakan dan peraturan negara, sebelumnya tidak tahu sama sekali,
                                                terima kasih!</p>
                                        </div>
                                        <div class="testi-box_bottom">
                                            <div>
                                                <h3 class="testi-box_name">Tasa Achmad</h3>
                                                <span class="testi-box_desig">Karyawan Swasta</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jascript')
@endsection
