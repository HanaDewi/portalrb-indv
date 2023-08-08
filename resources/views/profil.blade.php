@extends('layout.rubick')
@section('title', 'Profil')

@section('content')
@include('common.status')
<form action="{{ url('profil_simpan') }}" id="form-user" method="post">
    @csrf
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Profil Pengguna
                    </h2>
                </div>
                <div id="input" class="p-5">
                    <div class="preview">
                        <div class="mt-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text" class="form-control" placeholder="Username" value="{{ $user->username }}" disabled>
                        </div>
                        <div class="mt-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input id="nama" type="text" class="form-control" placeholder="Nama" value="{{ $user->nama }}" disabled>
                        </div>
                        <div class="mt-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Input -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Ganti Password
                    </h2>
                </div>
                <div id="input" class="p-5">
                    <div class="preview">
                        <div class="mt-3">
                            <label for="old_password" class="form-label">Password Lama</label>
                            <input id="old_password" type="password" class="form-control" placeholder="Password Lama" name="password_lama">
                        </div>
                        <div class="mt-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input id="password" type="password" class="form-control" placeholder="Password Baru" name="password">
                        </div>
                        <div class="mt-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input id="confirm_password" type="password" class="form-control" placeholder="Konfirmasi Password Baru" name="password_confirmation">
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Input -->
        </div><div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div id="input" class="p-5">
                    <div class="preview text-right">
                        <button class="btn btn-success w-24 mr-1 mb-2">Simpan</button>
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
        $('#form-user').validate({
            rules: {
                password_confirmation: {
                    equalTo: "#password"
                },
            },
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