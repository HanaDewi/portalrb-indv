@extends('layout.rubick')
@section('title', 'Hasil Semua')

@section('content')
    @include('common.status')
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <h2 class="font-bold text-base mr-auto"> Hasil Semua</h2>
            </div>
            <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
                <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                    <thead class="table-dark font-bold">
                        <tr>
                            <th class="w-5">No.</th>
                            <th class="w100">KLPD</th>
                            <th class="w200">Kegiatan </th>
                            <th class="w200">Index RB </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 0;
                        @endphp
                        @foreach ($instansis as $instansi)
                            @php
                                $no++;
                            @endphp
                            <tr>
                                <td>{{ $no }}</td>
                                <td><a href="{{ URL::to('/hasil/' . $instansi->id) }}">{{ $instansi->name }}</a></td>
                                <td>
                                    @php
                                        if (isset($instansi->lke_test_tp->lke_kegiatan)) {
                                            echo $instansi->lke_test_tp->lke_kegiatan->name;
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        if (isset($instansi->lke_test_tp)) {
                                            echo round($instansi->lke_test_tp->index_rb, 2);
                                        }
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form Baseline --}}
    <div id="modal-baseline" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium  fs-base me-auto" id="title">Data Baseline</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-general/perencanaan/simpanBaseline') }}" id="form-baseline" method="post">
                    @csrf
                    <input type="hidden" name="kegiatan_utama_id" id="baseline_kegiatan_utama_id">
                    <input type="hidden" name="indikator_id" id="baseline_indikator_id">
                    <div class="modal-body grid columns-12 gap-4 gap-y-3">
                        <div class="g-col-12">
                            <table class="table table-bordered hover">
                                <tr>
                                    <td class="font-bold">Kegiatan Utama</td>
                                    <td id="baseline_kegiatan_utama"></td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Indikator</td>
                                    <td id="baseline_indikator"></td>
                                </tr>
                            </table>
                            <hr class="mt-5 mb-5">
                            <table class="table table-bordered table-striped hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="baseline_tahun" id="baseline_tahun"
                                                class="form-control w-full tahun" value="{{ date('Y') - 1 }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="baseline_target" id="baseline_target"
                                                class="form-control w-full" required>
                                        </td>
                                        <td>
                                            <input type="text" name="baseline_realisasi" id="baseline_realisasi"
                                                class="form-control w-full" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-end">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 me-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20 saveButton">Simpan</button>
                    </div> <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div> <!-- END: Modal Content -->

    {{-- Modal Form Target --}}
    <div id="modal-target" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium fs-base me-auto" id="title">Data Target</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-general/perencanaan/simpanTarget') }}" id="form-target" method="post">
                    @csrf
                    <input type="hidden" name="kegiatan_utama_id" id="target_kegiatan_utama_id">
                    <input type="hidden" name="indikator_id" id="target_indikator_id">
                    <div class="modal-body grid columns-12 gap-4 gap-y-3">
                        <div class="g-col-12">
                            <table class="table table-bordered hover">
                                <tr>
                                    <td class="font-bold">Kegiatan Utama</td>
                                    <td id="target_kegiatan_utama"></td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Indikator</td>
                                    <td id="target_indikator"></td>
                                </tr>
                            </table>
                            <hr class="mt-5 mb-5">
                            <table class="table table-bordered table-striped hover" id="target-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" onclick="tambah_tahun();"
                                class="btn btn-outline-primary border-dashed w-full"><i data-lucide="plus"
                                    class="w-4 h-4 mr-2"></i></button>
                        </div>
                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-end">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 me-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20 saveButton">Simpan</button>
                    </div> <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div> <!-- END: Modal Content -->

    {{-- Modal Form Monev --}}
    <div id="modal-monev" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium fs-base me-auto" id="title-monev">Monitoring dan Evaluasi Perencanaan
                    </h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-general/perencanaan/simpanMonev') }}" id="form-monev" method="post">
                    @csrf
                    <input type="hidden" name="kegiatan_utama_id" id="monev_kegiatan_utama_id">
                    <input type="hidden" name="indikator_id" id="monev_indikator_id">
                    <div class="modal-body grid columns-12 gap-4 gap-y-3">
                        <div class="g-col-12">
                            <table class="table">
                                <tr>
                                    <td class="font-bold w-44">Realisasi Indikator <span class="text-danger">*</span></td>
                                    <td>
                                        <input type="text" name="realisasi_indikator" id="realisasi_indikator"
                                            placeholder="Realisasi Indikator" class="form-control" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-bold w-44">Capaian Indikator <span class="text-danger">*</span></td>
                                    <td>
                                        <input type="text" name="capaian_indikator" id="capaian_indikator"
                                            placeholder="Capaian Indikator" class="form-control" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-bold w-44 align-top">Catatan</td>
                                    <td>
                                        <textarea rows="5" name="catatan" id="catatan" placeholder="Penjelasan Rencana Aksi" class="form-control"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer text-end">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 me-1">Cancel</button>
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
            modal_baseline = tailwind.Modal.getInstance(document.querySelector("#modal-baseline"));
            modal_target = tailwind.Modal.getInstance(document.querySelector("#modal-target"));
            modal_monev = tailwind.Modal.getInstance(document.querySelector("#modal-monev"));
            $(".tahun").inputmask('2099');

            $('#form-baseline').validate({
                highlight: function(input) {
                    $(input).addClass('border-danger');
                },
                unhighlight: function(input) {
                    $(input).removeClass('border-danger');
                },
                errorPlacement: function(error, element) {
                    var placement = element.closest('td');
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
                    form.submit();
                }
            });

            $('#form-target').validate({
                highlight: function(input) {
                    $(input).addClass('border-danger');
                },
                unhighlight: function(input) {
                    $(input).removeClass('border-danger');
                },
                errorPlacement: function(error, element) {
                    var placement = element.closest('td');
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
                    form.submit();
                }
            });

            $('#form-monev').validate({
                highlight: function(input) {
                    $(input).addClass('border-danger');
                },
                unhighlight: function(input) {
                    $(input).removeClass('border-danger');
                },
                errorPlacement: function(error, element) {
                    var placement = element.closest('td');
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
                    form.submit();
                }
            });
        });

        function atur_baseline(kegiatan_utama_id, indikator_id) {
            $('#baseline_kegiatan_utama_id').val(kegiatan_utama_id);
            $('#baseline_indikator_id').val(indikator_id);
            kegiatan_utama = $('#kegiatan_utama' + indikator_id).html();
            indikator = $('#indikator' + indikator_id).html();
            $('#baseline_kegiatan_utama').html(kegiatan_utama);
            $('#baseline_indikator').html(indikator);
            $('.saveButton').prop('disabled', true);
            modal_baseline.show();
            $.getJSON("{{ url('rb-general/perencanaan/getData') }}/" + kegiatan_utama_id + "/" + indikator_id, function(
                data) {
                tahun = data.baseline_tahun ? data.baseline_tahun : 2022;
                $('#baseline_tahun').val(tahun);
                $('#baseline_target').val(data.baseline_target);
                $('#baseline_realisasi').val(data.baseline_realisasi);
                $('.saveButton').prop('disabled', false);
            });
        }

        function atur_target(kegiatan_utama_id, indikator_id) {
            $('#target-table tbody').html('');
            $('#target_kegiatan_utama_id').val(kegiatan_utama_id);
            $('#target_indikator_id').val(indikator_id);
            kegiatan_utama = $('#kegiatan_utama' + indikator_id).html();
            indikator = $('#indikator' + indikator_id).html();
            $('#target_kegiatan_utama').html(kegiatan_utama);
            $('#target_indikator').html(indikator);
            $('.saveButton').prop('disabled', true);
            modal_target.show();
            $.getJSON("{{ url('rb-general/perencanaan/getTarget') }}/" + kegiatan_utama_id + "/" + indikator_id, function(
                data) {
                if (data.success) {
                    $('#target-table tbody').append(data.input);
                } else {
                    Swal.fire('Aduh!',
                        'Data Target belum bisa di-input, Data Baseline-nya belum ada! Input dulu Data Baseline-nya ya..',
                        'error');
                    modal_target.hide();
                }
                $('.saveButton').prop('disabled', false);
            });
        }

        function atur_monev(kegiatan_utama_id, indikator_id) {
            $('#monev_kegiatan_utama_id').val(kegiatan_utama_id);
            $('#monev_indikator_id').val(indikator_id);
            $('#realisasi_indikator').val('');
            $('#capaian_indikator').val('');
            $('.saveButton').prop('disabled', true);
            modal_monev.show();
            $.getJSON("{{ url('rb-general/perencanaan/getData') }}/" + kegiatan_utama_id + "/" + indikator_id, function(
                data) {
                if (data.baseline_tahun) {
                    $('#realisasi_indikator').val(data.realisasi_indikator);
                    $('#capaian_indikator').val(data.capaian_indikator);
                    $('#catatan').val(data.catatan);
                    $('.saveButton').prop('disabled', false);
                } else {
                    Swal.fire('Aduh!',
                        'Data Monev belum bisa di-input, Data Baseline-nya belum ada! Input dulu Data Baseline-nya ya..',
                        'error');
                    modal_monev.hide();
                }
            });
        }

        function tambah_tahun() {
            idx = $('#target-table tbody tr').length;
            last_idx = idx - 1;
            last_tahun = $('#target_tahun' + last_idx).val();
            tahun = parseInt(last_tahun) + 1;
            input = '<tr>' +
                '<td><input type="text" name="tahun[' + idx + ']" id="target_tahun' + idx +
                '" class="form-control w-full tahun" value="' + tahun + '" required></td>' +
                '<td><input type="text" name="target[' + idx + ']" id="target_target' + idx +
                '" class="form-control w-full" required></td>' +
                '</tr>';
            $('#target-table tbody').append(input);
        }
    </script>
    <script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script>
        $(function() {
            $("#perencanaan").DataTable({
                scrollX: true,
                // 'orderFixed': [0, 'asc'],
                autoWidth: false,
                rowsGroup: [0, 1, 2, 3],
                paging: false,
                bInfo: false,
                ordering: false,
            });
        });
    </script>
@endpush
