@extends('zi.admin.rubick')
@section('title', 'Rekap Data Pengusulan ZI - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Rekap Data Pengusulan ZI - {{$instansi_ZI->klpd_instansi->name}}
            </h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="row">
                <div class=" col-md-4 col-md-offset-2 text-center" style="text-align: right">
                    <img src="{{asset('assets/images/syarat_min_instansi.jpg')}}" width="50%">
                </div>
            </div>
            <br />
            <table style="text-align: left" class="table">
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th class="text-center">Skor</th>
                        <th class="text-center">Predikat</th>
                        <th class="text-center">WBK</th>
                        <th class="text-center">WBBM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Opini BPK</td>
                        <td class="text-center"> {{$instansi_ZI->skor_bpk}} </td>
                        <td class="text-center" class="text-center">
                            {{($instansi_ZI->opini_bpk)?$instansi_ZI->opini_bpk:"-"}} </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_bpk=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_bpk=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Predikat SAKIP</td>
                        <td class="text-center">{{$instansi_ZI->skor_sakip}}</td>
                        <td class="text-center">{{($instansi_ZI->predikat_sakip)?$instansi_ZI->predikat_sakip:"-"}}
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_sakip_wbk=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_sakip_wbbm=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Indeks RB</td>
                        <td class="text-center"> {{$instansi_ZI->skor_indeks_rb}}</td>
                        <td class="text-center">{{($instansi_ZI->indeks_rb)?$instansi_ZI->indeks_rb:"-"}} </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_indeksrb_wbk=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_indeksrb_wbbm=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Maturitas SPIP</td>
                        <td class="text-center"> {{$instansi_ZI->skor_maturitas_spip}}</td>
                        <td class="text-center">{{($instansi_ZI->maturitas_spip)?$instansi_ZI->maturitas_spip:"-"}}
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_maturitas_spip=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_maturitas_spip=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-center">Kesimpulan</td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_akhir_wbk=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($instansi_ZI->syarat_akhir_wbbm=="LULUS")
                            <i class="fa fa-check text-success"></i>
                            @else
                            <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>



            <table id="rekap-zi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>

                        <th>#</th>
                        <th>Isi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Instansi</td>
                        <td>{{$instansi_ZI->klpd_instansi->name}}</td>
                    </tr>
                    <tr>
                        <td>PIC</td>
                        <td>{{$instansi_ZI->pic}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Kontak</td>
                        <td>{{$instansi_ZI->nomor_kontak}} </td>
                    </tr>
                    <tr>
                        <td>email</td>
                        <td>{{$instansi_ZI->email}}</td>
                    </tr>
                    <tr>
                        <td>Surat Usulan</td>
                        <td><a href="{{$instansi_ZI->surat_usulan}}" target="_blank">{{$instansi_ZI->surat_usulan}}</a>
                        </td>
                    </tr>
                    <tr>
                        <td>SPTJM</td>
                        <td><a href="{{$instansi_ZI->sptjm}}" target="_blank">{{$instansi_ZI->sptjm}}</a></td>
                    </tr>
                    <tr>
                        <td>TLHP</td>
                        <td> <a href="{{$instansi_ZI->tlhp}}" target="_blank">{{$instansi_ZI->tlhp}}</a></td>
                    </tr>
                    <tr>
                        <td>Survei Mandiri</td>
                        <td><a href="{{$instansi_ZI->survei_mandiri}}"
                                target="_blank">{{$instansi_ZI->survei_mandiri}}</a></td>
                    </tr>
                    <tr>
                        <td>Jumlah Unit WBK </td>
                        <td>{{$instansi_ZI->jml_wbk}}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Unit WBBM</td>
                        <td>{{$instansi_ZI->jml_wbbm}}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Unit</td>
                        <td>{{$instansi_ZI->jml_wbk + $instansi_ZI->jml_wbbm}}</td>
                    </tr>
                    @foreach ($instansi_ZI->unit_zi as $unit_zi )
                    <tr>
                        <td>{{$unit_zi->nama}} ({{($unit_zi->wbk)?"WBK":""}}{{($unit_zi->wbbm)?"WBBM":""}} )</td>
                        <td><a href="{{$unit_zi->lke}}" target="_blank">lke : {{$unit_zi->lke}}</a></td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>


{{-- Modal Form Catatan Evaluator --}}
<div id="modal-catatan" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="darkbg modal-header">
                <h2 class="font-bold fw-medium fs-base me-auto" id="title">Monitoring dan Evaluasi Perencanaan</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form action="{{ url('rb-general/rekap_data/simpanCatatanEvaluator') }}" id="form-catatan_evaluator"
                method="post">
                @csrf
                <input type="hidden" id="target_id" name="target_id">
                <div class="modal-body grid columns-12 gap-4 gap-y-3">
                    <div class="g-col-12">
                        <table class="table table-noborder">
                            <tr>
                                <td class="font-bold">Catatan Evaluator</td>
                                <td>
                                    <textarea name="catatan_evaluator" id="catatan_evaluator" cols="30" rows="10"
                                        placeholder="Catatan" class="form-control mt-4"></textarea>
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