@extends('home-template.template')
@section('cssJsHere')
<link rel="stylesheet" href="{{ asset('assets/css/timelinezi.css') }}" />
<style>
    .modal {
        z-index: 1999;
    }

    .modal-backdrop {
        z-index: 105;
    }

    .text-menpan {
        color: #b42b2d;
    }

    h1 {
        text-align: center;
        text-transform: uppercase;
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
                                <tr class="text-center">
                                    <th>Data</th>
                                    <th>Isian</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fa fa-user text-menpan"></i> &nbsp; PIC</td>
                                    <td>{{$instansiZI->pic}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope text-menpan"></i> &nbsp; Email</td>
                                    <td>{{$instansiZI->email}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-book text-menpan"></i> &nbsp; Nomor Kontak</td>
                                    <td>{{$instansiZI->nomor_kontak}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"></i> &nbsp; Surat Usulan</td>
                                    <td>{{$instansiZI->surat_usulan}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; SPTJM</td>
                                    <td>{{$instansiZI->sptjm}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; TLHP</td>
                                    <td>{{$instansiZI->tlhp}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; Mandiri</td>
                                    <td>{{$instansiZI->survei_mandiri}}</td>
                                    <td class="text-center"><a class="fa fa-edit"></a></td>
                                </tr>
                            </tbody>
                        </table>
                        <br />

                        <h6>WBK</h6>
                        <table class="table table-striped table-bordered table-shad">
                            <thead style="background: #b42b2d;color:white; text-align:center; ">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>LKE</th>
                                    <th>EDIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unit_wbks->count())
                                @foreach ($unit_wbks as $index => $unit_wbk)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td style="text-align: left">{{$unit_wbk->nama}}</td>
                                    <td style="text-align: left">{{$unit_wbk->lke}}</td>
                                    <td style="text-align: center"><a class="fa fa-edit"></a></td>
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
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>LKE</th>
                                    <th>EDIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unit_wbbms->count())
                                @foreach ($unit_wbbms as $index => $unit_wbbm)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td style="text-align: left">{{$unit_wbbm->nama}}</td>
                                    <td style="text-align: left">{{$unit_wbbm->lke}}</td>
                                    <td style="text-align: center"><a class="fa fa-edit"></a></td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" style="text-align: center">Tidak ada unit WBBM</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- Modal-->
                        <!-- Trigger -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModalll">
                            Launch modal
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">This is a modal body.</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--END MODAL-->
                    </div>
                </div>
            </div>



        </div>
    </div>
</section>
@endsection

@section('jsHere')
<!-- Add this just before </body> in your template -->

@endsection