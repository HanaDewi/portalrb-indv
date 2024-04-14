@extends('layout.rubick')
@section('title', 'RB Tematik - Perencanaan')

@section('content')
    @include('common.status')
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <h2 class="font-bold text-base mr-auto"> Perencanaan Aksi RB
                    Tematik</h2>
            </div>

            <div id="tab1" class="tab-pane leading-relaxed active">

                <div class="form-inline items-start flex-col xl:flex-row  pt-5 first:mt-0 first:pt-0">
                    <button class="btn btn-outline-primary border-dashed w-full" id="dynamic-ar"
                        onclick="tambah_sasaran_roadmap();">
                        <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="plus" data-lucide="plus"
                            class="lucide lucide-plus w-4 h-4 mr-2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg> Tambah Sasaran Tematik Roadmap </button>
                </div>
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
                                                class="w-4 h-4 mr-1"></i>Indikator</button>
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
                    <div class="modal-body grid columns-12 ">
                        <div class="g-col-12">
                            <div class="border  rounded-md ">
                                <div id="konten_tambah_aksi">
                                    <div class="relative   dark:border rounded-md">
                                        <table class="table table-bordered" id="inputform-sasaran-roadmap">
                                            <tr>
                                                <td class="font-bold w-30">Tema </td>
                                                <td colspan="5">
                                                    <select class="form-select mt-2 sm:mr-2 form-control" name="tema_id[]">
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
                                                    <input type="text" name="nama[]"
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
                                                    <input type="text" id="target-roadmap" name="target_satuan"
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

    {{-- Modal Form Permasalahan --}}
    <div id="modal-permasalahan" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium  fs-base me-auto" id="title">Permasalahan</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-tematik/perencanaan/simpan-permasalahan') }}" id="form-permasalahan"
                    method="post">
                    @csrf
                    <input type="hidden" name="tematik_indikator_roadmap_id" id="indikator-roadmap-id-onPermasalahan">
                    <div class="modal-body grid columns-12 ">
                        <div class="g-col-12">
                            <div class="border  rounded-md ">
                                <div id="konten_tambah_aksi">
                                    <div class="relative   dark:border rounded-md">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="font-bold w-30">Indikator Sasaran </td>
                                                <td colspan="5">
                                                    <input type="text" id="indikator-roadmap-onPermasalahan"
                                                        name="indikator_roadmap" class="form-control" disabled />
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="font-bold w-30">Target Indikator </td>
                                                <td colspan="5">
                                                    <input type="text" id="target-roadmap-onPermasalahan"
                                                        name="target_roadmap" class="form-control" disabled />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Satuan Target </td>
                                                <td colspan="5">
                                                    <input type="text" id="target-satuan-onPermasalahan"
                                                        name="target_satuan" class="form-control" disabled />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Permasalahan</td>
                                                <td colspan="5">
                                                    <input type="text" id="permasalahan" name="permasalahan"
                                                        placeholder="Masukan Permasalahan" class="form-control" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Sasaran Permasalahan</td>
                                                <td colspan="5">
                                                    <input type="text" id="sasaran-permasalahan"
                                                        name="sasaran_permasalahan"
                                                        placeholder="Masukan Sasaran Permasalahan" class="form-control" />
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

    {{-- Modal Form Indikator Permasalahan --}}
    <div id="modal-indikator-permasalahan" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="darkbg modal-header">
                    <h2 class="font-bold fw-medium  fs-base me-auto" id="title">Indikator Permasalahan</h2>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <form action="{{ url('rb-tematik/perencanaan/simpan-indikator-permasalahan') }}" id="form-permasalahan"
                    method="post">
                    @csrf
                    <input type="hidden" name="tematik_permasalahan_id_onIndikatorPermasalahan"
                        id="tematik-permasalahan-id-onIndikatorPermasalahan">
                    <div class="modal-body grid columns-12 ">
                        <div class="g-col-12">
                            <div class="border  rounded-md ">
                                <div id="konten_tambah_aksi">
                                    <div class="relative   dark:border rounded-md">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="font-bold w-30">Permasalahan </td>
                                                <td colspan="5">
                                                    <input type="text" id="permasalahan-onIndikatorPermasalahan"
                                                        name="permasalahan" class="form-control" disabled />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Sasaran</td>
                                                <td colspan="5">
                                                    <input type="text"
                                                        id="sasaran-permasalahan-onIndikatorPermasalahan"
                                                        name="permasalahan_sasaran" class="form-control" disabled />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Indikator </td>
                                                <td colspan="5">
                                                    <input type="text" id="indikator-permasalahan"
                                                        name="indikator_permasalahan" placeholder="Masukan Indikator"
                                                        class="form-control" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold w-30">Target</td>
                                                <td colspan="5">
                                                    <input type="text" id="target-permasalahan"
                                                        name="target_permasalahan" placeholder="Masukan Target"
                                                        class="form-control" />
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
@endsection

@push('js')
    <script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
    <script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>

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
            modal_permasalahan = tailwind.Modal.getInstance(document.querySelector(
                "#modal-permasalahan"));
            modal_indikator_permasalahan = tailwind.Modal.getInstance(document.querySelector(
                "#modal-indikator-permasalahan"));
        });

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

        function tambah_permasalahan(indikator_roadmap, target_roadmap, satuan, indikator_roadmap_id) {
            $('#indikator-roadmap-id-onPermasalahan').val(indikator_roadmap_id);
            $('#indikator-roadmap-onPermasalahan').val(indikator_roadmap);
            $('#target-roadmap-onPermasalahan').val(target_roadmap);
            $('#target-satuan-onPermasalahan').val(satuan);
            modal_permasalahan.show();
        }

        function tambah_indikator_permasalahan(permasalahan, sasaran, permasalahan_id) {
            $('#tematik-permasalahan-id-onIndikatorPermasalahan').val(permasalahan_id);
            $('#permasalahan-onIndikatorPermasalahan').val(permasalahan);
            $('#sasaran-permasalahan-onIndikatorPermasalahan').val(sasaran);
            modal_indikator_permasalahan.show();
        }
    </script>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script>
        $(function() {
            $("#perencanaan").DataTable({
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
