@extends('home-template.template')
@section('content')
<section class="features-area pt-50 pb-85 rel z-1"
    style="background-image: url({{ asset('assets/images/bg2.jpg')}}); background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="section-title text-center pb-35 ">
                <h2 style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;">Pengusulan Zona
                    Integritas</h2>
                <span class="line"></span>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="feature-item" style="background-color: white; border-radius: 25px;">
                    <div class="content">
                        <img src="{{ asset('assets/images/zi/SE4.jpg')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection