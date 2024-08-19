@extends('home-template.template')
@section('cssJsHere')
<style>
    .text-menpan {
        color: #b42b2d;
    }

    h1 {
        text-align: center;
        text-transform: uppercase;
    }


    .container-timeline {
        width: 1200px;
        margin: auto;

    }

    .timeline {
        counter-reset: test 0;
        position: relative;
    }

    .timeline li {
        list-style: none;
        float: left;
        width: 16%;
        position: relative;
        text-align: center;
        text-transform: uppercase;
        z-index: 0;


    }

    ul:nth-child(1) {
        color: white;
    }

    .timeline li:before {
        counter-increment: test;
        content: counter(test);
        width: 50px;
        height: 50px;
        border: 3px solid #b42b2d;
        border-radius: 50%;
        display: block;
        text-align: center;
        line-height: 50px;
        margin: 0 auto 10px auto;
        background: #fff;
        color: #000;
        transition: all ease-in-out .3s;
        cursor: pointer;
        left: 0%;
        z-index: 1;
    }

    .timeline li:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 4px;
        background-color: #b42b2d;
        top: 25px;
        left: -38%;
        z-index: -1;
        transition: all ease-in-out .3s;
    }

    .timeline li:first-child:after {
        content: none;
    }

    .timeline li.active-tl {
        color: #555555;
        content: "\f00c";
    }

    .timeline li.active-tl:before {
        background: #b42b2d;
        color: #F1F1F1;
        content: "\2713";
        font-size: 1.5em;
    }

    .timeline li.active-tl+li:after {
        background: #b42b2d;

    }


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

    .table-shad {
        box-shadow: 0 0 30px #9ecaed;
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
@endsection

@section('content')
<section class="features-area pt-50 pb-85 rel z-1"
    style="background-image: url({{ URL::to('/') }}/assets/images/bg2.jpg); background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="section-title text-center pb-35 ">
                <h2 style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;">
                    Pengusulan Zona
                    Integritas</h2>
            </div>
            @include('zi.evaluatan.progress')
            <hr />

            <div class="col-lg-12 col-md-12">
                <div class="feature-item" style="background-color: white; border-radius: 25px; padding: 20px 80px">
                    <div class="content">

                        <br>
                        <h5>Pengusulan Zona Integritas <br /> {{ $instansi}}</h5>
                        <h6>Berikut adalah data yang anda masukan</h6>
                        <br />
                        <table style="text-align:  left; " class="table table-striped table-bordered table-shad">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Isian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fa fa-user text-menpan"></i> &nbsp; PIC</td>
                                    <td>{{$instansiZI->pic}}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope text-menpan"></i> &nbsp; Email</td>
                                    <td>{{$instansiZI->email}}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-book text-menpan"></i> &nbsp; Nomor Kontak</td>
                                    <td>{{$instansiZI->nomor_kontak}}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"></i> &nbsp; Surat Usulan</td>
                                    <td>{{$instansiZI->surat_usulan}}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; SPTJM</td>
                                    <td>{{$instansiZI->sptjm}}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; TLHP</td>
                                    <td>{{$instansiZI->tlhp}}</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; Mandiri</td>
                                    <td>{{$instansiZI->survei_mandiri}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br />

                        <h6>WBK</h6>
                        <table class="table table-striped table-bordered table-shad">
                            <thead style="background: #b42b2d;color:white; text-align:center; ">
                                <tr>
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>LKE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unit_wbks->count())
                                @foreach ($unit_wbks as $index => $unit_wbk)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td style="text-align: left">{{$unit_wbk->nama}}</td>
                                    <td style="text-align: left">{{$unit_wbk->lke}}</td>
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
                        <table style="text-align: left" class="table table-striped table-bordered table-shad">
                            <thead style="background: #ffcc08;color:black;">
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
                                    <td style="text-align: left">{{$unit_wbbm->nama}}</td>
                                    <td style="text-align: left">{{$unit_wbbm->lke}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3" style="text-align: center">Tidak ada unit WBBM</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
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
@endsection