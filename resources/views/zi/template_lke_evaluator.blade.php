@extends('zi.admin.rubick')
@section('title', $title . ' - ' .auth()->user()->nama)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto"> {{$title}} </h2>
        </div>
        <br />
        <div class="col-span-12 grid grid-cols-12 " style="text-align:center;">
            <table class="table table-bordered table-striped" cellspacing="0" width="100%" style="align:center;   margin-left:55%; 
    margin-right:15">
                <tr>
                    <th>Template LKE </th>
                    <!-- <th>LKE Wawancara</th>
                    <th>LKE Verlap</th>-->
                </tr>
                <tr>
                    <td><a href="#" class="btn btn-primary" target="_blank">Download</a></td>
                    <!--<th><a href="#" class="btn btn-primary" target="_blank">Download</a></th>
                    <th><a href="#" class="btn btn-primary" target="_blank">Download</a></th>-->
                </tr>
            </table>


        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <div class="separator mt-5">

            </div>
            <br />

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
        
        
            
    });
</script>
@endpush