@extends('layout.rubick')
@section('title', 'Data LKE Renaksi - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data Konversi Jawaban - {{ auth()->user()->nama }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                <label for="indikator_id" class="form-label font-bold">Tahun</label>
                <select name="tahun" class="form-control" onchange="location.href='/master-data/data-konversi-jawaban?tahun=' + this.value">
                    @for ($i=date('Y'); $i>2015; $i--)
                    <option value="{{ $i }}" {{ ($i==$tahun) ? 'selected':'' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="separator mt-5"></div>
            <table id="table-konversi-jawaban" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Jawaban</th>
                        <th>Skor</th>
                        <th class="w-5">Tahun</th>
                        @if ($isadmin==true)
                        <th style="width:100px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $nn=>$de)
                    <tr>
                        <th>{{ $de->id }}</th>
                        <th>{{ $de->jawaban }}</th>
                        <th>{{ $de->skor }}</th>
                        <th>{{ $de->tahun }}</th>
                        @if ($isadmin==true)
                        <th>
                            <a class="btn btn-warning btn-xs" data-id="{{ $de->id }}" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-konversi-jawaban"><i class="nav-icon fas fa-edit"></i></a> &nbsp; 
                            <a class="btn btn-danger btn-xs" data-id="{{ $de->id }}" onclick="dodelete(this)" ><i class="nav-icon fas fa-remove"></i></a> &nbsp; 
                        </th>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($isadmin==true)
            <br/>
            <a class="btn btn-danger" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-konversi-jawaban">Tambah Data</a>
            @endif
        </div>
    </div>
</div>

<div id="modal-form-konversi-jawaban" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Data Konversi Jawaban</h2>
            </div>
            <form action="{{ url('master-data/data-konversi-jawaban/save') }}" id="form-konversi-jawaban" method="post">
                @csrf
                <input type="hidden" name="konversi_jawaban_id" id="konversi_jawaban_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label> 
                            <select id="tahun" name="tahun" class="form-control" required>
                                @for ($i=date('Y'); $i>2015; $i--)
                                <option value="{{ $i }}" {{ ($i==$tahun) ? 'selected':'' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="jawaban" class="form-label">Jawaban  <span class="text-danger">*</span></label> 
                            <input type="text" id="jawaban" name="jawaban" class="form-control" placeholder="Jawaban" required />
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="skor" class="form-label">Skor  <span class="text-danger">*</span></label> 
                            <input type="text" id="skor" name="skor" class="form-control" placeholder="Skor" required />
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
    window.rowdata = new DataTable('#table-konversi-jawaban');
});
window.modal_konversi_jawaban = tailwind.Modal.getInstance(document.querySelector("#modal-form-konversi-jawaban"));
window.showform = function(th) {
    const id = $(th).data('id');
    let jawaban = '';
    let skor = '';
    let tahun = '';
    if (id!=undefined) {
        $('#modal-form-konversi-jawaban').find('input[name=konversi_jawaban_id]').val(id);
        const rows = window.rowdata.rows().data();
        let selected;
        for (let n=0; n<rows.length; n++) {
            if (rows[n][0]==id) {
                selected = rows[n];
            }
        }
        if (selected!=undefined) {
            jawaban = selected[1];
            skor = selected[2];
            tahun = selected[3];
        }
    }
    $('#modal-form-konversi-jawaban').find('select[name=tahun]').val(tahun);
    $('#modal-form-konversi-jawaban').find('input[name=jawaban]').val(jawaban);
    $('#modal-form-konversi-jawaban').find('input[name=skor]').val(skor);
    window.modal_konversi_jawaban.show();
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
                url: "{{url('master-data/data-konversi-jawaban/delete')}}",
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
</script>
@endpush
