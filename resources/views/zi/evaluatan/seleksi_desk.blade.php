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

    .link-wrap {
        word-break: break-all;

    }

    textarea {
        padding: 5px;
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
                    <h5>{{$title}} <br /> {{ $instansi}}</h5><br />
                    @if (session()->has('message'))
                    <div @if(session('sukses')==1) class="alert alert-success" @elseif(session('sukses')===0)
                        class="alert alert-danger" @endif>
                        {{ session('message') }}
                    </div>
                    @endif
                    @if($buka_formulir)
                    <form method="POST" action="{{route('evaluatan_simpan_desk')}}">
                        @endif
                        @csrf
                        <div class="content">
                            <br>

                            <hr />
                            <h5 style="color:red">Proses evaluasi sedang berlangsung melalui mekanisme: analisa
                                dokumen / wawancara
                                (virtual) / observasi lapangan. Mekanisme evaluasi pada setiap unit / satker bisa
                                berbeda, tergantung kebutuhan
                                evaluator
                                dalam melakukan pendalaman / validasi / verifikasi hasil pembangunan ZI.

                                Hasil akhir evaluasi akan disampaikan melalui Lembar Hasil Evaluasi (LHE)
                                kemungkinan pada Desember 2025.
                            </h5>
                            <hr>
                            <br /><br />
                            <img src="{{ asset('/assets/images/teknis-wawancara-zi.png') }}">
                            <br /><br /><br />

                            <table class="table table-striped table-bordered table-shad">
                                <thead style="background: #b42b2d;color:white; text-align:center; ">
                                    <tr>
                                        <th>No</th>
                                        <th>Unit</th>
                                        <th>Jadwal Wawancara</th>
                                        <th>Keterangan / Link Zoom Wawancara</th>
                                        <th>Link Bahan Paparan Evaluatan</th>
                                        <th>Jadwal Verifikasi Lapangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 0;
                                    @endphp
                                    @if($units->count())
                                    @foreach ($units as $index => $unit)
                                    @if(isset($unit->analisis_dokumen))
                                    @if($unit->analisis_dokumen->status == 1)
                                    @php
                                    $no++;
                                    @endphp
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td style="text-align: left">
                                            {{$unit->nama}} <br>
                                            @if($unit->wbbm)
                                            (WBBM)
                                            @else
                                            (WBK)
                                            @endif
                                        </td>
                                        <td style="text-align: left">
                                            @if(isset($unit->wawancara->jadwal))
                                            <i class="fa fa-calendar fa-lg text-danger" aria-hidden="true"></i>
                                            {{\Carbon\Carbon::parse($unit->wawancara->jadwal)->isoFormat('dddd,
                                            D
                                            MMMM Y HH:mm');}} WIB
                                            @endif

                                        </td>
                                        <td style="text-align: left">
                                            @if(isset($unit->wawancara->link_zoom))
                                            <i class="fa fa-play" aria-hidden="true"></i>
                                            {{$unit->wawancara->link_zoom}}
                                            @endif

                                        </td>
                                        <td style="text-align: left" class="link-wrap">
                                            @if($buka_formulir)
                                            @if(isset($unit->wawancara->link_zoom))
                                            <textarea rows='4' cols='15' class='form-control glowing-border'
                                                name="link_paparan_{{$unit->id}}">@if(isset($unit->wawancara->link_paparan)){{$unit->wawancara->link_paparan}}@endif</textarea>
                                            @endif
                                            @else
                                            @if(isset($unit->wawancara))
                                            {{$unit->wawancara->link_paparan}}
                                            @endif
                                            @endif

                                        </td>
                                        <td style="text-align: left">
                                            @if(isset($unit->verifikasi_lapangan->jadwal))
                                            <i class="fa fa-calendar fa-lg text-danger" aria-hidden="true"></i>
                                            @if($unit->verifikasi_lapangan->jadwal == $unit->wawancara->jadwal)
                                            Sudah dilakukan verifikasi lapangan bersamaan dengan wawancara
                                            @else
                                            @if(\Carbon\Carbon::parse($unit->verifikasi_lapangan->jadwal)->isoFormat('HH')!='00')
                                            {{\Carbon\Carbon::parse($unit->verifikasi_lapangan->jadwal)->isoFormat('dddd,
                                            D
                                            MMMM Y HH:mm');}} WIB
                                            @else
                                            {{\Carbon\Carbon::parse($unit->verifikasi_lapangan->jadwal)->isoFormat('dddd,
                                            D
                                            MMMM Y');}}
                                            @endif
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" style="text-align: center">Tidak ada unit WBK</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <br />

                            <div class="row ">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-10 form-group">
                                </div>
                                <div class=" col-md-1">
                                </div>
                            </div>
                        </div>
                        @if($buka_formulir)
                        <input type="submit" class="btn btn-primary" value="KIRIM">
                        @endif
                    </form>
                </div>
            </div>



        </div>
    </div>
</section>
@endsection