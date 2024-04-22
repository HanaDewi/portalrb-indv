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
            <form method="get" id="filter-form">
                <table class="table table-bordered table-striped mt-5">
                    <tr>
                        <td class="font-bold" width="220">Tema</td>
                        <td>
                            <select class="form-control" name="ftema" onchange="$('#filter-form').submit();">
                                <option value=""> -- Pilih tema -- </option>
                                @foreach ($temas as $tema)
                                <option value="{{ $tema->id }}" {{ $ftema==$tema->id ? 'selected':'' }} >{{ $tema->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold">Sasaran Roadmap</td>
                        <td>
                            <select class="form-control" name="fsasaranroadmap" onchange="$('#filter-form').submit();">
                                <option value=""> -- Pilih sasaran roadmap -- </option>
                                @foreach ($filterSasaranRoadmap as $froadmap)
                                <option value="{{ $froadmap->id }}" {{ $fsasaranroadmap==$froadmap->id ? 'selected':'' }}>{{ $froadmap->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold">Indikator Roadmap</td>
                        <td>
                            <select class="form-control" name="findikatorroadmap" onchange="$('#filter-form').submit();">
                                <option value=""> -- Pilih indikator roadmap -- </option>
                                @foreach ($filterIndikatorRoadmap as $fir)
                                <option value="{{ $fir->nama }}" {{ $findikatorroadmap==$fir->nama ? 'selected':'' }}>{{ $fir->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold">Target</td>
                        <td>{{ $ftarget }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">Satuan Target</td>
                        <td>{{ $fsatuantarget }}</td>
                    </tr>
                </table>
            </form>
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
                        <th class="w-5">No</th>
                        <th class="w-5">Tema</th>
                        <th class="w-5">Sasaran & Indikator Roadmap</th>
                        <th class="w-5">Permasalahan (bottleneck)</th>
                        <th class="w-5">Sasaran</th>
                        <th class="w-5">Indikator</th>
                        <th class="w-5">Target</th>
                        <th class="w-5">Satuan</th>
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
                        <td>{{$no;}}</td>
                        <td>{{ $tematikData['tema_nama'] }}</td>
                        <td>
                            <b>Sasaran Roadmap :</b><br/>
                            {{ $tematikData['sasaran_nama'] }} <br/><br/>
                            <b>Indikator Roadmap :</b><br/>
                            {{ $tematikData['indikator_nama'] }} 
                        </td>
                        <td>
                            {{ $tematikData['permasalahan_nama'] }}
                            @if ($tematikData['permasalahan_nama'])
                            <a href="#" class="btn btn-pending btn-sm w-full mb-2"
                            onclick="editPermasalahan('{{$tematikData['permasalahan_id']}}')" > 
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit Permasalahan
                                <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                            </a>
                            @endif
                        </td>
                        <td>
                            {{ $tematikData['permasalahan_sasaran'] }}
                            @if ($tematikData['permasalahan_nama'])
                            <button onclick="tambah_indikator_permasalahan('{{ $tematikData['permasalahan_nama'] }}','{{ $tematikData['permasalahan_sasaran'] }}', '{{ $tematikData['permasalahan_id'] }}');" class="btn btn-success btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>Tambah Indikator</button>
                            @endif
                        </td>
                        <td>
                            <input type="hidden" name="indikator_permasalahan_id" id="indikator-permasalahan-id">
                            {{ $tematikData['indikator_permasalahan_nama'] }}
                        </td>
                        <td>
                            {{ $tematikData['indikator_permasalahan_target'] }}
                        </td>
                        <td>
                            {{ $tematikData['indikator_permasalahan_satuan_target'] }}
                        </td>
                        <td>
                            @if ($tematikData['indikator_permasalahan_nama'])
                            <a href="#" class="btn btn-pending btn-sm w-full mb-2"
                            onclick="edit('{{$tematikData['indikator_permasalahan_id']}}')" > 
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit Indikator
                                <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                            </a>
                            <br>
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
                <input type="hidden" name="permasalahan_id" id="permasalahan-id">
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
                                                <textarea id="modPermasalahan-permasalahan" name="permasalahan" class="form-control" rows="8"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold w-30">Sasaran Permasalahan</td>
                                            <td colspan="5">
                                            <textarea id="modPermasalahan-sasaran-permasalahan" name="sasaran_permasalahan"  class="form-control" rows="4" ></textarea>
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
                <input type="hidden" name="tematik_indikator_permasalahan_id" id="tematik-indikator-permasalahan-id">
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
                                        <tr>
                                            <td class="font-bold w-30">Satuan</td>
                                            <td colspan="5">
                                                <input type="text" id="satuan-target-permasalahan" name="satuan_target_permasalahan" placeholder="Masukan Target" class="form-control" />
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
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

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

     function edit(id) {
        $('#indikator-permasalahan-id').val(id);
        $('#title').html('Edit Indikator Permasalahan');
        $('.saveButton').prop('disabled', true);
        $.getJSON("{{url('rb-tematik/permasalahan/get-indikator-permasalahan')}}/"+id, function(data) {
            $('#permasalahan-onIndikatorPermasalahan').val(data.permasalahan);
            $('#tematik-indikator-permasalahan-id').val(data.indikator_permasalahan_id);
            $('#sasaran-permasalahan-onIndikatorPermasalahan').val(data.sasaran);
            $('#indikator-permasalahan').val(data.indikator_permasalahan_nama);
            $('#target-permasalahan').val(data.indikator_permasalahan_target);
            $('#satuan-target-permasalahan').val(data.indikator_permasalahan_satuan);
            $('.saveButton').prop('disabled', false);
            modal_indikator_permasalahan.show();
        });
    }

    function editPermasalahan(id) {
        $('#permasalahan-id').val(id);
        $('#title-permasalahan').html('Edit Permasalahan');
        $('.saveButton').prop('disabled', true);
        $.getJSON("{{url('rb-tematik/permasalahan/get-permasalahan/')}}/"+id, function(data) {
            $('#sasaran-roadmap-onPermasalahan').val(data.sasaran_roadmap_id).change();
            $('#indikator-roadmap-onPermasalahan').val(data.indikator_roadmap_id);
            $('#target-roadmap-onPermasalahan').val(data.target_roadmap);
            $('#target-satuan-onPermasalahan').val(data.target_satuan_roadmap);
            $('#modPermasalahan-permasalahan').val(data.permasalahan_nama);
            $('#modPermasalahan-sasaran-permasalahan').val(data.permasalahan_sasaran);
            $('.saveButton').prop('disabled', false);
            modal_permasalahan.show();
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
                    columns: [0,1,2,3,4,5,6,7]
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
            rowsGroup: [0, 1, 2,3,4],
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