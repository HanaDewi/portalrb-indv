@extends('layout.rubick')
@section('title', 'RB General - Perencanaan')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data RB General - Perencanaan</h2>
            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0"></div>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped hover mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th rowspan="2" class="w-5">No.</th>
                        <th class="w200" rowspan="2">Kegiatan Utama</th>
                        <th class="w150" rowspan="2">Indikator</th>
                        <th class="w200" colspan="3">Baseline</th>
                        <th class="w150" rowspan="2">Target</th>
                        <th  rowspan="2" class="w-5">Atur</th>
                    </tr>
                    <tr>
                        <th>Tahun</th>
                        <th>Target</th>
                        <th>Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($indikators as $indikator)
                    <tr>
                        <td class="font-bold w-2">{{ $no }}</td>
                        <td class="font-bold w-10" id="kegiatan_utama{{ $indikator->id }}">{{ $indikator->kegiatan_utama->nama }}</td>
                        <td class="w-5" id="indikator{{ $indikator->id }}">{{ $indikator->nama }}</td>
                        <td class="w-5">{{ $indikator->baseline_tahun }}</td>
                        <td class="w-5">{{ $indikator->baseline_target }}</td>
                        <td class="w-5">{{ $indikator->baseline_realisasi }}</td>
                        <td class="w-5">
                        @if (count($indikator->target))
                            @foreach ($indikator->target as $key => $target)


                            <div class="flex items-center"><i data-lucide="bar-chart" class="w-4 h-4 mr-2"></i><span class="font-bold"> {{ $target->tahun }}:</span> {{ $target->target }}</div>

                                


                            <div class="inline-flex w-full mb-5p" role="group">
                                <button class="btn btn-primary btn-sm mr-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i> Renaksi</button>
                                <button class="btn btn-dark btn-sm"><i data-lucide="edit" class="w-4 h-4 mr-1"></i> Monev</button>
                            </div>

                          <hr> 

              

                            @endforeach
                        @else
                        @endif
                        </td>
                        <td class="w-15">
                           <button onclick="atur_baseline('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-primary btn-sm w-full mb-5"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Baseline</button>

                            <button onclick="atur_target('{{ $indikator->kegiatan_utama_id }}', '{{ $indikator->id }}');" class="btn btn-dark btn-sm w-full mb-5"><i data-lucide="edit" class="w-4 h-4 mr-1"></i> Target</button>

                        </td>


                    </tr>
                    @php
                        $no++;
                    @endphp
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
@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>

<script>
    $(document).ready(function() {
        modal_baseline = tailwind.Modal.getInstance(document.querySelector("#modal-baseline"));
        modal_target = tailwind.Modal.getInstance(document.querySelector("#modal-target"));
        $(".tahun").inputmask('2099');
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
            'orderFixed': [0, 'asc'],
            'autoWidth': false,
            'rowsGroup': [1],
      });
         });
      </script>


@endpush
