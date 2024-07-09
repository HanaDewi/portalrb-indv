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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css"
        rel="stylesheet" />



    <style>
        #owl-demo .item img {
            display: block;
            width: 100%;
            height: auto;
        }

        .full-img img {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }

        .form-control {
            padding: .775rem .75rem;
            border-radius: 10px;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

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
                                            <a href="{{ url('/') }}">Beranda</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('dashboard') }}">Evaluasi</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/ruang-belajar/home') }}">Ruang Belajar</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/zi') }}">Zona Integritas</a>
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
        <div class="demo">

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
                        <h2 style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;">Pengusulan Zona
                            Integritas</h2>
                        <span class="line"></span>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="feature-item" style="background-color: white; border-radius: 25px;">
                            <div class="content">
                                @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn")

                                <form action="{{URL::to('zi')}}" method="get">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-9">
                                            <select name="instansi_id" class="form-control selectpicker"
                                                data-live-search="true">
                                                @foreach ($instansis as $inst )
                                                <option value="{{$inst->id}}" @if($inst->name==$instansi) selected
                                                    @endif>{{$inst->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                                <br>
                                <h5>{{ $instansi}}</h5>
                                <img src="assets/images/syarat_min_instansi.jpg" width="75%">
                                <hr />
                                <table style="text-align: left" class="table">
                                    <thead>
                                        <tr>
                                            <th>Indikator</th>
                                            <th>Skor</th>
                                            <th>Predikat</th>
                                            <th class="text-center">WBK</th>
                                            <th class="text-center">WBBM</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>Opini BPK</td>
                                        <td> {{$skor_opini_bpk}} </td>
                                        <td>{{($opini_bpk)?$opini_bpk:"-"}} </td>
                                        <td class="text-center">
                                            @if($syarat_bpk=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($syarat_bpk=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Predikat SAKIP</td>
                                        <td> {{$skor_predikat_sakip}}</td>
                                        <td>{{($predikat_sakip)?$predikat_sakip:"-"}} </td>
                                        <td class="text-center">
                                            @if($syarat_sakip_wbk=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($syarat_sakip_wbbm=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Indeks RB</td>
                                        <td> {{$skor_indeks_rb}}</td>
                                        <td>{{($indeks_rb)?$indeks_rb:"-"}} </td>
                                        <td class="text-center">
                                            @if($syarat_indeksrb_wbk=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($syarat_indeksrb_wbbm=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Maturitas SPIP</td>
                                        <td> {{$skor_maturitas_spip}}</td>
                                        <td>{{($maturitas_spip)?$maturitas_spip:"-"}} </td>
                                        <td class="text-center">
                                            @if($syarat_maturitas_spip=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($syarat_maturitas_spip=="LULUS")
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-center">Kesimpulan</td>
                                            <td class="text-center">
                                                @if($syarat_akhir_wbk=="LULUS")
                                                <i class="fa fa-check text-success"></i>
                                                @else
                                                <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($syarat_akhir_wbbm=="LULUS")
                                                <i class="fa fa-check text-success"></i>
                                                @else
                                                <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="row ">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <hr />
                                        <small class="form-text" {{ ($status_akhir==0 || $status_akhir==3
                                            )?"style=color:red ":"" }}>{{$keterangan}}.
                                            Jika terdapat kesalahan pada data diatas mohon menghubungi
                                            administrator</small>
                                    </div>

                                    <div class=" col-md-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($status_akhir > 0)
                    <form action="{{URL::to('zi')}}" method="post">
                        @csrf
                        @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn")
                        <input type="hidden" name="instansi_id" value="{{ $instansi_id}}">
                        @endif
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <div class="feature-item" style="background-color:#ffcc08; border-radius: 25px;">
                                    <div class="content">
                                        <div class="row ">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-10 form-group" style="text-align: left">
                                                <div class="form-group">
                                                    <label>PIC</label>
                                                    <input type="text" name="pic" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-left">Email</label>
                                                    <input type="email" name="email" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nomor Kontak</label>
                                                    <input type="text" name="nomor_kontak" class="form-control"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Surat Usulan Unit</label>
                                                    <input type="text" name="surat_usulan" class="form-control"
                                                        required>
                                                    <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                        yang
                                                        berisi surat usulan unit/satuan kerja
                                                        pembangunan ZI</small>
                                                </div>

                                                <div class="form-group">
                                                    <label> SPTJM </label>
                                                    <input type="text" name="sptjm" class="form-control" required>
                                                    <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                        yang
                                                        berisi Surat Pernyataan Tanggung Jawab
                                                        Mutlak (SPTJM) yang ditandatangani pimpinan instansi</small>
                                                </div>
                                                <div class="form-group">
                                                    <label>TLHP</label>
                                                    <input type="text" name="tlhp" class="form-control" required>
                                                    <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                        yang
                                                        berisi Surat Pernyataan Clearance TLHP oleh
                                                        APIP</small>
                                                </div>
                                                <div class="form-group">
                                                    <label>Survei Mandiri</label>
                                                    <input type="text" name="survei_mandiri" class="form-control"
                                                        required>
                                                    <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                        yang
                                                        berisi Laporan hasil pelaksanaan survei
                                                        mandiri yang memuat nilai SPAK dan SPKP</small>

                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />

                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <div class="feature-item"
                                    style="color:white; background-color:#b42b2d; border-radius: 25px;">
                                    <div class="content">
                                        <div class="row ">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-10" id="detail_unit">
                                                <div class="form-group">
                                                    <h6 style="text-align: center; color:white;">
                                                        Jumlah Unit yang diusulkan </h6>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label style="text-align: center; color:white;">WBK</label>
                                                            <input type="number" name="jml_wbk" id="jmlWBK"
                                                                onkeyup="hitungTotal();" class="form-control"
                                                                style="text-align: center;">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label style="text-align: center; color:white;">WBBM</label>
                                                            <input type="number" name="jml_wbbm" id="jmlWBBM"
                                                                onkeyup="hitungTotal()" class="form-control"
                                                                style="text-align: center;" @if($status_akhir==1 or
                                                                $status_akhir==3) disabled @endif>
                                                        </div>
                                                        <div class=" col-md-4">
                                                            <label
                                                                style="text-align: center; color:white;">Total</label>
                                                            <input type="text" id="jmlUnit" class="form-control"
                                                                style="text-align: center;""
                                                                                        disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <br />
                                                                        <a class=" btn btn-primary"
                                                                id="btn-tambah-unit"
                                                                style="color:black; background-color:#ffcc08; "
                                                                onclick="tambah_input();">Tambahkan
                                                            Unit
                                                            </a>
                                                        </div>
                                                        <div class=" col-md-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row" id="row-wbk" style="display:none">
                                        <div class="col-lg-2 col-md-2">
                                        </div>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="feature-item"
                                                style="color:white; background-color:#b42b2d; border-radius: 25px;">
                                                <div class="content">
                                                    <div class="row ">
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-10" id="detail_unit">
                                                            <div class="form-group">
                                                                <h5 style="text-align: center; color:white;">WBK</h5>
                                                            </div>
                                                        </div>
                                                        <div class=" col-md-1">
                                                        </div>
                                                    </div>
                                                    <div class=" form-group" id="cont-tambah-unit-wbk">
                                                        <hr />
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label style="color:white; ">Nama Unit WBK</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label style="color:white">Link LKE</label>
                                                            </div>
                                                            @if($group_kld == "prov" || $group_kld == "kab")
                                                            <div class="col-md-2">
                                                                <label style="color:white; ">Afirmasi</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                @elseif ($group_kld == "kl")
                                                                <div class="col-md-4">
                                                                    @endif
                                                                    <label style="color:white; ">Delete</label>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                        </div>
                                                        <br />
                                                        <a class=" btn btn-primary" id="btn-tambah-unit-wbk"
                                                            style="color:black; background-color:#ffcc08; "
                                                            onclick="tambah_wbk();">Tambahkan Unit WBK
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="row-wbbm" style="display:none">
                                            <div class="col-lg-2 col-md-2">
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <div class="feature-item"
                                                    style="color:white; background-color:#b42b2d; border-radius: 25px;">
                                                    <div class="content">
                                                        <div class="row ">
                                                            <div class="col-md-1">
                                                            </div>
                                                            <div class="col-md-10" id="detail_unit">
                                                                <div class="form-group">
                                                                    <h5 style="text-align: center; color:white;">WBBM
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class=" col-md-1">
                                                            </div>
                                                        </div>
                                                        <div class=" form-group" id="cont-tambah-unit-wbbm">
                                                            <hr />
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <label style="color:white; ">Nama Unit WBBM</label>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label style="color:white">Link LKE</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label style="color:white; ">Delete</label>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                        </div>
                                                        <br />
                                                        <a class=" btn btn-primary" id="btn-tambah-unit-wbbm"
                                                            style="color:black; background-color:#ffcc08; "
                                                            onclick="tambah_wbbm();">Tambahkan Unit WBBM
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="row-submit" style="display:none">
                                            <div class="col-lg-2 col-md-2">
                                            </div>
                                            <div class="col-lg-8 col-md-8" style=" padding:30px 0px;">
                                                <button type="submit" class="btn btn-primary"
                                                    style="width:100%; padding:15px; display:block;">Kirim</button>
                                            </div>
                                        </div>
                    </form>
                    @endif

                </div>
            </div>
        </section>
        <!-- Features Area end -->
        <!-- Meter Area start -->


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

    <script>
        function hitungTotal() {
            if ($('#jmlWBK').val() == '') {
                $('#jmlWBK').val(0);
            }
            if ($('#jmlWBBM').val() == '') {
                $('#jmlWBBM').val(0);
            }
            var total = parseInt($('#jmlWBK').val()) + parseInt($('#jmlWBBM').val())
            $('#jmlUnit').val( total); 
        };

        function tambah_input(){
            if ($('#jmlWBK').val() >0 || $('#jmlWBBM').val() >0 ) {
                $('#btn-tambah-unit').remove();
                $("#row-submit").show();
                if ($('#jmlWBK').val() >0 ) {
                    $("#row-wbk").show();
                    for (var i = 1; i <= $('#jmlWBK').val(); i++) {
                        tambah_wbk(pertama_kali = 1);
                    };
                }
                if ($('#jmlWBBM').val() >0 ) {
                    $("#row-wbbm").show();
                    for (var i = 1; i <= $('#jmlWBBM').val(); i++) {
                        tambah_wbbm(pertama_kali = 1);
                    };
                }
            }
        }

        function tambah_wbk(pertama_kali = 0){
            var row_str = '<br>\
                <div class="row deleteSegini" > \
                    <div class="col-md-4"> \
                        <input type="text" name="unit_wbk[]" class="form-control" style="text-align:center" placeholder="Nama Unit WBK " required> \
                    </div> \
                    <div class="col-md-4"> \
                        <input type="text" name="lke_wbk[]" class="form-control" style="text-align:center" placeholder="link LKE" required> \
                    </div>';

                @if($group_kld == "prov" || $group_kld == "kab")
                    row_str = row_str + '<div class="col-md-2"> \
                        <input type="checkbox" name="afirmasi[]" value=1 style=" margin-top:0.7em;\
                        height: 25px;\
                        width: 25px;\
                        background-color: #eee;"' ;  
                        
                        @if($status_akhir ==3 )
                        row_str = row_str + 'required';
                        @endif
                        
                        row_str = row_str +    '>\
                    </div> \
                    <div class="col-md-2">'; 
                @elseif ($group_kld == "kl")
                    row_str = row_str + '<div class="col-md-4">';
                @endif
                    row_str = row_str + '<a href="#inigakada" class="btn btn-danger " style=" margin-top:0.3em;\" onclick = "hapus_unit_wbk(this)" >X</a>\
                    </div> \
                </div>';
            $('#cont-tambah-unit-wbk').append(row_str);   
            if(!pertama_kali){
                jml_wbk = $('#jmlWBK').val();
                $('#jmlWBK').val(parseInt(jml_wbk)+1);
                hitungTotal();
            }
            
        }

        function hapus_unit_wbk(ortu){
            $(ortu).parent().parent().remove();
            jml_wbk = $('#jmlWBK').val();
            $('#jmlWBK').val(jml_wbk-1);
            hitungTotal();
        }

        function tambah_wbbm(pertama_kali = 0){
            var row_str = '<br>\
                <div class="row"> \
                    <div class="col-md-5"> \
                        <input type="text" name="unit_wbbm[]" class="form-control" style="text-align:center" placeholder="Nama Unit WBBM " required> \
                    </div> \
                    <div class="col-md-5"> \
                        <input type="text" name="lke_wbbm[]" class="form-control" style="text-align:center" placeholder="link LKE" required> \
                    </div> \
                    <div class="col-md-2"> \
                        <button  class="btn btn-danger" style=" margin-top:0.3em;\" onclick = "hapus_unit_wbbm(this)">X</button>\
                    </div> \
                </div>';
            $('#cont-tambah-unit-wbbm').append(row_str);      
            if(!pertama_kali){
                jml_wbbm = $('#jmlWBBM').val();
                $('#jmlWBBM').val(parseInt(jml_wbbm)+1);
                hitungTotal();
            }
        }

        function hapus_unit_wbbm(ortu){
            $(ortu).parent().parent().remove();
            jml_wbbm = $('#jmlWBBM').val();
            $('#jmlWBBM').val(jml_wbbm-1);
            hitungTotal();
            
        }   

        
    </script>

</body>


</html>