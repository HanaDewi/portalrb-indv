@extends('ruang-belajar.layouts.template')
@section('css_jquery')
@endsection

@section('content')
    <div class="breadcumb-wrapper" data-bg-src="assets/img/bgslider.jpg">
        <div class="container">
            <div class="breadcumb-content text-center">
                <h2 class="breadcumb-title">Halaman Pencarian</h2>
                </ul>-->
            </div>
        </div>
    </div>

    <section class="space-top space-extra2-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xxl-9 col-lg-8">
                    <div class="course-single">
                        <div class="course-single-bottom">
                            <div class="course-curriculam">
                                <form action="{{ route('ruang-belajar.article') }}" method="GET">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <input type="text" placeholder="Type here" value="{{ request()->search }}"
                                                name="search">
                                        </div>
                                        <div class="col-lg-4">
                                            <select name="category">
                                                @foreach ($categories as $category)
                                                    <option {{ $category->slug === request()->category ? 'selected' : '' }}
                                                        value="{{ $category->slug }}">{{ $category->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="submit">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <aside class="wrapper__list__article ">
                                @if (request()->has('category'))
                                    <h4 class="border_section">Kategori : {{ request()->category }}
                                    </h4>
                                @endif

                                <div class="row">
                                    @foreach ($articles as $post)
                                        <div class="col-lg-6">
                                            <!-- Post Article -->
                                            <div class="article__entry">
                                                <div class="article__image">
                                                    <a href="{{ route('ruang-belajar.praktek-details', $post->slug) }}">
                                                        <img src="{{ asset($post->image) }}" alt=""
                                                            class="img-fluid">
                                                    </a>
                                                </div>
                                                <div class="article__content">
                                                    <div class="article__category">
                                                        {{ $post->category->name }}
                                                    </div>
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item">
                                                            <span class="text-primary">
                                                                {{ $post->author->nama }}
                                                            </span>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <span class="text-dark text-capitalize">
                                                                {{ date('M d, Y', strtotime($post->created_at)) }}
                                                            </span>
                                                        </li>

                                                    </ul>
                                                    <h5>
                                                        <a
                                                            href="{{ route('ruang-belajar.praktek-details', $post->slug) }}">
                                                            {!! truncate($post->title) !!}
                                                        </a>
                                                    </h5>
                                                    <p>
                                                        {!! truncate($post->content, 100) !!}
                                                    </p>
                                                    <a href="{{ route('ruang-belajar.praktek-details', $post->slug) }}"
                                                        class="btn btn-outline-primary mb-4 text-capitalize">
                                                        readmore</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if (count($articles) === 0)
                                        <div class="text-center w-100">
                                            <h4>Artikel tidak ditemukan </h4>
                                        </div>
                                    @endif
                                </div>

                            </aside>
                        </div>
                    </div>
                    <div class="text-center" style="display: flex;
                justify-content: center;">
                        <!-- Pagination -->
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4">
                    <div class="sidebar-sticky">
                        <aside class="wrapper__list__article ">
                            <h4 class="border_section">Artikel Lainnya</h4>
                            <div class="wrapper__list__article-small">
                                @foreach ($recentArticles as $article)
                                    @if ($loop->index <= 2)
                                        <div class="mb-3">
                                            <!-- Post Article -->
                                            <div class="card__post card__post-list">
                                                <div class="image-sm">
                                                    <a href="{{ route('ruang-belajar.praktek-details', $article->slug) }}">
                                                        <img src="{{ asset($article->image) }}" class="img-fluid"
                                                            alt="">
                                                    </a>
                                                </div>


                                                <div class="card__post__body ">
                                                    <div class="card__post__content">

                                                        <div class="card__post__author-info mb-2">
                                                            <ul class="list-inline">
                                                                <li class="list-inline-item">
                                                                    <span class="text-primary">
                                                                        {{ __('frontend.by') }}
                                                                        {{ $article->author->nama }}
                                                                    </span>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <span class="text-dark text-capitalize">
                                                                        {{ date('M d, Y', strtotime($article->created_at)) }}
                                                                    </span>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                        <div class="card__post__title">
                                                            <h6>
                                                                <a
                                                                    href="{{ route('ruang-belajar.praktek-details', $article->slug) }}">
                                                                    {!! truncate($article->title) !!}
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @foreach ($recentArticles as $news)
                                    @if ($loop->index > 2)
                                        <!-- Post Article -->
                                        <div class="article__entry">
                                            <div class="article__image">
                                                <a href="{{ route('ruang-belajar.praktek-details', $news->slug) }}">
                                                    <img src="{{ asset($news->image) }}" alt="" class="img-fluid">
                                                </a>
                                            </div>
                                            <div class="article__content">
                                                <div class="article__category">
                                                    {{ $news->category->name }}
                                                </div>
                                                <ul class="list-inline">
                                                    <li class="list-inline-item">
                                                        <span class="text-primary">
                                                            {{ $news->author->nama }}
                                                        </span>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <span class="text-dark text-capitalize">
                                                            {{ date('M d, Y', strtotime($news->created_at)) }}
                                                        </span>
                                                    </li>

                                                </ul>
                                                <h5>
                                                    <a href="{{ route('ruang-belajar.praktek-details', $news->slug) }}">
                                                        {!! truncate($news->title) !!}
                                                    </a>
                                                </h5>
                                                <p>
                                                    {!! truncate($news->content, 100) !!}
                                                </p>
                                                <a href="{{ route('ruang-belajar.praktek-details', $news->slug) }}"
                                                    class="btn btn-outline-primary mb-4 text-capitalize">
                                                    Readmore</a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </aside>

                        <aside class="wrapper__list__article">
                            <h4 class="border_section">Tags</h4>
                            <div class="blog-tags p-0">
                                <ul class="list-inline">
                                    @foreach ($mostCommonTags as $tag)
                                        <li class="list-inline-item">
                                            <a href="{{ route('ruang-belajar.article', ['tag' => $tag->name]) }}">
                                                #{{ $tag->name }} ({{ $tag->count }})
                                            </a>
                                        </li>
                                    @endforeach


                                </ul>
                            </div>
                        </aside>

                        <aside class="wrapper__list__article">
                            <h4 class="border_section"></h4>
                            <!-- Form Subscribe -->
                            <div class="widget__form-subscribe bg__card-shadow">
                                <h6>

                                </h6>
                                <p><small></small></p>
                                <form action="" class="newsletter-form">
                                    <div class="input-group ">
                                        <input type="text" class="form-control" name="email"
                                            placeholder="Your email address">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary newsletter-button" type="submit"></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </aside>


                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </section>
@endsection

@section('jascript')
@endsection
