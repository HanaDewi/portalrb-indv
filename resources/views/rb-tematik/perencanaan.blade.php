@extends('layout.rubick')
@section('title', 'RB Tematik - Perencanaan')

@section('content')
   
    <div class="intro-y col-span-12 lg:col-span-12">
        @include('common.status')
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <h2 class="font-bold text-base mr-auto"> Perencanaan Aksi RB
                    Tematik</h2>
            </div>

           
            <div class="flex sm:flex-row items-center p-5 border-b border-slate-200/60">
                <h2 class="font-bold text-base mr-auto flex items-center justify-center">
                    <i data-lucide="file-text" class="mr-1"></i> Data Rencana Aksi
                </h2>
                <!--<a href="{{url('rb-tematik/perencanaan/downloadTemplate') }}" class="btn btn-success btn-sm mr-2"><svg width="16px" height="16px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g id="Complete"> <g id="download"> <g> <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> <g> <polyline data-name="Right" fill="none" id="Right-2" points="7.9 12.3 12 16.3 16.1 12.3" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polyline> <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="12" x2="12" y1="2.7" y2="14.2"></line> </g> </g> </g> </g> </g></svg>&nbsp;Template</a>-->
                <a href="{{asset('template_import/template_import_tematik.xlsx')}}" class="btn btn-success btn-sm mr-2"><svg width="16px" height="16px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g id="Complete"> <g id="download"> <g> <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> <g> <polyline data-name="Right" fill="none" id="Right-2" points="7.9 12.3 12 16.3 16.1 12.3" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polyline> <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="12" x2="12" y1="2.7" y2="14.2"></line> </g> </g> </g> </g> </g></svg>&nbsp;Template</a>
                <button class="btn btn-success btn-sm mr-2" onclick="importRBTematik();"><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>&nbsp;Import</button>
                <button class="btn btn-danger btn-sm shadow-md" onclick="tambah_sasaran_roadmap();" data-bs-toggle="modal" data-bs-target="#modal-kegiatan_utama"><i data-lucide="plus" class="mr-1" width="18px" height="18px"></i> Tambah Sasaran Tematik Roadmap</button>
            </div>

            <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
                <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                    <thead class="table-dark font-bold">
                        <tr>
                            <th class="w-5">No.</th>
                            <th class="w200">Tema</th>
                            <th class="w150">Sasaran Tematik Roadmap</th>
                            <th>Indikator</th>
                            <th class="w-5">Target</th>
                            <th class="w-5">Satuan Target</th>
                            <th class="w-5">Realisasi</th>
                            <th class="w-5">Capaian</th>
                            <th class="w-5">Catatan</th>
                            <th class="w-5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 0;
                        $nama = '';
                        @endphp

                        @foreach ($tematikDatas as $tematikData)
                            @php
                            if ($nama != $tematikData['tema_nama']) {
                                $nama = $tematikData['tema_nama'];
                                $no++;
                            }
                            @endphp
                            <tr>
                                <td class="font-bold">{{ $no }}</td>
                                <td class="font-bold" id="tema{{ $tematikData['tema_id'] }}">
                                    {{ $tematikData['tema_nama'] }}

                                </td>
                                <td id="sasaranRoadmap{{ $tematikData['sasaran_id'] }}">
                                    {{ $tematikData['sasaran_nama'] }}
                                    @if ($tematikData['sasaran_nama'])
                                        <button
                                            onclick="tambah_indikator_roadmap('{{ $tematikData['tema_nama'] }}','{{ $tematikData['sasaran_nama'] }}', '{{ $tematikData['sasaran_id'] }}');"
                                            class="btn btn-warning btn-sm w-full mb-2"><i data-lucide="edit"
                                                class="w-4 h-4 mr-1"></i>Tambah Indikator
                                        </button>
                                        <br/>
                                        <a href="#" class="btn btn-pending btn-sm w-full mb-2"
                                        onclick="edit_sasaran_roadmap('{{$tematikData['sasaran_id']}}')" > 
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit Sasaran Tematik
                                            <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                                        </a>
                                        <br/>
                                        <a href="#" class="btn btn-danger btn-sm w-full mb-2"
                                            onclick="hapus_sasaran_roadmap('{{$tematikData['sasaran_id']}}')" > 
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Hapus Sasaran Tematik
                                            <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{ $tematikData['indikator_nama'] }}
                                </td>
                               
                                <td>
                                    {{ $tematikData['indikator_target'] }}
                                </td>
                                <td>
                                    {{ $tematikData['indikator_satuan'] }}
                                </td>
                                <td>
                                    {{ $tematikData['indikator_realisasi_indikator'] }}
                                </td>
                                <td>
                                    {{ $tematikData['indikator_capaian_indikator'] }}
                                </td>
                                <td>
                                    {{ $tematikData['indikator_catatan'] }}
                                </td>
                                <td>
                                    @if ($tematikData['indikator_nama'])
                                    <a href="#" onclick="edit_monev('{{$tematikData['indikator_id']}}');" class="btn btn-dark btn-sm w-full mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>Monev
                                    </a>
                                    <br/>
                                    <a href="#" class="btn btn-pending btn-sm w-full mb-2"
                                    onclick="edit('{{$tematikData['indikator_id']}}')" > 
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit Indikator
                                        <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                                    </a>
                                    <br/>
                                    <a href="#" class="btn btn-danger btn-sm w-full mb-2"
                                    onclick="hapus('{{$tematikData['indikator_id']}}')" > 
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Hapus Indikator
                                        <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form Sasaran Roadmap --}}
    <div id="modal-sasaran-roadmap" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium  fs-base me-auto" id="title">Sasaran Roadmaps</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-tematik/perencanaan/simpan-sasaran-roadmap') }}" id="form-sasaran-roadmap"
                    method="post">
                    @csrf
                    <input type="hidden" name="tema_id" id="tema_id">
                    <input type="hidden" name="sasaran_roadmap_id" id="sasaran-roadmap-id">
                    <div class="modal-body grid columns-12 ">
                        <div class="g-col-12">
                            <div class="border  rounded-md ">
                                <div id="konten_tambah_aksi">
                                    <div class="relative   dark:border rounded-md">
                                        <table class="table table-bordered" id="inputform-sasaran-roadmap">
                                            <tr>
                                                <td class="font-bold w-30">Tema </td>
                                                <td colspan="5">
                                                    <select class="form-select mt-2 sm:mr-2 form-control" name="tema_id[]" id="tema_id_onSasaran">
                                                        @foreach ($temas as $tema)
                                                            <option value="{{ $tema->id }}">{{ $tema->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="font-bold w-30" rowspan="2" width="70px;">
                                                    <button class="btn btn-xs btn-danger delete-item" onclick="hapus_input(this)" style="display:none;">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Sasaran Tematik Roadmap</td>
                                                <td colspan="5">
                                                    <input type="text" name="nama[]" id="sasaranOnModalSasaran"
                                                        placeholder="Masukan Sasaran Roadmap" class="form-control" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="dynamicAddRemove"></div>
                                    <div id="target_output_ext"></div>
                                </div>
                            </div>
                        <button type="button" class="btn btn-outline-primary border-dashed w-full mt-4" onclick="tambah_input();" id="tambah_input_button"><i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Sasaran Tematik Roadmap</button>
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

    {{-- Modal Form Indikator Roadmap --}}
    <div id="modal-indikator-roadmap" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium  fs-base me-auto" id="title">Indikator Roadmap</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-tematik/perencanaan/simpan-indikator-roadmap') }}" id="form-indikator-roadmap"
                    method="post">
                    @csrf
                    <input type="hidden" name="sasaran_id" id="sasaran-id">
                    <input type="hidden" name="indikator_roadmap_id" id="indikator-roadmap-id">
                    <div class="modal-body grid columns-12 ">
                        <div class="g-col-12">
                            <div class="border  rounded-md ">
                                <div id="konten_tambah_aksi">
                                    <div class="relative   dark:border rounded-md">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="font-bold w-30">Tema </td>
                                                <td colspan="5">
                                                    <input type="text" id="tema-indikator" name="tema_indikator"
                                                        class="form-control" disabled />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Sasaran Roadmap </td>
                                                <td colspan="5">
                                                    <input type="text" id="sasaran-roadmap" name="sasaran_roadmap"
                                                        class="form-control" disabled />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Indikator Roadmap</td>
                                                <td colspan="5">
                                                    <input type="text" id="indikator-roadmap" name="indikator_roadmap"
                                                        placeholder="Masukan Indikator Roadmap" class="form-control" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Target</td>
                                                <td colspan="5">
                                                    <input type="text" class="form-control" id="target-roadmap" name="target_roadmap"
                                                        placeholder="Masukan Jumlah Target" class="form-control" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Satuan Target</td>
                                                <td colspan="5">
                                                    <input type="text" id="target-satuan" name="target_satuan"
                                                        placeholder="Masukan Satuan Target" class="form-control" />
                                                </td>
                                            </tr>
                                            
                                        </table>
                                        <div id="dynamicAddRemove"></div>
                                    </div>
                                </div>
                            </div>


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

    {{-- Modal Form Monev Indikator Roadmap --}}
    <div id="modal-monev-indikator-roadmap" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium fs-base me-auto" id="title">Monitoring dan Evaluasi Indikator Sasaran Permasalahan</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-tematik/perencanaan/monev/simpan-indikator-roadmap') }}" id="form-monev_perencanaan" method="post">
                    @csrf
                    <input type="hidden" name="monev_indikator_roadmap_id" id="monev-indikator-roadmap-id">
                    <div class="modal-body grid columns-12 gap-4 gap-y-3">
                        <div class="g-col-12">
                            <table class="table table-noborder">
                                <tr>
                                    <td class="font-bold w-44">Realisasi Indikator</td>
                                    <td>
                                        <input type="text" name="realisasi_indikator" id="realisasi-indikator" placeholder="Realisasi Indikator" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-bold">Capaian Indikator</td>
                                    <td>
                                        <input type="text" name="capaian_indikator" id="capaian-indikator" placeholder="Capaian Indikator" class="form-control mt-4">
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


    <!-- Modal Form Import RBTematik -->
<div id="modal-import_rbTematik" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Import RBTematik</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('/rb-tematik/perencanaan/import') }}" id="form-import_rencana_aksi" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <input type="file" id="file_rbTematik" name="file_rbTematik" required>
                    </div>
                    <div class="g-col-12">
                        <span class="text-danger font-bold"><i>* RB Tematik yang diupload harus sesuai dengan template yang diberikan</i></span>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end"> 
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Batal</button> 
                    <button type="submit" class="btn btn-primary w-20 saveButton">Import</button> 
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
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
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

            modal_sasaran_roadmap = tailwind.Modal.getInstance(document.querySelector("#modal-sasaran-roadmap"));
            modal_indikator_roadmap = tailwind.Modal.getInstance(document.querySelector(
                "#modal-indikator-roadmap"));
            modal_monev_indikator_roadmap = tailwind.Modal.getInstance(document.querySelector("#modal-monev-indikator-roadmap"));
            modal_import_rbTematik = tailwind.Modal.getInstance(document.querySelector("#modal-import_rbTematik"));

            

        });

        function importRBTematik() {
                modal_import_rbTematik.show();
        }

        function getData() {
            kegiatan_utama.ajax.url("{{url('master-data/kegiatan_utama/getDatas')}}").load(null, false);
        }

        function output_form() {
            const inputform = $('#inputform-sasaran-roadmap');
            const htmlstr = inputform.get(0).outerHTML;
            const wrapper = $('<div />',{html:htmlstr});
            return wrapper.html();
        }

        function tambah_input() {
            $('#target_output_ext').append(output_form());
        }

        function hapus_input(th) {
            $(th).parent().parent().parent().remove();
        }

        function tambah_sasaran_roadmap() {
            modal_sasaran_roadmap.show();
        }

        function tambah_indikator_roadmap(tema, sasaran_roadmap, sasaran_id) {
            $('#sasaran-id').val(sasaran_id);
            $('#tema-indikator').val(tema);
            $('#sasaran-roadmap').val(sasaran_roadmap);
            modal_indikator_roadmap.show();
        }

        function edit_sasaran_roadmap(id) {
            $('#sasaran-roadmap-id').val(id);
            $('.saveButton').prop('disabled', true);
            $.getJSON("{{url('rb-tematik/perencanaan/getSasaran/')}}/"+id, function(data) {
                $('#tema_id_onSasaran').val(data.tema_id);
                $('#sasaranOnModalSasaran').val(data.sasaran_roadmap);
                $('.saveButton').prop('disabled', false);
                $('#tambah_input_button').prop('disabled', true);
                modal_sasaran_roadmap.show();
            });
        }

        function hapus_sasaran_roadmap(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Sasaran Roadmap ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('rb-tematik/perencanaan/sasaran_roadmap/')}}/hapus/"+id,
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data Sasaran Roadmap berhasil dihapus!', 'success');
                            Swal.fire({
                                title: "'Selamat!",
                                text: 'Data Sasaran Roadmap berhasil dihapus!! '+terhapus.pesan,
                                icon: 'warning',
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Ya, Hapus aja!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(true);
                                };
                            });
                        } else {    
                            Swal.fire('Aduh!', 'Data sasaran Roadmap gagal dihapus! '+terhapus.pesan, 'error');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan! <br/> Pastikan menghapus dahulu indikator roadmap yang terkait dengan sasaran ini', 'error');
                    }
                });
            }
        });
        }

        function edit(id) {
        $('#indikator-roadmap-id').val(id);
        $('#title').html('Edit Rencana Aksi Output');
        $('.saveButton').prop('disabled', true);
        $.getJSON("{{url('rb-tematik/perencanaan/getData/')}}/"+id, function(data) {
            $('#tema-indikator').val(data.tema);
            $('#sasaran-roadmap').val(data.sasaran_roadmap);
            $('#indikator-roadmap').val(data.indikator_roadmap);
            $('#target-roadmap').val(data.target_roadmap);
            $('#target-satuan').val(data.target_satuan);
            $('.saveButton').prop('disabled', false);
            modal_indikator_roadmap.show();
        });
        }

        function edit_monev(id) {
            $('#monev-indikator-roadmap-id').val(id);
            $('#title').html('Edit Rencana Aksi Output');
            $('.saveButton').prop('disabled', true);
            $.getJSON("{{url('rb-tematik/perencanaan/getData/')}}/"+id, function(data) {
                $('#realisasi-indikator').val(data.realisasi_indikator);
                $('#capaian-indikator').val(data.capaian_indikator);
                $('#catatan').val(data.catatan);
            $('.saveButton').prop('disabled', false);
                modal_monev_indikator_roadmap.show();
            }); 
        }

        function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Indikator Roadmap ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('rb-tematik/perencanaan/indikator_roadmap/')}}/hapus/"+id,
                    type: "post",
                    data: {_token: '{{csrf_token()}}', id: id},
                    dataType: "json",
                    success: function(terhapus) {
                        if (terhapus.success) {
                            Swal.fire('Selamat!', 'Data Indikator Roadmap berhasil dihapus!', 'success');
                            Swal.fire({
                                title: "'Selamat!",
                                text: 'Data Indikator Roadmap berhasil dihapus!! '+terhapus.pesan,
                                icon: 'warning',
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Ya, Hapus aja!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(true);
                                };
                            });
                        } else {    
                            Swal.fire('Aduh!', 'Data indikator Roadmap gagal dihapus! '+terhapus.pesan, 'error');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan! <br/> Pastikan menghapus dahulu permalasahan yang terkait dengan indikator roadmap ini', 'error');
                    }
                });
            }
        });
        }
    </script>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script>
        $(function() {
            $("#perencanaan").DataTable({

            dom: 'Blfrtip',
            buttons: [
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                },
                text: '<button class="btn btn-danger btn-sm w-32 mr-2 mb-2"><svg fill="#ffffff" height="18px" width="18px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 482.14 482.14" xml:space="preserve" stroke="#ffffff"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <g> <path d="M142.024,310.194c0-8.007-5.556-12.782-15.359-12.782c-4.003,0-6.714,0.395-8.132,0.773v25.69 c1.679,0.378,3.743,0.504,6.588,0.504C135.57,324.379,142.024,319.1,142.024,310.194z"/> <path d="M202.709,297.681c-4.39,0-7.227,0.379-8.905,0.772v56.896c1.679,0.394,4.39,0.394,6.841,0.394 c17.809,0.126,29.424-9.677,29.424-30.449C230.195,307.231,219.611,297.681,202.709,297.681z"/> <path d="M315.458,0H121.811c-28.29,0-51.315,23.041-51.315,51.315v189.754h-5.012c-11.418,0-20.678,9.251-20.678,20.679v125.404 c0,11.427,9.259,20.677,20.678,20.677h5.012v22.995c0,28.305,23.025,51.315,51.315,51.315h264.223 c28.272,0,51.3-23.011,51.3-51.315V121.449L315.458,0z M99.053,284.379c6.06-1.024,14.578-1.796,26.579-1.796 c12.128,0,20.772,2.315,26.58,6.965c5.548,4.382,9.292,11.615,9.292,20.127c0,8.51-2.837,15.745-7.999,20.646 c-6.714,6.32-16.643,9.157-28.258,9.157c-2.585,0-4.902-0.128-6.714-0.379v31.096H99.053V284.379z M386.034,450.713H121.811 c-10.954,0-19.874-8.92-19.874-19.889v-22.995h246.31c11.42,0,20.679-9.25,20.679-20.677V261.748 c0-11.428-9.259-20.679-20.679-20.679h-246.31V51.315c0-10.938,8.921-19.858,19.874-19.858l181.89-0.19v67.233 c0,19.638,15.934,35.587,35.587,35.587l65.862-0.189l0.741,296.925C405.891,441.793,396.987,450.713,386.034,450.713z M174.065,369.801v-85.422c7.225-1.15,16.642-1.796,26.58-1.796c16.516,0,27.226,2.963,35.618,9.282 c9.031,6.714,14.704,17.416,14.704,32.781c0,16.643-6.06,28.133-14.453,35.224c-9.157,7.612-23.096,11.222-40.125,11.222 C186.191,371.092,178.966,370.446,174.065,369.801z M314.892,319.226v15.996h-31.23v34.973h-19.74v-86.966h53.16v16.122h-33.42 v19.875H314.892z"/> </g> </g> </svg> &nbsp;PDF </button>',
                        titleAttr: 'Download PDF'
            },
            {
                extend: 'excel',
                text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                        titleAttr: 'Download Excel'
            }],


                scrollX: true,
                autoWidth: true,
                rowsGroup: [0, 1, 2, 3, 4, 5],
                paging: true,
                bInfo: true
            });
        });
    </script>
    <style type="text/css">
        #target_output_ext .delete-item {display: block !important;}
    </style>
@endpush
