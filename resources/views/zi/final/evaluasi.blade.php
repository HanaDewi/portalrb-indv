@extends('zi.admin.rubick')
@section('title', $title)

@push('css')
<style>
    .glowing-border {
        border: 2px solid #b01133;
        border-radius: 7px;
    }

    .link-wrap {
        word-break: break-all;

    }

    select:has(option[value="1"]:checked) {
        background-color: green !important;
        color: white;
    }

    select:has(option[value="0"]:checked) {
        background-color: red !important;
        color: white;
    }

    td select {
        min-width: 120px
    }

    /*#rekap-zi tbody tr:nth-child(3n+1) {
        background-color: rgb(215, 213, 213);
        /* Light gray for the first row in each group 
    }
    */
</style>
@if($status !="Berhak")
<style>
    .form-control,
    .glowing-border {
        pointer-events: none;
    }


    #tombol-kirim,
    .kirim-file {
        display: none
    }
</style>

@endif
@endpush

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> {{$title}} - {{$instansi_ZI->klpd_instansi->name}}
                @if($instansi_ZI->instansi_wbk_mandiri)
                <b class="text-red-500">(WBK Mandiri)</b>
                @if ($instansi_ZI->hasil_wbk_mandiri)
                <a href="{{$instansi_ZI->hasil_wbk_mandiri}}" target="_blank" class="btn btn-primary">Lihat Hasil WBK
                    Mandiri</a>
                @else
                <a href="{{$instansi_ZI->hasil_wbk_mandiri}}" target="_blank" class="btn btn-secondary">Belum Mengunggah
                    Hasil WBK Mandiri</a>
                @endif
                @endif
            </h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="col-span-12 grid grid-cols-12 gap-6">
                    <div class="col-span-12 sm:col-span-4 2xl:col-span-4 intro-y">
                        @if ($instansi_ZI->lhe)
                        <a href="{{asset('storage/uploads/LHEZI2024/'.$instansi_ZI->lhe)}}" target="_blank"><img
                                src="{{asset('images/pdf.png')}}" width="10%"></a>
                        <br />
                        <h5>LHE {{$instansi_ZI->klpd_instansi->name}}</h5>
                        @endif
                        <br />
                        <button onclick="upload_lhe({{ $instansi_ZI->id }});"
                            class="btn btn-danger btn-sm kirim-file"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                            Upload LHE ZI</button>
                    </div>
                    <div class="col-span-12 sm:col-span-4 2xl:col-span-4 intro-y">
                        @if ($instansi_ZI->surat_undangan)
                        <a href="{{asset('storage/uploads/SuratUndangan2024/'.$instansi_ZI->surat_undangan)}}"
                            target="_blank"><img src="{{asset('images/pdf.png')}}" width="10%"></a>
                        <br />
                        <h5>Surat Undangan {{$instansi_ZI->klpd_instansi->name}}</h5>
                        @endif
                        <br />
                        <button onclick="upload_undangan({{ $instansi_ZI->id }});"
                            class="btn btn-warning btn-sm kirim-file"><i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                            Upload Surat Undangan</button>
                    </div>
                    <div class="col-span-12 sm:col-span-4 2xl:col-span-4 intro-y">
                        <br />
                        <a href="{{route('evaluatan_hasil_akhir')}}?instansi_zi_id={{$instansi_ZI->id}}" target="_blank"
                            class="btn btn-success btn-sm kirim-file">
                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                            Lihat Tampilan Evaluatan
                        </a>
                    </div>
                </div>


                <hr />


                <br />
                <br /><br />

            </div>


            <table id="rekap-zi" class="table table-bordered">
                <thead class="table-dark font-bold">
                    <tr>
                        <th>Unit</th>
                        <th>Link Lke</th>
                        <th>Tahapan</th>
                        <th>Status Tahapan</th>
                        <th>Catatan </th>
                        <th>Rekomendasi </th>
                        <th>Status Final</th>
                        <th>Kondisi / Catatan</th>
                        <th>Rekomendasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $wbk_i = 0;
                    $wbbm_i = 0;
                    $ganjil_genap=0;
                    @endphp
                    @foreach ($unit_ZIs as $key => $unit_zi )
                    @if(!$instansi_ZI->instansi_wbk_mandiri OR ($instansi_ZI->instansi_wbk_mandiri AND
                    $unit_zi->wbbm ))
                    @php
                    $ganjil_genap++;
                    @endphp
                    <tr @if($ganjil_genap % 2==0) style="background-color: #f5f5f5" @endif>
                        <td rowspan=5>
                            @if($unit_zi->wbk==1)
                            WBK {{++$wbk_i}}
                            @if($instansi_ZI->instansi_wbk_mandiri AND $unit_zi->wbk)
                            <strong style="color:red">(MANDIRI)</strong>
                            @endif
                            @elseif($unit_zi->wbbm==1)
                            WBBM {{++$wbbm_i}}
                            @endif
                            :
                            {{$unit_zi->nama}}
                        </td>
                        <td rowspan=5 class="bukti_dukung link-wrap">
                            @if(isset($unit_zi->analisis_dokumen))
                            <a href="{{$unit_zi->analisis_dokumen->bukti_dukung}}" target="_blank"
                                class="btn btn-primary">Lihat</a>
                            @endif
                        </td>
                        <td>Seleksi Administrasi dan sanggah</td>
                        <td>
                            @if(isset($unit_zi->seleksi_administrasi_unit))
                            @if($unit_zi->seleksi_administrasi_unit->status_final==1)
                            <p style="color:green">
                                LULUS
                            </p>
                            @elseif($unit_zi->sanggah_unit->status_final==1)
                            <p style="color:green">
                                LULUS
                            </p>
                            @elseif($unit_zi->sanggah_unit->status_final===0)
                            <p style="color:red">
                                TIDAK LULUS
                            </p>
                            @endif
                            @endif
                        </td>
                        <td class="link-wrap">

                            @if(isset($unit_zi->seleksi_administrasi_unit))
                            {{$unit_zi->seleksi_administrasi_unit->catatan_lke}} <br />
                            {{$unit_zi->seleksi_administrasi_unit->catatan_tlhp}} <br />
                            {{$unit_zi->seleksi_administrasi_unit->catatan_survei_mandiri}}<br />
                            {{$unit_zi->seleksi_administrasi_unit->catatan_2wbk}}<br />
                            @endif
                            @if(isset($unit_zi->sanggah_unit))
                            {{$unit_zi->sanggah_unit->catatan_lke}}<br />
                            {{$unit_zi->sanggah_unit->catatan_tlhp}}<br />
                            {{$unit_zi->sanggah_unit->catatan_survei_mandiri}}<br />
                            {{$unit_zi->sanggah_unit->catatan_2wbk}}<br />
                            @endif

                        </td>
                        <td class="link-wrap">
                            <!-- Rekomendasi -->
                        </td>
                        <td rowspan=5>
                            <select disabled class="form-control status" name="status-{{$unit_zi->id}}">
                                <option value="" disabled selected>Pilih Status</option>
                                <option @if(optional($unit_zi->panel)->status==1) selected
                                    @endif
                                    value="1">Lulus</option>
                                <option @if(optional($unit_zi->panel)->status===0) selected
                                    @elseif(optional($unit_zi->verifikasi_lapangan)->status===0) selected
                                    @elseif(optional($unit_zi->wawancara)->status===0) selected
                                    @elseif(optional($unit_zi->analisis_dokumen)->status===0) selected
                                    @elseif(optional($unit_zi->sanggah_unit)->status_final===0) selected
                                    @endif
                                    value="0">Tidak Lulus</option>
                            </select>
                        </td>
                        <td class="kondisi" rowspan=5 class="link-wrap">
                            {{optional($unit_zi->final)->kondisi}}
                        </td>
                        <td class="rekomendasi" rowspan=5 class="link-wrap">
                            {{optional($unit_zi->final)->rekomendasi}}
                        </td>
                        <td class="rekomendasi" rowspan=5 class="link-wrap">
                            <a href="{{route('proses_final_unit',$unit_zi->id)}}" class="btn btn-primary">Isi
                                Catatan dan Rekomendasi Final</a>
                        </td>

                    </tr>
                    <tr @if($ganjil_genap % 2==0) style="background-color: #f5f5f5" @endif>
                        <td>Analisis Dokumen</td>
                        <td>
                            @if(isset($unit_zi->analisis_dokumen))
                            @if($unit_zi->analisis_dokumen->status==1)
                            <p style="color:green">
                                LULUS
                            </p>
                            @elseif($unit_zi->analisis_dokumen->status===0)
                            <p style="color:red">
                                TIDAK LULUS
                            </p>
                            @endif
                            @endif
                        </td>
                        <td class="link-wrap">

                            @if(isset($unit_zi->analisis_dokumen))
                            {{$unit_zi->analisis_dokumen->kondisi}}
                            @endif

                        </td>
                        <td class="link-wrap">@if(isset($unit_zi->analisis_dokumen))
                            {{$unit_zi->analisis_dokumen->rekomendasi}}
                            @endif
                        </td>
                    </tr>
                    <tr @if($ganjil_genap % 2==0) style="background-color: #f5f5f5" @endif>
                        <td>Tahapan Wawancara</td>
                        <td>
                            @if(isset($unit_zi->wawancara))
                            @if($unit_zi->wawancara->status==1)
                            <p style="color:green">
                                LULUS
                            </p>
                            @elseif($unit_zi->wawancara->status===0)
                            <p style="color:red">
                                TIDAK LULUS
                            </p>
                            @endif
                            @endif
                        </td>

                        <td class="link-wrap">

                            @if(isset($unit_zi->wawancara))
                            {{$unit_zi->wawancara->kondisi}}
                            @endif

                        </td>
                        <td class="link-wrap">@if(isset($unit_zi->wawancara))
                            {{$unit_zi->wawancara->rekomendasi}}
                            @endif
                        </td>
                    </tr>
                    <tr @if($ganjil_genap % 2==0) style="background-color: #f5f5f5" @endif>
                        <td>Tahapan Verlap</td>
                        <td>
                            @if(isset($unit_zi->verifikasi_lapangan))
                            @if($unit_zi->verifikasi_lapangan->status==1)
                            <p style="color:green">
                                LULUS
                            </p>
                            @elseif($unit_zi->verifikasi_lapangan->status===0)
                            <p style="color:red">
                                TIDAK LULUS
                            </p>
                            @elseif($unit_zi->verifikasi_lapangan->status==2)
                            <p style="color:red">
                                Dibawa Ke Panel
                            </p>
                            @endif
                            @endif
                        </td>
                        <td class="link-wrap">

                            @if(isset($unit_zi->verifikasi_lapangan))
                            {{$unit_zi->verifikasi_lapangan->kondisi}}
                            @endif

                        </td>
                        <td class="link-wrap">
                            @if(isset($unit_zi->verifikasi_lapangan))
                            {{$unit_zi->verifikasi_lapangan->rekomendasi}}
                            @endif
                        </td>
                    </tr>
                    <tr @if($ganjil_genap % 2==0) style="background-color: #f5f5f5" @endif>
                        <td>Tahapan Panel</td>
                        <td>
                            @if(isset($unit_zi->panel))
                            @if($unit_zi->panel->status==1)
                            <p style="color:green">
                                LULUS
                            </p>
                            @elseif($unit_zi->panel->status===0)
                            <p style="color:red">
                                TIDAK LULUS
                            </p>
                            @elseif($unit_zi->panel->status==2)
                            <p style="color:red">
                                Dibawa Ke Panel
                            </p>
                            @endif
                            @endif
                        </td>
                        <td class="link-wrap">

                            @if(isset($unit_zi->panel))
                            {{$unit_zi->panel->kondisi}}
                            @endif

                        </td>
                        <td class="link-wrap">
                            @if(isset($unit_zi->panel))
                            {{$unit_zi->panel->rekomendasi}}
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            <hr />
            <br />
        </div>
    </div>
</div>


{{-- Modal Upload LHE --}}
<div id="modal-upload-lhe" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title-penyesuaian">Upload Hasil LHE</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ route('proses_upload_lhe_simpan') }} " id="form-penyesuaian" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="instansi_id" value="{{$instansi_ZI->id}}">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table">
                            <tr>
                                <td class="font-bold w-44">Berkas <span class="text-danger">*</span></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" onclick="pilih_berkas();"><i
                                            class="fa fa-plus"></i> Tambah Berkas</button>
                                    <input type="file" id="berkas" style="display: none">
                                    <div id="berkas_list" class="intro-y grid grid-cols-12 gap-6 mt-5"></div>
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
</div>
<!-- END: Modal Content -->

{{-- Modal Upload Surat Undangan --}}
<div id="modal_upload_undangan" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto">Upload Surat Undangan</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ route('proses_upload_surat_undangan_simpan') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="instansi_id" value="{{$instansi_ZI->id}}">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table">
                            <tr>
                                <td class="font-bold w-44">Berkas <span class="text-danger">*</span></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="pilih_berkas_undangan();"><i class="fa fa-plus"></i> Tambah
                                        Berkas</button>
                                    <input type="file" id="berkas_undangan" style="display: none">
                                    <div id="berkas_undangan_list" class="intro-y grid grid-cols-12 gap-6 mt-5"></div>
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
</div>
<!-- END: Modal Content -->

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
<script src="{{ asset('AdminLTE-3.2.0') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    var idx = {{ $instansi_ZI->id }};
    function upload_lhe(id) {
        modal_upload_lhe.show();
                // $.getJSON("{{ url('hasil/get_test_tp') }}/" + id, function(data) {
                //     $('#bobot_rb_general_penyesuaian').val(data.bobot_rb_general_penyesuaian);
                //     $('#berkas_list').html(data.berkas_list);
                //     $('.saveButton').prop('disabled', false);
                // });
    }

    function upload_undangan(id) {
        modal_upload_undangan.show();
                // $.getJSON("{{ url('hasil/get_test_tp') }}/" + id, function(data) {
                //     $('#bobot_rb_general_penyesuaian').val(data.bobot_rb_general_penyesuaian);
                //     $('#berkas_list').html(data.berkas_list);
                //     $('.saveButton').prop('disabled', false);
                // });
    }

    function pilih_berkas() {
        $('#berkas').trigger('click');
    }

    function pilih_berkas_undangan() {
        $('#berkas_undangan').trigger('click');
    }

    $('#berkas').change(function() {
        cek_berkas(this);
    })

    $('#berkas_undangan').change(function() {
        cek_berkas_undangan(this);
    })

    function isAllowed(ext) {
        switch (ext.toLowerCase()) {
            //case 'xlsx':
            //case 'xls':
            //case 'docx':
            //case 'doc':
            //case 'pptx':
            //case 'ppt':
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
                    Swal.fire("Perhatian", "File yang di input tidak sesuai ketentuan (pdf).", "error");
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

    function cek_berkas_undangan(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                filename = $('#berkas_undangan').val();
                newVal = $('#berkas_undangan').next().val();
                var parts = filename.split('.');
                var ext = parts[parts.length - 1];
                var desc = filename.replace("C:\\fakepath\\", "");
                var desc = desc.replace("."+ext, "");
                if (!isAllowed(ext)) {
                    Swal.fire("Perhatian", "File yang di input tidak sesuai ketentuan (pdf, word, excel, power point).", "error");
                } else {
                    src = ext.toLowerCase() == 'pdf' ? "{{asset('images/pdf.png')}}" : (ext.toLowerCase() == 'xls' || ext.toLowerCase() == 'xlsx' ? "{{asset('images/excel.png')}}" : (ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx' ? "{{asset('images/word.png')}}" : (ext.toLowerCase() == 'ppt' || ext.toLowerCase() == 'pptx' ? "{{asset('images/ppt.png')}}" : e.target.result)));
                    console.log(src, ext.toLowerCase());
                    berkas_undangan = $('#berkas_undangan').clone();
                    berkas_undangan.attr('name', 'berkas_undangan['+idx+']');
                    berkas_undangan.attr('id', 'berkas_undangan'+idx);
                    berkas_undangan_div = '<div class="col-span-12 lg:col-span-4" id="berkasundangandiv'+idx+'" style="position:relative;">'+
                            '<div style="height: 100px;">'+
                                '<img class="img-fluid card-img-top" src="'+src+'" alt="BerkasUndangan'+idx+'" style="max-height: 100px; max-width:100%; padding: 5px 0;">'+
                            '</div>'+
                            '<div class="form-group mb-0">'+
                                '<input type="text" name="deskripsi['+idx+']" class="form-control" id="deskripsi'+idx+'" placeholder="Deskripsi" value="'+desc+'" required>'+
                            '</div>'+
                            '<a href="javascript:void(0);" onclick="removeBerkasUndangan('+idx+')" class="remove-button text-danger">'+
                                '<div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </div>'
                            '</a>'+
                    '</div>';
                    $('#berkas_undangan_list').append(berkas_undangan_div);
                    $('#berkas_undangan_list').append(berkas_undangan);
                    idx++;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function(){

        modal_upload_lhe = tailwind.Modal.getInstance(document.querySelector("#modal-upload-lhe"));
        modal_upload_undangan = tailwind.Modal.getInstance(document.querySelector("#modal_upload_undangan"));

        $('.openNew').click(function(event) {
            event.preventDefault();
            window.open(
                $(this).attr('href'), 
                'newwindow', 
                'width=700,height=900'
            );
            return false;
        });

        $('.status').on('change',function() {
            if(this.value=="0"){
                $(this).parent().siblings(".rekomendasi").find("textarea").prop('required',true);
                $(this).parent().siblings(".rekomendasi").find("textarea").prop('disabled',false);
                $(this).parent().siblings(".kondisi").find("textarea").prop('required',true);
                $(this).parent().siblings(".bukti_dukung").find("input").prop('required',true);
            }else if(this.value=="1"){
                $(this).parent().siblings(".kondisi").find("textarea").prop('required',true);
                $(this).parent().siblings(".bukti_dukung").find("input").prop('required',true);
                $(this).parent().siblings(".rekomendasi").find("textarea").prop('disabled',true);
                var oldData = $(this).attr("data-old");
                if(oldData != "kosong" ){
                    let text = "Apakah anda yakin akan mengubah status? Perubahan status akan menghapus rekomendasi sebelumnya.";
                    if (confirm(text) == true) {
                        $(this).parent().siblings(".rekomendasi").find("textarea").prop('required',false);
                        $(this).parent().siblings(".rekomendasi").find("textarea").val('');
                    } else {
                        this.value="0"
                    }
                }
            }
            $(this).attr("data-old", this.value);
        });

        
        var empDataTable = $('#rekap-zi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    title: 'Rekap Data Pengusulan ZI',
                    text: '<button class="btn btn-warning btn-sm w-32 mr-2 mb-2"> <svg xmlns="https://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="18px" height="18px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                            titleAttr: 'Download Excel'
                } 
            ],
            scrollX: true,
            // 'orderFixed': [0, 'asc'],
            autoWidth: false,
            paging: false,
            bInfo: false,
            ordering: false,
        });


    });

</script>
@endpush