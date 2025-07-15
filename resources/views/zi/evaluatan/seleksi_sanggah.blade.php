@extends('home-template.template')
@section('cssJsHere')
<link rel="stylesheet" href="{{ asset('assets/css/timelinezi.css') }}" />
<style>
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
</style>

@if($status_akses =="Tutup")
<style>
    .form-control,
    .glowing-border {
        pointer-events: none;
    }


    #tombol-kirim {
        display: none
    }
</style>
@endif

@endsection

@section('content')
<section class="features-area pt-50 pb-85 rel z-1"
    style="background-image: url({{ URL::to('/') }}/assets/images/bg2.jpg); background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="section-title text-center pb-35 ">
                <h2 style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;">
                    {{$title}}</h2>
            </div>
            @include('zi.evaluatan.progress')
            <hr />

            <div class="col-lg-12 col-md-12">
                <div class="feature-item" style="background-color: white; border-radius: 25px; padding: 20px 80px">
                    <div class="content">

                        <br>
                        <h5>{{$title}} <br /> {{ $instansi}}</h5>
                        <h6>Berikut adalah Hasil Seleksi Admnistasi dan hasil sanggah</h6>
                        <br />


                        <h6>WBK</h6>
                        <table class="table table-striped table-bordered table-shad">
                            <thead style="background: #b42b2d;color:white; text-align:center; ">
                                <tr>
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unit_wbks->count())
                                @foreach ($unit_wbks as $index => $unit_wbk)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td style="text-align: left">{{$unit_wbk->nama}}</td>
                                    <td style="text-align: left">

                                        @if($unit_wbk->instansiZI->instansi_wbk_mandiri ==1)
                                        WBK Mandiri
                                        @else
                                        @if($unit_wbk->seleksi_administrasi_unit->status_final==1)
                                        Lulus
                                        @elseif ($unit_wbk->seleksi_administrasi_unit->status_final===0)
                                        @if ($unit_wbk->sanggah_unit->status_final ==1)
                                        Lulus
                                        @elseif($unit_wbk->sanggah_unit->status_final ===0)
                                        Tidak Lulus
                                        @else
                                        Belum Dinilai
                                        @endif
                                        @else
                                        Belum Dinilai
                                        @endif
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
                        <table style="text-align: left" class="table table-striped table-bordered table-shad">
                            <thead style="background: #ffcc08;color:black;">
                                <tr>
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unit_wbbms->count())
                                @foreach ($unit_wbbms as $index => $unit_wbbm)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td style="text-align: left">{{$unit_wbbm->nama}}</td>
                                    <td style="text-align: left">
                                        @if($unit_wbbm->seleksi_administrasi_unit->status_final==1)
                                        Lulus
                                        @elseif ($unit_wbbm->seleksi_administrasi_unit->status_final===0)
                                        @if ($unit_wbbm->sanggah_unit->status_final ==1)
                                        Lulus
                                        @elseif($unit_wbbm->sanggah_unit->status_final ===0)
                                        Tidak Lulus
                                        @else
                                        Belum Dinilai
                                        @endif
                                        @else
                                        Belum Dinilai
                                        @endif
                                    </td>
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