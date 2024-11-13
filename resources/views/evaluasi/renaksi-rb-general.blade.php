@extends('layout.rubick')
@section('title', 'Evaluasi Renaksi RB General - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Evaluasi Renaksi  RB General
                @if (isset($instansi))
                    - {{ $instansi->name }}
                @endif
            </h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                <label for="indikator_id" class="form-label font-bold">Tahun</label>
                <select name="tahun" class="form-control" onchange="location.href='/evaluasi/renaksi-rb-general?tahun=' + this.value">
                    @for ($i=date('Y'); $i>2015; $i--)
                    <option value="{{ $i }}" {{ ($i==$tahun) ? 'selected':'' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="row">
                <a class="btn btn-danger" href="/evaluasi/data-lke-renaksi">Data LKE Renaksi</a> &nbsp; 
                <a class="btn btn-danger" href="/evaluasi/renaksi-rb-general">Data Konversi Jawaban</a>
            </div>
            <div class="separator mt-5"></div>
            @if($kembali==false)
            <table id="table-instansi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Instansi</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $cc=>$jw)
                    <tr>
                        <td>{{ $cc+1 }}</td>
                        @if ($istpn)
                        <td><a href="?instansi={{ $jw->instansi_id }}" style="color:blue">{{ $jw->instansi->name }}</a></td>
                        @else 
                        <td><a href="?instansi={{ $jw->id }}" style="color:blue">{{ $jw->name }}</a></td>
                        @endif
                        <td class="text-center">{{ $jw->skor }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <table id="table-instansi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>LKE Renaksi</th>
                        <th>Jawaban</th>
                        <th>Catatan</th>
                        <th>Rekomendasi</th>
                        @if($check==true)
                        <th class="w-20">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $cc=>$jw)
                    <tr>
                        <td>{{ $cc+1 }}</td>
                        <td>{{ $jw->lke->kriteria }}</td>
                        <td>{{ $jw->jawaban }}</td>
                        <td>{{ $jw->catatan }}</td>
                        <td>{{ $jw->rekomendasi }}</td>
                        @if($check==true)
                        <td class="text-center">
                            <a class="btn btn-warning btn-xs" data-raw="{{ json_encode($jw) }}" data-id="{{ $jw->id }}" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-jawaban-renaksi"><i class="nav-icon fas fa-edit"></i></a> &nbsp; 
                            <a class="btn btn-danger btn-xs" data-id="{{ $jw->id }}" onclick="dodelete(this)" ><i class="nav-icon fas fa-remove"></i></a> &nbsp; 
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <div class="row mt-4">
                @if($kembali==true)
                <a href="/evaluasi/renaksi-rb-general" class="btn btn-warning">&lt; Kembali</a>
                @endif
                @if($check==true)
                &nbsp; <a  onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-jawaban-renaksi" class="btn btn-danger">Tambah</a>
                @endif
            </div>
        </div>
    </div>
</div>

@if($check==true)
<div id="modal-form-jawaban-renaksi" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Data Jawaban Renaksi</h2>
            </div>
            <form action="{{ url('evaluasi/renaksi-rb-general/save') }}" id="form-konversi-jawaban" method="post">
                @csrf
                <input type="hidden" name="instansi_id" id="instansi_id" value="{{ $instansi->id }}">
                <input type="hidden" name="jawaban_renaksi_id" id="jawaban_renaksi_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label> 
                            <input type="text" name="tahun" value="{{ $tahun }}" class="form-control" readonly placeholder="Tahun"/>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="lkerenaksi" class="form-label">LKE Renaksi <span class="text-danger">*</span></label> 
                            <select id="lkerenaksi" name="lke_renaksi_id" class="form-control" required onchange="showInfo(this)">
                                <option></option>
                                @foreach ($renaksi as $ren)
                                <option value="{{ $ren->id }}" data-info="{{ addslashes($ren->info) }}">{{ $ren->kriteria }}</option>
                                @endforeach
                            </select>
                            <textarea class="form-control" disabled id="renaksiinfo" rows="10" style="font-size:9pt !important;"></textarea>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="jawaban" class="form-label">Jawaban  <span class="text-danger">*</span></label> 
                            <select id="jawbaan" name="jawaban" class="form-control" required placeholder="Pilih jawaban">
                                <option></option>
                                @foreach ($list_jawaban as $lj)
                                <option value="{{ $lj->jawaban }}">{{ $lj->jawaban }}</option>
                                @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="catatan" class="form-label">Catatan  </label> 
                            <textarea id="catatan" name="catatan" class="form-control" placeholder="Catatan"></textarea>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="rekomendasi" class="form-label">Rekomendasi  </label> 
                            <textarea id="rekomendasi" name="rekomendasi" class="form-control" placeholder="Rekomendasi"></textarea>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer text-end"> 
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Batal</button> &nbsp; 
                    <button type="submit" class="btn btn-success w-20 saveButton">Simpan</button> 
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('css')
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function(){
    window.rowdata = new DataTable('#table-instansi');
});
@if($check==true)
window.modal_jawaban_renaksi = tailwind.Modal.getInstance(document.querySelector("#modal-form-jawaban-renaksi"));
window.showform = function(th) {
    const id = $(th).data('id');
    let tahun = '{{ date('Y') }}';
    let lkerenaksi_id = '';
    let jawaban = '';
    let catatan = '';
    let rekomendasi = '';
    if (id!=undefined) {
        const raw = $(th).data('raw');
        tahun = raw.tahun;
        lkerenaksi_id = raw.lke_renaksi_id;
        jawaban = raw.jawaban;
        catatan = raw.catatan;
        rekomendasi = raw.rekomendasi;
    }
    $('#modal-form-jawaban-renaksi').find('input[name=jawaban_renaksi_id]').val(id);
    $('#modal-form-jawaban-renaksi').find('input[name=tahun]').val(tahun);
    $('#modal-form-jawaban-renaksi').find('select[name=lke_renaksi_id]').val(lkerenaksi_id).change();
    $('#modal-form-jawaban-renaksi').find('select[name=jawaban]').val(jawaban);
    $('#modal-form-jawaban-renaksi').find('textarea[name=catatan]').val(catatan);
    $('#modal-form-jawaban-renaksi').find('textarea[name=rekomendasi]').val(rekomendasi);
    window.modal_jawaban_renaksi.show();
}
window.showInfo = function(th) {
    const info = $(th).find('option:selected').data('info');
    $('#renaksiinfo').val(info);
}
window.dodelete = function(th) {
    const id = $(th).data('id');
    if (!id) {
        return false;
    }
    Swal.fire({
        title: "Yakin?",
        text: "Hapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus aja!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{url('evaluasi/renaksi-rb-general/delete')}}",
                type: "delete",
                data: {_token: '{{csrf_token()}}', id: id},
                dataType: "json",
                success: function(res) {
                    if (res.result) {
                        Swal.fire('Selamat!', 'Data data berhasil dihapus!', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Aduh!', 'Data data gagal dihapus! Coba lagi nanti ya..', 'error');
                    }
                },
                error: function(err) {
                    Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                }
            });
        }
    });
}
@endif
</script>
@endpush
