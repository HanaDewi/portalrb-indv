<div class="menu-area">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-2">
                <div class="header-logo">
                    <a href="{{ URL::to('/') }}"><img
                            src="{{ URL::to('/ruangbelajar/') }}/assets/img/logoruangbelajar.jpg"
                            alt="Ruang Belajar Reformasi Birokrasi"></a>
                </div>
            </div>
            <div class="col-10">
                <div class="row">
                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block mt-10">
                            <ul>
                                <li><a href="   {{ route('ruang-belajar.home') }}">Beranda</a></li>
                                <li class="menu-item-has-children"><a href="#">Pusat
                                        Pengetahuan</a>
                                    <ul class="sub-menu">
                                        <li><a href="#">Praktik Baik
                                                RB</a></li>
                                        <li><a href="#">Pembangunan RB</a></li>
                                        <li><a href="">Hasil Penelitian RB</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Pusat Pelatihan</a></li>
                                <li><a href="#">F.A.Q</a></li>
                                <li><a href="{{ URL::to('/') }}">Portal RB</a></li>
                                @if (auth()->check())
                                <li><a href="{{ URL::to('/ruang-belajar/dashboard') }}">Admin</a></li>
                                @endif                            </ul>
                        </nav>
                        <button type="button" class="th-menu-toggle d-block d-lg-none"><i
                                class="far fa-bars"></i></button>
                    </div>

                    <div class="col-auto d-none d-xl-block">
                        <div class="header-button">
                            <a href="wishlist.html" class="icon-btn">
                                <i class="far fa-heart"></i>
                                <span class="badge">3</span>
                            </a>
                            @if (!auth()->check())
                                <a href="{{ URL::to('/') }}/login" class="th-btn ml-25">Login<i
                                        class="fas fa-arrow-right ms-1"></i></a>
                            @else
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="th-btn ml-25"
                                        onclick="event.preventDefault();
                                    this.closest('form').submit();"
                                        href= "">Logout<i class="fas fa-arrow-right ms-1"></i></a>
                                </form>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
