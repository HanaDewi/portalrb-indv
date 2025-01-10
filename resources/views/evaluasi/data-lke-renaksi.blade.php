@extends('layout.rubick')
@section('title', 'Data LKE Renaksi - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data LKE Renaksi - {{ auth()->user()->nama }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                <label for="indikator_id" class="form-label font-bold">Tahun</label>
                <select name="tahun" class="form-control" onchange="location.href='/master-data/data-lke-renaksi?tahun=' + this.value">
                    @for ($i=date('Y'); $i>2015; $i--)
                    <option value="{{ $i }}" {{ ($i==$tahun) ? 'selected':'' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="separator mt-5"></div>
            <table id="data-lke" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Kriteria</th>
                        <th>Parent</th>
                        <th>Info</th>
                        <th class="w-5">Tahun</th>
                        @if ($isadmin==true)
                        <th style="width:100px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datalke as $nn=>$de)
                    <tr>
                        <th>{{ $de->id }}</th>
                        <th>{{ $de->kriteria }}</th>
                        <th>{{ $de->parent_id }}</th>
                        <th>{{ $de->info }}</th>
                        <th>{{ $de->tahun }}</th>
                        @if ($isadmin==true)
                        <th>
                            <a class="btn btn-warning btn-xs" data-id="{{ $de->id }}" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-lke-renaksi"><i class="nav-icon fas fa-edit"></i></a> &nbsp; 
                            <a class="btn btn-danger btn-xs" data-id="{{ $de->id }}" onclick="dodelete(this)" ><i class="nav-icon fas fa-remove"></i></a> &nbsp; 
                        </th>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($isadmin==true)
            <br/>
            <a class="btn btn-danger" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-lke-renaksi">Tambah Data</a>
            @endif
        </div>
    </div>
</div>

<div id="modal-form-lke-renaksi" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Data LKE Renaksi</h2>
            </div>
            <form action="{{ url('master-data/data-lke-renaksi/save') }}" id="form-lke-renaksi" method="post">
                @csrf
                <input type="hidden" name="lke_renaksi_id" id="lke_renaksi_id">
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
                            <label for="kriteria" class="form-label">Kriteria  <span class="text-danger">*</span></label> 
                            <input type="text" id="kriteria" name="kriteria" class="form-control" placeholder="Kriteria" required />
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="parent_id" class="form-label">Parent </label> 
                            <select id="parent_id" name="parent_id" class="form-control">
                                @foreach ($datalke as $dd)
                                <option value="{{ $dd->id }}">{{ $dd->kriteria }}</option>
                                @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="info" class="form-label">Info </label> 
                            <textarea id="info" name="info" class="form-control" placeholder="Info" rows="20"></textarea>
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
    window.rowdata = new DataTable('#data-lke');
});
window.modal_lke_renaksi = tailwind.Modal.getInstance(document.querySelector("#modal-form-lke-renaksi"));
window.showform = function(th) {
    const id = $(th).data('id');
    let kriteria = '';
    let parent = '';
    let info = '';
    let tahun = '';
    if (id!=undefined) {
        $('#modal-form-lke-renaksi').find('input[name=lke_renaksi_id]').val(id);
        const rows = window.rowdata.rows().data();
        let selected;
        for (let n=0; n<rows.length; n++) {
            if (rows[n][0]==id) {
                selected = rows[n];
            }
        }
        if (selected!=undefined) {
            kriteria = selected[1];
            parent = selected[2];
            info = selected[3];
            tahun = selected[4];
        }
    }
    $('#modal-form-lke-renaksi').find('select[name=tahun]').val(tahun);
    $('#modal-form-lke-renaksi').find('input[name=kriteria]').val(kriteria);
    $('#modal-form-lke-renaksi').find('select[name=parent_id]').val(parent);
    $('#modal-form-lke-renaksi').find('textarea[name=info]').val(info);
    window.modal_lke_renaksi.show();
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
                url: "{{url('master-data/data-lke-renaksi/delete')}}",
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
