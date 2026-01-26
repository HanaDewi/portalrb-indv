@extends('layout.rubick')
@section('title', 'Rekap Data RB Tematik - ' . auth()->user()->nama)

@section('content')
    <div class="intro-y col-span-12 lg:col-span-12">
        @include('common.status')
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <h2 class="font-bold text-base mr-auto"> Rekap Data RB Tematik - {{ auth()->user()->nama }}</h2>
            </div>
            <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
                <form id="filter-form" method="get">
                    <table class="table table-bordered table-striped mt-5">
                        @if (in_array(auth()->user()->level, ['admin', 'tpn', 'viewer']))
                            <tr>
                                <td class="font-bold">Instansi</td>
                                <td>
                                    <select class="form-control tom-select mt-1" name="instansi_id[]" multiple>
                                        @foreach (instansis() as $idx => $ins)
                                            <option value="{{ $idx }}" {{ in_array($idx, $finstansi) ? 'selected' : '' }}>{{ $ins }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="font-bold" width="220">Tahun</td>
                            <td>
                                {!! Form::select('tahun', ['2024' => '2024', '2025' => '2025'], $tahun, ['class' => 'w-full', 'id' => 'tahun', 'data-placeholder' => 'Pilih Tahun', 'required', 'onchange' => 'getTemas();']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold" width="220">Tema</td>
                            <td>
                                <select class="form-control tom-select mt-1" name="ftema[]" id="tema" multiple>
                                    <option value=""> -- Pilih Tema -- </option>
                                    @foreach ($temas as $tema)
                                        <option value="{{ $tema->id }}" {{ in_array($tema->id, $ftema) ? 'selected' : '' }}>{{ $tema->nama }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold" width="220">Fokus Intervensi</td>
                            <td>
                                <select class="form-control tom-select mt-1" name="fintervensi">
                                    <option value=""> -- Pilih Fokus Intervensi -- </option>
                                    @foreach (fokusIntervensi() as $fid => $fnama)
                                        <option value="{{ $fid }}" {{ $fintervensi == $fid ? 'selected' : '' }}>{{ $fnama }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td><button type="submit" id="filter-button" name="filter" value="1" class="btn btn-primary saveButton mt-10"><i class="fa fa-search"> </i>
                                    &nbsp; Lihat Data</button></td>
                        </tr>
                    </table>
                </form>
                <div class="separator mt-5"></div>
                <table id="perencanaan" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead class="table-dark font-bold">
                        <tr>
                            <th class="w-5" rowspan="2">No.</th>
                            @if (in_array(auth()->user()->level, ['admin', 'tpn', 'viewer']))
                                <th class="w-10" rowspan="2">Instansi</th>
                            @endif
                            <th class="w-10" rowspan="2">Tema</th>
                            <th class="w-10" rowspan="2">Sasaran</th>
                            <th rowspan="2">Indikator_roadmap</th>
                            <th class="w-5" rowspan="2">Target</th>
                            <th class="w-10" rowspan="2">Satuan Target</th>
                            <th class="w-10" rowspan="2">Realisasi</th>
                            <th class="w-10" rowspan="2">Capaian</th>
                            <th class="w-10" rowspan="2">Catatan</th>
                            <th class="w-10" rowspan="2">Catatan Evaluator</th>
                            <th class="w-15" rowspan="2">Permasalahan</th>
                            <th class="w-5" rowspan="2" style="text-align: center;">Sasaran</th>
                            <th class="w-5" rowspan="2" style="text-align: center;">Indikator Permasalahan</th>
                            <th class="w-5" rowspan="2" style="text-align: center;">Target</th>
                            <th class="w-5" rowspan="2" style="text-align: center;">Satuan</th>
                            <th class="w-5" rowspan="2" style="text-align: center;">Realisasi</th>
                            <th class="w-10" rowspan="2">Capaian</th>
                            <th class="w-10" rowspan="2">Catatan</th>
                            <th class="w-10" rowspan="2">Catatan Evaluator</th>
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
                            <th class="w-5" rowspan="2" style="text-align: center; 
                        width: 150px;
                        word-wrap: break-word;
                        overflow-wrap: break-word;
                        white-space: normal;
                        ">Keterangan</th>
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
                                @if (in_array(auth()->user()->level, ['admin', 'tpn', 'viewer']))
                                    <td>{{ $nama_instansi }}</td>
                                @endif
                                <td class="font-bold">{{ $data['sasaran_roadmap']->tema ? $data['sasaran_roadmap']->tema->nama : '' }}</td>
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
                                    @if (auth()->user()->level == 'tpn' && hasAksesRencanaAksi() && $data['indikator_roadmap']->id)
                                        <button onclick="catatan_evaluator_roadmap({{ $data['indikator_roadmap']->id }});" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="https://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg></button><br>
                                    @endif
                                    {{ $data['indikator_roadmap']->catatan_evaluator }}
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
                                    @if (auth()->user()->level == 'tpn' && hasAksesRencanaAksi() && $data['indikator_permasalahan']->id)
                                        <button onclick="catatan_evaluator_permasalahan({{ $data['indikator_permasalahan']->id }});" class="mb-3 btn btn-warning btn-sm w-10"><svg xmlns="https://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg></button><br>
                                    @endif
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
                                            <tr>
                                                <th>TW 1 </th>
                                                <td>: <i class="digit">{{ $data['output']->target_tw1 }}</i> </td>
                                            </tr>
                                            <tr>
                                                <th>TW 2 </th>
                                                <td>: <i class="digit">{{ $data['output']->target_tw2 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>TW 3 </th>
                                                <td>: <i class="digit">{{ $data['output']->target_tw3 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>TW 4 </th>
                                                <td>: <i class="digit">{{ $data['output']->target_tw4 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>Total </th>
                                                <td>: <i class="digit">{{ $data['output']->target_total }}</i></td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td>
                                    @if ($data['output']->anggaran_total)
                                        <table class="table table-noborder">
                                            <tr>
                                                <td>Rp. <i class="digit">{{ $data['output']->anggaran_total }}</i></td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td>
                                    {{ $data['output']->get_intervensi ? $data['output']->get_intervensi->nama : '' }}
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
                                            <tr>
                                                <th>TW 1</th>
                                                <td>: <i class="digit"> {{ $data['output']->realisasi_output_tw1 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>TW 2</th>
                                                <td>: <i class="digit"> {{ $data['output']->realisasi_output_tw2 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>TW 3</th>
                                                <td>: <i class="digit"> {{ $data['output']->realisasi_output_tw3 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>TW 4</th>
                                                <td>: <i class="digit"> {{ $data['output']->realisasi_output_tw4 }}</i></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>: <i class="digit"> {{ $data['output']->realisasi_output_total }}</i></td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td>
                                    @if ($data['output']->realisasi_anggaran_total)
                                        <table class="table table-noborder">
                                            <tr>
                                                <td>Rp. <i class="digit"> {{ $data['output']->realisasi_anggaran_total }} </i></td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td>
                                    @if ($data['output']->capaian_output_total)
                                        <table class="table table-noborder">
                                            <tr>
                                                <th>TW 1</th>
                                                <td>: <i class="digit">{{ $data['output']->capaian_output_tw1 }}</i>%</td>
                                            </tr>
                                            <tr>
                                                <th>TW 2</th>
                                                <td>: <i class="digit">{{ $data['output']->capaian_output_tw2 }}</i>%</td>
                                            </tr>
                                            <tr>
                                                <th>TW 3</th>
                                                <td>: <i class="digit">{{ $data['output']->capaian_output_tw3 }}</i>%</td>
                                            </tr>
                                            <tr>
                                                <th>TW 4</th>
                                                <td>: <i class="digit">{{ $data['output']->capaian_output_tw4 }}</i>%</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>: <i class="digit">{{ $data['output']->capaian_output_total }}</i>%</td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td>
                                    @if ($data['output']->capaian_anggaran_total)
                                        <table class="table table-noborder">
                                            <tr>
                                                <td><i class="digit">{{ $data['output']->capaian_anggaran_total, 2 }}</i>%</td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td>
                                    @if ($data['output']->catatan)
                                        {{ $data['output']->catatan }}
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
        <div id="modal-catatan-roadmap" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- BEGIN: Modal Header -->
                    <div class="darkbg modal-header">
                        <h2 class="font-bold fw-medium fs-base me-auto" id="title">Catatan Evaluator untuk Indikator Roadmap</h2>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <form action="{{ url('rencana_aksi/rb-tematik/rekap_data/simpanCatatanEvaluatorRoadmap') }}" id="form-catatan_evaluator_roadmap" method="post">
                        @csrf
                        <input type="hidden" id="indikator_roadmap_id" name="indikator_roadmap_id">
                        <div class="modal-body grid columns-12 gap-4 gap-y-3">
                            <div class="g-col-12">
                                <table class="table table-noborder">
                                    <tr>
                                        <td class="font-bold">Catatan Evaluator</td>
                                        <td>
                                            <textarea name="catatan_evaluator" id="catatan_evaluator_roadmap" cols="30" rows="10" placeholder="Catatan" class="form-control mt-4"></textarea>
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

        {{-- Modal Form Catatan Evaluator --}}
        <div id="modal-catatan-permasalahan" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- BEGIN: Modal Header -->
                    <div class="darkbg modal-header">
                        <h2 class="font-bold fw-medium fs-base me-auto" id="title">Catatan Evaluator untuk Indikator Permasalahan</h2>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <form action="{{ url('rencana_aksi/rb-tematik/rekap_data/simpanCatatanEvaluatorPermasalahan') }}" id="form-catatan_evaluator_permasalahan" method="post">
                        @csrf
                        <input type="hidden" id="indikator_permasalahan_id" name="indikator_permasalahan_id">
                        <div class="modal-body grid columns-12 gap-4 gap-y-3">
                            <div class="g-col-12">
                                <table class="table table-noborder">
                                    <tr>
                                        <td class="font-bold">Catatan Evaluator</td>
                                        <td>
                                            <textarea name="catatan_evaluator" id="catatan_evaluator_permasalahan" cols="30" rows="10" placeholder="Catatan" class="form-control mt-4"></textarea>
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
        var temaSelectEl = document.getElementById('tema');

        var perencanaanTable = $('#perencanaan').DataTable({
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excel',
                title: 'Rekap Data RB Tematik - {{ $nama_instansi }}',
                text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="https://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                titleAttr: 'Download Excel'
            }],
            scrollX: true,
            scrollCollapse: true,
            // 'orderFixed': [0, 'asc'],
            autoWidth: false,
            rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
            paging: true,
            bInfo: false,
            ordering: false,
        });

        $(document).ready(function() {
            getTemas();

            @if (auth()->user()->level == 'tpn')
                modal_catatan_roadmap = tailwind.Modal.getInstance(document.querySelector("#modal-catatan-roadmap"));
                modal_catatan_permasalahan = tailwind.Modal.getInstance(document.querySelector("#modal-catatan-permasalahan"));
            @endif

            setTimeout(() => {
                perencanaanTable.columns.adjust();
            }, 1000);

            $(".digit").each(function() {
                numberAsIs = $(this).html().replace('.', ',');;
                numberFormated = formatNumber(numberAsIs);
                $(this).html(numberFormated)
            });


            function formatNumber(num) {
                return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
            }
        });

        function updateTemaOptions(temas) {
            if (!temaSelectEl) {
                return;
            }
            if (temaSelectEl.tomselect) {
                var temaSelect = temaSelectEl.tomselect;
                temaSelect.clear(true);
                temaSelect.clearOptions();
                temas.forEach(function(tema) {
                    temaSelect.addOption({
                        value: tema.id,
                        text: tema.nama
                    });
                });
                temaSelect.refreshOptions(false);
            } else {
                var options = '<option value=""> -- Pilih Tema -- </option>';
                temas.forEach(function(tema) {
                    options += '<option value="' + tema.id + '">' + tema.nama + '</option>';
                });
                $('#tema').html(options).trigger('change');
            }
        }

        function getTemas() {
            var tahun = $('#tahun').val();
            $('#tema').prop('disabled', true);
            $.getJSON("{{ url('rencana_aksi/rb-tematik/rekap_data/get-temas') }}", {
                    tahun: tahun
                })
                .done(function(response) {
                    updateTemaOptions(response);
                })
                .fail(function() {
                    updateTemaOptions([]);
                })
                .always(function() {
                    $('#tema').prop('disabled', false);
                });
        }

        @if (auth()->user()->level == 'tpn')
            function catatan_evaluator_roadmap(id) {
                $('#indikator_roadmap_id').val(id);
                $.getJSON("{{ url('rencana_aksi/rb-tematik/rekap_data/get-indikator-roadmap') }}/" + id, function(data) {
                    $('#catatan_evaluator_roadmap').val(data.catatan_evaluator);
                    modal_catatan_roadmap.show();
                });
            }

            function catatan_evaluator_permasalahan(id) {
                $('#indikator_permasalahan_id').val(id);
                $.getJSON("{{ url('rencana_aksi/rb-tematik/rekap_data/get-indikator-permasalahan') }}/" + id, function(data) {
                    $('#catatan_evaluator_permasalahan').val(data.catatan_evaluator);
                    modal_catatan_permasalahan.show();
                });
            }
        @endif
    </script>
@endpush
