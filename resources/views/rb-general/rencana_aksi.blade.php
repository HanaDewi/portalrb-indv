@extends('layout.rubick')
@section('title', 'RB General - Rencana Aksi')

@section('button')
@endsection
@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto flex items-center justify-center"> <i data-lucide="pie-chart" class="mr-1"></i> RB General - Rencana Aksi</h2>
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
            </table>
        </div>
        <div class="flex sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto flex items-center justify-center">
                <i data-lucide="file-text" class="mr-1"></i> Data Rencana Aksi
            </h2>
            <button class="btn btn-danger shadow-md" onclick="tambah();" data-bs-toggle="modal" data-bs-target="#modal-kegiatan_utama"><i data-lucide="plus" class="mr-1"></i> Tambah Rencana Aksi</button>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="rencana_aksi-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Rencana Aksi</th>
                            <th>Satuan Output</th>
                            <th>Indikator Output</th>
                            <th>Target</th>
                            <th>Anggaran</th>
                            <th>Pelaksana</th>
                            <th>Koordinator</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Form Rencana Aksi --}}
<div id="modal-rencana_aksi" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Tambah Rencana Aksi</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/simpan') }}" id="form-rencana_aksi" method="post">
                @csrf
                <input type="hidden" name="rencana_aksi_id" id="rencana_aksi_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table table-noborder">
                            <tr>
                                <td class="font-bold w-44">Rencana Aksi <span class="text-danger">*</span></td>
                                <td colspan="5">
                                    <textarea rows="5" name="rencana_aksi" id="rencana_aksi" placeholder="Penjelasan Rencana Aksi" class="form-control" required></textarea>
                                </td>
                            </tr>
                        </table>
                        <hr class="my-4">
                        <table class="table table-noborder">
                            <input type="hidden" name="target_output[0][rencana_aksi_output_id]" id="rencana_aksi_output_id">
                            <tr>
                                <td class="font-bold w-44">Satuan Output<span class="text-danger">*</span></td>
                                <td colspan="5">
                                    <input type="text" name="target_output[0][satuan_output]" id="satuan_output0" placeholder="Satuan Output" class="form-control" required>
                                </td>
                                
                            </tr>

                              <tr>
                                <td class="font-bold w-44">Indikator Output <span class="text-danger">*</span></td>
                                
                                <td colspan="5">
                                	<div class="mt-4">
                                    <input type="text" name="target_output[0][indikator_output]" id="indikator_output0" placeholder="Indikator Output" class="form-control" required>
                                    </div>
                                </td>
                            </tr>



                            <tr>
                                <td class="font-bold">Target Output <span class="text-danger">*</span></td>
                                <td>
																
                                	<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW1</div>
                                    <input type="text" name="target_output[0][target_tw1]" id="target_tw10" placeholder="Triwulan 1" class="form-control digit" onkeyup="hitungTotal(0);" required>
                                     </div>
                                </td>
                                <td>
                                		<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW2</div>
                                    <input type="text" name="target_output[0][target_tw2]" id="target_tw20" placeholder="Triwulan 2" class="form-control digit" onkeyup="hitungTotal(0);" required>
                                </div>
                                </td>
                                <td>
                                		<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW3</div>
                                    <input type="text" name="target_output[0][target_tw3]" id="target_tw30" placeholder="Triwulan 3" class="form-control digit" onkeyup="hitungTotal(0);" required>
                                </div>
                                </td>
                                <td>
                                		<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW4</div>
                                    <input type="text" name="target_output[0][target_tw4]" id="target_tw40" placeholder="Triwulan 4" class="form-control digit" onkeyup="hitungTotal(0);" required>
                                </div>
                                </td>
                                <td>
                                	<div class="input-group mt-4">
                                       <div class="input-group-text">Total</div>
                                    <input type="text" name="target_output[0][target_total]" id="target_total0" placeholder="Total" class="form-control digit" readonly required>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Anggaran <span class="text-danger">*</span></td>
                                <td>
                                	  	<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW1</div>
                                    <input type="text" name="target_output[0][anggaran_tw1]" id="anggaran_tw10" placeholder="Triwulan 1" class="form-control digit" onkeyup="hitungTotalAnggaran(0);" required>
                                </div>
                                </td>
                                <td>
                                	  	<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW2</div>
                                    <input type="text" name="target_output[0][anggaran_tw2]" id="anggaran_tw20" placeholder="Triwulan 2" class="form-control digit" onkeyup="hitungTotalAnggaran(0);" required>
                                </div>
                                </td>
                                <td>
                                	  	<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW3</div>
                                    <input type="text" name="target_output[0][anggaran_tw3]" id="anggaran_tw30" placeholder="Triwulan 3" class="form-control digit" onkeyup="hitungTotalAnggaran(0);" required>
                                </div>
                                </td>
                                <td>
                                	  	<div class="input-group mt-4 mr-2">
                                       <div class="input-group-text">TW4</div>
                                    <input type="text" name="target_output[0][anggaran_tw4]" id="anggaran_tw40" placeholder="Triwulan 4" class="form-control digit" onkeyup="hitungTotalAnggaran(0);" required>
                                </div>
                                </td>
                                <td>
                                	<div class="input-group mt-4">
                                       <div class="input-group-text">Total</div>
                                    <input type="text" name="target_output[0][anggaran_total]" id="anggaran_total0" placeholder="Total" class="form-control digit" readonly required>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Unit Kerja Pelaksana <span class="text-danger">*</span></td>
                                <td colspan="5">
                               
                                    <input type="text" name="target_output[0][pelaksana]" id="pelaksana0" placeholder="Pelaksana" class="form-control mt-4" required>
                               
                                </td>
                                
                            </tr>


                             <tr>
                                <td class="font-bold">Koordinator Pelaksana <span class="text-danger">*</span></td>
                               
                                <td colspan="5">
                                    <input type="text" name="target_output[0][koordinator]" id="koordinator0" placeholder="Koordinator" class="form-control mt-4" required>
                                </td>
                            </tr>




                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                        </table>
                        <div id="target_output_ext">
                        </div>
                        <button type="button" class="btn btn-outline-primary border-dashed w-full mt-4" onclick="tambah_input();" id="tambah_input_button"><i data-lucide="plus" class="w-4 h-4 mr-2"></i></button>
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
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script>
    $(document).ready(function() {
        idx = 0;
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
    var nom = 0;            
    var nama = "";
    var rencana_aksi = $('#rencana_aksi-table').DataTable( {
        processing: true,
        ordering: false,
        columns: [
            {
               data: 'rencana_aksi.rencana_aksi',  
                    render: function (data, type, row, meta) {      
                    if (nama!=data){nama=data,nom++}        
                    return nom; }
            },
            { data: 'rencana_aksi.rencana_aksi' },
            { data: 'satuan_output' },
            { data: 'indikator_output' },
            { 
                render: function (data, type, row, meta) {
                    return '<table class="table table-noborder">'+
                            '<tr><th>TW 1</th><td>: '+formatNumber(row.target_tw1)+'</td></tr>'+
                            '<tr><th>TW 2</th><td>: '+formatNumber(row.target_tw2)+'</td></tr>'+
                            '<tr><th>TW 3</th><td>: '+formatNumber(row.target_tw3)+'</td></tr>'+
                            '<tr><th>TW 4</th><td>: '+formatNumber(row.target_tw4)+'</td></tr>'+
                            '<tr><th>Total</th><td>: '+formatNumber(row.target_total)+'</td></tr>'+
                        '</table>';
                }
            },
            { 
                render: function (data, type, row, meta) {
                    return '<table class="table table-noborder">'+
                            '<tr><th>TW 1</th><td>: '+formatNumber(row.anggaran_tw1)+'</td></tr>'+
                            '<tr><th>TW 2</th><td>: '+formatNumber(row.anggaran_tw2)+'</td></tr>'+
                            '<tr><th>TW 3</th><td>: '+formatNumber(row.anggaran_tw3)+'</td></tr>'+
                            '<tr><th>TW 4</th><td>: '+formatNumber(row.anggaran_tw4)+'</td></tr>'+
                            '<tr><th class="border-top">Total</th><td>: '+formatNumber(row.anggaran_total)+'</td></tr>'+
                        '</table>';
                }
            },
            { data: 'pelaksana' },
            { data: 'koordinator' },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.id+');" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><button onclick="hapus('+row.id+');" class="btn btn-danger btn-sm w-10"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>';
                },
            },
        ],
        rowsGroup: [0,1] 
    }); 

    function getData() {
        rencana_aksi.ajax.url("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/getDatas')}}").load(null, false);
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function clearForm() {
        $('#form-rencana_aksi').trigger('reset');
        $('#rencana_aksi_id').val('');
        $('#rencana_aksi_output_id').val('');
    }

    function tambah() {
        clearForm();
        $('#target_output_ext').html('');
        $('.saveButton').prop('disabled', false);
        modal_rencana_aksi.show();
    }

    function hitungTotal(idx) {
        if ($('#target_tw1'+idx).val() == '') {
            $('#target_tw1'+idx).val(0);
        }
        if ($('#target_tw2'+idx).val() == '') {
            $('#target_tw2'+idx).val(0);
        }
        if ($('#target_tw3'+idx).val() == '') {
            $('#target_tw3'+idx).val(0);
        }
        if ($('#target_tw4'+idx).val() == '') {
            $('#target_tw4'+idx).val(0);
        }
        tw1 = $('#target_tw1'+idx).val().replaceAll('.', '');
        tw2 = $('#target_tw2'+idx).val().replaceAll('.', '');
        tw3 = $('#target_tw3'+idx).val().replaceAll('.', '');
        tw4 = $('#target_tw4'+idx).val().replaceAll('.', '');
        total = parseFloat(tw1) + parseFloat(tw2) + parseFloat(tw3) + parseFloat(tw4);
        $('#target_total'+idx).val(total);
    }

    function hitungTotalAnggaran(idx) {
        if ($('#anggaran_tw1'+idx).val() == '') {
            $('#anggaran_tw1'+idx).val(0);
        }
        if ($('#anggaran_tw2'+idx).val() == '') {
            $('#anggaran_tw2'+idx).val(0);
        }
        if ($('#anggaran_tw3'+idx).val() == '') {
            $('#anggaran_tw3'+idx).val(0);
        }
        if ($('#anggaran_tw4'+idx).val() == '') {
            $('#anggaran_tw4'+idx).val(0);
        }
        tw1 = $('#anggaran_tw1'+idx).val().replaceAll('.', '');
        tw2 = $('#anggaran_tw2'+idx).val().replaceAll('.', '');
        tw3 = $('#anggaran_tw3'+idx).val().replaceAll('.', '');
        tw4 = $('#anggaran_tw4'+idx).val().replaceAll('.', '');
        console.log(tw1, tw2, tw3, tw4);
        total = parseInt(tw1) + parseInt(tw2) + parseInt(tw3) + parseInt(tw4);
        $('#anggaran_total'+idx).val(total);
    }

    function output_form() {
        return '<table class="table table-noborder" id="output_form'+idx+'">'+
                    '<tr>'+
                        '<td class="font-bold w-44">Output <span class="text-danger">*</span></td>'+
                        '<td colspan="2">'+
                            '<input type="text" name="target_output['+idx+'][satuan_output]" id="satuan_output'+idx+'" placeholder="Satuan Output" class="form-control" required>'+
                        '</td>'+
                        '<td colspan="3">'+
                            '<input type="text" name="target_output['+idx+'][indikator_output]" id="indikator_output'+idx+'" placeholder="Indikator Output" class="form-control" required>'+
                        '</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="font-bold">Target Output <span class="text-danger">*</span></td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][target_tw1]" id="target_tw1'+idx+'" placeholder="Triwulan 1" class="form-control numeric" onkeyup="hitungTotal('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][target_tw2]" id="target_tw2'+idx+'" placeholder="Triwulan 2" class="form-control numeric" onkeyup="hitungTotal('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][target_tw3]" id="target_tw3'+idx+'" placeholder="Triwulan 3" class="form-control numeric" onkeyup="hitungTotal('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][target_tw4]" id="target_tw4'+idx+'" placeholder="Triwulan 4" class="form-control numeric" onkeyup="hitungTotal('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][target_total]" id="target_total'+idx+'" placeholder="Total" class="form-control numeric" readonly required>'+
                        '</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="font-bold">Anggaran <span class="text-danger">*</span></td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][anggaran_tw1]" id="anggaran_tw1'+idx+'" placeholder="Triwulan 1" class="form-control digit" onkeyup="hitungTotalAnggaran('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][anggaran_tw2]" id="anggaran_tw2'+idx+'" placeholder="Triwulan 2" class="form-control digit" onkeyup="hitungTotalAnggaran('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][anggaran_tw3]" id="anggaran_tw3'+idx+'" placeholder="Triwulan 3" class="form-control digit" onkeyup="hitungTotalAnggaran('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][anggaran_tw4]" id="anggaran_tw4'+idx+'" placeholder="Triwulan 4" class="form-control digit" onkeyup="hitungTotalAnggaran('+idx+');" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="target_output['+idx+'][anggaran_total]" id="anggaran_total'+idx+'" placeholder="Total" class="form-control digit" readonly required>'+
                        '</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="font-bold">Unit Kerja Pelaksana <span class="text-danger">*</span></td>'+
                        '<td colspan="2">'+
                            '<input type="text" name="target_output['+idx+'][pelaksana]" id="pelaksana'+idx+'" placeholder="Pelaksana" class="form-control" required>'+
                        '</td>'+
                        '<td colspan="3">'+
                            '<input type="text" name="target_output['+idx+'][koordinator]" id="koordinator'+idx+'" placeholder="Koordinator" class="form-control" required>'+
                        '</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td colspan="6"><button type="button" class="btn btn-outline-dark border-dashed w-full" onclick="hapus_input('+idx+');">hapus</button></td>'+
                    '</tr>'+
                '</table>';
    }

    function tambah_input() {
        idx++;
        $('#target_output_ext').append(output_form(idx));
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
    }

    function hapus_input(idx) {
        $('#output_form'+idx).remove();
    }

    function edit(id) {
        clearForm();
        $('#rencana_aksi_output_id').val(id);
        $('#title').html('Edit Rencana Aksi Output');
        $('.saveButton').prop('disabled', true);
        $.getJSON("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/getData')}}/"+id, function(data) {
            $('#rencana_aksi_id').val(data.general_rencana_aksi_id);
            $('#rencana_aksi').val(data.rencana_aksi.rencana_aksi);
            $('#satuan_output0').val(data.satuan_output);
            $('#indikator_output0').val(data.indikator_output);
            $('#target_tw10').val(data.target_tw1);
            $('#target_tw20').val(data.target_tw2);
            $('#target_tw30').val(data.target_tw3);
            $('#target_tw40').val(data.target_tw4);
            $('#target_total0').val(data.target_total);
            $('#anggaran_tw10').val(data.anggaran_tw1);
            $('#anggaran_tw20').val(data.anggaran_tw2);
            $('#anggaran_tw30').val(data.anggaran_tw3);
            $('#anggaran_tw40').val(data.anggaran_tw4);
            $('#anggaran_total0').val(data.anggaran_total);
            $('#pelaksana0').val(data.pelaksana);
            $('#koordinator0').val(data.koordinator);
            $('.saveButton').prop('disabled', false);
            modal_rencana_aksi.show();
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Rencana Aksi ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/rencana_aksi/hapus')}}",
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data Rencana Aksi berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Rencana Aksi gagal dihapus! Coba lagi nanti ya..', 'error');
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