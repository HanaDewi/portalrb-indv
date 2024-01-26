@extends('layout.rubick')
@section('title', 'Hasil Semua')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Hasil {{ $instansi->name }}</h2>
        </div>
        @if (in_array(auth()->user()->level, ['instansi', 'kl', 'admin', 'tpn']))
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h1 class="font-bold text-base mr-auto">Index RB :
                @php
                    if (isset($instansi->lke_test_tp)) {
                        echo round($instansi->lke_test_tp->index_rb, 2);
                    }
                @endphp
            </h1>
        </div>            
        @endif
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th class="w-5">No.</th>
                        <th class="w100">Komponen</th>
                        <th class="w200">Sub Komponen </th>
                        <th class="w200">Indikator Penilaian </th>
                        <th class="w200">Bobot </th>
                        <th class="w200">Skor </th>
                        <th class="w200">Skor Index </th>
                        <th class="w200">Catatan </th>
                        <th class="w200">Rekomendasi </th>
                        <th class="w200">Tim Penilai</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @if (isset($lkeTestTPLine))
                        @foreach ($lkeTestTPLine as $testTPLine)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $testTPLine->paramL0->name }}</td>
                                <td>{{ $testTPLine->paramL1->name }}</td>
                                <td>{{ $testTPLine->paramL4->name }} </td>
                                <td>{{ round($testTPLine->pertanyaan->weight, 2) }} </td>
                                <td>{{ $testTPLine->score }}</td>
                                <td>
                                    {{ $testTPLine->score_index }}
                                    @if (in_array(auth()->user()->level, ['admin', 'tpn', 'tpm']))
                                    <button onclick="edit_score({{ $testTPLine->id }});" class="btn btn-warning btn-sm w-full mb-2"><i data-lucide="edit" class="w-4 h-4 mr-1"></i></button>
                                    @endif
                                </td>
                                <td>{{ $testTPLine->note }}</td>
                                <td>{{ $testTPLine->todo }}</td>
                                <td>{{ $testTPLine->tim_penilai->name }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form score --}}
<div id="modal-score" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title-score">Perbaharui Score Index
                </h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('hasil/simpan_test_tp_line') }}" id="form-score" method="post">
                @csrf
                <input type="hidden" name="test_tp_line_id" id="test_tp_line_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table">
                            <tr>
                                <td class="font-bold w-44">Skor Index <span class="text-danger">*</span></td>
                                <td>
                                    <input type="text" name="score_index" id="score_index" placeholder="Skor Index" class="form-control digit" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold w-44 align-top">Catatan</td>
                                <td>
                                    <textarea rows="5" name="note" id="note" placeholder="Catatan" class="form-control"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold w-44 align-top">Rekomendasi</td>
                                <td>
                                    <textarea rows="5" name="todo" id="todo" placeholder="Rekomendasi" class="form-control"></textarea>
                                </td>
                            </tr>
                        </table>
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
            modal_score = tailwind.Modal.getInstance(document.querySelector("#modal-score"));

            $(".digit").inputmask("decimal",{
                radixPoint:".",
                digits: 2,
                autoGroup: true,
                rightAlign: false,
                min: 0,
                max: 10,
            });

            $('#form-score').validate({
                highlight: function(input) {
                    $(input).addClass('border-danger');
                },
                unhighlight: function(input) {
                    $(input).removeClass('border-danger');
                },
                errorPlacement: function(error, element) {
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

        function edit_score(id) {
            $('#test_tp_line_id').val(id);
            $('.saveButton').prop('disabled', true);
            modal_score.show();
            $.getJSON("{{ url('hasil/get_test_tp_line') }}/" + id, function(
                data) {
                $('#score_index').val(data.score_index);
                $('#note').val(data.note);
                $('#todo').val(data.todo);
                $('.saveButton').prop('disabled', false);
            });
        }
    </script>
        <script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function(){
        var empDataTable = $('#perencanaan').DataTable({
            dom: 'Blfrtip',
            buttons: [
            {  
                extend: 'copy'
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9] // Column index which needs to export
                }
            },
            {
                extend: 'csv',
            },
            {
                extend: 'excel',
            } 
            ] 

        });

        });


    </script>
@endpush
