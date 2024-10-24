@extends('layout.rubick')
@section('title', 'LKE Parameter')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> LKE Parameter</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-lke_parameter">Tambah LKE Parameter</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="lke_parameter" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Nama Parameter</th>
                        <th class="w-20">Level</th>
                        <th class="w-20">Parent</th>
                        <th class="w-10">Tahun</th>
                        <th>Bobot</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-lke_parameter" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Tambah LKE Parameter</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('master-data/lke_parameter/simpan') }}" id="form-lke_parameter" method="post">
                @csrf
                <input type="hidden" name="lke_parameter_id" id="lke_parameter_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12"> 
                        <div class="form-group">
                            <label for="tahun" class="form-label mt-2">Tahun <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="tahun" placeholder="Tahun" class="form-control tahun" required>
                        </div>
                        <div class="form-group">
                            <label for="level" class="form-label mt-2">Level <span class="text-danger">*</span></label>
                            {!! Form::select('level', level(), null, ['class' => 'w-full', 'id' => 'level', 'data-placeholder' => 'Pilih Level Parameter', 'required', 'onchange' => 'cekLevel();']) !!}
                        </div>
                        <div class="form-group" id="komponen-form">
                            <label for="komponen" class="form-label mt-2">Komponen <span class="text-danger">*</span></label>
                            {!! Form::select('komponen', parameter('komponen'), null, ['class' => 'w-full', 'id' => 'komponen', 'data-placeholder' => 'Pilih Komponen', 'onchange' => 'getSubKomponen();']) !!}
                        </div>
                        <div class="form-group" id="subkomponen-form">
                            <label for="subkomponen" class="form-label mt-2">Sub Komponen <span class="text-danger">*</span></label>
                            {!! Form::select('subkomponen', [], null, ['class' => 'w-full', 'id' => 'subkomponen', 'data-placeholder' => 'Pilih Komponen']) !!}
                        </div>
                        <div id="lke_parameter_input">
                            <div class="form-group">
                                <label for="nama" class="form-label mt-2">Nama Parameter <span class="text-danger">*</span></label> 
                                <textarea id="nama" name="nama" class="form-control" placeholder="Nama Parameter" required></textarea>
                            </div>
                            <div class="mt-5">
                                <hr class="mb-5">
                                <label>Pengguna LKE Parameter</label>
                                <div class="form-check mt-2">
                                    <input id="kl" name="kl" class="form-check-input" type="checkbox" value="1" onchange="cekPengguna();">
                                    <label class="form-check-label" for="kl" name="kl">Kementrian / Lembaga</label>
                                </div>
                                <div id="kl-form">
                                    <div class="form-group">
                                        <label for="kl_bobot" class="form-label mt-2">Bobot</label>
                                        <input type="text" name="kl_bobot" id="kl_bobot" placeholder="Bobot" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="kl_target_baik" class="form-label mt-2">Target Baik</label>
                                        <input type="text" name="kl_target_baik" id="kl_target_baik" placeholder="Target Baik" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="kl_min_value" class="form-label mt-2">Minimal</label>
                                        <input type="text" name="kl_min_value" id="kl_min_value" placeholder="Minimal" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="kl_max_value" class="form-label mt-2">Maksimal</label>
                                        <input type="text" name="kl_max_value" id="kl_max_value" placeholder="Maksimal" class="form-control digit">
                                    </div>
                                </div>
                                <div class="form-check mt-2">
                                    <input id="provinsi" name="provinsi" class="form-check-input" type="checkbox" value="1" onchange="cekPengguna();">
                                    <label class="form-check-label" for="provinsi" name="provinsi">Provinsi</label>
                                </div>
                                <div id="provinsi-form">
                                    <div class="form-group">
                                        <label for="provinsi_bobot" class="form-label mt-2">Bobot</label>
                                        <input type="text" name="provinsi_bobot" id="provinsi_bobot" placeholder="Bobot" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="provinsi_target_baik" class="form-label mt-2">Target Baik</label>
                                        <input type="text" name="provinsi_target_baik" id="provinsi_target_baik" placeholder="Target Baik" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="provinsi_min_value" class="form-label mt-2">Minimal</label>
                                        <input type="text" name="provinsi_min_value" id="provinsi_min_value" placeholder="Minimal" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="provinsi_max_value" class="form-label mt-2">Maksimal</label>
                                        <input type="text" name="provinsi_max_value" id="provinsi_max_value" placeholder="Maksimal" class="form-control digit">
                                    </div>
                                </div>
                                <div class="form-check mt-2">
                                    <input id="kabupaten" name="kabupaten" class="form-check-input" type="checkbox" value="1" onchange="cekPengguna();">
                                    <label class="form-check-label" for="kabupaten" name="kabupaten">Kabupaten / Kota</label>
                                </div>
                                <div id="kabupaten-form">
                                    <div class="form-group">
                                        <label for="kabupaten_bobot" class="form-label mt-2">Bobot</label>
                                        <input type="text" name="kabupaten_bobot" id="kabupaten_bobot" placeholder="Bobot" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="kabupaten_target_baik" class="form-label mt-2">Target Baik</label>
                                        <input type="text" name="kabupaten_target_baik" id="kabupaten_target_baik" placeholder="Target Baik" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="kabupaten_min_value" class="form-label mt-2">Minimal</label>
                                        <input type="text" name="kabupaten_min_value" id="kabupaten_min_value" placeholder="Minimal" class="form-control digit">
                                    </div>
                                    <div class="form-group">
                                        <label for="kabupaten_max_value" class="form-label mt-2">Maksimal</label>
                                        <input type="text" name="kabupaten_max_value" id="kabupaten_max_value" placeholder="Maksimal" class="form-control digit">
                                    </div>
                                </div>
                            </div>
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
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script>
    var idx = 0;
    $(document).ready(function() {
        getData();
        modal_lke_parameter = tailwind.Modal.getInstance(document.querySelector("#modal-lke_parameter"));
        
        $(".digit").inputmask("decimal",{
            radixPoint:",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            min: 0,
        });
        
        $(".tahun").inputmask("2029");

        $('#form-lke_parameter').validate({
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
                            Swal.fire('Selamat!', 'Data LKE Parameter berhasil disimpan!', 'success');
                            modal_lke_parameter.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data LKE Parameter gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_lke_parameter.hide();
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

    var lke_parameter = $('#lke_parameter').DataTable( {
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
            { data: 'nama' },
            { data: 'level' },
            { data: 'parent.nama' },
            { data: 'tahun' },
            { data: 'bobot' },
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
        lke_parameter.ajax.url("{{url('master-data/lke_parameter/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#komponen-form').hide();
        $('#subkomponen-form').hide();
        $('#kl-form').hide();
        $('#provinsi-form').hide();
        $('#kabupaten-form').hide();
        $('#form-lke_parameter').trigger('reset');
        $('#lke_parameter_id').val('');
    }

    function tambah() {
        clearForm();
        tahun = {{ date('Y') }};
        $("#tahun").val(tahun);
        $('.saveButton').prop('disabled', false);
        cekLevel();
        cekPengguna();
        modal_lke_parameter.show();
    }

    function cekPengguna() {
        $('#kl-form').hide();
        $('#provinsi-form').hide();
        $('#kabupaten-form').hide();
        kl = $('#kl').is(':checked');
        provinsi = $('#provinsi').is(':checked');
        kabupaten = $('#kabupaten').is(':checked');
        if (kl) {
            $('#kl-form').show();
        }
        if (provinsi) {
            $('#provinsi-form').show();
        }
        if (kabupaten) {
            $('#kabupaten-form').show();
        }
    }
    
    function cekLevel() {
        $('#komponen-form').hide();
        $('#subkomponen-form').hide();
        $('#komponen').prop('required', false);
        $('#subkomponen').prop('required', false);
        level = $('#level').val();
        if (level == 'Sub Komponen') {
            $('#komponen-form').show();
            $('#komponen').prop('required', true);
        } else if (level == 'Indikator') {
            $('#komponen-form').show();
            $('#subkomponen-form').show();
            $('#komponen').prop('required', true);
            $('#subkomponen').prop('required', true);
            getSubKomponen();
        }
    }

    function getSubKomponen() {
        komponen = $('#komponen').val();
        $.get("{{url('master-data/lke_parameter/getSubKomponen')}}/"+komponen, function(data) {
            $('#subkomponen').html(data);
        });
    }

    function edit(id) {
        clearForm();
        $('#lke_parameter_id').val(id);
        $('#title').html('Edit LKE Parameter');
        $('.saveButton').prop('disabled', true);
        $.getJSON("{{url('master-data/lke_parameter/getData')}}/"+id, function(data) {
            $('#tahun').val(data.tahun);
            $('#level').val(data.level);
            $('#nama').val(data.nama);
            if (data.level == 'Sub Komponen') {
                $('#komponen-form').show();
                $('#komponen').prop('required', true);
                $('#komponen').val(data.komponen_id);
            } else if (data.level == 'Indikator') {
                $('#komponen-form').show();
                $('#komponen').val(data.komponen_id);
                $('#subkomponen-form').show();
                $('#subkomponen').html(data.subkomponens);
                $('#subkomponen').val(data.subkomponen_id);
                $('#komponen').prop('required', true);
                $('#subkomponen').prop('required', true);
            }
            data.bobots.forEach(bobot => {
                if (bobot.group == 'kl') {
                    $('#kl').prop('checked', true);
                    $('#kl_bobot').val(bobot.bobot);
                    $('#kl_target_baik').val(bobot.target_baik);
                    $('#kl_min_value').val(bobot.min_value);
                    $('#kl_max_value').val(bobot.max_value);
                }
                if (bobot.group == 'provinsi') {
                    $('#provinsi').prop('checked', true);
                    $('#provinsi_bobot').val(bobot.bobot);
                    $('#provinsi_target_baik').val(bobot.target_baik);
                    $('#provinsi_min_value').val(bobot.min_value);
                    $('#provinsi_max_value').val(bobot.max_value);
                }
                if (bobot.group == 'kabupaten') {
                    $('#kabupaten').prop('checked', true);
                    $('#kabupaten_bobot').val(bobot.bobot);
                    $('#kabupaten_target_baik').val(bobot.target_baik);
                    $('#kabupaten_min_value').val(bobot.min_value);
                    $('#kabupaten_max_value').val(bobot.max_value);
                }
                cekPengguna();
            });
            $('.saveButton').prop('disabled', false);
            modal_lke_parameter.show();
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus LKE Parameter ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('master-data/lke_parameter/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data LKE Parameter berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data LKE Parameter gagal dihapus! Coba lagi nanti ya..', 'error');
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
