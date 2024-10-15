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
                    <form method="POST" action="{{route('evaluatan_simpan_desk')}}">
                        @csrf
                        <div class="content">
                            <br>
                            <h5>{{$title}} <br /> {{ $instansi}}</h5><br />
                            <hr />
                            <h5 style="color:red">Proses evaluasi sedang berlangsung melalui mekanisme: analisa
                                dokumen / wawancara
                                (virtual) / observasi lapangan. <br />

                                Mekanisme evaluasi pada setiap unit / satker bisa berbeda, tergantung kebutuhan
                                evaluator
                                dalam melakukan pendalaman / validasi / verifikasi hasil pembangunan ZI.
                            </h5>
                            <hr>
                            <br /><br />
                            <img src="{{ asset('/assets/images/teknis-wawancara-zi.png') }}">
                            <br /><br /><br />

                            <h6>WBK</h6>
                            <table class="table table-striped table-bordered table-shad">
                                <thead style="background: #b42b2d;color:white; text-align:center; ">
                                    <tr>
                                        <th>No</th>
                                        <th>Unit</th>
                                        <th>Jadwal Wawancara</th>
                                        <th>Link Zoom Wawancara</th>
                                        <th>Link Bahan Paparan Evaluatan</th>
                                        <th>Jadwal Verifikasi Lapangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($unit_wbks->count())
                                    @foreach ($unit_wbks as $index => $unit_wbk)
                                    @if(isset($unit_wbk->analisis_dokumen))
                                    @if($unit_wbk->analisis_dokumen->status == 1)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td style="text-align: left">{{$unit_wbk->nama}}</td>
                                        <td style="text-align: left">
                                            <i class="fa fa-calendar fa-lg text-danger" aria-hidden="true"></i>
                                            @if(isset($unit_wbk->wawancara->jadwal))
                                            {{\Carbon\Carbon::parse($unit_wbk->wawancara->jadwal)->isoFormat('dddd, D
                                            MMMM Y HH:mm');}} WIB
                                            @endif

                                        </td>
                                        <td style="text-align: left">

                                            <i class="fa fa-play" aria-hidden="true"></i>
                                            @if(isset($unit_wbk->wawancara->link_zoom))
                                            {{$unit_wbk->wawancara->link_zoom}}
                                            @endif

                                        </td>
                                        <td style="text-align: left">

                                            @if(isset($unit_wbk->wawancara->jadwal))
                                            <input type="text" @if(isset($unit_wbk->wawancara->link_paparan))
                                            value={{$unit_wbk->wawancara->link_paparan}}
                                            @endif
                                            name="link_paparan_{{$unit_wbk->id}}">

                                            @endif

                                        </td>
                                        <td style="text-align: left">

                                            <i class="fa fa-calendar fa-lg text-danger" aria-hidden="true"></i>
                                            @if(isset($unit_wbk->verifikasi_lapangan))
                                            {{$unit_wbk->verifikasi_lapangan->jadwal}}
                                            @endif

                                        </td>
                                    </tr>
                                    @endif
                                    @endif
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
                                <thead style="background: #ffcc08;color:black; text-align:center; ">
                                    <tr>
                                        <th>No</th>
                                        <th>Unit</th>
                                        <th>Jadwal Wawancara</th>
                                        <th>Link Zoom Wawancara</th>
                                        <th>Link Bahan Paparan Evaluatan</th>
                                        <th>Jadwal Verifikasi Lapangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($unit_wbbms->count())
                                    @foreach ($unit_wbbms as $index => $unit_wbbm)
                                    @if(isset($unit_wbbm->analisis_dokumen) )
                                    @if($unit_wbbm->analisis_dokumen->status == 1)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td style="text-align: left">{{$unit_wbbm->nama}}</td>
                                        <td style="text-align: left">
                                            <i class="fa fa-calendar fa-lg text-danger" aria-hidden="true"></i>
                                            @if(isset($unit_wbbm->wawancara->jadwal))
                                            {{\Carbon\Carbon::parse($unit_wbbm->wawancara->jadwal)->isoFormat('dddd, D
                                            MMMM Y HH:mm');}} WIB
                                            @endif

                                        </td>
                                        <td style="text-align: left">
                                            <i class="fa fa-play" aria-hidden="true"></i>
                                            @if(isset($unit_wbbm->wawancara->link_zoom))
                                            {{$unit_wbbm->wawancara->link_zoom}}
                                            @endif

                                        </td>
                                        <td style="text-align: left">

                                            @if(isset($unit_wbbm->wawancara->jadwal))
                                            <input type="text" @if(isset($unit_wbbm->wawancara->link_paparan))
                                            value={{$unit_wbbm->wawancara->link_paparan}}
                                            @endif
                                            name="link_paparan_{{$unit_wbbm->id}}">
                                            @endif

                                        </td>
                                        <td style="text-align: left">
                                            <i class="fa fa-calendar fa-lg text-danger" aria-hidden="true"></i>
                                            @if(isset($unit_wbbm->verifikasi_lapangan))
                                            {{$unit_wbbm->verifikasi_lapangan->jadwal}}
                                            @endif

                                        </td>
                                    </tr>
                                    @endif
                                    @endif
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
                        <input type="submit" class="btn btn-primary" value="KIRIM">
                    </form>
                </div>
            </div>



        </div>
    </div>
</section>
@endsection