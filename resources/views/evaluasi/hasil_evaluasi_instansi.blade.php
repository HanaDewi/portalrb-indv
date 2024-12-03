@extends('layout.rubick')
@section('title', 'Database Indikator')

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">Hasil Evaluasi {{ $instansi->name }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table class="table table-bordered table-striped mt-5">
                <tr>
                    <td class="font-bold" width="220">RB General Awal</td>
                    <td>{{ round($test_tp->rb_general, 2) }}</td>
                </tr>
                <tr>
                    <td class="font-bold">Koefisien</td>
                    <td>{{ round($test_tp->koefisien, 2) }}</td>
                </tr>
                <tr>
                    <td class="font-bold">RB General</td>
                    <td>{{ round(($test_tp->rb_general + $test_tp->koefisien), 2) }}</td>
                </tr>
                <tr>
                    <td class="font-bold">Total Bobot RB General</td>
                    <td>100</td>
                </tr>
                <tr>
                    <td class="font-bold">Bobot RB General Penyesuaian</td>
                    <td>{{ $test_tp->bobot_rb_general_penyesuaian }}</td>
                </tr>
                <tr>
                    <td class="font-bold">RB General Penyesuaian</td>
                    <td>{{ round($test_tp->rb_general_penyesuaian, 2) }}</td>
                </tr>
                <tr>
                    <td class="font-bold">RB Tematik</td>
                    <td>{{ round($test_tp->rb_tematik, 2) }}</td>
                </tr>
                <tr>
                    <td class="font-bold">Index RB</td>
                    <td>{{ round($test_tp->index_rb, 2) }}</td>
                </tr>
                @php
                $idx = 0;
                @endphp
                @if (auth()->user()->level != 'tpm')
                <tr>
                    <td class="font-bold">File Berkas</td>
                    <td>
                        @php
                        $idx = $test_tp->files ? $test_tp->files->max('id') + 1 : 0;
                        $berkas_list = '';
                        if ($test_tp->files) {
                            foreach ($test_tp->files as $berkas) {
                                $ext = pathinfo($berkas->file, PATHINFO_EXTENSION);
                                $src = asset('storage/berkas/' . $berkas->file);
                                $berkas_list .= '<a href="' . $src . '" target="_blank" title="' . $berkas->deskripsi . '" class="inline-block"><img src="' . asset('images') . '/'.exts($ext).'" style="width: 50px; margin-right: 5px; margin-top: 5px;"></a>';
                            }
                        }
                        @endphp
                        {!! $berkas_list !!}
                    </td>
                </tr>
                @endif
                @if (in_array(auth()->user()->level, ['admin', 'tpn']) && $test_tp->rb_general)
                <tr>
                    <td class="font-bold">Aksi</td>
                    <td>
                        <button onclick="edit_test_tp();" class="btn btn-warning btn-sm"><i data-lucide="edit" class="w-4 h-4 mr-1"></i> Perbaharui Data TP</button>
                    </td>
                </tr>
                @endif
            </table>
            <br>
            <table id="hasil_evaluasi_instansi" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parameters as $parameter)
                        <tr>
                            <td></td>
                            <td>{{ $parameter->komponen }}</td>
                            <td>{{ $parameter->subkomponen }}</td>
                            <td>{{ $parameter->indikator }}</td>
                            <td>{{ $parameter->bobot }}</td>
                            <td>{{ $parameter->score }}</td>
                            <td>{{ $parameter->score_index }}</td>
                            <td>{{ $parameter->catatan }}</td>
                            <td>{{ $parameter->rekomendasi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form RB General Penyesuaian --}}
<div id="modal-penyesuaian" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title-penyesuaian">Perbaharui Data TP
                </h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('evaluasi/hasil-evaluasi/'.$instansi->id.'/'.$test_tp->lke_kegiatan_id.'/simpan') }}" id="form-penyesuaian" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="test_tp_id" id="test_tp_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table">
                            <tr>
                                <td class="font-bold" width="230">Koefisien</td>
                                <td>
                                    <input type="text" name="koefisien" id="koefisien" placeholder="Koefisien" class="form-control digit">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Bobot RB General Penyesuaian</td>
                                <td>
                                    <input type="text" name="bobot_rb_general_penyesuaian" id="bobot_rb_general_penyesuaian" placeholder="Bobot RB General Penyesuaian" class="form-control numeric">
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold w-44">Berkas <span class="text-danger">*</span></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" onclick="pilih_berkas();"><i class="fa fa-plus"></i> Tambah Berkas</button>
                                    <input type="file" id="berkas" style="display: none;">
                                    <div id="berkas_list" class="intro-y grid grid-cols-12 gap-6 mt-5">
                                        {!! $test_tp->berkas_list !!}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-end">
                    <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 me-1">Batal</button>
                    <button type="submit" class="btn btn-primary w-20 saveButton">Simpan</button>
                </div> <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div> <!-- END: Modal Content -->
@endsection

@push('js')
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script>
    @if (in_array(auth()->user()->level, ['admin', 'tpn', 'tpm']))
    var idx = {{ $idx }};
    $(document).ready(function() {
        modal_penyesuaian = tailwind.Modal.getInstance(document.querySelector("#modal-penyesuaian"));

        $(".numeric").inputmask("decimal",{
            radixPoint:".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            min: 1,
            max: 100,
        });

        $(".digit").inputmask("decimal",{
            radixPoint:".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            min: -100,
            max: 100,
        });

        $('#form-penyesuaian').validate({
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

        $('#berkas').change(function() {
            cek_berkas(this);
        })
    });

    function edit_test_tp() {
        $('#koefisien').val('{{ $test_tp->koefisien }}');
        $('#bobot_rb_general_penyesuaian').val('{{ $test_tp->bobot_rb_general_penyesuaian }}');
        modal_penyesuaian.show();
    }
    
    function pilih_berkas() {
        $('#berkas').trigger('click');
    }

    function isAllowed(ext) {
        switch (ext.toLowerCase()) {
            case 'xlsx':
            case 'xls':
            case 'docx':
            case 'doc':
            case 'pptx':
            case 'ppt':
            case 'pdf':
            return true;
        }
        return false;
    }

    function cek_berkas(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                filename = $('#berkas').val();
                newVal = $('#berkas').next().val();
                var parts = filename.split('.');
                var ext = parts[parts.length - 1];
                var desc = filename.replace("C:\\fakepath\\", "");
                var desc = desc.replace("."+ext, "");
                if (!isAllowed(ext)) {
                    Swal.fire("Perhatian", "File yang di input tidak sesuai ketentuan (pdf, word, excel, power point).", "error");
                } else {
                    src = ext.toLowerCase() == 'pdf' ? "{{asset('images/pdf.png')}}" : (ext.toLowerCase() == 'xls' || ext.toLowerCase() == 'xlsx' ? "{{asset('images/excel.png')}}" : (ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx' ? "{{asset('images/word.png')}}" : (ext.toLowerCase() == 'ppt' || ext.toLowerCase() == 'pptx' ? "{{asset('images/ppt.png')}}" : e.target.result)));
                    console.log(src, ext.toLowerCase());
                    berkas = $('#berkas').clone();
                    berkas.attr('name', 'berkas['+idx+']');
                    berkas.attr('id', 'berkas'+idx);
                    berkas_div = '<div class="col-span-12 lg:col-span-4" id="berkasdiv'+idx+'" style="position:relative;">'+
                            '<div style="height: 100px;">'+
                                '<img class="img-fluid card-img-top" src="'+src+'" alt="Berkas'+idx+'" style="max-height: 100px; max-width:100%; padding: 5px 0;">'+
                            '</div>'+
                            '<div class="form-group mb-0">'+
                                '<input type="text" name="deskripsi['+idx+']" class="form-control" id="deskripsi'+idx+'" placeholder="Deskripsi" value="'+desc+'" required>'+
                            '</div>'+
                            '<a href="javascript:void(0);" onclick="removeBerkas('+idx+')" class="remove-button text-danger">'+
                                '<div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </div>'
                            '</a>'+
                    '</div>';
                    $('#berkas_list').append(berkas_div);
                    $('#berkas_list').append(berkas);
                    idx++;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeBerkas(id) {
        Swal.fire({
            title: "Yakin?",
            text: "berkas-nya mau di hapus?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus aja!"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#berkasdiv'+id).remove();
                $('#berkas'+id).remove();
            }
        });
    }
    @endif
</script>
<script>
    var hasil_evaluasi_instansi = $('#hasil_evaluasi_instansi').DataTable({
        responsive: true,
		columnDefs: [
			{
				targets: [0],
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
			}
		]
    });
</script>
@endpush