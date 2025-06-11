@extends('zi.admin.rubick')
@section('title',$title)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6  intro-y">
                <div class="box p-5 zoom-in">
                    <div class="flex items-center">
                        <div class="text-lg font-bold truncate">Tahun :
                            <select id="filter-tahun">
                                @for ($i =date('Y'); $i >= 2024; $i--)
                                <option value="{{$i}}" @if($i==$tahun ) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> {{$title}}</h2>
            <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal"
                data-bs-target="#modal-kelola-tim"><i class="fa fa-add"></i> &nbsp; Tambah Jadwal</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="lapor_wbk_mandiri" class="table table-bordered table-striped table-hover" cellspacing="0"
                width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Tahun</th>
                        <th>Tahap Seleksi</th>
                        <th>Link</th>
                        <th>Keterangan</th>
                        <th>Tanggal Update</th>
                        <th width="20%">Aksi</th>
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
            <form action="{{ route('lapor_wbk_mandiri_simpan') }}" id="form-kelola-jadwal" method="post">
                @csrf
                <input type="hidden" name="laporan_id" id="laporan_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">

                        <div class="form-group">
                            <label for="tahap_seleksi" class="form-label">Tahun <span
                                    class="text-danger">*</span></label>
                            <br />
                            <select name="tahap_seleksi_id" id="tahap_seleksi">
                                <option value="" disabled selected>Pilih Tahap Seleksi</option>
                                @foreach ($tahap_seleksis as $tahap_seleksi )
                                <option value="{{$tahap_seleksi->id}}">
                                    {{$tahap_seleksi->tahap_seleksi}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <br />
                        <div class="form-group">
                            <label for="link_bukti" class="form-label">Link Bukti Dukung<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="link_bukti" name="link_bukti" class="form-control"
                                placeholder="link_bukti" required>
                        </div>
                        <br />
                        <div class="form-group">
                            <label for="keterangan" class="form-label">Keterangan<span
                                    class="text-danger">*</span></label>
                            <textarea type="text" id="keterangan" name="keterangan" class="form-control"
                                placeholder="Keterangan" required></textarea>
                        </div>
                        <br />
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
        $('#filter-tahun').change(function() {
            var selectedValue = $(this).val();
            window.location.href = window.location.pathname + '?tahun=' + selectedValue;
        });


        getData();
        modal_kelola_jadwal = tailwind.Modal.getInstance(document.querySelector("#modal-kelola-tim"));
        
        $('#form-kelola-jadwal').validate({
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
                            modal_kelola_jadwal.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Tim gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_kelola_jadwal.hide();
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

    var tim_evaluasi = $('#lapor_wbk_mandiri').DataTable( {
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
            { data: 'tahap_seleksi' },
            { data: 'link' },
            { data: 'keterangan' },
            { data: 'updated_at' },
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
                "targets": 5, // kolom dengan indeks ini yang akan diubah menjadi center
                "className": "text-center",
            },
            {
                "targets": 6, // kolom dengan indeks ini yang akan diubah menjadi center
                "className": "text-center",
            },
            
        ],
    }); 

    function getData() {
        tim_evaluasi.ajax.url("{{route('lapor_wbk_mandiri_getDatas',['tahun'=>$tahun])}}").load(null, false);
    }

    function clearForm() {
        $('#form-kelola-jadwal').trigger('reset');
        $('#jadwal_id').val('');
    }

    function tambah() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        modal_kelola_jadwal.show();
    }

    function edit(id) {
        clearForm();
        $('#jadwal_id').val(id);
        $('#title').html('Edit Jadwal');
        $('.saveButton').prop('disabled', true);
        modal_kelola_jadwal.show();
        $.getJSON("{{url('/zi/lapor-wbk-mandiri/getData/')}}/"+id, function(data) {
            $('#laporan_id').val(data.id).change();
            $('#tahap_seleksi').val(data.tahap_seleksi_id).change();
            $('#link_bukti').val(data.link);
            $('#keterangan').val(data.keterangan);
            $('.saveButton').prop('disabled', false);
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Laporan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/zi/lapor-wbk-mandiri/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        console.log(terhapus);
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data Jadwal Evaluasi berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Jadwal Evaluasi gagal dihapus! '+terhapus.pesan, 'error');
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