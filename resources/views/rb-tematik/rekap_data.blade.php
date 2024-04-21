@extends('layout.rubick')
@section('title', 'Rekap Data RB General - ' .auth()->user()->nama)

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Rekap Data RB General - {{ auth()->user()->nama }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <form>
                <div>
                    <label for="indikator_id" class="form-label font-bold">Indikator</label>
                    {!! Form::select('sasaran_id[]', indikators(), $sasaran_id, ['class' => 'w-full', 'id' => 'indikator_id', 'data-placeholder' => 'Pilih Indikator', 'multiple' => 'multiple']) !!}
                </div>
                @if (in_array(auth()->user()->level, ['admin', 'tpn']))
                <div class="grid grid-cols-4">
                    <div class="col-span-3">
                        <label for="instansi_id" class="form-label mt-2 font-bold">Instansi</label>
                        {!! Form::select('instansi_id', instansis(), $instansi_id, ['class' => 'tom-select mt-1', 'id' => 'instansi_id', 'data-placeholder' => 'Pilih Kegiatan Utama', 'required']) !!}
                    </div>
                    <div class="ml-5">
                        <button type="submit" class="btn btn-success saveButton mt-10">Lihat Data</button>
                    </div>
                </div>
                @else
                <div class="mt-5 pb-10">
                    <button type="submit" class="btn btn-success saveButton float-right">Lihat Data</button>
                </div>
                @endif
            </form>
            <div class="separator mt-5"></div>
            <table id="perencanaan" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5" rowspan="2">No.</th>
                        <th class="w-10" rowspan="2">Tema</th>
                        <th class="w-10" rowspan="2">Sasaran</th>
                        <th rowspan="2">Indikator_roadmap</th>
                        <th class="w-5" rowspan="2">Target</th>
                        <th class="w-10" rowspan="2">Satuan Target</th>
                        <th class="w-10" rowspan="2">Realisasi</th>
                        <th class="w-10" rowspan="2">Capaian</th>
                        <th class="w-10" rowspan="2">Catatan</th>
                        <th class="w-10" rowspan="2">Catatan Evalator</th>
                        <th class="w-15" rowspan="2">Permasalahan</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Sasaran</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Indikator Permasalahan</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Target</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Satuan</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Realisasi</th>
                        <th class="w-10" rowspan="2">Capaian</th>
                        <th class="w-10" rowspan="2">Catatan</th>
                        <th class="w-10" rowspan="2">Catatan Evalator</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Rencana Aksi</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Indikator Output</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Satuan Output</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Target</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Anggaran</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Fokus Intervensi</th>
                        <th class="w-5" colspan="2" style="text-align: center;">Unit Satuan Kerja</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Realisasi Output</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Realisasi Anggaran</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Capaian Output</th>
                        <th class="w-5" rowspan="2" style="text-align: center;">Capaian Anggaran</th>
                    </tr>
                    <tr>
                        <th>Koordinator</th>
                        <th>Pelaksana</th>
                    </tr>
                </thead>
                <tbody>
                @php 
                    $no = 0; 
                    $nama = '';
                @endphp
              
                @foreach ($datas as $data)
                    @php 
                        if ($nama != $data['sasaran_roadmap']->tema->nama) {
                            $nama = $data['sasaran_roadmap']->tema->nama;
                            $no++;
                        }
                    @endphp
                        <tr>
                            <td class="font-bold">{{ $no }}</td>
                            <td class="font-bold">{{ $data['sasaran_roadmap']->tema->nama }}</td>
                            <td>{{ $data['sasaran_roadmap']->nama }}</td>
                            <td>
                                {{ $data['indikator_roadmap']->nama }}
                            </td>
                            <td>
                                {{ $data['indikator_roadmap']->target }}
                            </td>
                            <td>
                                {{ $data['indikator_roadmap']->satuan }}
                            </td>
                            <td>{{ $data['indikator_roadmap']->realisasi_indikator }}</td>
                            <td>{{ $data['indikator_roadmap']->capaian_indikator }}</td>
                            <td>{{ $data['indikator_roadmap']->catatan }}</td>
                            <td>
                                {{ $data['indikator_roadmap']->catatan_evaluator }}
                                @if (auth()->user()->level == 'tpn')
                                <button onclick="catatan_evaluator({{ $data['indikator_roadmap']->id }});" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="https://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><br>
                                @endif
                                
                            </td>
                            <td>
                                {{ $data['permasalahan']->nama }}
                            </td>
                            <td>
                                {{ $data['permasalahan']->sasaran_permasalahan }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->nama }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->target }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->satuan }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->realisasi_indikator }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->capaian_indikator }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->catatan }}
                            </td>
                            <td>
                                {{ $data['indikator_permasalahan']->catatan_evaluator }}
                            </td>   
                            <td>
                                {{ $data['rencana_aksi']->nama }}
                            </td>
                            <td>
                                {{ $data['output']->indikator_output }}
                            </td>
                            <td>
                                {{ $data['output']->satuan_output }}
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
                                {{ $data['output']->get_intervensi }}
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
                                @if ($data['output']->capaian_output_total)
                                <table class="table table-noborder">
                                    <tr><th>TW 1</th><td>: {{ fnumber($data['output']->capaian_output_tw1) }}</td></tr>
                                    <tr><th>TW 2</th><td>: {{ fnumber($data['output']->capaian_output_tw2) }}</td></tr>
                                    <tr><th>TW 3</th><td>: {{ fnumber($data['output']->capaian_output_tw3) }}</td></tr>
                                    <tr><th>TW 4</th><td>: {{ fnumber($data['output']->capaian_output_tw4) }}</td></tr>
                                    <tr><th>Total</th><td>: {{ fnumber($data['output']->capaian_output_total) }}</td></tr>
                                </table>
                                @endif
                            </td>
                            <td>
                                @if ($data['output']->capaian_anggaran_total)
                                <table class="table table-noborder">
                                    <tr><th>TW 1</th><td>: {{ fnumber($data['output']->capaian_anggaran_tw1, 2) }}</td></tr>
                                    <tr><th>TW 2</th><td>: {{ fnumber($data['output']->capaian_anggaran_tw2, 2) }}</td></tr>
                                    <tr><th>TW 3</th><td>: {{ fnumber($data['output']->capaian_anggaran_tw3, 2) }}</td></tr>
                                    <tr><th>TW 4</th><td>: {{ fnumber($data['output']->capaian_anggaran_tw4, 2) }}</td></tr>
                                    <tr><th>Total</th><td>: {{ fnumber($data['output']->capaian_anggaran_total, 2) }}</td></tr>
                                </table>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if (auth()->user()->level == 'tpn')
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
                <input type="hidden" id="target_id" name="target_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table table-noborder">
                            <tr>
                                <td class="font-bold">Catatan Evaluator</td>
                                <td>
                                    <textarea name="catatan_evaluator" id="catatan_evaluator" cols="30" rows="10" placeholder="Catatan" class="form-control mt-4"></textarea>
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

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function(){
    $('#indikator_id').select2();

    var empDataTable = $('#perencanaan').DataTable({
        dom: 'Blfrtip',
        buttons: [
        {
            extend: 'pdf',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,18,19,20]
            },
            orientation: 'landscape',
            pageSize: 'Legal',
            title: 'Rekap Data RB General - {{ $nama_instansi }}',
            customize: function(doc) {
            doc.defaultStyle.fontSize = 8; 
            doc.styles.tableHeader.fontSize = 7.5;
            doc.content[1].table.widths = [ '1.7%', '7%', '4.5%', '4.5%', '4.5%', '4.5%', '6%', '4.5%', '4.5%', '6.5%', '4.5%', '4.5%', '5.5%','6%', '5.5%', '5%', '6%', '6%', '6%', '6%' ]; },
            text: '<button class="btn btn-danger btn-sm w-32 mr-2 mb-2"><svg fill="#ffffff" height="18px" width="18px" version="1.1" id="Capa_1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" viewBox="0 0 482.14 482.14" xml:space="preserve" stroke="#ffffff"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <g> <path d="M142.024,310.194c0-8.007-5.556-12.782-15.359-12.782c-4.003,0-6.714,0.395-8.132,0.773v25.69 c1.679,0.378,3.743,0.504,6.588,0.504C135.57,324.379,142.024,319.1,142.024,310.194z"/> <path d="M202.709,297.681c-4.39,0-7.227,0.379-8.905,0.772v56.896c1.679,0.394,4.39,0.394,6.841,0.394 c17.809,0.126,29.424-9.677,29.424-30.449C230.195,307.231,219.611,297.681,202.709,297.681z"/> <path d="M315.458,0H121.811c-28.29,0-51.315,23.041-51.315,51.315v189.754h-5.012c-11.418,0-20.678,9.251-20.678,20.679v125.404 c0,11.427,9.259,20.677,20.678,20.677h5.012v22.995c0,28.305,23.025,51.315,51.315,51.315h264.223 c28.272,0,51.3-23.011,51.3-51.315V121.449L315.458,0z M99.053,284.379c6.06-1.024,14.578-1.796,26.579-1.796 c12.128,0,20.772,2.315,26.58,6.965c5.548,4.382,9.292,11.615,9.292,20.127c0,8.51-2.837,15.745-7.999,20.646 c-6.714,6.32-16.643,9.157-28.258,9.157c-2.585,0-4.902-0.128-6.714-0.379v31.096H99.053V284.379z M386.034,450.713H121.811 c-10.954,0-19.874-8.92-19.874-19.889v-22.995h246.31c11.42,0,20.679-9.25,20.679-20.677V261.748 c0-11.428-9.259-20.679-20.679-20.679h-246.31V51.315c0-10.938,8.921-19.858,19.874-19.858l181.89-0.19v67.233 c0,19.638,15.934,35.587,35.587,35.587l65.862-0.189l0.741,296.925C405.891,441.793,396.987,450.713,386.034,450.713z M174.065,369.801v-85.422c7.225-1.15,16.642-1.796,26.58-1.796c16.516,0,27.226,2.963,35.618,9.282 c9.031,6.714,14.704,17.416,14.704,32.781c0,16.643-6.06,28.133-14.453,35.224c-9.157,7.612-23.096,11.222-40.125,11.222 C186.191,371.092,178.966,370.446,174.065,369.801z M314.892,319.226v15.996h-31.23v34.973h-19.74v-86.966h53.16v16.122h-33.42 v19.875H314.892z"/> </g> </g> </svg> &nbsp;PDF </button>',
                    titleAttr: 'Download PDF'
        },
        {
            extend: 'excel',
            text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="https://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                    titleAttr: 'Download Excel'
        } 
        ],
        scrollX: true,
            // 'orderFixed': [0, 'asc'],
            autoWidth: false,
            rowsGroup: [0, 1, 2, 3, 4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
            paging: true,
            bInfo: false,
            ordering: false,
    });
        modal_catatan = tailwind.Modal.getInstance(document.querySelector("#modal-catatan"));
    });

@if (auth()->user()->level == 'tpn')
function catatan_evaluator(id) {
    $('#target_id').val(id);
    $.getJSON("{{url('rb-general/rekap_data/getTarget')}}/"+id, function(data) {
        $('#catatan_evaluator').val(data.catatan_evaluator);
        modal_catatan.show();
    });
}
@endif
</script>
@endpush
