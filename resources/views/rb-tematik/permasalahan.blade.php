@extends('layout.rubick')
@section('title', 'RB Tematik - Perencanaan')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Rencana Aksi RB
                Tematik</h2>
        </div>

      

        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped mt-5">
                <tr>
                    <td class="font-bold" width="220">Tema</td>
                    <td>Semua</td>
                </tr>
                <tr>
                    <td class="font-bold">Sasaran Roadmap</td>
                    <td>Semua</td>
                </tr>
                <tr>
                    <td class="font-bold">Indikator Roadmap</td>
                    <td>Semua</td>
                </tr>
                <tr>
                    <td class="font-bold">Target</td>
                    <td>Semua</td>
                </tr>
                <tr>
                    <td class="font-bold">Satuan Target</td>
                    <td>Semua</td>
                </tr>
            </table>
        </div>

        <div id="tab1" class="tab-pane leading-relaxed active">

            <div class="form-inline items-start flex-col xl:flex-row  pt-5 first:mt-0 first:pt-0">
                <button class="btn btn-outline-primary border-dashed w-full" id="dynamic-ar" onclick="tambah_permasalahan();">
                    <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" data-lucide="plus" class="lucide lucide-plus w-4 h-4 mr-2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg> Tambah Permasalahan </button>
            </div>
        </div>

        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">Roadmap</th>
                        <th class="w-5">Permasalahan (bottleneck)</th>
                        <th class="w-5">Sasaran</th>
                        <th class="w-5">Indikator</th>
                        <th class="w-5">Target</th>
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
                        <td>
                            <b>Tema Roadmap :</b><br/>
                            {{ $tematikData['tema_nama'] }} <br/><br/>
                            <b>Sasaran Roadmap :</b><br/>
                            {{ $tematikData['sasaran_nama'] }} <br/><br/>
                            <b>Indikator Roadmap :</b><br/>
                            {{ $tematikData['indikator_nama'] }} 
                        </td>
                        <td>
                            {{ $tematikData['permasalahan_nama'] }}
                        </td>
                        <td>
                            {{ $tematikData['permasalahan_sasaran'] }}
                            @if ($tematikData['permasalahan_nama'])
                            <button onclick="tambah_indikator_permasalahan('{{ $tematikData['permasalahan_nama'] }}','{{ $tematikData['permasalahan_sasaran'] }}', '{{ $tematikData['permasalahan_id'] }}');" class="btn btn-success btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Indikator</button>
                            @endif
                        </td>
                        <td>
                            {{ $tematikData['indikator_permasalahan_nama'] }}
                        </td>
                        <td>
                            {{ $tematikData['indikator_permasalahan_target'] }}
                        </td>
                        <td>
                            @if ($tematikData['indikator_permasalahan_nama'])
                            <a href="{{ url('rb-tematik/permasalahan/renaksi/' . $tematikData['indikator_permasalahan_id'])}}" class="btn btn-primary btn-sm w-full mb-2">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Renaksi
                                <span class="text-xs px-1 rounded-full bg-warning text-white badge"> <!-- count($indikator->rencana_aksi) --> </span>
                            </a>
                            <br>
                            <a href="{{ url('rb-tematik/permasalahan/monev/' . $tematikData['indikator_permasalahan_id'])}}" class="btn btn-dark btn-sm w-full mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit w-4 h-4 mr-1">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>Monev</a>
                            
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
            <form action="{{ url('rb-tematik/perencanaan/simpan-sasaran-roadmap') }}" id="form-sasaran-roadmap" method="post">
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
                                            <td class="font-bold w-30" rowspan="2">
                                                <button class="btn btn-xs btn-danger" onclick="hapus_input(this)">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Sasaran Tematik Roadmap</td>
                                            <td colspan="5">
                                                <input type="text" name="nama[]" placeholder="Masukan Sasaran Roadmap" class="form-control" />
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
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Cancel</button>
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
            <form action="{{ url('rb-tematik/permasalahan/simpan-permasalahan') }}" id="form-permasalahan" method="post">
                @csrf
                <input type="hidden" name="tematik_indikator_roadmap_id" id="indikator-roadmap-id-onPermasalahan">
                <div class="modal-body grid columns-12 ">
                    <div class="g-col-12">
                        <div class="border  rounded-md ">
                            <div id="konten_tambah_aksi">
                                <div class="relative   dark:border rounded-md">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="font-bold w-30">Sasaran Roadmap</td>
                                            <td colspan="5">
                                            <select class="form-select mt-2 sm:mr-2 form-control" id="sasaran-roadmap-onPermasalahan" name="sasaran_roadmap">
                                                <option selected="true" disabled="disabled">Pilih Sasaran Roadmap</option>    
                                                @foreach ($sasaranRoadmaps as $sasaran)
                                                <option value="{{ $sasaran->id }}">{{ $sasaran->nama }}
                                                </option>
                                                @endforeach
                                            </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Indikator Roadmap</td>
                                            <td colspan="5">
                                                <select class="form-select mt-2 sm:mr-2 form-control" id="indikator-roadmap-onPermasalahan" name="tematik_indikator_roadmap_id">
                                                    <option selected="true" disabled="disabled">Pilih Indikator Roadmap</option>    
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Target Indikator </td>
                                            <td colspan="5">
                                                <input type="text" id="target-roadmap-onPermasalahan" name="target_roadmap" class="form-control" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="font-bold w-30">Satuan Target </td>
                                            <td colspan="5">
                                                <input type="text" id="target-satuan-onPermasalahan" name="target_satuan" class="form-control" disabled />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Permasalahan</td>
                                            <td colspan="5">
                                                <textarea id="permasalahan" name="permasalahan" class="form-control" rows="8"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Sasaran Permasalahan</td>
                                            <td colspan="5">
                                            <textarea id="sasaran-permasalahan" name="sasaran_permasalahan"  class="form-control" rows="4" ></textarea>
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
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Cancel</button>
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
            <form action="{{ url('rb-tematik/permasalahan/simpan-indikator-permasalahan') }}" id="form-permasalahan" method="post">
                @csrf
                <input type="hidden" name="tematik_permasalahan_id_onIndikatorPermasalahan" id="tematik-permasalahan-id-onIndikatorPermasalahan">
                <div class="modal-body grid columns-12 ">
                    <div class="g-col-12">
                        <div class="border  rounded-md ">
                            <div id="konten_tambah_aksi">
                                <div class="relative   dark:border rounded-md">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="font-bold w-30">Permasalahan </td>
                                            <td colspan="5">
                                                <input type="text" id="permasalahan-onIndikatorPermasalahan" name="permasalahan" class="form-control" disabled />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Sasaran</td>
                                            <td colspan="5">
                                                <input type="text" id="sasaran-permasalahan-onIndikatorPermasalahan" name="permasalahan_sasaran" class="form-control" disabled />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Indikator </td>
                                            <td colspan="5">
                                                <input type="text" id="indikator-permasalahan" name="indikator_permasalahan" placeholder="Masukan Indikator" class="form-control" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Target</td>
                                            <td colspan="5">
                                                <input type="text" id="target-permasalahan" name="target_permasalahan" placeholder="Masukan Target" class="form-control" />
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
        $(".numeric").inputmask("decimal", {
            groupSeparator: "",
            digits: 0,
            autoGroup: false,
            rightAlign: false,
            min: 0
        });
        $(".digit").inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            min: 0,
        });

        modal_permasalahan = tailwind.Modal.getInstance(document.querySelector(
            "#modal-permasalahan"));
        modal_indikator_permasalahan = tailwind.Modal.getInstance(document.querySelector(
            "#modal-indikator-permasalahan"));
    });

    function output_form() {
        const inputform = $('#inputform-sasaran-roadmap');
        const htmlstr = inputform.get(0).outerHTML;
        const wrapper = $('<div />', {
            html: htmlstr
        });
        return wrapper.html();
    }

    function tambah_input() {
        $('#target_output_ext').append(output_form());
    }

    function hapus_input(th) {
        $(th).parent().parent().parent().remove();
    }

    function tambah_permasalahan(indikator_roadmap, target_roadmap, satuan, indikator_roadmap_id) {
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
            rowsGroup: [0, 1, 2],
            paging: true,
            bInfo: true
        });
    });
</script>
<script>
    $('#sasaran-roadmap-onPermasalahan').on('change', function() {
        var firstSelectValue = $(this).val();

        $.ajax({
            url: '{{url("/rb-tematik/permasalahan/get-indikator-roadmap")}}',
            type: 'GET',
            data: {"tematik_sasaran_roadmap_id": firstSelectValue},
            success: function(response) {
                // Clear previous options
                $('#indikator-roadmap-onPermasalahan').empty();
                // Add new options based on the response
                $.each(response, function(key, value) {
                    $('#indikator-roadmap-onPermasalahan').append('<option value="' + value["id"] + '">' + value["nama"] + '</option>');
                    $('#target-roadmap-onPermasalahan').val(value["target"]);
                    $('#target-satuan-onPermasalahan').val(value["satuan"]);
                });
            },
        });
    });
</script>
@endpush