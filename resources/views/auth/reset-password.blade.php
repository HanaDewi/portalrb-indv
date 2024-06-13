<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8">
    <link href="{{ asset('template_lkerb') }}/dist/images/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lembar Kerja Evaluasi">
    <meta name="keywords" content="Lembar Kerja Evaluasi">
    <meta name="author" content="MENPANRB">
    <title>KEMENPANRB - LKE RB</title>
    <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css" />
</head>

<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                </a>
                <div class="my-auto">
                    <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16"
                        src="{{ asset('template_lkerb') }}/dist/images/logo.jpg">
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

                <div
                    class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">


                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        {{ __('Reset Password') }}
                    </h2>


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="intro-x mt-8">
                            <input id="email" type="email" class="intro-x login__input form-control py-3 px-4"
                                name="email" placeholder="Email" name="email" value="{{ $email }}" required
                                readonly>

                            @if ($errors->has('email'))
                                <span class="error invalid-feedback text-danger mt-2">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="intro-x mt-8">

                            <input id="password" type="password" class="intro-x login__input form-control py-3 px-4"
                                name="password" placeholder="Password" required autofocus>

                            @if ($errors->has('password'))
                                <span
                                    class="error invalid-feedback text-danger mt-2">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="intro-x mt-8">
                            <input id="password-confirm" type="password"
                                class="intro-x login__input form-control py-3 px-4" name="password_confirmation"
                                placeholder="Password confirmation" required>

                            @if ($errors->has('password_confirmation'))
                                <span
                                    class="error invalid-feedback text-danger mt-2">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    </div>
</body>

</html>