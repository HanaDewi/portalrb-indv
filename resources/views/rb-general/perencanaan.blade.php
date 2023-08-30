@extends('layout.rubick')
@section('title', 'RB General - Perencanaan')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data RB General - Perencanaan</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w200">Kegiatan Utama</th>
                        <th class="w150">Indikator</th>
                        <th>Baseline</th>
                        <th class="w-5">Target</th>
                        <th class="w-5">Realisasi Indikator</th>
                        <th class="w-5">Capaian Indikator</th>
                        <th class="w-5">Catatan</th>
                        <th class="w-5">Atur</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 0; 
                        $nama = '';
                    @endphp
                    @foreach ($indikators as $indikator)
                    @php 
                        if ($nama != $indikator->kegiatan_utama->nama) {
                            $nama = $indikator->kegiatan_utama->nama;
                            $no++;
                        }
                    @endphp
                    @if (count($indikator->target))
                    @foreach ($indikator->target as $target)
                    <tr>
                        <td class="font-bold">{{ $no }}</td>
                        <td class="font-bold" id="kegiatan_utama{{ $indikator->id }}">{{ $nama }}</td>
                        <td id="indikator{{ $indikator->id }}">{{ $indikator->nama }}</td>
                        <td>
                            @if ($indikator->baseline_tahun)
                            <table class="table table-noborder">
                                <tr>
                                    <td class="font-bold w-16">Tahun</td>
                                    <td>: {{ $indikator->baseline_tahun }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Target</td>
                                    <td>: {{ $indikator->baseline_target }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Realisasi</td>
                                    <td>: {{ $indikator->baseline_realisasi }}</td>
                                </tr>
                            </table>
                            @endif
                            <button onclick="atur_baseline('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-warning btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Baseline</button>
                            <button onclick="atur_target('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-primary btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Target</button>
                        </td>
                        <td>
                            <div class="flex items-center"><i data-lucide="bar-chart" class="w-4 h-4 mr-1"></i><span class="font-bold mr-1"> {{ $target->tahun }}: </span> {{ $target->target }}</div>
                        </td>
                        <td>{{ $target->realisasi_indikator }}</td>
                        <td>{{ $target->capaian_indikator }}</td>
                        <td>{{ $target->catatan }}</td>
                        <td>
                            <a href="{{ url('rb-general/perencanaan/'.$indikator->perencanaan_id.'/'.$target->id.'/rencana_aksi') }}" class="btn btn-primary btn-sm w-full mb-2">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                Renaksi
                                <span class="text-xs px-1 rounded-full bg-warning text-white badge">{{ count($target->rencana_aksi) }}</span>
                            </a>
                            <br>
                            <a href="{{ url('rb-general/perencanaan/'.$indikator->perencanaan_id.'/'.$target->id.'/monev') }}" class="btn btn-dark btn-sm w-full"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Monev</a>
                            {{-- <button onclick="atur_monev('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-dark btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Monev</button> --}}
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="font-bold">{{ $no }}</td>
                        <td class="font-bold" id="kegiatan_utama{{ $indikator->id }}">{{ $nama }}</td>
                        <td id="indikator{{ $indikator->id }}">{{ $indikator->nama }}</td>
                        <td>
                            @if ($indikator->baseline_tahun)
                            <table class="table table-noborder">
                                <tr>
                                    <td class="font-bold w-16">Tahun</td>
                                    <td>: {{ $indikator->baseline_tahun }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Target</td>
                                    <td>: {{ $indikator->baseline_target }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Realisasi</td>
                                    <td>: {{ $indikator->baseline_realisasi }}</td>
                                </tr>
                            </table>
                            @endif
                            <button onclick="atur_baseline('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-warning btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Baseline</button>
                        </td>
                        <td>
                        @if (count($indikator->target))
                            <button onclick="atur_target('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-primary btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Target</button>
                        @else
                        @endif
                        </td>
                        <td>{{ $indikator->realisasi_indikator }}</td>
                        <td>{{ $indikator->capaian_indikator }}</td>
                        <td>{{ $indikator->catatan }}</td>
                        <td>
                            @foreach ($indikator->target as $key => $target)
                            @php
                                $hr = $key > 0 ? '<hr class="mt-2 mb-2">' : '';
                            @endphp
                            {!! $hr !!}
                            <div class="flex items-center"><i data-lucide="bar-chart" class="w-4 h-4 mr-1"></i><span class="font-bold mr-1"> {{ $target->tahun }}: </span> {{ $target->target }}</div>
                            <a href="{{ url('rb-general/perencanaan/'.$indikator->perencanaan_id.'/'.$target->id.'/rencana_aksi') }}" class="btn btn-primary btn-sm w-full mb-2">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                Renaksi
                                <span class="text-xs px-1 rounded-full bg-warning text-white badge">{{ count($target->rencana_aksi) }}</span>
                            </a>
                            <br>
                            <a href="{{ url('rb-general/perencanaan/'.$indikator->perencanaan_id.'/'.$target->id.'/monev') }}" class="btn btn-dark btn-sm w-full"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Monev</a>
                            @endforeach
                            {{-- <button onclick="atur_monev('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-dark btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Monev</button> --}}
                        </td>
                    </tr>
                    @endif
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
                                        <input type="text" name="baseline_tahun" id="baseline_tahun" class="form-control w-full tahun" value="{{ date('Y') - 1 }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="baseline_target" id="baseline_target" class="form-control w-full" required>
                                    </td>
                                    <td>
                                        <input type="text" name="baseline_realisasi" id="baseline_realisasi" class="form-control w-full" required>
                                    </td>
                                </tr>
                            </tbody>
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
                        <button type="button" onclick="tambah_tahun();" class="btn btn-outline-primary border-dashed w-full"><i data-lucide="plus" class="w-4 h-4 mr-2"></i></button>
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

{{-- Modal Form Monev --}}
<div id="modal-monev" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title-monev">Monitoring dan Evaluasi Perencanaan</h2>
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
                                    <input type="text" name="realisasi_indikator" id="realisasi_indikator" placeholder="Realisasi Indikator" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold w-44">Capaian Indikator <span class="text-danger">*</span></td>
                                <td>
                                    <input type="text" name="capaian_indikator" id="capaian_indikator" placeholder="Capaian Indikator" class="form-control" required>
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
        modal_baseline = tailwind.Modal.getInstance(document.querySelector("#modal-baseline"));
        modal_target = tailwind.Modal.getInstance(document.querySelector("#modal-target"));
        modal_monev = tailwind.Modal.getInstance(document.querySelector("#modal-monev"));
        $(".tahun").inputmask('2099');

        $('#form-baseline').validate({
            highlight: function (input) {
                $(input).addClass('border-danger');
            },
            unhighlight: function (input) {
                $(input).removeClass('border-danger');
            },
            errorPlacement: function( error, element ) {
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
            highlight: function (input) {
                $(input).addClass('border-danger');
            },
            unhighlight: function (input) {
                $(input).removeClass('border-danger');
            },
            errorPlacement: function( error, element ) {
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
            highlight: function (input) {
                $(input).addClass('border-danger');
            },
            unhighlight: function (input) {
                $(input).removeClass('border-danger');
            },
            errorPlacement: function( error, element ) {
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
        kegiatan_utama = $('#kegiatan_utama'+indikator_id).html();
        indikator = $('#indikator'+indikator_id).html();
        $('#baseline_kegiatan_utama').html(kegiatan_utama);
        $('#baseline_indikator').html(indikator);
        $('.saveButton').prop('disabled', true);
        modal_baseline.show();
        $.getJSON("{{url('rb-general/perencanaan/getData')}}/"+kegiatan_utama_id+"/"+indikator_id, function(data) {
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
        kegiatan_utama = $('#kegiatan_utama'+indikator_id).html();
        indikator = $('#indikator'+indikator_id).html();
        $('#target_kegiatan_utama').html(kegiatan_utama);
        $('#target_indikator').html(indikator);
        $('.saveButton').prop('disabled', true);
        modal_target.show();
        $.getJSON("{{url('rb-general/perencanaan/getTarget')}}/"+kegiatan_utama_id+"/"+indikator_id, function(data) {
            if (data.success) {
                $('#target-table tbody').append(data.input);
            } else {
                Swal.fire('Aduh!', 'Data Target belum bisa di-input, Data Baseline-nya belum ada! Input dulu Data Baseline-nya ya..', 'error');
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
        $.getJSON("{{url('rb-general/perencanaan/getData')}}/"+kegiatan_utama_id+"/"+indikator_id, function(data) {
            if (data.baseline_tahun) {
                $('#realisasi_indikator').val(data.realisasi_indikator);
                $('#capaian_indikator').val(data.capaian_indikator);
                $('#catatan').val(data.catatan);
                $('.saveButton').prop('disabled', false);
            } else {
                Swal.fire('Aduh!', 'Data Monev belum bisa di-input, Data Baseline-nya belum ada! Input dulu Data Baseline-nya ya..', 'error');
                modal_monev.hide();
            }
        });
    }

    function tambah_tahun() {
        idx = $('#target-table tbody tr').length;
        last_idx = idx - 1;
        last_tahun = $('#target_tahun'+last_idx).val();
        tahun = parseInt(last_tahun) + 1;
        input = '<tr>'+
                    '<td><input type="text" name="tahun['+idx+']" id="target_tahun'+idx+'" class="form-control w-full tahun" value="'+tahun+'" required></td>'+
                    '<td><input type="text" name="target['+idx+']" id="target_target'+idx+'" class="form-control w-full" required></td>'+
                '</tr>';
        $('#target-table tbody').append(input);
    }
</script>
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script>
    $(function() {
        $("#perencanaan").DataTable({
            'scrollX': true,
            // 'orderFixed': [0, 'asc'],
            'autoWidth': false,
            'rowsGroup': [0, 1, 2, 3],
            paging: false,
            bInfo: false,
        });
    });
</script>
@endpush
