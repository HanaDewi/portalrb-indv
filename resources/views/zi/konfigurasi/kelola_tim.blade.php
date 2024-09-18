@extends('zi.admin.rubick')
@section('title',$title)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Kelola Tim Evaluasi</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal"
                data-bs-target="#modal-kelola-tim"><i class="fa fa-add"></i> &nbsp; Tambah Tim Evaluasi</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="tim_evaluasi" class="table table-bordered table-striped table-hover" cellspacing="0"
                width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Nama Tim</th>
                        <th>Keterangan</th>
                        <th class="w-30">Aksi</th>
                    </tr>
                </thead>
                <tbody class>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-kelola-tim" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header text-white font-bold" style="background: #DC2626">
                <h2 class="fw-medium fs-base me-auto" id="title">Tambah Tim</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ route('kelola_tim_zi_simpan') }}" id="form-kelola-tim" method="post">
                @csrf
                <input type="hidden" name="tim_id" id="tim_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Tim <span class="text-danger">*</span></label>
                            <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Tim"
                                required>
                        </div>
                        <br />
                        <div class="form-group">
                            <label for="nama" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan"
                                required></textarea>
                        </div>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end">
                    <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 me-1">Batal</button>
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
        modal_kelola_tim = tailwind.Modal.getInstance(document.querySelector("#modal-kelola-tim"));
        
        $('#form-kelola-tim').validate({
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
                            Swal.fire('Selamat!', 'Data Tim berhasil disimpan!', 'success');
                            modal_kelola_tim.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Tim gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_kelola_tim.hide();
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

    var tim_evaluasi = $('#tim_evaluasi').DataTable( {
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
            { data: 'keterangan' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.id+');" class="btn btn-warning"><i class="fa fa-edit"></i> &nbsp; Edit</button> &nbsp; <button onclick="hapus('+row.id+');" class="btn btn-danger"> <i class="fa fa-trash"></i> &nbsp; Hapus</button>';
                },
            },
        ],
        columnDefs: [
            {
                "targets": 3, // your case first column
                "className": "text-center",
                "width": "20%"
            },
            
        ],
    }); 

    function getData() {
        tim_evaluasi.ajax.url("{{route('getData_timEvaluasi')}}").load(null, false);
    }

    function clearForm() {
        $('#form-kelola-tim').trigger('reset');
        $('#tim_id').val('');
    }

    function tambah() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        modal_kelola_tim.show();
    }

    function edit(id) {
        clearForm();
        $('#tim_id').val(id);
        $('#title').html('Edit Tim Evaluasi');
        $('.saveButton').prop('disabled', true);
        modal_kelola_tim.show();
        $.getJSON("{{url('/zi/kelola-tim/getData')}}/"+id, function(data) {
            $('#nama').val(data.nama);
            $('#keterangan').val(data.keterangan);
            $('.saveButton').prop('disabled', false);
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Tim Evaluasi ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/zi/kelola-tim/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        console.log(terhapus);
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data Tim Evaluasi berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Tim Evaluasi gagal dihapus! '+terhapus.pesan, 'error');
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