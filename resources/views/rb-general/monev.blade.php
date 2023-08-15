@extends('layout.rubick')
@section('title', 'RB General - Rencana Aksi')

@section('button')
@endsection
@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto flex items-center justify-center"> <i data-lucide="pie-chart" class="mr-1"></i> RB General - Monitoring dan Evaluasi</h2>
            <button class="btn btn-danger shadow-md float-right mr-2" onclick="monev();" data-bs-toggle="modal" data-bs-target="#modal-kegiatan_utama"><i data-lucide="edit" class="mr-1"></i> Evaluasi</button>
            <a href="{{ url('rb-general/perencanaan') }}" class="btn btn-warning shadow-md float-right"><i data-lucide="chevron-left"></i> Kembali</a>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <td class="font-bold align-top">Kegiatan Utama</td>
                    <td>{{ $target->perencanaan->kegiatan_utama->nama }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Indikator</td>
                    <td>{{ $target->perencanaan->indikator->nama }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Baseline</td>
                    <td>
                        <table class="table table-noborder">
                            <tr>
                                <td class="font-bold w-16">Tahun</td>
                                <td>: {{ $target->perencanaan->baseline_tahun }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Target</td>
                                <td>: {{ $target->perencanaan->baseline_target }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Realisasi</td>
                                <td>: {{ $target->perencanaan->baseline_realisasi }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Tahun</td>
                    <td>{{ $target->tahun }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Target</td>
                    <td>{{ $target->target }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Realisasi Indikator</td>
                    <td>{{ $target->perencanaan->realisasi_indikator }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Capaian Indikator</td>
                    <td>{{ $target->perencanaan->capaian_indikator }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Catatan</td>
                    <td>{{ $target->perencanaan->catatan }}</td>
                </tr>
            </table>
        </div>
        <div class="flex sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto flex items-center justify-center"><i data-lucide="file-text" class="mr-1"></i> Data Evaluasi Rencana Aksi</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped table-hover" id="rencana_aksi-table">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Rencana Aksi</th>
                        <th>Satuan Output</th>
                        <th>Indikator Output</th>
                        <th>Target Total</th>
                        <th>Anggaran</th>
                        <th>Realisasi Output</th>
                        <th>Realisasi Anggaran</th>
                        <th>Capaian Anggaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form Rencana Aksi --}}
<div id="modal-rencana_aksi" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Tambah Monitoring dan Evaluasi Rencana Aksi</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/monev/simpan') }}" id="form-rencana_aksi" method="post">
                @csrf
                <input type="hidden" name="rencana_aksi_id" id="rencana_aksi_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table>
                            <tr>
                                <td class="font-bold w-44">Rencana Aksi</td>
                                <td colspan="5">
                                    <textarea rows="5" name="rencana_aksi" id="rencana_aksi" placeholder="Penjelasan Rencana Aksi" class="form-control" readonly></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Output</td>
                                <td colspan="2">
                                    <input type="text" name="satuan_output" id="satuan_output" placeholder="Satuan Output" class="form-control" readonly>
                                </td>
                                <td colspan="3">
                                    <input type="text" name="indikator_output" id="indikator_output" placeholder="Indikator Output" class="form-control" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Target Output</td>
                                <td>
                                    <input type="text" name="target_tw1" id="target_tw1" placeholder="Triwulan 1" class="form-control numeric" onkeyup="hitungTotal();" readonly>
                                </td>
                                <td>
                                    <input type="text" name="target_tw2" id="target_tw2" placeholder="Triwulan 2" class="form-control numeric" onkeyup="hitungTotal();" readonly>
                                </td>
                                <td>
                                    <input type="text" name="target_tw3" id="target_tw3" placeholder="Triwulan 3" class="form-control numeric" onkeyup="hitungTotal();" readonly>
                                </td>
                                <td>
                                    <input type="text" name="target_tw4" id="target_tw4" placeholder="Triwulan 4" class="form-control numeric" onkeyup="hitungTotal();" readonly>
                                </td>
                                <td>
                                    <input type="text" name="target_total" id="target_total" placeholder="Total" class="form-control numeric" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Anggaran</td>
                                <td colspan="5">
                                    <input type="text" name="anggaran" id="anggaran" placeholder="Masukan Data Anggaran" class="form-control digit" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Unit Kerja Pelaksana</td>
                                <td colspan="2">
                                    <input type="text" name="pelaksana" id="pelaksana" placeholder="Pelaksana" class="form-control" readonly>
                                </td>
                                <td colspan="3">
                                    <input type="text" name="koordinator" id="koordinator" placeholder="Koordinator" class="form-control" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Realisasi Output <span class="text-danger">*</span></td>
                                <td colspan="5">
                                    <input type="text" name="realisasi_output" id="realisasi_output" placeholder="Realisasi Output" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Realisasi Anggaran <span class="text-danger">*</span></td>
                                <td colspan="5">
                                    <input type="text" name="realisasi_anggaran" id="realisasi_anggaran" placeholder="Realisasi Anggaran" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Capaian Anggaran <span class="text-danger">*</span></td>
                                <td colspan="5">
                                    <input type="text" name="capaian_anggaran" id="capaian_anggaran" placeholder="Capaian Anggaran" class="form-control">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end"> 
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Cancel</button> 
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
    $(document).ready(function() {
        getData();
        modal_rencana_aksi = tailwind.Modal.getInstance(document.querySelector("#modal-rencana_aksi"));

        $(".numeric").inputmask("decimal",{
            groupSeparator: "",
            digits: 0,
            autoGroup: false,
            rightAlign: false,
            min: 0
        });

        $(".digit").inputmask("decimal",{
            radixPoint:",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            min: 0,
        });

        $('#form-rencana_aksi').validate({
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
                            Swal.fire('Selamat!', 'Data Rencana Aksi berhasil disimpan!', 'success');
                            modal_rencana_aksi.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Rencana Aksi gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_rencana_aksi.hide();
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

    var rencana_aksi = $('#rencana_aksi-table').DataTable( {
        responsive: true,
        processing: true,
        ordering: false,
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
            { data: 'rencana_aksi' },
            { data: 'satuan_output' },
            { data: 'indikator_output' },
            { data: 'target_total' },
            { data: 'anggaran' },
            { data: 'realisasi_output' },
            { data: 'realisasi_anggaran' },
            { data: 'capaian_anggaran' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.id+');" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>';
                },
            },
        ],
        columnDefs: [
            {
                targets: [9],
                render: $.fn.dataTable.render.number('.', ',', 0, '')
            }
        ],
    }); 

    function getData() {
        rencana_aksi.ajax.url("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/getDatas')}}").load(null, false);
    }

    function clearForm() {
        $('#form-rencana_aksi').trigger('reset');
        $('#target_tw1').val('0');
        $('#target_tw2').val('0');
        $('#target_tw3').val('0');
        $('#target_tw4').val('0');
        $('#rencana_aksi_id').val('');
    }

    function tambah() {
        clearForm();
        $('.saveButton').prop('disabled', false);
        modal_rencana_aksi.show();
    }

    function hitungTotal() {
        if ($('#target_tw1').val() == '') {
            $('#target_tw1').val(0);
        }
        if ($('#target_tw2').val() == '') {
            $('#target_tw2').val(0);
        }
        if ($('#target_tw3').val() == '') {
            $('#target_tw3').val(0);
        }
        if ($('#target_tw4').val() == '') {
            $('#target_tw4').val(0);
        }
        tw1 = $('#target_tw1').val();
        tw2 = $('#target_tw2').val();
        tw3 = $('#target_tw3').val();
        tw4 = $('#target_tw4').val();
        total = parseFloat(tw1) + parseFloat(tw2) + parseFloat(tw3) + parseFloat(tw4);
        $('#target_total').val(total);
    }

    function edit(id) {
        clearForm();
        $('#rencana_aksi_id').val(id);
        $('#title').html('Edit Monitoring dan Evaluasi Rencana Aksi');
        $('.saveButton').prop('disabled', true);
        modal_rencana_aksi.show();
        $.getJSON("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/getData')}}/"+id, function(data) {
            $('#rencana_aksi').val(data.rencana_aksi);
            $('#satuan_output').val(data.satuan_output);
            $('#indikator_output').val(data.indikator_output);
            $('#target_tw1').val(data.target_tw1);
            $('#target_tw2').val(data.target_tw2);
            $('#target_tw3').val(data.target_tw3);
            $('#target_tw4').val(data.target_tw4);
            $('#target_total').val(data.target_total);
            $('#anggaran').val(data.anggaran);
            $('#pelaksana').val(data.pelaksana);
            $('#koordinator').val(data.koordinator);
            $('#realisasi_output').val(data.realisasi_output);
            $('#realisasi_anggaran').val(data.realisasi_anggaran);
            $('#capaian_anggaran').val(data.capaian_anggaran);
            $('.saveButton').prop('disabled', false);
        });
    }
</script>
@endpush
