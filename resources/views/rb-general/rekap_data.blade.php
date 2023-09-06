@extends('layout.rubick')
@section('title', 'RB General - Rekap Data')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Data RB General - Rekap Data</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            @if (in_array(auth()->user()->level, ['admin', 'evaluator']))
            <div class="p-5">
                <form>
                    <label for="kegiatan_utama_id" class="form-label mt-2">Instansi</label>
                    {!! Form::select('instansi_id', instansis(), $instansi_id, ['class' => 'mt-2', 'id' => 'instansi_id', 'data-placeholder' => 'Pilih Kegiatan Utama', 'required']) !!}
                    <button type="submit" class="btn btn-success saveButton">Lihat Data</button>
                </form>
            </div>
            @endif
            <table id="perencanaan" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5" rowspan="2">No.</th>
                        <th class="w-10" rowspan="2">Kegiatan Utama</th>
                        <th class="w-10" rowspan="2">Indikator</th>
                        <th class="w-10" rowspan="2">Catatan Evalator</th>
                        <th rowspan="2">Baseline</th>
                        <th class="w-5" rowspan="2">Tahun Target</th>
                        <th class="w-10" rowspan="2">Realisasi Indikator</th>
                        <th class="w-10" rowspan="2">Capaian Indikator</th>
                        <th class="w-10" rowspan="2">Catatan</th>
                        <th class="w-15" rowspan="2">Rencana Aksi</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Output</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Target</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Anggaran</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Unit Satuan Kerja Pelaksana</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Realisasi</th>
                        <th class="w-5" rowspan="2">Capaian Anggaran</th>
                    </tr>
                    <tr>
                        <th>Satuan</th>
                        <th>Indikator</th>
                        <th>Koordinator</th>
                        <th>Pelaksana</th>
                        <th>Output</th>
                        <th>Anggaran</th>
                    </tr>
                </thead>
                <tbody>
                @php 
                    $no = 0; 
                    $nama = '';
                @endphp
                @foreach ($datas as $data)
                    @php 
                        if ($nama != $data['perencanaan']->kegiatan_utama->nama) {
                            $nama = $data['perencanaan']->kegiatan_utama->nama;
                            $no++;
                        }
                    @endphp
                        <tr>
                            <td class="font-bold">{{ $no }}</td>
                            <td class="font-bold">{{ $data['perencanaan']->kegiatan_utama->nama }}</td>
                            <td>{{ $data['perencanaan']->indikator->nama }}</td>
                            <td>
                                @if (auth()->user()->level == 'evaluator')
                                <button onclick="catatan({{ $data['perencanaan']->id }});" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><br>
                                @endif
                                {{ $data['perencanaan']->catatan }}</td>
                            <td>
                                <table class="table table-noborder w-full">
                                    <tr>
                                        <td class="font-bold w-16">Tahun</td>
                                        <td>: {{ $data['perencanaan']->baseline_tahun }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold">Target</td>
                                        <td>: {{ $data['perencanaan']->baseline_target }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold">Realisasi</td>
                                        <td>: {{ $data['perencanaan']->baseline_realisasi }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                @if ($data['target']->target)
                                    <div class="flex items-center"><i data-lucide="bar-chart"
                                            class="w-4 h-4 mr-1"></i><span class="font-bold mr-1">
                                            {{ $data['target']->tahun }}: </span> {{ $data['target']->target }}</div>
                                @endif
                            </td>
                            <td>{{ $data['target']->realisasi_indikator }}</td>
                            <td>{{ $data['target']->capaian_indikator }}</td>
                            <td>{{ $data['target']->catatan }}</td>
                            <td>
                                {{ $data['rencana_aksi']->rencana_aksi }}
                            </td>
                            <td>
                                {{ $data['output']->satuan_output }}
                            </td>
                            <td>
                                {{ $data['output']->indikator_output }}
                            </td>
                            <td>
                                @if ($data['output']->target_total)
                                <table class="table table-noborder">
                                    <tr><th>TW 1</th><td>: {{ fnumber($data['output']->target_tw1) }}</td></tr>
                                    <tr><th>TW 2</th><td>: {{ fnumber($data['output']->target_tw2) }}</td></tr>
                                    <tr><th>TW 3</th><td>: {{ fnumber($data['output']->target_tw3) }}</td></tr>
                                    <tr><th>TW 4</th><td>: {{ fnumber($data['output']->target_tw4) }}</td></tr>
                                    <tr><th>Total</th><td>: {{ fnumber($data['output']->target_total) }}</td></tr>
                                </table>
                                @endif
                            </td>
                            <td>
                                @if ($data['output']->anggaran_total)
                                <table class="table table-noborder">
                                    <tr><th>TW 1</th><td>: {{ fnumber($data['output']->anggaran_tw1) }}</td></tr>
                                    <tr><th>TW 2</th><td>: {{ fnumber($data['output']->anggaran_tw2) }}</td></tr>
                                    <tr><th>TW 3</th><td>: {{ fnumber($data['output']->anggaran_tw3) }}</td></tr>
                                    <tr><th>TW 4</th><td>: {{ fnumber($data['output']->anggaran_tw4) }}</td></tr>
                                    <tr><th>Total</th><td>: {{ fnumber($data['output']->anggaran_total) }}</td></tr>
                                </table>
                                @endif
                            </td>
                            <td>
                                {{ $data['output']->koordinator }}
                            </td>
                            <td>
                                {{ $data['output']->pelaksana }}
                            </td>
                            <td>
                                @if ($data['output']->realisasi_output_total)
                                <table class="table table-noborder">
                                    <tr><th>TW 1</th><td>: {{ fnumber($data['output']->realisasi_output_tw1) }}</td></tr>
                                    <tr><th>TW 2</th><td>: {{ fnumber($data['output']->realisasi_output_tw2) }}</td></tr>
                                    <tr><th>TW 3</th><td>: {{ fnumber($data['output']->realisasi_output_tw3) }}</td></tr>
                                    <tr><th>TW 4</th><td>: {{ fnumber($data['output']->realisasi_output_tw4) }}</td></tr>
                                    <tr><th>Total</th><td>: {{ fnumber($data['output']->realisasi_output_total) }}</td></tr>
                                </table>
                                @endif
                            </td>
                            <td>
                                @if ($data['output']->realisasi_anggaran_total)
                                <table class="table table-noborder">
                                    <tr><th>TW 1</th><td>: {{ fnumber($data['output']->realisasi_anggaran_tw1) }}</td></tr>
                                    <tr><th>TW 2</th><td>: {{ fnumber($data['output']->realisasi_anggaran_tw2) }}</td></tr>
                                    <tr><th>TW 3</th><td>: {{ fnumber($data['output']->realisasi_anggaran_tw3) }}</td></tr>
                                    <tr><th>TW 4</th><td>: {{ fnumber($data['output']->realisasi_anggaran_tw4) }}</td></tr>
                                    <tr><th>Total</th><td>: {{ fnumber($data['output']->realisasi_anggaran_total) }}</td></tr>
                                </table>
                                @endif
                            </td>
                            <td>
                                {{ $data['output']->capaian_anggaran }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if (auth()->user()->level == 'evaluator')
{{-- Modal Form Catatan Evaluator --}}
<div id="modal-catatan" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Monitoring dan Evaluasi Perencanaan</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('rb-general/rekap_data/simpanCatatanEvaluator') }}" id="form-catatan_evaluator" method="post">
                @csrf
                <input type="hidden" id="perencanaan_id" name="perencanaan_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table table-noborder">
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
@endif
@endsection

@push('js')
<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script>
    $(function() {
        $("#perencanaan").DataTable({
            scrollX: true,
            orderFixed: [0, 'asc'],
            autoWidth: false,
            rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ordering: false,
        });
        modal_catatan = tailwind.Modal.getInstance(document.querySelector("#modal-catatan"));
    });

    @if (auth()->user()->level == 'evaluator')
    function catatan(id) {
        $('#perencanaan_id').val(id);
        $.getJSON("{{url('rb-general/rekap_data/getPerencanaan')}}/"+id, function(data) {
            $('#catatan').val(data.catatan);
            modal_catatan.show();
        });
    }
    @endif
</script>
@endpush
