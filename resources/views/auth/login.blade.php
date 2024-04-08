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
                    <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('template_lkerb') }}/dist/images/logo.jpg">
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
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>
                        @if (app('request')->input('ruang-belajar'))
                            <input id="ruang-belajar" type="hidden" class="form-check-input border mr-2" name="ruang-belajar" value="1">
                            <input id="remember" type="checkbox" class="form-check-input border mr-2" name="remember">
                        @endif
                        <div class="intro-x mt-8">
                            <input type="text" class="intro-x login__input form-control py-3 px-4 block @error('username') border-danger @enderror" placeholder="Username" name="username" value="{{ old('username') }}">
                            @if ($errors->has('username'))
                                <span class="error invalid-feedback text-danger mt-2">{{ $errors->first('username') }}</span>
                            @endif
                            <input type="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Password" name="password">
                        </div>
                        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember" type="checkbox" class="form-check-input border mr-2" name="remember">
                                
                                <label class="cursor-pointer select-none" for="remember">Remember me</label>
                            </div>
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <a href="index.html">
                                <button class="btn btn-danger py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('template_lkerb') }}/dist/js/app.js"></script>
</body>
</html>