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
            <table id="tabel-user" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
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

<div id="modal-user" class="modal fade" tabindex="-1" aria-hidden="true" data-tw-backdrop="static" data-tw-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="fw-medium fs-base me-auto" id="title">Tambah User</h1>
            </div> 
            <form action="{{ url('manage-user/simpan') }}" id="form-user" method="post">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <div class="form-group">
                            <label for="idusername" class="form-label">Username  <span class="text-danger">*</span></label> 
                            <input id="idusername" type="text" name="username" class="form-control" placeholder="Username" required onfocus="$(this).removeAttr('readonly');" readonly/>
                        </div> 
                        <div class="form-group">
                            <label for="idemail" class="form-label">Email  <span class="text-danger">*</span></label> 
                            <input id="idemail" type="email" name="email" class="form-control" placeholder="Email" required />
                        </div> 
                        <div class="form-group">
                            <label for="idnama" class="form-label">Nama  <span class="text-danger">*</span></label> 
                            <input id="idnama" type="text" name="nama" class="form-control" placeholder="Nama" required />
                            <div class="mt-4 edit-info" hidden>
                                <p style="font-size:10pt;font-style:italic;">Kosongkan password jika tidak akan diedit</p>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="idpassword" class="form-label">Password  <span class="text-danger">*</span></label> 
                            <div class="input-group">
                                <input id="idpassword" type="password" name="pwda" class="form-control" placeholder="Password" autocomplete="" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePWD(this)"><span class="fa fa-eye-slash"></span> &nbsp; </button>
                                </div>
                            </div>
                            <div class="password-requirements mb-4" style="font-size:10pt;">
                                <p class="requirement" id="length">Min. 8 characters</p>
                                <p class="requirement" id="lowercase">Include lowercase letter</p>
                                <p class="requirement" id="uppercase">Include uppercase letter</p>
                                <p class="requirement" id="number">Include number</p>
                                <p class="requirement" id="characters">Include a special character: #.-?!@$%^&*</p>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="idpassword_" class="form-label">Password lagi  <span class="text-danger">*</span></label> 
                            <div class="input-group">
                                <input id="idpassword_" type="password" name="pwdb" class="form-control" placeholder="Password lagi" placeholder=""/>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePWD(this)"><span class="fa fa-eye-slash"></span> &nbsp; </button>
                                </div>
                            </div>
                            <div class="password-requirements mb-4" style="font-size:10pt;">
                                <p class="requirement" id="confirmpwd">Password tidak sama</p>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="idlevel" class="form-label">Level  <span class="text-danger">*</span></label> 
                            <select id="idlevel" name="level" class="form-control" placeholder="Level">
                                <option value="kabupaten">Kabupaten</option>
                                <option value="provinsi">Provinsi</option>
                                <option value="kl">KL</option>
                                <option value="tpm">TPM</option>
                                <option value="tpn">TPN</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label for="idinstansi" class="form-label">Instansi  <span class="text-danger"></span></label> 
                            {!! Form::select('instansi_id', instansis(), '', ['class' => 'tom-select mt-1', 'id' => 'idinstansi', 'data-placeholder' => 'Pilih Instansi', '']) !!}
                        </div> 
                        <div class="form-group">
                            <label for="idpenilai" class="form-label">Penilai  <span class="text-danger"></span></label> 
                            {!! Form::select('penilai_id', timpenilai(), '', ['class' => 'tom-select mt-1', 'id' => 'idpenilai', 'data-placeholder' => 'Pilih Tim Penilai', '']) !!}
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
<style type="text/css">
    .password-requirements .requirement {color:red}
</style>
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
                // console.log(error, placement);
            },
            submitHandler: function(form) {
                if (!validate()) {
                    return;
                }
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
                            Swal.fire('Selamat!', 'Data user berhasil disimpan!', 'success');
                            modal_user.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data user gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_user.hide();
                        }
                        datauser.ajax.reload();
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                        $('.saveButton').prop('disabled', false);
                    }
                });
            }
        });

        document.getElementById('idpassword').addEventListener("input", (event) => {
            const value = event.target.value;
            window.validLength = value.length >= 8;
            window.validLCase = /[a-z]/.test(value);
            window.validUCase =/[A-Z]/.test(value);
            window.validNum = /\d/.test(value);
            window.validChr = /[#.?!@$%^&*-]/.test(value);
            if (window.validLength)
                $('#length').css('color', 'blue');
            else
                $('#length').css('color', 'red');
            if (window.validLCase)
                $('#lowercase').css('color', 'blue');
            else
                $('#lowercase').css('color', 'red');
            if (window.validUCase)
                $('#uppercase').css('color', 'blue');
            else
                $('#uppercase').css('color', 'red');
            if (window.validNum)
                $('#number').css('color', 'blue');
            else
                $('#number').css('color', 'red');
            if (window.validChr)
                $('#characters').css('color', 'blue');
            else
                $('#characters').css('color', 'red');
            $('#idpassword_').trigger('change');
        });
        document.getElementById('idpassword_').addEventListener("input", (event) => {
            const value = event.target.value;
            const pval = document.getElementById('idpassword').value;
            window.validConfirm = value==pval;
            if (window.validConfirm)
                $('#confirmpwd').css('color', 'blue');
            else
                $('#confirmpwd').css('color', 'red');
        });
    });

    function validate() {
        const pval = document.getElementById('idpassword').value;
        const cpval = document.getElementById('idpassword_').value;
        return (window.validLength===true 
            && window.validLCase===true 
            && window.validUCase===true 
            && window.validNum===true 
            && window.validChr==true 
            && window.validConfirm===true) || (window.editMode===true && pval=='' && cpval=='');
    }

    const datauser = $('#tabel-user').DataTable( {
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
        datauser.ajax.url("{{url('manage-user/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#form-kegiatan_utama').trigger('reset');
        $('#tabel-user_id').val('');
    }

    function tambah() {
        window.editMode = false;
        clearForm();
        $('.edit-info').hide();
        $('.saveButton').prop('disabled', false);
        modal_user.show();
        setTimeout(()=>$('#idusername').removeAttr('readonly'), 100);
    }

    function edit(id) {
        window.editMode = true;
        clearForm();
        $('input[name=id]').val(id);
        $('#title').html('Edit User');
        $('.saveButton').prop('disabled', true);
        $('.edit-info').show();
        modal_user.show();
        $.getJSON("{{url('manage-user/getData')}}/"+id, function(data) {
            $('input[name=username]').val(data.username);
            $('input[name=email]').val(data.email);
            $('input[name=nama]').val(data.nama);
            $('select[name=level]').val(data.level);
            document.getElementById('idinstansi').tomselect.setValue(data.instansi_id);
            document.getElementById('idpenilai').tomselect.setValue(data.penilai_id);
            $('.saveButton').prop('disabled', false);
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus data user ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('manage-user/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        console.log(terhapus);
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data user berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data user gagal dihapus! '+terhapus.pesan, 'error');
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

    function togglePWD(th) {
        if ($(th).parent().siblings('input').attr('type')=='password') {
            $(th).parent().siblings('input').attr('type','text');
            $(th).find('span').attr('class', 'fa fa-eye');
        } else {
            $(th).parent().siblings('input').attr('type','password');
            $(th).find('span').attr('class', 'fa fa-eye-slash');
        }
    }
</script>
@endpush
