@extends('layout.rubick')
@section('title', 'Buka Akses')

@section('content')
@include('common.status')
<form action="{{ url('access/simpan') }}" id="form-access" method="post">
    @csrf
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-bold text-base mr-auto">
                        Buka Akses
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 p-5">
                    <div class="intro-y col-span-12 lg:col-span-4">
                        <div class="form-group">
                            <label for="kegiatan_utama_id" class="form-label">Instansi <span class="text-danger">*</span></label>
                            {!! Form::select('user_level', ['kabupaten' => 'Kabupaten', 'provinsi' => 'Provinsi', 'kl' => 'Kementrian Lain'], null, ['class' => 'w-full', 'id' => 'user_level', 'data-placeholder' => 'Pilih User Level', 'required']) !!}
                        </div>
                    </div>
                    <div class="intro-y col-span-12 lg:col-span-4">
                        <div class="form-group">
                            <label for="nama" class="form-label">Waktu Awal</label>
                            <input type="text" name="waktu_awal" class="datepicker form-control w-full block" data-single-mode="true" required> 
                        </div>
                    </div>
                    <div class="intro-y col-span-12 lg:col-span-4">
                        <div class="form-group">
                            <label for="email" class="form-label">Waktu Akhir</label>
                            <input type="text" name="waktu_akhir" class="datepicker form-control w-full block" data-single-mode="true" required> 
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Input -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Input -->
            <div class="intro-y box p-5">
                <div class="preview text-right">
                    <button class="btn btn-primary w-24 mr-1 mb-2">Simpan</button>
                </div>
            </div>
            <!-- END: Input -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-bold text-base mr-auto">
                        Daftar Akses
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 p-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <table class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Level User</th>
                                    <th>Waktu Awal</th>
                                    <th>Waktu Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($access))
                                @foreach ($access as $akses)
                                    <tr>
                                        <td>{{ $akses->user_level }}</td>
                                        <td>{{ $akses->waktu_awal }}</td>
                                        <td>{{ $akses->waktu_akhir }}</td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3"><i>Belum ada data.</i></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END: Input -->
        </div>
    </div>
</form>
@endsection

@push('js')
<script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
<script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function () {
        $('#form-access').validate({
            highlight: function (input) {
                $(input).addClass('border-danger');
            },
            unhighlight: function (input) {
                $(input).removeClass('border-danger');
            },
            errorPlacement: function( error, element ) {
                var placement = element.closest('.mt-3');
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
</script>
@endpush