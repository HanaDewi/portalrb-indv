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

    select:has(option[value="2"]:checked) {
        background-color: blue !important;
        color: white;
    }


    select:has(option[value="1"]:checked) {
        background-color: green !important;
        color: white;
    }

    select:has(option[value="0"]:checked) {
        background-color: red !important;
        color: white;
    }
</style>
@if($status !="Berhak")
<style>
    .form-control,
    .glowing-border {
        pointer-events: none;
    }


    #tombol-kirim {
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
                @endif
            </h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">



            <br />
            <br />
            <br />
            <form action="{{ route('proses_panel_simpan') }}" method="POST">
                @csrf
                <input type="hidden" id="instansiZIId" name="instansiZIId" value="{{$instansi_ZI->id}}">

                <table id="rekap-zi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead class="table-dark font-bold">
                        <tr>

                            <th width="15%">Unit</th>
                            <th>Link Lke</th>
                            <th width="15%">Status</th>
                            <th>Catatan Tahap Sebelumnya</th>
                            <th>Catatan Panel</th>
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
                            <td>
                                @if($unit_zi->wbk==1)
                                WBK {{++$wbk_i}}
                                @elseif($unit_zi->wbbm==1)
                                WBBM {{++$wbbm_i}}
                                @endif
                                :
                                {{$unit_zi->nama}}
                            </td>
                            <td class="bukti_dukung text-center">
                                @if(isset($unit_zi->analisis_dokumen))
                                <a href="{{$unit_zi->analisis_dokumen->bukti_dukung}}" class="btn btn-warning"
                                    target="_blank">Lihat
                                    LKE</a>
                                @endif
                            </td>
                            <td>
                                <select class="form-control status" name="status-{{$unit_zi->id}}"
                                    data-old=@if(isset($unit_zi->panel->status))
                                    @if($unit_zi->panel->status==1) "1"
                                    @elseif($unit_zi->panel->status===0) "0"
                                    @else "kosong"
                                    @endif
                                    @else
                                    "kosong"
                                    @endif
                                    data-id="{{$unit_zi->id}}">>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option @if(isset($unit_zi->panel->status))
                                        @if($unit_zi->panel->status==1) selected
                                        @endif
                                        @endif
                                        value="1">Lulus</option>
                                    <option @if(isset($unit_zi->panel->status))
                                        @if($unit_zi->panel->status===0) selected
                                        @endif
                                        @endif
                                        value="0">Tidak Lulus</option>
                                </select>
                            </td>
                            <td class="rekomendasi">
                                <strong>Analisis Dokumen : </strong><br>
                                <p>
                                    {{$unit_zi->analisis_dokumen->kondisi}}
                                </p>
                                <br /><strong>Wawancara :</strong><br>
                                <p>
                                    {{$unit_zi->wawancara->kondisi}}
                                </p>
                                <br /><strong>Verifikasi Lapangan :</strong>
                                <p>
                                    {{$unit_zi->verifikasi_lapangan->kondisi}}
                                </p>
                            </td>
                            <td class="kondisi">
                                <textarea rows='4' cols='30' class='glowing-border' name='kondisi-{{$unit_zi->id}}'
                                    placeholder="Kondisi / Catatan">@if(isset($unit_zi->panel)){{$unit_zi->panel->kondisi}}@endif </textarea>
                            </td>

                        </tr>
                        @endif
                        @endforeach
                </table>
                <hr />
                <br />
                <div class="text-right">
                    <input type="submit" id="tombol-kirim" class="btn btn-primary" value="Simpan">
                </div>
            </form>
        </div>
    </div>
</div>




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