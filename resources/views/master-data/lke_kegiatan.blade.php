@extends('layout.rubick')
@section('title', 'LKE Kegiatan')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> LKE Kegiatan</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-lke_kegiatan">Tambah LKE Kegiatan</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="lke_kegiatan" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Tahun</th>
                        <th>Nama LKE Kegiatan</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-lke_kegiatan" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Tambah LKE Kegiatan</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('master-data/lke_kegiatan/simpan') }}" id="form-lke_kegiatan" method="post">
                @csrf
                <input type="hidden" name="lke_kegiatan_id" id="lke_kegiatan_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="tahun" class="form-label">Tahun Kegiatan  <span class="text-danger">*</span></label> 
                            <input type="text" name="tahun" id="tahun" class="form-control" placeholder="Tahun Kegiatan" required>
                        </div> 
                    </div>
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama LKE Kegiatan  <span class="text-danger">*</span></label> 
                            <textarea id="nama" name="nama" class="form-control" placeholder="Nama LKE Kegiatan" required></textarea>
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
        getData();
        $("#tahun").inputmask('2099');
        modal_lke_kegiatan = tailwind.Modal.getInstance(document.querySelector("#modal-lke_kegiatan"));
        
        $('#form-lke_kegiatan').validate({
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
                            Swal.fire('Selamat!', 'Data LKE Kegiatan berhasil disimpan!', 'success');
                            modal_lke_kegiatan.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data LKE Kegiatan gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_lke_kegiatan.hide();
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

    var lke_kegiatan = $('#lke_kegiatan').DataTable( {
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
            { data: 'tahun' },
            { data: 'nama' },
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
        lke_kegiatan.ajax.url("{{url('master-data/lke_kegiatan/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#form-lke_kegiatan').trigger('reset');
        $('#lke_kegiatan_id').val('');
    }

    function tambah() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        modal_lke_kegiatan.show();
    }

    function edit(id) {
        clearForm();
        $('#lke_kegiatan_id').val(id);
        $('#title').html('Edit LKE Kegiatan');
        $('.saveButton').prop('disabled', true);
        modal_lke_kegiatan.show();
        $.getJSON("{{url('master-data/lke_kegiatan/getData')}}/"+id, function(data) {
            $('#tahun').val(data.tahun);
            $('#nama').val(data.nama);
            $('.saveButton').prop('disabled', false);
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus LKE Kegiatan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('master-data/lke_kegiatan/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        console.log(terhapus);
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data LKE Kegiatan berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data LKE Kegiatan gagal dihapus! '+terhapus.pesan, 'error');
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
