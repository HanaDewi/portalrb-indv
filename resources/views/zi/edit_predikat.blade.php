@extends('zi.admin.rubick')
@section('title', 'Rekap Data Pengusulan ZI - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> Update Predikat - {{ $instansi_ZI->klpd_instansi->name }}</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">

            <div class="separator mt-5">
                <img src="{{asset('assets/images/syarat_min_instansi.jpg')}}" width="50%">
                <form method="POST" action="{{ route('edit_predikat_store') }}">
                    @csrf
                    <input type="hidden" name="zi_id" value="{{ $instansi_ZI->id}}">
                    <table class="table table-bordered" id="inputform-sasaran-roadmap">
                        <tr>
                            <td class="font-bold w-15">Opini BPK </td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="opini_bpk">
                                    <option value="WTP">WTP</option>
                                    <option value="WDP">WDP</option>
                                    <option value="TW">TW</option>
                                    <option value="-">-</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">Indeks RB</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="skor_index_rb">
                                    <option valus="AA">AA</option>
                                    <option value="A">A</option>
                                    <option value="BB">BB</option>
                                    <option value="B">B</option>
                                    <option value="CC">CC</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>

                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">Predikat Sakip</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="skor_sakip">
                                    <option valus="AA">AA</option>
                                    <option value="A">A</option>
                                    <option value="BB">BB</option>
                                    <option value="B">B</option>
                                    <option value="CC">CC</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">Maturitas SPIP</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="skor_maturitas_spip">
                                    <option value="Level 6">Level 6</option>
                                    <option value="Level 5">Level 5</option>
                                    <option value="Level 4">Level 4</option>
                                    <option value="Level 3">Level 3</option>
                                    <option value="Level 2">Level 2</option>
                                    <option value="Level 1">Level 1</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_bpk</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_bpk">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_sakip_wbk</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_sakip_wbk">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_sakip_wbbm</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_sakip_wbbm">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_indeksrb_wbk</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_indeksrb_wbk">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_indeksrb_wbbm</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_indeksrb_wbbm">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_maturitas_spip</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_sakip_wbk">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_akhir_wbk</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_akhir_wbk">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">syarat_akhir_wbbm</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_akhir_wbbm">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">Keterangan</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="syarat_sakip_wbk">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold w-15">Status Akhir</td>
                            <td>
                                <select class="form-select mt-2 sm:mr-2 form-control" name="status_akhir">
                                    <option>LULUS</option>
                                    <option>GAGAL</option>
                                </select>
                            </td>
                        </tr>

                    </table>
                    <hr />
                    <br />
                    <button type="submit" class="btn btn-primary w-20 saveButton">Simpan</button>
                </form>

            </div>

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
            paging: true,
            bInfo: false,
            ordering: false,
        });

        
    });

</script>
@endpush