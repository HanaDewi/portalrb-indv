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
                    <div class="col-lg-10 col-md-10">
                        <div class="feature-item" style="background-color: white; border-radius: 25px;">
                            <div class="content">

                                <br>
                                <h5>{{ $instansi}}</h5>
                                <h6>Berikut adalah data yang anda masukan</h6>
                                <table style="text-align:  left; " class="table">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Isian</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PIC</td>
                                            <td>{{$instansiZI->pic}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{$instansiZI->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor Kontak</td>
                                            <td>{{$instansiZI->nomor_kontak}}</td>
                                        </tr>
                                        <tr>
                                            <td>Surat Usulan</td>
                                            <td>{{$instansiZI->surat_usulan}}</td>
                                        </tr>
                                        <tr>
                                            <td>SPTJM</td>
                                            <td>{{$instansiZI->sptjm}}</td>
                                        </tr>
                                        <tr>
                                            <td>TLHP</td>
                                            <td>{{$instansiZI->tlhp}}</td>
                                        </tr>
                                        <tr>
                                            <td>Survei Mandiri</td>
                                            <td>{{$instansiZI->survei_mandiri}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />

                                <h6>WBK</h6>
                                <table style="text-align: left; display: block;
                                    overflow: auto;
                                    white-space: nowrap;" class="table">
                                    <thead style="background: #b42b2d;color:white; text-align:center; ">
                                        <tr>
                                            <th>No</th>
                                            <th>Unit</th>
                                            <th>LKE</th>
                                            <th>Afirmasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($unit_wbks->count())
                                        @foreach ($unit_wbks as $index => $unit_wbk)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$unit_wbk->nama}}</td>
                                            <td>{{$unit_wbk->lke}}</td>
                                            <td style="text-align: center">
                                                @if($unit_wbk->afirmasi)
                                                <i class='fa fa-check text-success'></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="4" style="text-align: center">Tidak ada unit WBK</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <br />
                                <h6>WBBM</h6>
                                <table style="text-align: left" class="table">
                                    <thead style="background: #ffcc08;color:black; text-align:center">
                                        <tr>
                                            <th>No</th>
                                            <th>Unit</th>
                                            <th>LKE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($unit_wbbms->count())
                                        @foreach ($unit_wbbms as $index => $unit_wbbm)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$unit_wbbm->nama}}</td>
                                            <td>{{$unit_wbbm->lke}}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="3" style="text-align: center">Tidak ada unit WBBM</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                                @if($status_akhir > 0)
                                <!--
                                <form action="{{URL::to('zi')}}" method="post">
                                    @csrf
                                    @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn")
                                    <input type="hidden" name="instansi_id" value="{{$instansi_id}}">
                                    @endif
                                    <input type="hidden" name="final" value="1">
                                    <button type="submit" class="btn btn-primary"
                                        style="width:100%; padding:15px; display:block;">Kirim</button>
                                </form>
                                -->
                                @endif

                                <div class="row ">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-10 form-group">

                                    </div>

                                    <div class=" col-md-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



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
                        tambah_wbk();
                    };
                }
                if ($('#jmlWBBM').val() >0 ) {
                    $("#row-wbbm").show();
                    for (var i = 1; i <= $('#jmlWBBM').val(); i++) {
                        tambah_wbbm();
                    };
                }
            }
        }

        function tambah_wbk(){
            var row_str = '<br>\
                <div class="row"> \
                    <div class="col-md-4"> \
                        <input type="text" name="unit_wbk[]" class="form-control" style="text-align:center" placeholder="Nama Unit WBK "> \
                    </div> \
                    <div class="col-md-4"> \
                        <input type="text" name="lke_wbk[]" class="form-control" style="text-align:center" placeholder="link LKE"> \
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
                    row_str = row_str + '<a href="#" class="btn btn-danger" style=" margin-top:0.3em;\">X</a>\
                    </div> \
                </div>';
            $('#cont-tambah-unit-wbk').append(row_str);   
        }
        function tambah_wbbm(){
            var row_str = '<br>\
                <div class="row"> \
                    <div class="col-md-5"> \
                        <input type="text" name="unit_wbbm[]" class="form-control" style="text-align:center" placeholder="Nama Unit WBBM "> \
                    </div> \
                    <div class="col-md-5"> \
                        <input type="text" name="lke_wbbm[]" class="form-control" style="text-align:center" placeholder="link LKE"> \
                    </div> \
                    <div class="col-md-2"> \
                        <a href="#" class="btn btn-danger" style=" margin-top:0.3em;\">X</a>\
                    </div> \
                </div>';
            $('#cont-tambah-unit-wbbm').append(row_str);      
        }
    </script>
</body>


</html>