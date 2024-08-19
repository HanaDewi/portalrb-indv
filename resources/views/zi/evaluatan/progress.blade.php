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
                        <a href="{{ route('evaluatan_desk') }}">Desk Evaluasi</a>
                    </li>
                    <li class=@if($instansiZI->tahap_seleksi >= 6) "active-tl" @endif>
                        <a href="{{ route('evaluatan_verifikasi_lapangan') }}">Verifikasi Lapangan</a>
                    </li>
                    <li class=@if($instansiZI->tahap_seleksi >= 7) "active-tl" @endif>
                        <a href="{{ route('evaluatan_hasil_akhir') }}">Hasil Akhir</a>
                    </li>
                </ul>
            </div>
            <br /><br /><br /><br />

        </div>
    </div>
</div>