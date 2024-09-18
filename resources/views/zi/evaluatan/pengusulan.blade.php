@extends('home-template.template')
@section('content')
<section class="features-area pt-50 pb-85 rel z-1"
    style="background-image: url({{ URL::to('/') }}/assets/images/bg2.jpg); background-size: cover;">
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
                        @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn")

                        <form action="{{URL::to('zi')}}" method="get">
                            @csrf
                            <div class="row">
                                <div class="col-md-9">
                                    <select name="instansi_id" class="form-control selectpicker"
                                        data-live-search="true">
                                        @foreach ($instansis as $inst )
                                        <option value="{{$inst->id}}" @if($inst->name==$instansi) selected
                                            @endif>{{$inst->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                        </form>
                        @endif
                        <br>
                        <h5>{{ $instansi}}</h5>
                        <img src="assets/images/syarat_min_instansi.jpg" width="75%">
                        <hr />
                        <table style="text-align: left" class="table">
                            <thead>
                                <tr>
                                    <th>Indikator</th>
                                    <!--<th>Skor</th>-->
                                    <th>Predikat</th>
                                    <th class="text-center">WBK</th>
                                    <th class="text-center">WBBM</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>Opini BPK</td>
                                <!--<td> {{$skor_opini_bpk}} </td>-->
                                <td>{{($opini_bpk)?$opini_bpk:"-"}} </td>
                                <td class="text-center">
                                    @if($syarat_bpk=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($syarat_bpk=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Predikat SAKIP</td>
                                <!--<td> {{$skor_predikat_sakip}}</td>-->
                                <td>{{($predikat_sakip)?$predikat_sakip:"-"}} </td>
                                <td class="text-center">
                                    @if($syarat_sakip_wbk=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($syarat_sakip_wbbm=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Indeks RB</td>
                                <!-- <td> {{$skor_indeks_rb}}</td> -->

                                <td>{{($indeks_rb)?$indeks_rb:"-"}} </td>
                                <td class="text-center">
                                    @if($syarat_indeksrb_wbk=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($syarat_indeksrb_wbbm=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Maturitas SPIP</td>
                                <!-- <td> {{$skor_maturitas_spip}}</td> -->
                                <td>{{($maturitas_spip)?$maturitas_spip:"-"}} </td>
                                <td class="text-center">
                                    @if($syarat_maturitas_spip=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($syarat_maturitas_spip=="LULUS")
                                    <i class="fa fa-check text-success"></i>
                                    @else
                                    <i class="fas fa-times text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">Kesimpulan</td>
                                    <td class="text-center">
                                        @if($syarat_akhir_wbk=="LULUS")
                                        <i class="fa fa-check text-success"></i>
                                        @else
                                        <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($syarat_akhir_wbbm=="LULUS")
                                        <i class="fa fa-check text-success"></i>
                                        @else
                                        <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="row ">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-10 form-group">
                                <hr />
                                <small class="form-text" {{ ($status_akhir==0 || $status_akhir==3 )?"style=color:red ":"" }}>{{$keterangan}}.
                                                Jika terdapat ketidaksesuaian pada data diatas harap menghubungi 
                                                PIC kementerian PANRB</small>
                                        </div>

                                        <div class=" col-md-1">
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-10 form-group">
                                <hr />
                                <button class="btn btn-primary" id="infoAffirmasi">Info Unit Afirmasi</button>
                                <button class="btn btn-warning" id="infoCp">Info PIC Instansi</button>
                                <hr />
                                <img id="infoAffirmasiImg" src="assets/images/unit_kerja_affirmasi.jpeg"
                                    style="display: none;" width="75%">
                                <hr />
                                <img id="infoCpImg" src="assets/images/cp_instansi.jpeg" style="display: none;">

                            </div>

                            <div class=" col-md-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($status_akhir > 0)
            <form action="{{URL::to('zi')}}" method="post">
                @csrf
                @if(Auth::User()->level =="admin" || Auth::User()->level == "tpn")
                <input type="hidden" name="instansi_id" value="{{ $instansi_id}}">
                @endif
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="feature-item" style="background-color:#ffcc08; border-radius: 25px;">
                            <div class="content">
                                <div class="row ">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-10 form-group" style="text-align: left">
                                        <div class="form-group">
                                            <label>PIC Instansi Pemerintah</label>
                                            <input type="text" name="pic" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-left">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nomor Kontak</label>
                                            <input type="text" name="nomor_kontak" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Surat Usulan</label>
                                            <input type="text" name="surat_usulan" class="form-control" required>
                                            <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                yang berisi surat usulan dari pimpinan instansi pemerintah
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label> SPTJM </label>
                                            <input type="text" name="sptjm" class="form-control" required>
                                            <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                yang
                                                berisi Surat Pernyataan Tanggung Jawab
                                                Mutlak (SPTJM) yang ditandatangani pimpinan instansi</small>
                                        </div>
                                        <div class="form-group">
                                            <label>TLHP</label>
                                            <input type="text" name="tlhp" class="form-control" required>
                                            <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                yang
                                                berisi Surat Pernyataan Clearance TLHP oleh
                                                APIP</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Survei Mandiri</label>
                                            <input type="text" name="survei_mandiri" class="form-control" required>
                                            <small id="emailHelp" class="form-text text-muted">Input link Drive
                                                yang
                                                berisi Laporan hasil pelaksanaan survei
                                                mandiri yang memuat nilai SPAK dan SPKP</small>
                                        </div>
                                        <div class="form-group">
                                            <hr />
                                            <h1 class="form-text text-center text-danger" style="font-size:1.3em">
                                                Mohon dipastikan
                                                seluruh link drive
                                                yang disampaikan tidak terkunci dan dapat diakses </h1>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />

                <div class="row">
                    <div class="col-lg-2 col-md-2">
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="feature-item" style="color:white; background-color:#b42b2d; border-radius: 25px;">
                            <div class="content">
                                <div class="row ">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-10" id="detail_unit">
                                        <div class="form-group">
                                            <h6 style="text-align: center; color:white;">
                                                Jumlah Unit yang diusulkan </h6>
                                            <hr />
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label style="text-align: center; color:white;">WBK</label>
                                                    <input type="number" name="jml_wbk" id="jmlWBK"
                                                        onkeyup="hitungTotal();" class="form-control"
                                                        style="text-align: center;">
                                                </div>
                                                <div class="col-md-4">
                                                    <label style="text-align: center; color:white;">WBBM</label>
                                                    <input type="number" name="jml_wbbm" id="jmlWBBM"
                                                        onkeyup="hitungTotal()" class="form-control"
                                                        style="text-align: center;" @if($status_akhir==1 or
                                                        $status_akhir==3 or $status_akhir==4 ) disabled @endif>
                                                </div>
                                                <div class=" col-md-4">
                                                    <label style="text-align: center; color:white;">Total</label>
                                                    <input type="text" id="jmlUnit" class="form-control"
                                                        style="text-align: center;""
                                                                                            disabled>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <br />
                                                                            <a class=" btn btn-primary"
                                                        id="btn-tambah-unit"
                                                        style="color:black; background-color:#ffcc08; "
                                                        onclick="tambah_input();">Tambahkan
                                                    Unit
                                                    </a>
                                                </div>
                                                <div class=" col-md-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row" id="row-wbk" style="display:none">
                                <div class="col-lg-2 col-md-2">
                                </div>
                                <div class="col-lg-8 col-md-8">
                                    <div class="feature-item"
                                        style="color:white; background-color:#b42b2d; border-radius: 25px;">
                                        <div class="content">
                                            <div class="row ">
                                                <div class="col-md-1">
                                                </div>
                                                <div class="col-md-10" id="detail_unit">
                                                    <div class="form-group">
                                                        <h5 style="text-align: center; color:white;">WBK</h5>
                                                    </div>
                                                </div>
                                                <div class=" col-md-1">
                                                </div>
                                            </div>
                                            <div class=" form-group" id="cont-tambah-unit-wbk">
                                                <hr />
                                                <hr />
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label style="color:white; ">Nama Unit WBK</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label style="color:white">Link File LKE</label>
                                                    </div>
                                                    @if($group_kld == "prov" || $group_kld == "kab")
                                                    <div class="col-md-2">
                                                        <label style="color:white; ">Afirmasi</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        @elseif ($group_kld == "kl")
                                                        <div class="col-md-4">
                                                            @endif
                                                            <label style="color:white; ">Delete</label>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                                <br />
                                                <a class=" btn btn-primary" id="btn-tambah-unit-wbk"
                                                    style="color:black; background-color:#ffcc08; "
                                                    onclick="tambah_wbk();">Tambahkan Unit WBK
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="row-wbbm" style="display:none">
                                    <div class="col-lg-2 col-md-2">
                                    </div>
                                    <div class="col-lg-8 col-md-8">
                                        <div class="feature-item"
                                            style="color:white; background-color:#b42b2d; border-radius: 25px;">
                                            <div class="content">
                                                <div class="row ">
                                                    <div class="col-md-1">
                                                    </div>
                                                    <div class="col-md-10" id="detail_unit">
                                                        <div class="form-group">
                                                            <h5 style="text-align: center; color:white;">WBBM
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class=" col-md-1">
                                                    </div>
                                                </div>
                                                <div class=" form-group" id="cont-tambah-unit-wbbm">
                                                    <hr />
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label style="color:white; ">Nama Unit WBBM</label>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label style="color:white">Link File LKE</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label style="color:white; ">Delete</label>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                                <br />
                                                <a class=" btn btn-primary" id="btn-tambah-unit-wbbm"
                                                    style="color:black; background-color:#ffcc08; "
                                                    onclick="tambah_wbbm();">Tambahkan Unit WBBM
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="row-submit" style="display:none">
                                    <div class="col-lg-2 col-md-2">
                                    </div>
                                    <div class="col-lg-8 col-md-8" style=" padding:30px 0px;">
                                        <button type="submit" class="btn btn-primary"
                                            style="width:100%; padding:15px; display:block;">Kirim</button>
                                    </div>
                                </div>
            </form>
            @endif

        </div>
    </div>
</section>
<!-- Features Area end -->
<!-- Meter Area start -->
@endsection


@section('jsHere')
<script>
    function hitungTotal() {
            if ($('#jmlWBK').val() == '') {
                $('#jmlWBK').val(0);
            }
            if ($('#jmlWBBM').val() == '') {
                $('#jmlWBBM').val(0);
            }
            var total = parseInt($('#jmlWBK').val()) + parseInt($('#jmlWBBM').val())
            $('#jmlUnit').val( total); 
        };

        function tambah_input(){
            if ($('#jmlWBK').val() >0 || $('#jmlWBBM').val() >0 ) {
                $('#btn-tambah-unit').remove();
                $("#row-submit").show();
                if ($('#jmlWBK').val() >0 ) {
                    $("#row-wbk").show();
                    for (var i = 1; i <= $('#jmlWBK').val(); i++) {
                        tambah_wbk(pertama_kali = 1);
                    };
                }
                if ($('#jmlWBBM').val() >0 ) {
                    $("#row-wbbm").show();
                    for (var i = 1; i <= $('#jmlWBBM').val(); i++) {
                        tambah_wbbm(pertama_kali = 1);
                    };
                }
                $('#jmlWBK').attr('disabled', 'disabled');
                $('#jmlWBBM').attr('disabled', 'disabled');
            }
        }

        function tambah_wbk(pertama_kali = 0){
            var row_str = '<br>\
                <div class="row deleteSegini" > \
                    <div class="col-md-4"> \
                        <input type="text" name="unit_wbk[]" class="form-control" style="text-align:center" placeholder="Nama Unit WBK " required> \
                    </div> \
                    <div class="col-md-4"> \
                        <input type="text" name="lke_wbk[]" class="form-control" style="text-align:center" placeholder="Link File LKE" required> \
                    </div>';

                @if($group_kld == "prov" || $group_kld == "kab")
                    row_str = row_str + '<div class="col-md-2"> \
                        <input type="hidden" name="afirmasi[]" value="0" />\
                        <input type="checkbox" class="checkbox"  value=1 style=" margin-top:0.7em;\
                        height: 25px;\
                        width: 25px;\
                        background-color: #eee;"' ;  
                        
                        @if($status_akhir ==3 )
                        row_str = row_str + 'required';
                        @endif
                        
                        row_str = row_str +    '>\
                    </div> \
                    <div class="col-md-2">'; 
                @elseif ($group_kld == "kl")
                    row_str = row_str + '<div class="col-md-4">';
                @endif
                    row_str = row_str + '<a href="#inigakada" class="btn btn-danger " style=" margin-top:0.3em;\" onclick = "hapus_unit_wbk(this)" >X</a>\
                    </div> \
                </div>';
            $('#cont-tambah-unit-wbk').append(row_str);   
            if(!pertama_kali){
                jml_wbk = $('#jmlWBK').val();
                $('#jmlWBK').val(parseInt(jml_wbk)+1);
                hitungTotal();
            }
            
        }

        function hapus_unit_wbk(ortu){
            $(ortu).parent().parent().remove();
            jml_wbk = $('#jmlWBK').val();
            $('#jmlWBK').val(jml_wbk-1);
            hitungTotal();
        }

        function tambah_wbbm(pertama_kali = 0){
            var row_str = '<br>\
                <div class="row"> \
                    <div class="col-md-5"> \
                        <input type="text" name="unit_wbbm[]" class="form-control" style="text-align:center" placeholder="Nama Unit WBBM " required> \
                    </div> \
                    <div class="col-md-5"> \
                        <input type="text" name="lke_wbbm[]" class="form-control" style="text-align:center" placeholder="Link File LKE" required> \
                    </div> \
                    <div class="col-md-2"> \
                        <button  class="btn btn-danger" style=" margin-top:0.3em;\" onclick = "hapus_unit_wbbm(this)">X</button>\
                    </div> \
                </div>';
            $('#cont-tambah-unit-wbbm').append(row_str);      
            if(!pertama_kali){
                jml_wbbm = $('#jmlWBBM').val();
                $('#jmlWBBM').val(parseInt(jml_wbbm)+1);
                hitungTotal();
            }
        }

        function hapus_unit_wbbm(ortu){
            $(ortu).parent().parent().remove();
            jml_wbbm = $('#jmlWBBM').val();
            $('#jmlWBBM').val(jml_wbbm-1);
            hitungTotal();
            
        }   

        $("#infoAffirmasi").click(function(){
            $("#infoAffirmasiImg").toggle();
        });

        $("#infoCp").click(function(){
            $("#infoCpImg").toggle();
        });

        $(document).on("change", "input.checkbox", function() {
            var value = $(this).is(":checked") ? $(this).val() : 0;
            $(this).siblings("input[name='afirmasi[]']").val(value);
        });

        
</script>
@endsection