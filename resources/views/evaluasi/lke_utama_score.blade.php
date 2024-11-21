@extends('layout.rubick')
@section('title', 'LKE Utama')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">Lembar Kerja Evaluasi {{ $parameter->nama }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-noborder">
                <tr>
                    <td class="w-32"><strong>Komponen</strong></td>
                    <td>: {{ $parameter->parent->parent->nama }}</td>
                </tr>
                <tr>
                    <td><strong>Sub Komponen</strong></td>
                    <td>: {{ $parameter->parent->nama }}</td>
                </tr>
                <tr>
                    <td><strong>Indikator</strong></td>
                    <td>: {{ $parameter->nama }}</td>
                </tr>
            </table>
            <br>
            <table id="lke_utama_score" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w-32">Kelompok Instansi</th>
                        <th>Nama Instansi</th>
                        <th class="w-5">Bobot</th>
                        <th class="w-5">Skor</th>
                        <th class="w-5">Index</th>
                        <th>Catatan</th>
                        <th>Rekomendasi</th>
                        <th class="w-5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ((in_array($user->level, ['tpn', 'tpm']) && $parameter->penilai_id == $user->penilai_id) || ($user->level == 'admin'))
<div id="modal-lke_score" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="fw-medium fs-base me-auto" id="title">Perbaharui Skor Indikator {{ $parameter->nama }}</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('evaluasi/lke-utama/'.$parameter->id.'/simpan') }}" id="form-lke-score" method="post">
                @csrf
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <input type="hidden" name="lke_bobot_id" id="lke_bobot_id">
                        <input type="hidden" name="instansi_id" id="instansi_id">
                        <table class="table">
                            <tr>
                                <td class="w-32"><strong>Instansi</strong></td>
                                <td id="nama_instansi"></td>
                            </tr>
                        </table>
                        <div class="form-group">
                            <label for="score" class="form-label mt-2">Skor <span class="text-danger">*</span></label>
                            <input type="text" name="score" id="score" placeholder="Skor" class="form-control" required>
                            <span><b>Min: </b></span><span id="min"></span>, <span><b>Max: </b></span><span id="max"></span>
                        </div>
                        <div class="form-group">
                            <label for="catatan" class="form-label mt-2">Catatan</label> 
                            <textarea id="catatan" name="catatan" class="form-control" placeholder="Catatan" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rekomendasi" class="form-label mt-2">Rekomendasi</label> 
                            <textarea id="rekomendasi" name="rekomendasi" class="form-control" placeholder="Rekomendasi" required></textarea>
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
@endif
@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function() {
        getData();

        @if ((in_array($user->level, ['tpn', 'tpm']) && $parameter->penilai_id == $user->penilai_id) || ($user->level == 'admin'))
        modal_lke_score = tailwind.Modal.getInstance(document.querySelector("#modal-lke_score"));

        $('#form-lke-score').validate({
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
                            Swal.fire('Selamat!', 'Data Skor berhasil disimpan!', 'success');
                            modal_lke_score.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Skor gagal disimpan!<br>'+data.message, 'error');
                            modal_lke_score.hide();
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
        @endif
    });

    var lke_utama_score = $('#lke_utama_score').DataTable( {
        responsive: true,
        processing: true,
        ordering: false,
        ajax: {
            url: "{{url('emptyDT')}}",
            data: function(d) {
                d.kegiatan_id = $('#kegiatan_id').val();
            }
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
            { data: 'group_instansi' },
            { data: 'nama_instansi' },
            { data: 'bobot' },
            { data: 'score' },
            { data: 'score_index' },
            { data: 'catatan' },
            { data: 'rekomendasi' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    @if ((in_array($user->level, ['tpn', 'tpm']) && $parameter->penilai_id == $user->penilai_id) || ($user->level == 'admin'))
                    return '<button onclick="edit('+row.instansi_id+', '+row.lke_bobot_id+');" class="btn btn-warning btn-sm w-10">Edit</button>';
                    @else
                    return '';
                    @endif
                },
            },
        ],
    }); 

    function getData() {
        lke_utama_score.ajax.url("{{url('evaluasi/lke-utama/'.$parameter->id.'/getDatas')}}").load(null, false);
    }

    @if ((in_array($user->level, ['tpn', 'tpm']) && $parameter->penilai_id == $user->penilai_id) || ($user->level == 'admin'))
    function edit(instansi_id, lke_bobot_id) {
        $.getJSON("{{url('evaluasi/lke-utama/'.$parameter->id.'/getData')}}/"+instansi_id+"/"+lke_bobot_id, function(data) {
            $('#instansi_id').val(instansi_id);
            $('#lke_bobot_id').val(lke_bobot_id);
            $('#nama_instansi').html(data.nama_instansi);
            $('#min').html(data.min);
            $('#max').html(data.max);
            $('#score').val(data.score);
            $('#rekomendasi').val(data.rekomendasi);
            $('#catatan').val(data.catatan);
            $("#score").inputmask("decimal",{
                radixPoint:".",
                digits: 2,
                autoGroup: true,
                rightAlign: false,
                min: data.min,
                max: data.max,
            });
            modal_lke_score.show();
        });
    }
    @endif
</script>
@endpush
