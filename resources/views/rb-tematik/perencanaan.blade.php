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
                                    <a href="#" class="btn btn-pending btn-sm w-full mb-2"
                                    onclick="edit('{{$tematikData['indikator_id']}}')" > 
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                        <span class="text-xs px-1 rounded-full bg-warning text-white badge"><!-- count($target->rencana_aksi) --></span>
                                    </a>
                                    <br>
                                    <a href="#" onclick="edit_monev('{{$tematikData['indikator_id']}}');" class="btn btn-dark btn-sm w-full mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>Monev
                                    </a>
                                    <br/>
                                    <a href="#" class="btn btn-danger btn-sm w-full mb-2"
                                    onclick="hapus('{{$tematikData['indikator_id']}}')" > 
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Hapus
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
            modal_monev_indikator_roadmap = tailwind.Modal.getInstance(document.querySelector("#modal-monev-indikator-roadmap"));

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
                $('#realisasi_indikator').val(data.realisasi_indikator);
                $('#capaian_indikator').val(data.capaian_indikator);
                $('#catatan').val(data.catatan);
            $('.saveButton').prop('disabled', false);
                modal_monev_indikator_roadmap.show();
            });
        }

        function hapus(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Hapus Rencana Aksi ini?",
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
                        if (terhapus) {
                            Swal.fire('Selamat!', 'Data Rencana Aksi berhasil dihapus!', 'success');
                        } else {
                            Swal.fire('Aduh!', 'Data Rencana Aksi gagal dihapus! Coba lagi nanti ya..', 'error');
                        }
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
                        
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
