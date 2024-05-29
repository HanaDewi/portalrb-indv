@extends('layout.rubick')
@section('title', 'Dokumen')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> Dokumen</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-tema">Tambah Dokumen</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="tabel-dokumen" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w-5">Tahun</th>
                        <th>Nama Kategori Dokumen</th>
                        <th>File Dokumen</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form Dokumen --}}
<div id="modal-dokumen" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Tambah Dokumen
                </h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('dokumen/simpan') }}" id="form-dokumen" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table">
                            <tr>
                                <td class="font-bold" width="230">Tahun <span class="text-danger">*</span></td>
                                <td>
                                    <div class="form-group">
                                        {!! Form::select('tahun', tahun(), null, ['class' => 'w-full mt-2', 'id' => 'tahun', 'placeholder' => 'Pilih Tahun', 'required', 'onchange' => 'getFile();']) !!}
                                        <input type="hidden" name="tahun_edit" id="tahun_edit">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold" width="230">Kategori <span class="text-danger">*</span></td>
                                <td>
                                    <div class="form-group">
                                        {!! Form::select('kategori_id', dokumen_kategori(), null, ['class' => 'w-full mt-2', 'id' => 'kategori_id', 'placeholder' => 'Pilih Kategori Dokumen', 'required', 'onchange' => 'getFile();']) !!}
                                        <input type="hidden" name="kategori_id_edit" id="kategori_id_edit">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold w-44">Dokumen <span class="text-danger">*</span></td>
                                <td>
                                    <img src="{{ asset('images/loader.gif') }}" style="display: none;" class="loader">
                                    <div id="dokumen_tambah" style="display: none;">
                                        <button type="button" class="btn btn-info btn-sm" onclick="pilih_dokumen();"><i class="fa fa-plus"></i> Tambah Dokumen</button>
                                        <input type="file" id="dokumen" style="display: none;">
                                        <div id="dokumen_list" class="intro-y grid grid-cols-12 gap-6 mt-5"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end">
                    <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 me-1">Batal</button>
                    <button type="submit" class="btn btn-primary w-20 saveButton">Simpan</button>
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
    var idx = {{ $idx }};
    $(document).ready(function() {
        getData();
        modal_dokumen = tailwind.Modal.getInstance(document.querySelector("#modal-dokumen"));

        $('#form-dokumen').validate({
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
                filenya = $('.filenya').length;
                if (filenya > 0) {
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
                                Swal.fire('Selamat!', 'Data Dokumen berhasil disimpan!', 'success');
                                modal_dokumen.hide();
                            } else {
                                Swal.fire('Aduh!', 'Data Dokumen gagal disimpan! Coba lagi nanti ya..', 'error');
                                modal_dokumen.hide();
                            }
                            getData();
                        },
                        error: function(err) {
                            Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                            $('.saveButton').prop('disabled', false);
                        }
                    });
                } else {
                    Swal.fire('Error!', 'Filenya mana?')
                }
            }
        });

        $('#dokumen').change(function() {
            cek_dokumen(this);
        });
    });

    var dokumen_tabel = $('#tabel-dokumen').DataTable( {
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
            { data: 'nama_kategori' },
            { data: 'filenya' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.tahun+', '+row.kategori_id+');" class="btn btn-warning btn-sm w-10">Edit</button><button onclick="hapus('+row.id+');" class="btn btn-danger btn-sm w-10">Hapus</button>';
                },
            },
        ],
    }); 

    function getData() {
        dokumen_tabel.ajax.url("{{url('dokumen/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#tahun').val('');
        $('#kategori_id').val('');
        $('#tahun_edit').val('');
        $('#kategori_id_edit').val('');
        $('#tahun').prop('disabled', false);
        $('#kategori_id').prop('disabled', false);
        $('#form-dokumen').trigger('reset');
        $('#kategori_id').val('');
        $('#dokumen_tambah').hide();
        $('#dokumen_list').html('');
        $('.loader').hide();
        $('.saveButton').prop('disabled', false);
    }

    function tambah() {
        clearForm();
        modal_dokumen.show();
    }

    function edit(tahun, kategori_id) {
        clearForm();
        $('#tahun').val(tahun);
        $('#kategori_id').val(kategori_id);
        $('#tahun_edit').val(tahun);
        $('#kategori_id_edit').val(kategori_id);
        $('#tahun').prop('disabled', true);
        $('#kategori_id').prop('disabled', true);
        $('#title').html('Edit Dokumen');
        $('.saveButton').prop('disabled', true);
        modal_dokumen.show();
        $.getJSON("{{url('dokumen/getData')}}/"+tahun+"/"+kategori_id, function(data) {
            $('#dokumen_list').html(data.dokumen_list);
            $('#dokumen_tambah').show();
            modal_dokumen.show();
            $('.saveButton').prop('disabled', false);
        });
    }

    function getFile() {
        tahun = $('#tahun').val();
        kategori_id = $('#kategori_id').val();
        $('#dokumen_tambah').hide();
        if (tahun && kategori_id) {
            $('.loader').show();
            $.getJSON("{{url('dokumen/getData')}}/"+tahun+"/"+kategori_id, function(data) {
                $('#dokumen_list').html(data.dokumen_list);
                $('.loader').hide();
                $('#dokumen_tambah').show();
            });
        }
    }
    
    function pilih_dokumen() {
        $('#dokumen').trigger('click');
    }

    function isAllowed(ext) {
        switch (ext.toLowerCase()) {
            case 'xlsx':
            case 'xls':
            case 'docx':
            case 'doc':
            case 'pptx':
            case 'ppt':
            case 'pdf':
            return true;
        }
        return false;
    }

    function cek_dokumen(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                filename = $('#dokumen').val();
                newVal = $('#dokumen').next().val();
                var parts = filename.split('.');
                var ext = parts[parts.length - 1];
                var desc = filename.replace("C:\\fakepath\\", "");
                var desc = desc.replace("."+ext, "");
                if (!isAllowed(ext)) {
                    Swal.fire("Perhatian", "File yang di input tidak sesuai ketentuan (pdf, word, excel, power point).", "error");
                } else if (input.files[0].size > 5242880) {
                    Swal.fire("Maaf!", "File yang di input tidak boleh lebih dari 5MB.", "error");
                    $(input).val('');
                } else {
                    src = ext.toLowerCase() == 'pdf' ? "{{asset('images/pdf.png')}}" : (ext.toLowerCase() == 'xls' || ext.toLowerCase() == 'xlsx' ? "{{asset('images/excel.png')}}" : (ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx' ? "{{asset('images/word.png')}}" : (ext.toLowerCase() == 'ppt' || ext.toLowerCase() == 'pptx' ? "{{asset('images/ppt.png')}}" : e.target.result)));
                    console.log(src, ext.toLowerCase());
                    dokumen = $('#dokumen').clone();
                    dokumen.attr('name', 'dokumen['+idx+']');
                    dokumen.attr('id', 'dokumen'+idx);
                    dokumen_div = '<div class="col-span-12 lg:col-span-4" id="dokumendiv'+idx+'" style="position:relative;">'+
                            '<div style="height: 100px;">'+
                                '<img class="img-fluid card-img-top" src="'+src+'" alt="File'+idx+'" style="max-height: 100px; max-width:100%; padding: 5px 0;">'+
                            '</div>'+
                            '<div class="form-group mb-0">'+
                                '<input type="text" name="deskripsi['+idx+']" class="form-control filenya" id="deskripsi'+idx+'" placeholder="Deskripsi" value="'+desc+'" required>'+
                            '</div>'+
                            '<a href="javascript:void(0);" onclick="removeFile('+idx+')" class="remove-button text-danger">'+
                                '<div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </div>'
                            '</a>'+
                    '</div>';
                    $('#dokumen_list').append(dokumen_div);
                    $('#dokumen_list').append(dokumen);
                    idx++;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeFile(id) {
        Swal.fire({
            title: "Yakin?",
            text: "dokumen-nya mau di hapus?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#dokumendiv'+id).remove();
                $('#dokumen'+id).remove();
            }
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Dokumen ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('dokumen/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data Dokumen berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Dokumen gagal dihapus! Coba lagi nanti ya..', 'error');
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
