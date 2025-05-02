@extends('home-template.template')
@section('cssJsHere')
<link rel="stylesheet" href="{{ asset('assets/css/timelinezi.css') }}" />
<style>
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
<section class="features-area pt-50 pb-85 rel"
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

                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @elseif(session()->has('error'))
                        {{ session()->get('error') }}
                        @endif


                        @error('lke')
                        <div class="text-danger"><strong>Update Gagal</strong> : {{ $message }}. LKE harus dalam bentuk
                            Link (mengandung
                            https://)</div>
                        @enderror

                        @if ($errors->any())
                        <div class="alert alert-danger text-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li><strong>Update Gagal</strong> : {{ $error }} . Masukan halaman link yang benar
                                    (mengandung
                                    https://)</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
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
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editPICModal"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope text-menpan"></i> &nbsp; Email</td>
                                    <td>{{$instansiZI->email}}</td>
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editEmailModal"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-book text-menpan"></i> &nbsp; Nomor Kontak</td>
                                    <td>{{$instansiZI->nomor_kontak}}</td>
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editHpModal"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"></i> &nbsp; Surat Usulan</td>
                                    <td>{{$instansiZI->surat_usulan}}</td>
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editSuratUsulanModal"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; SPTJM</td>
                                    <td>{{$instansiZI->sptjm}}</td>
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editSptjmModal"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; TLHP</td>
                                    <td>{{$instansiZI->tlhp}}</td>
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editTlhpModal"></a></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-envelope-open text-menpan"> </i> &nbsp; Survei Mandiri</td>
                                    <td>{{$instansiZI->survei_mandiri}}</td>
                                    <td class="text-center"><a href="#" class="fa fa-edit text-primary ms-2"
                                            data-bs-toggle="modal" data-bs-target="#editSurveiMandiriModal"></a></td>
                                </tr>
                            </tbody>
                        </table>
                        <br />

                        <h6>Unit Yang Diusulkan</h6>
                        @error('lke')
                        <div class="text-danger">Update Gagal : {{ $message }}. LKE harus dalam bentuk Link (mengandung
                            https://)</div>
                        @enderror

                        <table class="table table-striped table-bordered table-shad">
                            <thead style="background: #b42b2d;color:white; text-align:center; ">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>WBK/WBBM</th>
                                    <th>LKE</th>
                                    <th>EDIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($units->count())
                                @foreach ($units as $index => $unit)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td style="text-align: left">{{$unit->nama}}</td>
                                    <td class="text-center">
                                        @if($unit->wbbm==1)
                                        WBBM
                                        @elseif ($unit->wbk ==1)
                                        WBK
                                        @endif
                                    </td>
                                    <td style="text-align: left">{{$unit->lke}}</td>
                                    <td style="text-align: center">
                                        <button class="fa fa-edit text-primary ms-2 edit-unit-btn"
                                            data-unit-id="{{ $unit->id }}" data-unit-nama="{{ $unit->nama }}"
                                            data-unit-lke="{{ $unit->lke }}">
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" style="text-align: center">Tidak ada unit </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <br />
                        <!-- Modal-->
                        <!-- Modal Edit PIC -->
                        <div class="modal fade" id="editPICModal" tabindex="-1" aria-labelledby="editPICModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'pic']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Nama PIC</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="value" class="form-control"
                                                value="{{ $instansiZI->pic }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Edit Email PIC -->
                        <div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'email']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Email PIC</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="email" name="value" class="form-control"
                                                value="{{ $instansiZI->email }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Edit No HP PIC -->
                        <div class="modal fade" id="editHpModal" tabindex="-1" aria-labelledby="editHpModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'nomor_kontak']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit No HP PIC</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="value" class="form-control"
                                                value="{{ $instansiZI->nomor_kontak }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Edit Surat Usulan -->
                        <div class="modal fade" id="editSuratUsulanModal" tabindex="-1"
                            aria-labelledby="editHpModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'surat_usulan']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Surat Usulan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="value" class="form-control"
                                                value="{{ $instansiZI->surat_usulan }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal SPTJM -->
                        <div class="modal fade" id="editSptjmModal" tabindex="-1" aria-labelledby="editHpModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'sptjm']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit STPJM</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="value" class="form-control"
                                                value="{{ $instansiZI->sptjm }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Edit TLHP -->
                        <div class="modal fade" id="editTlhpModal" tabindex="-1" aria-labelledby="editHpModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'tlhp']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit TLHP</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="value" class="form-control"
                                                value="{{ $instansiZI->tlhp }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Edit Survei Mandiri -->
                        <div class="modal fade" id="editSurveiMandiriModal" tabindex="-1"
                            aria-labelledby="editHpModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form
                                    action="{{ route('zi.updateField', ['id' => $instansiZI->id, 'field' => 'survei_mandiri']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Survei Mandiri</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="value" class="form-control"
                                                value="{{ $instansiZI->survei_mandiri }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Dinamis -->
                        <div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" id="editUnitForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUnitModalLabel">Edit Unit </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body" style="text-align: left">
                                            <div class="mb-3">
                                                <label for="namaUnit" class="form-label">Nama
                                                    Unit</label>
                                                <input type="text" class="form-control" name="nama" id="namaUnit"
                                                    required>
                                                <label for="lke" class="form-label">Link
                                                    LKE</label>
                                                <input type="text" class="form-control" name="lke" id="linkLKE"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
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
<script>
    $(document).ready(function() {
        $('.edit-unit-btn').on('click', function() {
            const unitId = $(this).data('unit-id');
            const unitName = $(this).data('unit-nama');
            const unitLKE = $(this).data('unit-lke');

            // Set action form ke route sesuai unit ID
            $('#editUnitForm').attr('action', 'zi/unit/' + unitId + '/update-unit');

            // Set judul dan nilai input
            $('#editUnitModalLabel').text('Edit Unit - ' + unitName);
            $('#linkLKE').val(unitLKE);
            $('#namaUnit').val(unitName);

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('editUnitModal'));
            modal.show();
        });
    });
</script>




@endsection