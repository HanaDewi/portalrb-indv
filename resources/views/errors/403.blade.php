<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('template_lkerb') }}/dist/images/favicon.ico" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Halaman Tidak Ditemukan</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ asset('template_lkerb') }}/dist/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="main">
        <div class="container">
            <!-- BEGIN: Error Page -->
            <div class="error-page flex flex-col lg:flex-row items-center justify-center h-screen text-center lg:text-left">
                <div class="-intro-x lg:mr-20">
                    <img alt="Rubick Tailwind HTML Admin Template" class="h-48 lg:h-auto" src="https://sixghakreasi.com/demos/rubick/dist/images/error-illustration.svg">
                </div>
                <div class="text-white mt-10 lg:mt-0">
                    <div class="intro-x text-8xl font-medium">403</div>
                    <div class="intro-x text-xl lg:text-3xl font-medium mt-5">Maap ya.. Anda tidak diijinkan mengakses halaman ini.</div>
                    <div class="intro-x text-lg mt-3">Mungkin masih dalam tahap pengembangan ya..</div>
                    <a href="{{ url('/dashboard') }}" class="intro-x btn py-3 px-4 text-white border-white dark:border-dark-5 dark:text-gray-300 mt-10">Kembali ke Dashboard</a>
                </div>
            </div>
            <!-- END: Error Page -->
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>