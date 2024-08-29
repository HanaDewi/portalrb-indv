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
                    Seleksi Administrasi</h2>
            </div>

            @include('zi.evaluatan.progress')

            <hr />

            <div class="col-lg-12 col-md-12">
                <div class="feature-item" style="background-color: white; border-radius: 25px; padding: 20px 80px">
                    <div class="content">

                        <br>
                        <h5>Seleksi Administrasi <br />
                            {{ $instansi}}</h5>
                        <h6>Berikut adalah hasil seleksi administrasi</h6>
                        <br />

                        <form method="POST" action="{{route('evaluatan_simpan_sanggah')}}">
                            @csrf
                            <input type="hidden" name="instansi_id" value="{{$instansiZI->id}}">
                            <table style="text-align:  left; " class="table table-striped table-bordered table-shad">
                                <thead>
                                    <tr>
                                        <th>Persyaratan</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th width="25%">Keterangan</th>
                                        <th>Link Data Sanggah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="fa fa-envelope-open text-menpan"></i> &nbsp; Surat Usulan</td>
                                        <td><a href="{{$instansiZI->surat_usulan}}" target="_blank">Lihat</a></td>
                                        <td>
                                            @if(isset($instansiZI->administrasi_instansi))
                                            @if($instansiZI->administrasi_instansi->surat_usulan == 1)
                                            Sesuai
                                            @elseif($instansiZI->administrasi_instansi->surat_usulan === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($instansiZI->administrasi_instansi))
                                            @if($instansiZI->administrasi_instansi->surat_usulan === 0)
                                            {{$instansiZI->administrasi_instansi->catatan_surat_usulan}}
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($instansiZI->administrasi_instansi))
                                            @if($instansiZI->administrasi_instansi->surat_usulan === 0)
                                            <input type="text" name="surat_usulan" @if($instansiZI->sanggah_instansi)
                                            value = "{{$instansiZI->sanggah_instansi->surat_usulan}}"
                                            @endif
                                            >
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; SPTJM</td>
                                        <td><a href="{{$instansiZI->sptjm}}" target="_blank">Lihat</a></td>
                                        <td>
                                            @if(isset($instansiZI->administrasi_instansi))
                                            @if($instansiZI->administrasi_instansi->sptjm == 1)
                                            Sesuai
                                            @elseif($instansiZI->administrasi_instansi->sptjm === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($instansiZI->administrasi_instansi))
                                            @if($instansiZI->administrasi_instansi->sptjm === 0)
                                            {{$instansiZI->administrasi_instansi->catatan_sptjm}}
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" name="sptjm" @if($instansiZI->sanggah_instansi)
                                            value = "{{$instansiZI->sanggah_instansi->sptjm}}"
                                            @endif>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br />


                            <h6>WBK</h6>
                            @if($instansiZI->instansi_wbk_mandiri)
                            WBK Mandiri <br />
                            @else
                            <table class="table  table-bordered table-shad">
                                <thead style="background: #b42b2d;color:white; text-align:center; ">
                                    <tr>
                                        <th>No</th>
                                        <th width="23%">Unit</th>
                                        <th>Syarat</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th width="20%">Keterangan</th>
                                        <th>Link Data Sanggah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($unit_wbks->count())
                                    @foreach ($unit_wbks as $index => $unit_wbk)
                                    @if(isset($unit_wbk->seleksi_administrasi_unit))
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td rowspan=4>{{$index+1}}</td>
                                        <td rowspan=4 style="text-align: left">{{$unit_wbk->nama}}</td>
                                        <td style="text-align: left">LKE</td>
                                        <td><a href="{{$unit_wbk->lke}}" target="_blank">Lihat</a></td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_lke == 1)
                                            Sesuai
                                            @elseif($unit_wbk->seleksi_administrasi_unit->status_lke === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_lke === 0)
                                            {{$unit_wbk->seleksi_administrasi_unit->catatan_lke}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_lke === 0)
                                            <input type="text" name="lke_{{$unit_wbk->id}}" @if($unit_wbk->sanggah_unit)
                                            value = "{{$unit_wbk->sanggah_unit->lke}}" @endif
                                            >
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td style="text-align: left">TLHP </td>
                                        <td><a href="{{$instansiZI->tlhp}}" target="_blank">Lihat</a></td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_tlhp == 1)
                                            Sesuai
                                            @elseif($unit_wbk->seleksi_administrasi_unit->status_tlhp === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($unit_wbk->seleksi_administrasi_unit))
                                            @if($unit_wbk->seleksi_administrasi_unit->status_tlhp === 0)
                                            {{$unit_wbk->seleksi_administrasi_unit->catatan_tlhp}}
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($unit_wbk->seleksi_administrasi_unit))
                                            @if($unit_wbk->seleksi_administrasi_unit->status_tlhp === 0)
                                            <input type="text" name="tlhp_{{$unit_wbk->id}}"
                                                @if($unit_wbk->sanggah_unit)
                                            value = "{{$unit_wbk->sanggah_unit->tlhp}}" @endif>
                                            @endif
                                            @endif
                                        </td>

                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td style="text-align: left">Survei Mandiri</td>
                                        <td>
                                            <a href="{{$instansiZI->survei_mandiri}}" target="_blank">Lihat</a>
                                        </td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_survei_mandiri == 1)
                                            Sesuai
                                            @elseif($unit_wbk->seleksi_administrasi_unit->status_survei_mandiri === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_survei_mandiri === 0)
                                            {{$unit_wbk->seleksi_administrasi_unit->catatan_survei_mandiri}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($unit_wbk->seleksi_administrasi_unit))
                                            @if($unit_wbk->seleksi_administrasi_unit->status_survei_mandiri === 0)
                                            <input type="text" name="survei_mandiri_{{$unit_wbk->id}}"
                                                @if($unit_wbk->sanggah_unit)
                                            value = "{{$unit_wbk->sanggah_unit->survei_mandiri}}" @endif
                                            >
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td style="text-align: left">LHKPN </td>
                                        <td>-</td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_lhkpn == 1)
                                            Sesuai
                                            @elseif($unit_wbk->seleksi_administrasi_unit->status_lhkpn === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_lhkpn === 0)
                                            {{$unit_wbk->seleksi_administrasi_unit->catatan_lhkpn}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbk->seleksi_administrasi_unit->status_lhkpn === 0)
                                            <input type="text" name="lhkpn_{{$unit_wbk->id}}"
                                                @if($unit_wbk->sanggah_unit)
                                            value = "{{$unit_wbk->sanggah_unit->lhkpn}}" @endif
                                            >
                                            @endif
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4" style="text-align: center">Tidak ada unit WBK</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            @endif
                            <br />
                            <h6>WBBM</h6>
                            <table style="text-align: left" class="table  table-bordered table-shad">
                                <thead style="background: #ffcc08;color:black;">
                                    <tr>
                                        <th>No</th>
                                        <th width="25%">Unit</th>
                                        <th>Syarat</th>
                                        <th>Data Pengusulan</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Link data sanggah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($unit_wbbms->count())
                                    @foreach ($unit_wbbms as $index => $unit_wbbm)
                                    @if($unit_wbbm->seleksi_administrasi_unit)
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td rowspan=5>{{$index+1}}</td>
                                        <td rowspan=5 style="text-align: left">{{$unit_wbbm->nama}}</td>
                                        <td style="text-align: left">LKE </td>
                                        <td>
                                            <a href="{{$unit_wbbm->lke}}" target="_blank">Lihat</a>
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_lke == 1)
                                            Sesuai
                                            @elseif($unit_wbbm->seleksi_administrasi_unit->status_lke === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            LKE Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_lke === 0)
                                            {{$unit_wbbm->seleksi_administrasi_unit->catatan_lke}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_lke === 0)
                                            <input type="text" name="lke_{{$unit_wbbm->id}}"
                                                @if($unit_wbbm->sanggah_unit)
                                            value = "{{$unit_wbbm->sanggah_unit->lke}}" @endif>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td style="text-align: left">TLHP </td>
                                        <td><a href="{{$instansiZI->tlhp}}" target="_blank">Lihat</a></td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_tlhp == 1)
                                            Sesuai
                                            @elseif($unit_wbbm->seleksi_administrasi_unit->status_tlhp === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_tlhp === 0)
                                            {{$unit_wbbm->seleksi_administrasi_unit->catatan_tlhp}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_tlhp === 0)
                                            <input type="text" name="tlhp_{{$unit_wbbm->id}}"
                                                @if($unit_wbbm->sanggah_unit)
                                            value = "{{$unit_wbbm->sanggah_unit->tlhp}}" @endif
                                            >
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td style="text-align: left">Survei Mandiri</td>
                                        <td><a href="{{$instansiZI->survei_mandiri}}" target="_blank">Lihat</a></td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_survei_mandiri == 1)
                                            Sesuai
                                            @elseif($unit_wbbm->seleksi_administrasi_unit->status_survei_mandiri === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_survei_mandiri === 0)
                                            {{$unit_wbbm->seleksi_administrasi_unit->catatan_survei_mandiri}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_survei_mandiri === 0)
                                            <input type="text" name="survei_mandiri_{{$unit_wbbm->id}}"
                                                @if($unit_wbbm->sanggah_unit)
                                            value = "{{$unit_wbbm->sanggah_unit->survei_mandiri}}" @endif
                                            >
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td style="text-align: left">LHKPN</td>
                                        <td>-</td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_lhkpn == 1)
                                            Sesuai
                                            @elseif($unit_wbbm->seleksi_administrasi_unit->status_lhkpn === 0)
                                            <p style="color:red">Tidak Sesuai</p>
                                            @else
                                            Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_lhkpn === 0)
                                            {{$unit_wbbm->seleksi_administrasi_unit->catatan_lhkpn}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_lhkpn === 0)
                                            <input type="text" name="lhkpn_{{$unit_wbbm->id}}"
                                                @if($unit_wbbm->sanggah_unit)
                                            value = "{{$unit_wbbm->sanggah_unit->lhkpn}}" @endif
                                            >
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="@if(($index+1)%2) table-row-genap @else table-row-ganjil @endif">
                                        <td>
                                            Syarat minimal 2 tahun WBK
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_2wbk == 1)
                                            Sesuai
                                            @elseif($unit_wbbm->seleksi_administrasi_unit->status_2wbk === 0)
                                            <p style="color:red">
                                                Tidak Sesuai
                                            </p>
                                            @else
                                            Syarat minimal 2 tahun WBK Belum dinilai
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_2wbk === 0)
                                            {{$unit_wbbm->seleksi_administrasi_unit->catatan_2wbk}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit_wbbm->seleksi_administrasi_unit->status_2wbk === 0)
                                            <input type="text" name="2wbk_{{$unit_wbbm->id}}"
                                                @if($unit_wbbm->sanggah_unit)
                                            value = "{{$unit_wbbm->sanggah_unit->th2wbk}}" @endif
                                            >
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3" style="text-align: center">Tidak ada unit WBBM</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <button class="btn btn-lg btn-primary">Simpan</button><br>
                            * Data Pada Formulir akan secara otomatis terkirim saat waktu penutupan sanggah telah
                            terlewati

                        </form>
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