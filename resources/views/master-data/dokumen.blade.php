@extends('layout.rubick')
@section('title', 'Dokumen')

@section('content')
<div class="intro-y col-span-4 lg:col-span-4">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Tahun</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambahTahun();" data-bs-toggle="modal" data-bs-target="#modal-tema">Tambah Tahun</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="tabel-tahun" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Tahun</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="intro-y col-span-8 lg:col-span-8">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Kategori Dokumen</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambahKategori();" data-bs-toggle="modal" data-bs-target="#modal-tema">Tambah Kategori Dokumen</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="tabel-kategori" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Nama Kategori Dokumen</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-tahun" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Tambah Tahun</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('master-data/dokumen/simpanTahun') }}" id="form-tahun" method="post">
                @csrf
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="nama" class="form-label">Tahun <span class="text-danger">*</span></label> 
                            <input type="text" name="tahun" id="tahun" class="form-control tahun">
                        </div> 
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end"> 
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Batal</button> 
                    <button type="submit" class="btn btn-success w-20 saveButton">Simpan</button> 
                </div> <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div> <!-- END: Modal Content -->

<div id="modal-kategori" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title-kategori">Tambah Kategori Dokumen</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('master-data/dokumen/simpanKategori') }}" id="form-kategori" method="post">
                @csrf
                <input type="hidden" name="kategori_id" id="kategori_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Kategori Dokumen <span class="text-danger">*</span></label> 
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Kategori Dokumen" required>
                        </div> 
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end"> 
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Batal</button> 
                    <button type="submit" class="btn btn-success w-20 saveButton">Simpan</button> 
                </div> <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div> <!-- END: Modal Content -->
@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function() {
        getDataTahun();
        getDataKategori();
        modal_tahun = tailwind.Modal.getInstance(document.querySelector("#modal-tahun"));
        modal_kategori = tailwind.Modal.getInstance(document.querySelector("#modal-kategori"));

        $('.tahun').inputmask('2099');
        
        $('#form-tahun').validate({
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
                            Swal.fire('Selamat!', 'Data Tahun berhasil disimpan!', 'success');
                            modal_tahun.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Tahun gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_tahun.hide();
                        }
                        getDataTahun();
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                        $('.saveButton').prop('disabled', false);
                    }
                });
            }
        });
        
        $('#form-kategori').validate({
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
                            Swal.fire('Selamat!', 'Data Kategori Dokumen berhasil disimpan!', 'success');
                            modal_kategori.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Kategori Dokumen gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_kategori.hide();
                        }
                        getDataKategori();
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                        $('.saveButton').prop('disabled', false);
                    }
                });
            }
        });
    });

    var tahun = $('#tabel-tahun').DataTable( {
        responsive: true,
        processing: true,
        searching: false,
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
            { data: 'tahun' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="hapusTahun('+row.tahun+');" class="btn btn-danger btn-sm w-10">Hapus</button>';
                },
            },
        ],
    }); 

    function getDataTahun() {
        tahun.ajax.url("{{url('master-data/dokumen/getDataTahun')}}").load(null, false);
    }

    var kategori = $('#tabel-kategori').DataTable( {
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
            { data: 'nama' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="editKategori('+row.id+');" class="btn btn-warning btn-sm w-10">Edit</button><button onclick="hapusKategori('+row.tahun+');" class="btn btn-danger btn-sm w-10">Hapus</button>';
                },
            },
        ],
    }); 

    function getDataKategori() {
        kategori.ajax.url("{{url('master-data/dokumen/getDataKategoris')}}").load(null, false);
    }

    function clearForm() {
        $('#form-tahun').trigger('reset');
        $('#form-kategori').trigger('reset');
        $('#kategori_id').val('');
    }

    function tambahTahun() {
        clearForm();
        modal_tahun.show();
    }

    function hapusTahun(tahun) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Tahun ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('master-data/dokumen/hapusTahun')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', tahun: tahun},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data Tahun berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Tahun gagal dihapus! Coba lagi nanti ya..', 'error');
                        }
                        getDataTahun();
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                    }
                });
            }
        });
    }

    function tambahKategori() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        modal_kategori.show();
    }

    function editKategori(id) {
        clearForm();
        $('#kategori_id').val(id);
        $('#title-kategori').html('Edit Kategori');
        $('.saveButton').prop('disabled', true);
        modal_kategori.show();
        $.getJSON("{{url('master-data/dokumen/getDataKategori')}}/"+id, function(data) {
            $('#kategori').val(data.kategori);
            $('.saveButton').prop('disabled', false);
        });
    }

    function hapusKategori(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Kategori Dokumen ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('master-data/dokumen/hapusTahun')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data Kategori Dokumen berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Kategori Dokumen gagal dihapus! Coba lagi nanti ya..', 'error');
                        }
                        getDataTahun();
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
