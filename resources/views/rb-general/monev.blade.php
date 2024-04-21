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
            <button class="btn btn-danger btn-sm shadow-md float-right mr-2" onclick="edit_monev();" data-bs-toggle="modal" data-bs-target="#modal-kegiatan_utama"><i data-lucide="edit" class="mr-1" width="18px" height="18px"></i> Evaluasi</button>
            <a href="{{ url('rb-general/perencanaan') }}" class="btn btn-warning btn-sm shadow-md float-right"><i data-lucide="chevron-left" width="18px" height="18px"></i> Kembali</a>
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
                    <input type="hidden" id="target_indikator" value="{{ $target->target }}">
                </tr>
                <tr>
                    <td class="font-bold align-top">Realisasi Indikator</td>
                    <td id="info_realisasi_indikator">{{ $target->realisasi_indikator }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Capaian Indikator</td>
                    <td id="info_capaian_indikator">{{ $target->capaian_indikator }}</td>
                </tr>
                <tr>
                    <td class="font-bold align-top">Catatan</td>
                    <td id="info_catatan">{{ $target->catatan }}</td>
                </tr>
            </table>
        </div>
        <div class="flex sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto flex items-center justify-center"><i data-lucide="file-text" class="mr-1"></i> Data Evaluasi Rencana Aksi</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped table-hover" id="monev-table">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Rencana Aksi</th>
                        <th>Satuan Output</th>
                        <th>Indikator Output</th>
                        <th>Target</th>
                        <th>Anggaran</th>
                        <th>Realisasi Output</th>
                        <th>Realisasi Anggaran</th>
                        <th>Capaian Output</th>
                        <th>Capaian Anggaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form Rencana Aksi --}}
<div id="modal-monev" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Monitoring dan Evaluasi Rencana Aksi</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/monev/simpan') }}" id="form-monev" method="post">
                @csrf
                <input type="hidden" name="output_id" id="output_id">
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
                                <td class="font-bold">Satuan Output</td>
                                <td colspan="5">
                                    <input type="text" name="satuan_output" id="satuan_output" placeholder="Satuan Output" class="form-control mt-4" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Indikator Output</td>
                                <td colspan="5">
                                    <input type="text" name="indikator_output" id="indikator_output" placeholder="Indikator Output" class="form-control mt-4" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Target Output</td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW1</div>
                                        <input type="text" name="target_tw1" id="target_tw1" placeholder="Triwulan 1"  class="form-control numeric" readonly>
                                    </div>    
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW2</div>
                                        <input type="text" name="target_tw2" id="target_tw2" placeholder="Triwulan 2" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW3</div>
                                        <input type="text" name="target_tw3" id="target_tw3" placeholder="Triwulan 3" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW4</div>
                                        <input type="text" name="target_tw4" id="target_tw4" placeholder="Triwulan 4" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4">
                                        <div class="input-group-text">Total</div>
                                        <input type="text" name="target_total" id="target_total" placeholder="Total" class="form-control numeric" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Anggaran</td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW1</div>
                                        <input type="text" name="anggaran_tw1" id="anggaran_tw1" placeholder="Triwulan 1" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW2</div>
                                        <input type="text" name="anggaran_tw2" id="anggaran_tw2" placeholder="Triwulan 2" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW3</div>
                                        <input type="text" name="anggaran_tw3" id="anggaran_tw3" placeholder="Triwulan 3" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW4</div>
                                        <input type="text" name="anggaran_tw4" id="anggaran_tw4" placeholder="Triwulan 4" class="form-control numeric" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">Total</div>
                                        <input type="text" name="anggaran_total" id="anggaran_total" placeholder="Total" class="form-control numeric" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Unit Kerja Pelaksana</td>
                                <td colspan="5">
                                    <input type="text" name="pelaksana" id="pelaksana" placeholder="Pelaksana" class="form-control mt-4" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Koordinator Pelaksana</td>
                                <td colspan="5">
                                    <input type="text" name="koordinator" id="koordinator" placeholder="Koordinator" class="form-control mt-4" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Realisasi Output <span class="text-danger">*</span></td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW1</div>
                                        <input type="text" name="realisasi_output_tw1" id="realisasi_output_tw1" placeholder="Triwulan 1" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW2</div>
                                        <input type="text" name="realisasi_output_tw2" id="realisasi_output_tw2" placeholder="Triwulan 2" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW3</div>
                                        <input type="text" name="realisasi_output_tw3" id="realisasi_output_tw3" placeholder="Triwulan 3" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW4</div>
                                        <input type="text" name="realisasi_output_tw4" id="realisasi_output_tw4" placeholder="Triwulan 4" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4">
                                        <div class="input-group-text">Total</div>
                                        <input type="text" name="realisasi_output_total" id="realisasi_output_total" placeholder="Total" class="form-control digit" readonly required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Realisasi Anggaran <span class="text-danger">*</span></td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW1</div>
                                        <input type="text" name="realisasi_anggaran_tw1" id="realisasi_anggaran_tw1" placeholder="Triwulan 1" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW2</div>
                                        <input type="text" name="realisasi_anggaran_tw2" id="realisasi_anggaran_tw2" placeholder="Triwulan 2" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW3</div>
                                        <input type="text" name="realisasi_anggaran_tw3" id="realisasi_anggaran_tw3" placeholder="Triwulan 3" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW4</div>
                                        <input type="text" name="realisasi_anggaran_tw4" id="realisasi_anggaran_tw4" placeholder="Triwulan 4" class="form-control digit" onkeyup="hitungTotal();" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4">
                                        <div class="input-group-text">Total</div>
                                        <input type="text" name="realisasi_anggaran_total" id="realisasi_anggaran_total" placeholder="Total" class="form-control digit" readonly required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Capaian Output <span class="text-danger">*</span></td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW1</div>
                                        <input type="text" name="capaian_output_tw1" id="capaian_output_tw1" placeholder="Triwulan 1" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW2</div>
                                        <input type="text" name="capaian_output_tw2" id="capaian_output_tw2" placeholder="Triwulan 2" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW3</div>
                                        <input type="text" name="capaian_output_tw3" id="capaian_output_tw3" placeholder="Triwulan 3" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW4</div>
                                        <input type="text" name="capaian_output_tw4" id="capaian_output_tw4" placeholder="Triwulan 4" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4">
                                        <div class="input-group-text">Total</div>
                                        <input type="text" name="capaian_output_total" id="capaian_output_total" placeholder="Total" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Capaian Anggaran <span class="text-danger">*</span></td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW1</div>
                                        <input type="text" name="capaian_anggaran_tw1" id="capaian_anggaran_tw1" placeholder="Triwulan 1" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW2</div>
                                        <input type="text" name="capaian_anggaran_tw2" id="capaian_anggaran_tw2" placeholder="Triwulan 2" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW3</div>
                                        <input type="text" name="capaian_anggaran_tw3" id="capaian_anggaran_tw3" placeholder="Triwulan 3" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4 mr-2">
                                        <div class="input-group-text">TW4</div>
                                        <input type="text" name="capaian_anggaran_tw4" id="capaian_anggaran_tw4" placeholder="Triwulan 4" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mt-4">
                                        <div class="input-group-text">Total</div>
                                        <input type="text" name="capaian_anggaran_total" id="capaian_anggaran_total" placeholder="Total" class="form-control digit" readonly>
                                        <div class="input-group-text">%</div>
                                    </div>
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

{{-- Modal Form Monev Perencanaan --}}
<div id="modal-monev_perencanaan" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Monitoring dan Evaluasi Perencanaan</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/monev/simpanTarget') }}" id="form-monev_perencanaan" method="post">
                @csrf
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table table-noborder">
                            <tr>
                                <td class="font-bold w-44">Realisasi Indikator</td>
                                <td>
                                    <input type="text" name="realisasi_indikator" id="realisasi_indikator" placeholder="Realisasi Indikator" class="form-control numeric" onkeyup="hitungCapaian();">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Capaian Indikator</td>
                                <td>
                                    <input type="text" name="capaian_indikator" id="capaian_indikator" placeholder="Capaian Indikator" class="form-control mt-4" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Catatan</td>
                                <td>
                                    <textarea name="catatan" id="catatan" cols="30" rows="10" placeholder="Catatan" class="form-control mt-4"></textarea>
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
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        getData();
        modal_monev = tailwind.Modal.getInstance(document.querySelector("#modal-monev"));
        modal_monev_perencanaan = tailwind.Modal.getInstance(document.querySelector("#modal-monev_perencanaan"));

        $(".numeric").inputmask("decimal",{
            groupSeparator: "",
            digits: 0,
            autoGroup: false,
            rightAlign: false,
            min: 0
        });

        $(".digit").inputmask("decimal",{
            radixPoint:".",
            groupSeparator: "",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            min: 0,
        });

        $('#form-monev').validate({
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
                            Swal.fire('Selamat!', 'Data Monitoring dan Evaluasi Rencana Aksi berhasil disimpan!', 'success');
                            modal_monev.hide();
                        } else {
                            Swal.fire('Aduh!', 'Data Monitoring dan Evaluasi Rencana Aksi gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_monev.hide();
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

        $('#form-monev_perencanaan').validate({
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
                            Swal.fire('Selamat!', 'Data Monitoring dan Evaluasi Perencanaan berhasil disimpan!', 'success');
                            modal_monev_perencanaan.hide();
                            $('#info_realisasi_indikator').html(data.target.realisasi_indikator);
                            $('#info_capaian_indikator').html(data.target.capaian_indikator);
                            $('#info_catatan').html(data.target.catatan);
                        } else {
                            Swal.fire('Aduh!', 'Data Monitoring dan Evaluasi Perencanaan gagal disimpan! Coba lagi nanti ya..', 'error');
                            modal_monev_perencanaan.hide();
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

    function hitungCapaian() {
        target = $('#target_indikator').val();
        realisasi = $('#realisasi_indikator').val();
        capaian = (realisasi / target) * 100;
        console.log(target, realisasi, capaian);
        $('#capaian_indikator').val(capaian);
    }

    var monev = $('#monev-table').DataTable( {
        dom: 'Blfrtip',
            buttons: [
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                },
                text: '<button class="btn btn-danger btn-sm w-32 mr-2 mb-2"><svg fill="#ffffff" height="18px" width="18px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 482.14 482.14" xml:space="preserve" stroke="#ffffff"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <g> <path d="M142.024,310.194c0-8.007-5.556-12.782-15.359-12.782c-4.003,0-6.714,0.395-8.132,0.773v25.69 c1.679,0.378,3.743,0.504,6.588,0.504C135.57,324.379,142.024,319.1,142.024,310.194z"/> <path d="M202.709,297.681c-4.39,0-7.227,0.379-8.905,0.772v56.896c1.679,0.394,4.39,0.394,6.841,0.394 c17.809,0.126,29.424-9.677,29.424-30.449C230.195,307.231,219.611,297.681,202.709,297.681z"/> <path d="M315.458,0H121.811c-28.29,0-51.315,23.041-51.315,51.315v189.754h-5.012c-11.418,0-20.678,9.251-20.678,20.679v125.404 c0,11.427,9.259,20.677,20.678,20.677h5.012v22.995c0,28.305,23.025,51.315,51.315,51.315h264.223 c28.272,0,51.3-23.011,51.3-51.315V121.449L315.458,0z M99.053,284.379c6.06-1.024,14.578-1.796,26.579-1.796 c12.128,0,20.772,2.315,26.58,6.965c5.548,4.382,9.292,11.615,9.292,20.127c0,8.51-2.837,15.745-7.999,20.646 c-6.714,6.32-16.643,9.157-28.258,9.157c-2.585,0-4.902-0.128-6.714-0.379v31.096H99.053V284.379z M386.034,450.713H121.811 c-10.954,0-19.874-8.92-19.874-19.889v-22.995h246.31c11.42,0,20.679-9.25,20.679-20.677V261.748 c0-11.428-9.259-20.679-20.679-20.679h-246.31V51.315c0-10.938,8.921-19.858,19.874-19.858l181.89-0.19v67.233 c0,19.638,15.934,35.587,35.587,35.587l65.862-0.189l0.741,296.925C405.891,441.793,396.987,450.713,386.034,450.713z M174.065,369.801v-85.422c7.225-1.15,16.642-1.796,26.58-1.796c16.516,0,27.226,2.963,35.618,9.282 c9.031,6.714,14.704,17.416,14.704,32.781c0,16.643-6.06,28.133-14.453,35.224c-9.157,7.612-23.096,11.222-40.125,11.222 C186.191,371.092,178.966,370.446,174.065,369.801z M314.892,319.226v15.996h-31.23v34.973h-19.74v-86.966h53.16v16.122h-33.42 v19.875H314.892z"/> </g> </g> </svg> &nbsp;PDF </button>',
                        titleAttr: 'Download PDF'
            },
            {
                extend: 'excel',
                text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                        titleAttr: 'Download Excel'
            }],
        responsive: true,
        processing: true,
        ordering: false,
        columns: [
            {
                data: 'no'
            },
            { data: 'nama_rencana_aksi' },
            { data: 'satuan_output' },
            { data: 'indikator_output' },
            { 
                render: function (data, type, row, meta) {
                    return '<table class="table table-noborder">'+
                            '<tr><th> TW 1</th><td>: '+formatNumber(row.target_tw1)+'</td></tr>'+
                            '<tr><th> TW 2</th><td>: '+formatNumber(row.target_tw2)+'</td></tr>'+
                            '<tr><th> TW 3</th><td>: '+formatNumber(row.target_tw3)+'</td></tr>'+
                            '<tr><th> TW 4</th><td>: '+formatNumber(row.target_tw4)+'</td></tr>'+
                            '<tr><th> Total</th><td>: '+formatNumber(row.target_total)+'</td></tr>'+
                        '</table>';
                }
            },
            { 
                render: function (data, type, row, meta) {
                    return '<table class="table table-noborder">'+
                            '<tr><th> TW 1</th><td>: '+formatNumber(row.anggaran_tw1)+'</td></tr>'+
                            '<tr><th> TW 2</th><td>: '+formatNumber(row.anggaran_tw2)+'</td></tr>'+
                            '<tr><th> TW 3</th><td>: '+formatNumber(row.anggaran_tw3)+'</td></tr>'+
                            '<tr><th> TW 4</th><td>: '+formatNumber(row.anggaran_tw4)+'</td></tr>'+
                            '<tr><th class="border-top">Total</th><td>: '+formatNumber(row.anggaran_total)+'</td></tr>'+
                        '</table>';
                }
            },
            { 
                render: function (data, type, row, meta) {
                    return row.realisasi_output_total ? '<table class="table table-noborder">'+
                            '<tr><th> TW 1</th><td>: '+formatNumber(row.realisasi_output_tw1)+'</td></tr>'+
                            '<tr><th> TW 2</th><td>: '+formatNumber(row.realisasi_output_tw2)+'</td></tr>'+
                            '<tr><th> TW 3</th><td>: '+formatNumber(row.realisasi_output_tw3)+'</td></tr>'+
                            '<tr><th> TW 4</th><td>: '+formatNumber(row.realisasi_output_tw4)+'</td></tr>'+
                            '<tr><th> Total</th><td>: '+formatNumber(row.realisasi_output_total)+'</td></tr>'+
                        '</table>' : '';
                }
            },
            { 
                render: function (data, type, row, meta) {
                    return row.realisasi_anggaran_total ? '<table class="table table-noborder">'+
                            '<tr><th> TW 1</th><td>: '+formatNumber(row.realisasi_anggaran_tw1)+'</td></tr>'+
                            '<tr><th> TW 2</th><td>: '+formatNumber(row.realisasi_anggaran_tw2)+'</td></tr>'+
                            '<tr><th> TW 3</th><td>: '+formatNumber(row.realisasi_anggaran_tw3)+'</td></tr>'+
                            '<tr><th> TW 4</th><td>: '+formatNumber(row.realisasi_anggaran_tw4)+'</td></tr>'+
                            '<tr><th class="border-top"> Total</th><td>: '+formatNumber(row.realisasi_anggaran_total)+'</td></tr>'+
                        '</table>' : '';
                }
            },
            { 
                render: function (data, type, row, meta) {
                    return row.capaian_output_total ? '<table class="table table-noborder">'+
                            '<tr><th> TW 1</th><td>: '+formatNumber(row.capaian_output_tw1)+'</td></tr>'+
                            '<tr><th> TW 2</th><td>: '+formatNumber(row.capaian_output_tw2)+'</td></tr>'+
                            '<tr><th> TW 3</th><td>: '+formatNumber(row.capaian_output_tw3)+'</td></tr>'+
                            '<tr><th> TW 4</th><td>: '+formatNumber(row.capaian_output_tw4)+'</td></tr>'+
                            '<tr><th class="border-top"> Total</th><td>: '+formatNumber(row.capaian_output_total)+'</td></tr>'+
                        '</table>' : '';
                }
            },
            { 
                render: function (data, type, row, meta) {
                    return row.capaian_anggaran_total ? '<table class="table table-noborder">'+
                            '<tr><th> TW 1</th><td>: '+formatNumber(row.capaian_anggaran_tw1)+'</td></tr>'+
                            '<tr><th> TW 2</th><td>: '+formatNumber(row.capaian_anggaran_tw2)+'</td></tr>'+
                            '<tr><th> TW 3</th><td>: '+formatNumber(row.capaian_anggaran_tw3)+'</td></tr>'+
                            '<tr><th> TW 4</th><td>: '+formatNumber(row.capaian_anggaran_tw4)+'</td></tr>'+
                            '<tr><th class="border-top"> Total</th><td>: '+formatNumber(row.capaian_anggaran_total)+'</td></tr>'+
                        '</table>' : '';
                }
            },
            { 
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button onclick="edit('+row.id+');" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>';
                },
            },
        ],
        rowsGroup: [0,1] 
    }); 

    function getData() {
        monev.ajax.url("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/monev/getDatas')}}").load(null, false);
    }

    function formatNumber(num) {
        return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
    }

    function hitungTotal() {
        if ($('#realisasi_output_tw1').val() == '') {
            $('#realisasi_output_tw1').val(0);
        }
        if ($('#realisasi_output_tw2').val() == '') {
            $('#realisasi_output_tw2').val(0);
        }
        if ($('#realisasi_output_tw3').val() == '') {
            $('#realisasi_output_tw3').val(0);
        }
        if ($('#realisasi_output_tw4').val() == '') {
            $('#realisasi_output_tw4').val(0);
        }
        ro1 = $('#realisasi_output_tw1').val();
        ro2 = $('#realisasi_output_tw2').val();
        ro3 = $('#realisasi_output_tw3').val();
        ro4 = $('#realisasi_output_tw4').val();
        ro_total = parseFloat(ro1) + parseFloat(ro2) + parseFloat(ro3) + parseFloat(ro4);
        $('#realisasi_output_total').val(ro_total);
        co1 = t1 > 0 ? (ro1 / t1) * 100 : 0;
        co2 = t2 > 0 ? (ro2 / t2) * 100 : 0;
        co3 = t3 > 0 ? (ro3 / t3) * 100 : 0;
        co4 = t4 > 0 ? (ro4 / t4) * 100 : 0;
        $('#capaian_output_tw1').val(co1);
        $('#capaian_output_tw2').val(co2);
        $('#capaian_output_tw3').val(co3);
        $('#capaian_output_tw4').val(co4);
        co_pembagi = 0;
        if (co1 > 0) {
            co_pembagi += 1;
        }
        if (co2 > 0) {
            co_pembagi += 1;
        }
        if (co3 > 0) {
            co_pembagi += 1;
        }
        if (co4 > 0) {
            co_pembagi += 1;
        }
        co_total = (co1 + co2 + co3 + co4) / co_pembagi;
        $('#capaian_output_total').val(co_total);
        
        // Hitung Total Anggaran
        if ($('#realisasi_anggaran_tw1').val() == '') {
            $('#realisasi_anggaran_tw1').val(0);
        }
        if ($('#realisasi_anggaran_tw2').val() == '') {
            $('#realisasi_anggaran_tw2').val(0);
        }
        if ($('#realisasi_anggaran_tw3').val() == '') {
            $('#realisasi_anggaran_tw3').val(0);
        }
        if ($('#realisasi_anggaran_tw4').val() == '') {
            $('#realisasi_anggaran_tw4').val(0);
        }
        ra1 = $('#realisasi_anggaran_tw1').val();
        ra2 = $('#realisasi_anggaran_tw2').val();
        ra3 = $('#realisasi_anggaran_tw3').val();
        ra4 = $('#realisasi_anggaran_tw4').val();
        ra_total = parseFloat(ra1) + parseFloat(ra2) + parseFloat(ra3) + parseFloat(ra4);
        $('#realisasi_anggaran_total').val(ra_total);
        ca1 = a1 > 0 ? (ra1 / a1) * 100 : 0;
        ca2 = a2 > 0 ? (ra2 / a2) * 100 : 0;
        ca3 = a3 > 0 ? (ra3 / a3) * 100 : 0;
        ca4 = a4 > 0 ? (ra4 / a4) * 100 : 0;
        $('#capaian_anggaran_tw1').val(ca1);
        $('#capaian_anggaran_tw2').val(ca2);
        $('#capaian_anggaran_tw3').val(ca3);
        $('#capaian_anggaran_tw4').val(ca4);
        ca_pembagi = 0;
        if (ca1 > 0) {
            ca_pembagi += 1;
        }
        if (ca2 > 0) {
            ca_pembagi += 1;
        }
        if (ca3 > 0) {
            ca_pembagi += 1;
        }
        if (ca4 > 0) {
            ca_pembagi += 1;
        }
        ca_total = (ca1 + ca2 + ca3 + ca4) / ca_pembagi;
        $('#capaian_anggaran_total').val(ca_total);
    }

    function edit_monev() {
        $.getJSON("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/monev/getTarget')}}", function(data) {
            $('#realisasi_indikator').val(data.realisasi_indikator);
            $('#capaian_indikator').val(data.capaian_indikator);
            $('#catatan').val(data.catatan);
            modal_monev_perencanaan.show();
        });
    }

    function clearForm() {
        $('#form-monev').trigger('reset');
        $('#rencana_aksi_id').val('');
    }

    function edit(id) {
        clearForm();
        $('#output_id').val(id);
        $('#title').html('Monitoring dan Evaluasi Rencana Aksi');
        $('.saveButton').prop('disabled', true);
        modal_monev.show();
        $.getJSON("{{url('rb-general/perencanaan/'.$target->perencanaan->id.'/'.$target->id.'/monev/getData')}}/"+id, function(data) {
            $('#rencana_aksi').val(data.rencana_aksi.rencana_aksi);
            $('#satuan_output').val(data.satuan_output);
            $('#indikator_output').val(data.indikator_output);
            t1 = data.target_tw1;
            t2 = data.target_tw2;
            t3 = data.target_tw3;
            t4 = data.target_tw4;
            a1 = data.anggaran_tw1;
            a2 = data.anggaran_tw2;
            a3 = data.anggaran_tw3;
            a4 = data.anggaran_tw4;
            $('#target_tw1').val(data.target_tw1);
            $('#target_tw2').val(data.target_tw2);
            $('#target_tw3').val(data.target_tw3);
            $('#target_tw4').val(data.target_tw4);
            $('#target_total').val(data.target_total);
            $('#anggaran_tw1').val(data.anggaran_tw1);
            $('#anggaran_tw2').val(data.anggaran_tw2);
            $('#anggaran_tw3').val(data.anggaran_tw3);
            $('#anggaran_tw4').val(data.anggaran_tw4);
            $('#anggaran_total').val(data.anggaran_total);
            $('#pelaksana').val(data.pelaksana);
            $('#koordinator').val(data.koordinator);
            $('#realisasi_output_tw1').val(data.realisasi_output_tw1);
            $('#realisasi_output_tw2').val(data.realisasi_output_tw2);
            $('#realisasi_output_tw3').val(data.realisasi_output_tw3);
            $('#realisasi_output_tw4').val(data.realisasi_output_tw4);
            $('#realisasi_output_total').val(data.realisasi_output_total);
            $('#realisasi_anggaran_tw1').val(data.realisasi_anggaran_tw1);
            $('#realisasi_anggaran_tw2').val(data.realisasi_anggaran_tw2);
            $('#realisasi_anggaran_tw3').val(data.realisasi_anggaran_tw3);
            $('#realisasi_anggaran_tw4').val(data.realisasi_anggaran_tw4);
            $('#realisasi_anggaran_total').val(data.realisasi_anggaran_total);
            $('#capaian_anggaran_tw1').val(data.capaian_anggaran_tw1);
            $('#capaian_anggaran_tw2').val(data.capaian_anggaran_tw2);
            $('#capaian_anggaran_tw3').val(data.capaian_anggaran_tw3);
            $('#capaian_anggaran_tw4').val(data.capaian_anggaran_tw4);
            $('#capaian_anggaran_total').val(data.capaian_anggaran_total);
            $('.saveButton').prop('disabled', false);
        });
    }
</script>
@endpush