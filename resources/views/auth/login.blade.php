<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('assets') }}/images/garuda.png" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Lembar Kerja Evaluasi">
        <meta name="keywords" content="Lembar Kerja Evaluasi">
        <meta name="author" content="MENPANRB">
        <title>KEMENPANRB - LKE RB</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css" />
        <style>
            .password-container {
                position: relative;
                width: 100%;
            }

            .password-container i {
                position: absolute;
                right: 10px;
                top: 65%;
                transform: translateY(-50%);
                cursor: pointer;
            }
        </style>
    </head>

    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                    </a>
                    <div class="my-auto">
                        <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('assets') }}/images/logo-portalrb.png">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Kementerian Pendayagunaan
                            <br>
                            Aparatur Negara dan <br>
                            Reformasi Birokrasi
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Sistem Informasi
                            Lembar Kerja Evaluasi RB</div>
                    </div>
                </div>
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <form action="{{ route('login') }}" method="post" id="form-login">
                            @csrf
                            <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                                Sign In
                            </h2>
                            
                            <div class="form-group">
                                {!! Form::select('modul', ['evaluasi_rb' => 'Evaluasi RB', 'zi' => 'Zona Integritas', 'evaluasi_akip' => 'Evaluasi Akip'], 'evaluasi_rb', ['class' => 'w-full mt-5', 'id' => 'modul', 'data-placeholder' => 'Pilih Modul', 'required']) !!}
                            </div>
                            <div class="intro-x mt-8">
                                <input type="text" class="intro-x login__input form-control py-3 px-4 block @error('username') border-danger @enderror" placeholder="Username" name="username" value="{{ old('username') }}">
                                @if ($errors->has('username'))
                                    <span class="error invalid-feedback text-danger mt-2">{{ $errors->first('username') }}</span>
                                @endif
                                <div class="password-container">
                                    <input type="password" id="password" name="password" placeholder="Enter your password" class='py-3 px-4 mt-4 form-control'>
                                    <i id="togglePassword" class="fas fa-eye"></i>
                                </div>
                            </div>
                            <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                                <div class="flex items-center mr-auto">
                                    <input id="remember" type="checkbox" class="form-check-input border mr-2" name="remember">
                                    
                                    <label class="cursor-pointer select-none" for="remember">Remember me</label>
                                </div>
                                <button type="button" onclick="$('#form-login').hide();$('#form-forget').fadeIn();">Forget password</button>
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <a href="index.html">
                                    <button class="btn btn-info py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                                </a>
                            </div>
                        </form>
                        <form action="{{ route('forgot') }}" method="post" id="form-forget" hidden>
                            @csrf
                            <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                                Forget Password
                            </h2>
                            <div class="intro-x mt-8">
                                <input type="email" class="intro-x login__input form-control py-3 px-4 block @error('email') border-danger @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="error invalid-feedback text-danger mt-2">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                                <div class="flex items-center mr-auto">
                                &nbsp; &lt; &nbsp; <button type="button" onclick="$('#form-forget').hide();$('#form-login').fadeIn();">Back to login</button>
                                </div>
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <a href="index.html">
                                    <button class="btn btn-info py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Submit</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('template_lkerb') }}/dist/js/app.js"></script>
        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                var passwordField = document.getElementById('password');
                var icon = document.getElementById('togglePassword');

                // Toggle the type of the password field
                if (passwordField.type === 'password') {
                    passwordField.type = 'text'; // Show the password
                    icon.classList.remove('fa-eye'); // Remove the eye icon
                    icon.classList.add('fa-eye-slash'); // Add the eye-slash icon
                } else {
                    passwordField.type = 'password'; // Hide the password
                    icon.classList.remove('fa-eye-slash'); // Remove the eye-slash icon
                    icon.classList.add('fa-eye'); // Add the eye icon
                }
            });
        </script>
    </body>
</html>