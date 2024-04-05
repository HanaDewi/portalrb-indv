@extends('ruang-belajar.layouts.template')
@section('css_jquery')
@endsection


@section('content')
    <div class="breadcumb-wrapper" data-bg-src="assets/img/bgslider.jpg">
        <div class="container">
            <div class="breadcumb-content text-center">
                <h2 class="breadcumb-title">{!! $article->title !!}</h2>
                <!--<ul class="breadcumb-menu">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <li><a href="materi-page.html">Beranda</a></li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <li><a href="materi-page.html">Materi Ruang Belajar</a></li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <li>Penanggulangan Kemiskinan Provinsi D.I Yogyakarta</li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </ul>-->
            </div>
        </div>
    </div>

    <section class="space-top space-extra2-bottom"> 
        <div class="container">
            <div class="row">
                <div class="col-xxl-9 col-lg-8">
                    <div class="course-single">
                        <div class="course-single-top">
                            <div class="course-img1">
                                <img src="{{ asset($article->image) }}">
                            </div>
                            <div class="course-meta style2">
                                <span><i class="fal fa-file"></i>{{ $article->category->name }}</span>
                                <span><i class="fal fa-user"></i>{{ $article->views }}</span>
                                <span><i class="fal fa-chart-simple"></i>Hasil Kajian</span>
                            </div>
                            <h2 class="course-title">{!! $article->title !!}</h2>
                            <ul class="course-single-meta">
                                <li class="course-single-meta-author">
                                    <img src="{{ asset('ruangbelajar/assets/img/kursus/garuda.png') }}" alt="DI Yogyakarta">
                                    <span>
                                        <span class="meta-title">Instansi: </span>
                                        <a href="course.html">D.I Yogyakarta</a>
                                    </span>
                                </li>
                                <li>
                                    <span class="meta-title">Kategori: </span>
                                    <a href="course.html">{{ $article->category->name }}</a>
                                </li>
                                <li>
                                    <span class="meta-title">Diperbarui: </span>
                                    @php
                                        $dt = new DateTime($article->created_at);
                                        $tz = new DateTimeZone('Asia/Jakarta');

                                        $dt->setTimezone($tz);

                                    @endphp
                                    <a href="#">{{ $dt->format('d M Y') }}</a>
                                </li>
                                <li>
                                    <span class="meta-title">Diskusi: </span>
                                    {{ $article->comments()->count() }} Komentar
                                </li>
                            </ul>
                        </div>
                        <div class="course-single-bottom">
                            <ul class="nav course-tab" id="courseTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                        href="#Coursedescription" role="tab" aria-controls="Coursedescription"
                                        aria-selected="true"><i class="fa-regular fa-bookmark"></i>Penjelasan</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="curriculam-tab" data-bs-toggle="tab" href="#curriculam"
                                        role="tab" aria-controls="curriculam" aria-selected="false"><i
                                            class="fa-regular fa-book"></i>Materi</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="instructor-tab" data-bs-toggle="tab" href="#instructor"
                                        role="tab" aria-controls="instructor" aria-selected="false"><i
                                            class="fa-regular fa-video"></i>Video</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab"
                                        aria-controls="reviews" aria-selected="false"><i
                                            class="fa-regular fa-comment"></i>Diskusi</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="Coursedescription" role="tabpanel"
                                    aria-labelledby="description-tab">
                                    <div class="course-description">
                                        {!! $article->content !!}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="curriculam" role="tabpanel" aria-labelledby="curriculam-tab">
                                    <div class="course-curriculam">
                                        <h5 class="h5">Ringkasan Materi</h5>
                                        <p class="mb-30">Lumbung mataraman Kalurahan merupakan perluasan lumbung
                                            mataraman tingkat Kalurahan yang dapat mendukung ketahanan
                                            pangan, kemandirian pangan, dan kedaulatan pangan di wilayah. Setiap
                                            Kalurahan memiliki potensi baik potensi fisik yang
                                            berupa tanah, air, iklim, lingkungan geografis, pertanian, dan sumber daya
                                            manusia, serta potensi non-fisik berupa
                                            masyarakat dengan corak dan interaksinya.
                                        </p>

                                        <embed type="application/pdf" src="assets/file/materitest.pdf" width="100%"
                                            height="600"></embed>

                                        <a href="materi-page.html" class="th-btn mt-25">Download Materi <i
                                                class="fa-solid fa-arrow-right ms-1 ylwcolor"></i></a>


                                    </div>
                                </div>
                                <div class="tab-pane fade" id="instructor" role="tabpanel"
                                    aria-labelledby="instructor-tab">
                                    <div class="course-instructor mt-45">
                                        <h5 class="h5">Tentang Video</h5>
                                        <p class="mb-30">DIY menggalakkan kembali konsep Lumbung Mataraman pada tahun
                                            2021. Dahulu, Lumbung Mataraman adalah konsep yang
                                            diterapkan Sultan Agung sejak abad ke 17, dengan pola pertanian CLS (Crop
                                            Livestock System). Sistem ini mengintegrasikan
                                            cocok tanam dengan ternak. Bahkan di tahun 1944, Sri Sultan Hamengkubuwono
                                            IX meneruskan apa yang sudah dilakukan Sultan
                                            Agung. Sri Sultan IX ini membangun Selokan Mataram
                                        </p>
                                        <div class="course-author-box">

                                            <iframe width="560" height="515"
                                                src="https://www.youtube.com/embed/N-lv3f9OBdM?si=ZOtphUwmhu59V0dr"
                                                title="YouTube video player" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                    <div class="course-Reviews">
                                        <div class="th-comments-wrap ">
                                            <ul class="comment-list">
                                                @foreach ($article->comments()->whereNull('parent_id')->get() as $comment)
                                                    <li class="review th-comment-item">
                                                        <div class="th-post-comment">
                                                            <div class="comment-avater">
                                                                <img
                                                                    src="{{ asset('ruangbelajar/assets/img/kursus/user1.png') }}">
                                                            </div>
                                                            <div class="comment-content">
                                                                <h4 class="name">@php print_r($comment->user); @endphp</h4>
                                                                <span class="commented-on"><i
                                                                        class="fal fa-calendar-alt"></i>{{ date('d M Y H:i', strtotime($comment->created_at)) }}</span>

                                                                <p class="text">{{ $comment->comment }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div> <!-- Comment Form -->
                                        <div class="th-comment-form ">
                                            <div class="form-title">
                                                <h3 class="blog-inner-title ">Tambahkan Diskusi</h3>
                                            </div>
                                            <div class="row">
                                                <form action="{{ route('ruang-belajar.article-comment-replay') }}"
                                                    method="POST">
                                                    @csrf
                                                    <textarea name="replay" cols="30" rows="7" placeholder="Type. . ."></textarea>
                                                    <input type="hidden" name="article_id" value="{{ $article->id }}">

                                                    <button type="submit" class="th-btn">Tambah Diskusi <i
                                                            class="far fa-arrow-right ms-1"></i></button>

                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4">
                    <aside class="sidebar-area">
                        <div class="widget widget_info  ">
                            <div class="th-video">
                                <iframe width="200" height="230"
                                    src="https://www.youtube.com/embed/N-lv3f9OBdM?si=ZOtphUwmhu59V0dr"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            </div>

                            <a href="materi-page.html" class="th-btn">Tambah ke wishlist</a>
                            <a href="materi-page.html" class="th-btn style4">IKUTI PEMBELAJARAN </a>
                            <h3 class="widget_title">Informasi Materi</h3>
                            <div class="info-list">
                                <ul>
                                    <li>
                                        <i class="fa-light fa-user"></i>
                                        <strong>Instansi: </strong>
                                        <span>D.I Yogyakarta</span>
                                    </li>
                                    <li>
                                        <i class="fa-light fa-file"></i>
                                        <strong>Kategori: </strong>
                                        <span>{{ $article->category->name }}</span>
                                    </li>
                                    <li>
                                        <i class="fa-light fa-clock"></i>
                                        <strong>Durasi: </strong>
                                        <span>2 Jam 30 Menit</span>
                                    </li>
                                    <li>
                                        <i class="fa-light fa-globe"></i>
                                        <strong>Bahasa: </strong>
                                        <span>Indonesia</span>
                                    </li>
                                    <li>
                                        <i class="fa-light fa-puzzle-piece"></i>
                                        <strong>Tipe File: </strong>
                                        <span>PDF & Video</span>
                                    </li>
                                    <li>
                                        <i class="fa-light fa-tag"></i>
                                        <strong>Tags: </strong>

                                        @foreach ($mostCommonTags as $tag)
                                            <span><a href="{{ route('ruang-belajar.article', ['tag' => $tag->name]) }}"
                                                    style="color:black">
                                                    #{{ $tag->name }} ({{ $tag->count }})
                                                </a> |
                                            </span>
                                        @endforeach

                                    </li>
                                </ul>
                            </div>
                            <a href="#" class="th-btn style6 mt-35 mb-0"><i
                                    class="far fa-share-nodes me-2"></i>Bagikan
                                Materi Ini</a>


                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </section>

    <section class="space-bottom">
        <div class="container">
            @if (count($relatedPosts) > 0)
                <div class="title-area text-center">
                    <span class="sub-title"><i class="fal fa-book me-2"></i> Materi Terkait</span>
                    <h2 class="sec-title">Materi Ruang Belajar</h2>
                </div>
                <div class="row slider-shadow th-carousel course-slider-1" data-slide-show="4" data-ml-slide-show="3"
                    data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="1" data-arrows="true">
                    @foreach ($relatedPosts as $post)
                        <div class="col-md-6 col-lg-4">
                            <div class="course-box style2">
                                <div class="course-img">
                                    <img src="{{ URL::to('/' . $post->image) }}" alt="img">
                                    <span class="tag"><i class="fas fa-book"></i> PDF</span>
                                    <span class="tags"></span>
                                </div>
                                <div class="course-content">

                                    <h3 class="course-title">
                                        <a href="{{ route('ruang-belajar.praktek-details', $post->slug) }}">
                                            {!! ($post->title) !!}
                                        </a>
                                    </h3>
                                    <div class="course-meta">
                                        <span><i class="fal fa-file"></i>{{ $post->category->name }}</span>
                                        <span><i class="fal fa-user"></i>{{ $post->views }} Views</span>
                                    </div>
                                    <div class="course-author">
                                        <a href="{{ route('ruang-belajar.praktek-details', $post->slug) }}"
                                            class="th-btn">Selengkapnya
                                            <i class="fa-solid fa-arrow-right ms-1 ylwcolor"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <br /><br /><br /><br />
@endsection

@section('jascript')
@endsection
