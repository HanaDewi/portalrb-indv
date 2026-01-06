@extends('layout.rubick')
@section('title', 'Buka Akses')

@section('content')
@include('common.status')
<div class="intro-y col-span-12 lg:col-span-12">
    <div class="intro-y box">
        <div class="grid grid-cols-12 gap-6 p-5">
            <div class="intro-y col-span-12 lg:col-span-12">
                <p>
                    Mohon Maaf! Halaman Belum Bisa Diakses! Instansi belum mengisi data COI. mohon isi terlebih dulu data COI pada menu <a href="{{ url('pelaporan-coi') }}" class="font-bold">Pelaporan COI</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection