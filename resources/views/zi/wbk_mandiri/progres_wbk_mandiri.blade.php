@extends('zi.admin.rubick')
@section('title',$title)

@section('content')
<div class="intro-y col-span-12 lg:col-span-12">
    @include('common.status')
    <div class="intro-y box">
        <div class="col-span-12 grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6  intro-y">
                <div class="box p-5 zoom-in">
                    <div class="flex items-center">
                        <div class="text-lg font-bold truncate">Tahun :
                            <select id="filter-tahun">
                                @for ($i =date('Y'); $i >= 2024; $i--)
                                <option value="{{$i}}" @if($i==$tahun ) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto"> {{$title}}</h2>
            <!-- <button class="btn btn-danger shadow-md mr-2 float-right" onclick="tambah();" data-bs-toggle="modal"
                data-bs-target="#modal-kelola-tim"><i class="fa fa-add"></i> &nbsp; Tambah Bukti Dukung</button> -->
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="lapor_wbk_mandiri" class="table table-bordered table-striped table-hover" cellspacing="0"
                width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-5">No.</th>
                        <th>Tahun</th>
                        <th>Instansi</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instansiZIs as $key=>$instansi_zi)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$instansi_zi->tahun}}</td>
                        <td>{{$instansi_zi->klpd_instansi->name}}</td>
                        <td><a href="{{$instansi_zi->link_progres_wbk_mandiri}}"
                                target="_blank">{{$instansi_zi->link_progres_wbk_mandiri}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
@endpush