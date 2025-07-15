<div class="col-lg-12col-md-12">
    <div class="feature-item" style="background-color: white; border-radius: 25px;">
        <div class="content" style="overflow-x: scroll;">
            <div class="container-timeline">
                <span class="line"></span>
                <br />

                <ul class="timeline">
                    <li class=@if($instansiZI->tahap_seleksi >= 1) "active-tl" @endif >
                        <a href="{{ route('pengusulan_zi') }}">Pengusulan </a>
                    </li>
                    <li class=@if($instansiZI->tahap_seleksi >= 2) "active-tl" @endif>
                        <a href="{{ route('evaluatan_seleksi_administrasi') }}">Seleksi Administrasi</a>
                    </li>
                    <li class=@if($instansiZI->tahap_seleksi >= 3) "active-tl" @endif>
                        <a href="{{ route('evaluatan_hasil_sanggah') }}">Hasil Sanggah</a>
                    </li>
                    <li class=@if($instansiZI->tahap_seleksi >= 4) "active-tl" @endif>
                        <a href="{{ route('evaluatan_desk') }}">Desk Evaluasi & Observasi lapangan</a>
                    </li>

                    <li class=@if($instansiZI->tahap_seleksi >= 5) "active-tl" @endif>
                        <a href="{{ route('evaluatan_hasil_akhir') }}">Hasil Akhir</a>
                    </li>
                </ul>
            </div>
            <br /><br /><br /><br />
            @if($instansiZI->instansi_wbk_mandiri)
            <hr />
            <a href="{{route('lapor_wbk_mandiri')}}" class="btn btn-warning font-bold ">Lapor WBK Mandiri</a>
            @if($instansiZI->link_progres_wbk_mandiri)
            &nbsp;&nbsp;&nbsp;
            <a href="{{$instansiZI->link_progres_wbk_mandiri}}" class="btn btn-warning font-bold "
                target="_blank">Progres
                WBK Mandiri</a>
            @endif
            @endif


        </div>
    </div>
</div>