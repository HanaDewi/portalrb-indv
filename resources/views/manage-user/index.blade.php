@extends('layout.rubick')
@section('title', 'Kegiatan Utama')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Kelola User</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-user">Tambah User</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="kegiatan_utama" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Instansi</th>
                        <th>Penilai</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-user" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Tambah User</h2>
            </div> 
            <form action="{{ url('manage-user/simpan') }}" id="form-user" method="post">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Kegiatan Utama  <span class="text-danger">*</span></label> 
                            <textarea id="nama" name="nama" class="form-control" placeholder="Nama Kegiatan Utama" required></textarea>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer text-end"> 
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Batal</button> 
                    <button type="submit" class="btn btn-success w-20 saveButton">Simpan</button> 
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function() {
        getData();

        modal_user = tailwind.Modal.getInstance(document.querySelector("#modal-user"));
        
        $('#form-user').validate({
            highlight: function (input) {
                $(input).addClass('border-danger');
            },
            unhighlight: function (input) {
                $(input).removeClass('border-danger');
            },
            errorPlacement: function( error, element ) {
                var placement = element.closest('.form-group');
                if (!placement.get(0)) {
                    placement = element;
                }
                if (error.text() !== '') {
                    placement.append(error);
                }
                console.log(error, placement);
            },
            submitHandler: function(form) {
                $('.saveButton').prop('disabled', true);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(data) {
                        $('.saveButton').prop('disabled', false);
                        if (data.success) {
                            Swal.fire('Selamat!', 'Data Kegiatan Utama berhasil disimpan!', 'success');
                            modal_kegiatan_utama.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Kegiatan Utama gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_kegiatan_utama.hide();
                        }
                        getData();
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                        $('.saveButton').prop('disabled', false);
                    }
                });
            }
        });
    });

    var kegiatan_utama = $('#kegiatan_utama').DataTable( {
        responsive: true,
        processing: true,
        ajax: {
            url: "{{url('emptyDT')}}",
        },
        columns: [
            {
                data: null,
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'username' },
            { data: 'nama' },
            { data: 'email' },
            { data: 'level' },
            { 
                render: function (data, type, row, meta) {
                    if (row.user_rel && row.user_rel.instansi && row.user_rel.instansi.name) {
                        return row?.user_rel?.instansi?.name;
                    }
                    return '';
                },
            },
            { data: 'penilai.name' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.id+');" class="btn btn-warning btn-sm w-10">Edit</button><button onclick="hapus('+row.id+');" class="btn btn-danger btn-sm w-10">Hapus</button>';
                },
            },
        ],
    }); 

    function getData() {
        kegiatan_utama.ajax.url("{{url('manage-user/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#form-kegiatan_utama').trigger('reset');
        $('#kegiatan_utama_id').val('');
    }

    function tambah() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        modal_user.show();
    }

    function edit(id) {
        clearForm();
        $('#kegiatan_utama_id').val(id);
        $('#title').html('Edit Kegiatan Utama');
        $('.saveButton').prop('disabled', true);
        modal_kegiatan_utama.show();
        $.getJSON("{{url('master-data/kegiatan_utama/getData')}}/"+id, function(data) {
            $('#nama').val(data.nama);
            $('.saveButton').prop('disabled', false);
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Kegiatan Utama ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('master-data/kegiatan_utama/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        console.log(terhapus);
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data Kegiatan Utama berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Kegiatan Utama gagal dihapus! '+terhapus.pesan, 'error');
                        }
                        getData();
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
