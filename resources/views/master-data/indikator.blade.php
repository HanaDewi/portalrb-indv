@extends('layout.rubick')
@section('title', 'Indikator')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Indikator</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-indikator">Tambah Indikator</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="indikator" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Nama Kegiatan Utama</th>
                        <th>Indikator</th>
                        <th class="w-10">Pengguna Indikator</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-indikator" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Tambah Indikator</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('master-data/indikator/simpan') }}" id="form-indikator" method="post">
                @csrf
                <input type="hidden" name="indikator_id" id="indikator_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12"> 
                        <div class="form-group">
                            <label for="kegiatan_utama_id" class="form-label mt-2">Kegiatan Utama <span class="text-danger">*</span></label>
                            {!! Form::select('kegiatan_utama_id', kegiatanUtama(), null, ['class' => 'w-full mt-2', 'id' => 'kegiatan_utama_id', 'data-placeholder' => 'Pilih Kegiatan Utama', 'required']) !!}
                        </div> <!-- END: Basic Select -->
                        <div id="indikator_input">
                            <div class="form-group">
                                <label for="nama" class="form-label mt-2">Nama Indikator <span class="text-danger">*</span></label> 
                                <textarea id="nama0" name="nama[0]" class="form-control" placeholder="Nama Indikator" required></textarea>
                            </div>
                            <div>
                                <label>Pengguna Indikator</label>
                                <div class="form-check mt-2">
                                    <input id="kl0" class="form-check-input" type="checkbox" value="1">
                                    <label class="form-check-label" for="kl0" name="kl[0]">Kementrian / Lembaga</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input id="provinsi0" class="form-check-input" type="checkbox" value="1">
                                    <label class="form-check-label" for="provinsi0" name="provinsi[0]">Provinsi</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input id="kabupaten0" class="form-check-input" type="checkbox" value="1">
                                    <label class="form-check-label" for="kabupaten0" name="kabupaten[0]">Kabupaten / Kota</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary border-dashed w-full tambahinput" onclick="tambahinput();"><i data-lucide="plus" class="w-4 h-4 mr-2"></i></button>
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
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script>
    var idx = 0;
    $(document).ready(function() {
        getData();
        modal_indikator = tailwind.Modal.getInstance(document.querySelector("#modal-indikator"));
        
        $('#form-indikator').validate({
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
                            Swal.fire('Selamat!', 'Data Indikator berhasil disimpan!', 'success');
                            modal_indikator.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Indikator gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_indikator.hide();
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

    var indikator = $('#indikator').DataTable( {
        responsive: true,
        processing: true,
        ordering: false,
        ajax: {
            url: "{{url('emptyDT')}}",
        },
        rowsGroup: [1],
        columns: [
            {
                data: null,
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nama_kegiatan_utama' },
            { data: 'nama' },
            { data: 'pengguna_indikator' },
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
        indikator.ajax.url("{{url('master-data/indikator/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#form-indikator').trigger('reset');
        $('#indikator_id').val('');
    }

    function indikator_input(idx) {
        return '<div class="form-group">'+
                    '<label for="nama'+idx+'" class="form-label mt-2">Nama Indikator <span class="text-danger">*</span></label> '+
                    '<textarea id="nama'+idx+'" name="nama['+idx+']" class="form-control" placeholder="Nama Indikator" required></textarea>'+
                '</div>'+
                '<div class="mt-5 mb-5"><hr class="mb-5">'+
                    '<label>Pengguna Indikator</label>'+
                    '<div class="form-check mt-2">'+
                        '<input id="kl'+idx+'" class="form-check-input" type="checkbox" name="kl['+idx+']" value="1">'+
                        '<label class="form-check-label" for="kl'+idx+'">Kementrian / Lembaga</label>'+
                    '</div>'+
                    '<div class="form-check mt-2">'+
                        '<input id="provinsi'+idx+'" class="form-check-input" type="checkbox" name="provinsi['+idx+']" value="1">'+
                        '<label class="form-check-label" for="provinsi'+idx+'">Provinsi</label>'+
                    '</div>'+
                    '<div class="form-check mt-2">'+
                        '<input id="kabupaten'+idx+'" class="form-check-input" type="checkbox" name="kabupaten['+idx+']" value="1">'+
                        '<label class="form-check-label" for="kabupaten'+idx+'">Kabupaten / Kota</label>'+
                    '</div>'+
                '</div>';
    }

    function tambah() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        $('.tambahinput').show();
        idx = 0;
        $('#indikator_input').html(indikator_input(idx));
        modal_indikator.show();
    }

    function tambahinput() {
        idx++;
        $('#indikator_input').append(indikator_input(idx));
    }

    function edit(id) {
        clearForm();
        $('#indikator_id').val(id);
        $('#title').html('Edit Indikator');
        $('.saveButton').prop('disabled', true);
        $.getJSON("{{url('master-data/indikator/getData')}}/"+id, function(data) {
            console.log(data);
            $('#indikator_input').html(indikator_input(0));
            $('#kegiatan_utama_id').val(data.kegiatan_utama_id);
            $('#nama0').val(data.nama);
            kl_checked = data.kl == 1 ? true : false;
            provinsi_checked = data.provinsi == 1 ? true : false;
            kabupaten_checked = data.kabupaten == 1 ? true : false;
            $('#kl0').prop('checked', kl_checked);
            $('#provinsi0').prop('checked', provinsi_checked);
            $('#kabupaten0').prop('checked', kabupaten_checked);
            $('.tambahinput').hide();
            $('.saveButton').prop('disabled', false);
            modal_indikator.show();
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Indikator ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('master-data/indikator/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data Indikator berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Indikator gagal dihapus! Coba lagi nanti ya..', 'error');
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
