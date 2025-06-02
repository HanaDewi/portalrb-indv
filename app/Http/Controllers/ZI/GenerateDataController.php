<?php

namespace App\Http\Controllers\ZI;

use App\Models\KlpdInstansi;
use App\Models\LKE\LkeBobot;
use App\Models\ZI\InstansiZI;
use App\Models\JawabanRenaksi;
use App\Imports\ImportRBTematik;
use App\Models\LKE\LkeTestTpLine;
use App\Http\Controllers\Controller;
use App\Models\LKE\LkeParameter;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;
use App\Models\ZI\UnitZI;

class GenerateDataController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::User()->level == "admin") {
                return $next($request);
            }
            abort('403');
        });
    }

    public function sinkron_final_completed()
    {
        $seleksi_administrasi_units = SeleksiAdministrasiUnit::all();
        foreach ($seleksi_administrasi_units as $unit_administrasi) {
            $instansi_zi_id = $unit_administrasi->unitZI->instansi_zi_id;
            $seleksi_administrasi_instansi = SeleksiAdministrasiInstansi::where('instansi_zi_id', $instansi_zi_id)->first();
            $unit_administrasi->status_completed = 0;
            if (
                !is_null($seleksi_administrasi_instansi->surat_usulan) &&
                !is_null($seleksi_administrasi_instansi->sptjm) &&
                !is_null($unit_administrasi->status_lke) &&
                !is_null($unit_administrasi->status_tlhp) &&
                !is_null($unit_administrasi->status_survei_mandiri)
            ) {
                $status_final = 1;
                if ($unit_administrasi->unitZI->wbbm) {
                    if (!is_null($unit_administrasi->status_2wbk)) {
                        $unit_administrasi->status_completed = 1;
                        ($unit_administrasi->status_2wbk === 0) ? $status_final = 0 : null;
                    }
                }

                if ($unit_administrasi->unitZI->wbk) {
                    $unit_administrasi->status_completed = 1;
                }

                ($unit_administrasi->status_lke === 0) ? $status_final = 0 : null;
                ($unit_administrasi->status_tlhp === 0) ? $status_final = 0 : null;
                ($unit_administrasi->status_survei_mandiri === 0) ? $status_final = 0 : null;
                $unit_administrasi->status_final = $status_final;
            };
            $unit_administrasi->save();
        }
        echo "Sinkronisasi Selesai";
    }

    public function generate_rekap_instansi_skor()
    {
        $instansis = KlpdInstansi::get();
        $lke_kegiatan_id = 1; //LKE Kegiatan 1 artinya evaluasi RB tahun 2024
        foreach ($instansis as $instansi) {
            $instansi_id = $instansi->id;
            $group_kld = $instansi->group;
            $lkeTestTP = $instansi->lke_test_tps->where('lke_kegiatan_id', $lke_kegiatan_id)->first();
            $skor_opini_bpk = 0;
            $skor_predikat_sakip = 0;
            $skor_maturitas_spip = 0;
            $opini_bpk = 0;
            $predikat_sakip = "";
            $indeks_rb = "";
            $maturitas_spip = "";
            $syarat_maturitas_spip = "";
            $syarat_akhir_wbk = "";
            $syarat_akhir_wbbm = "";
            $status_akhir = 0; #0 untuk tidak lolos, 1 untuk wbk saja, 2 untuk wbk dan wbbm, 3 untuk afirmasi
            $keterangan = "";

            // echo $instansi->name . " | " . $group_kld . "<br>";
            // echo "lke_test_tp = " . $lkeTestTP . "<br/>";
            if (isset($lkeTestTP)) {
                $skor_indeks_rb = $lkeTestTP->index_rb;

                $lke_parameters = LkeParameter::where('lke_kegiatan_id', operator: $lke_kegiatan_id)->get();
                foreach ($lke_parameters as $lke_parameter) {
                    $lke_bobots = LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', $group_kld)->get();
                    foreach ($lke_bobots as $lke_bobot) {
                        $lkeTestTPLines = LkeTestTpLine::where("lke_bobot_id", $lke_bobot->id)->where("instansi_id", $instansi_id)->get();
                        foreach ($lkeTestTPLines as $tpLine) {
                            if ($tpLine->lke_bobot->lke_parameter->nama == "Opini BPK") {
                                ($tpLine->score) ? $skor_opini_bpk = $tpLine->score_index : $skor_opini_bpk = 0; #khusus opini BPK nilai yang diambil adalah skor opini bpk
                            } elseif ($tpLine->lke_bobot->lke_parameter->nama == "Nilai SAKIP") { // di database paramerter l4 nya ada dua
                                ($tpLine->score) ? $skor_predikat_sakip = $tpLine->score : $skor_predikat_sakip = 0;
                            } elseif ($tpLine->lke_bobot->lke_parameter->nama == "Tingkat Maturitas SPIP") {
                                ($tpLine->score) ? $skor_maturitas_spip = $tpLine->score : $skor_maturitas_spip;
                            }
                        }
                    }
                }
                // echo "skor indeks :" . $skor_indeks_rb . "<br/>";
                // echo "skor predikat sakip" . $skor_predikat_sakip . "<br/>";
                // echo "skor opini bpk" . $skor_opini_bpk . "<br/>";
                // echo "skor maturitas spip" . $skor_maturitas_spip . "<br/>";
                #konversi Skor ke Predikat
                $indeks_rb = $this->konversi_predikat_rb($skor_indeks_rb);
                $predikat_sakip = $this->konversi_predikat_sakip($skor_predikat_sakip);
                $opini_bpk = $this->konversi_predikat_bpk($skor_opini_bpk);
                $maturitas_spip = "Level " . (int)$skor_maturitas_spip;
                // echo "indeks rb :" . $indeks_rb . "<br/>";
                // echo "predikat sakip" . $predikat_sakip . "<br/>";
                // echo "opini bpk" . $opini_bpk . "<br/>";
                // echo "maturitas spip" . $maturitas_spip . "<br/>";
                #Syarat Instansi 
                ($opini_bpk == "WTP") ? $syarat_bpk = "LULUS" : $syarat_bpk = "GAGAL"; #syarat BPK WTP
                ($skor_predikat_sakip > 60) ? $syarat_sakip_wbk = "LULUS" : $syarat_sakip_wbk = "GAGAL"; #Syarat SAKIP predikat wbk B keatas 
                ($skor_predikat_sakip > 70) ? $syarat_sakip_wbbm = "LULUS" : $syarat_sakip_wbbm = "GAGAL"; #Syarat SAKIT predikat wbbm BB keatas
                if ($group_kld == "provinsi" || $group_kld == "kabupaten") { #Jika Pemda
                    ($skor_indeks_rb) > 50 ? $syarat_indeksrb_wbk = "LULUS" : $syarat_indeksrb_wbk = "GAGAL"; #wbk CC keatas, 
                    ($skor_indeks_rb > 60) ? $syarat_indeksrb_wbbm = "LULUS" : $syarat_indeksrb_wbbm = "GAGAL"; #wbbm B keatas
                } elseif ($group_kld  == "kl") { #Jika KL
                    ($skor_indeks_rb > 60) ? $syarat_indeksrb_wbk = "LULUS" : $syarat_indeksrb_wbk = "GAGAL"; #wbk index B keatas
                    ($skor_indeks_rb > 70) ? $syarat_indeksrb_wbbm = "LULUS" : $syarat_indeksrb_wbbm = "GAGAL"; #wbbm index BB
                } else {
                    continue;
                }

                ($skor_maturitas_spip >= 3) ? $syarat_maturitas_spip = "LULUS" : $syarat_maturitas_spip = "GAGAL"; #predikat B keatas

                #Perhitungan Akhir
                if ($syarat_bpk == "LULUS" && $syarat_maturitas_spip == "LULUS") {
                    #WBK
                    if ($syarat_indeksrb_wbk == "LULUS" && $syarat_sakip_wbk == "LULUS") {
                        $syarat_akhir_wbk = "LULUS";
                    } else {
                        $syarat_akhir_wbk = "GAGAL";
                    }
                    #WBBM
                    if ($syarat_indeksrb_wbbm == "LULUS" && $syarat_sakip_wbbm == "LULUS") {
                        $syarat_akhir_wbbm = "LULUS";
                    } else {
                        $syarat_akhir_wbbm = "GAGAL";
                    }

                    if ($syarat_akhir_wbk == "LULUS" || $syarat_akhir_wbbm == "LULUS") {
                        if ($syarat_akhir_wbk == "LULUS") {
                            $status_akhir = 1;
                            $keterangan = "Selamat anda dapat mengusulkan unit penerima WBK";
                            if ($syarat_akhir_wbbm == "LULUS") {
                                $status_akhir = 2;
                                $keterangan = $keterangan . " dan WBBM";
                            } else {
                                $keterangan = $keterangan . " namun belum bisa mengajukan unit penerima WBBM";
                            }
                        } else {
                            $keterangan = "Mohon Maaf Anda belum memenuhi persyaratan untuk mengusulkan unit penerima WBK maupun WBBM";
                        }
                    } elseif (in_array($instansi->id, [628, 412, 551, 554, 555, 437, 607, 539, 541])) { #Jika termasuk instansi wbk mandiri 
                        $keterangan = "Anda tidak dapat mengajukan WBBM tapi anda dapat mengajukan WBK mandiri";
                        $status_akhir = 4;
                    } else {
                        $keterangan = "Mohon Maaf Anda belum memenuhi persyaratan untuk mengusulkan unit penerima WBK maupun WBBM. ";
                        #cek kalau pemda afirmasi
                        if ($group_kld == "provinsi" || $group_kld == "kabupaten") {
                            $keterangan = $keterangan . ". Namun anda masih bisa mengajukan Unit Afirmasi untuk nominasi penerima WBK";
                            $status_akhir = 3;
                        }
                    }
                } else {
                    if (in_array($instansi->id, [628, 412, 551, 554, 555, 437, 607, 539, 541])) { #Jika termasuk instansi wbk mandiri 
                        $keterangan = "Anda tidak dapat mengajukan WBBM tapi anda dapat mengajukan WBK mandiri";
                        $syarat_akhir_wbk = "GAGAL";
                        $syarat_akhir_wbbm = "GAGAL";
                        $status_akhir = 4;
                    } else {
                        $keterangan = "Anda tidak dapat mengajukan ZI karena tidak memenuhi persyaratan ";
                        $syarat_akhir_wbk = "GAGAL";
                        $syarat_akhir_wbbm = "GAGAL";
                        if ($syarat_bpk != "LULUS") {
                            $keterangan = $keterangan . " opini BPK ";
                            if ($syarat_maturitas_spip != "LULUS") {
                                $keterangan = $keterangan . "dan maturitas SPIP";
                            }
                        } elseif ($syarat_maturitas_spip != "LULUS") {
                            $keterangan = $keterangan . " maturitas SPIP";
                        }
                    }
                    #cek kalau pemda afirmasi
                    if ($group_kld == "provinsi" || $group_kld == "kabupaten") {
                        $keterangan = $keterangan . "Anda hanya bisa mengajukan Unit Afirmasi untuk nominasi penerima WBK";
                        $status_akhir = 3;
                    }
                }

                $instansiZI = InstansiZI::create(array('instansi_id' => $instansi_id));
                if (in_array($instansi->id, [628, 412, 551, 554, 555, 437, 607, 539, 541])) { #Jika termasuk instansi wbk mandiri 
                    $instansiZI->instansi_wbk_mandiri = true;
                }
                $instansiZI->tahun = "2025";
                $instansiZI->tahap_seleksi = 0;
                $instansiZI->skor_bpk = $skor_opini_bpk;
                $instansiZI->skor_indeks_rb = $skor_indeks_rb;
                $instansiZI->skor_sakip = $skor_predikat_sakip;
                $instansiZI->skor_maturitas_spip = $skor_maturitas_spip;
                $instansiZI->opini_bpk = $opini_bpk;
                $instansiZI->indeks_rb = $indeks_rb;
                $instansiZI->predikat_sakip = $predikat_sakip;
                $instansiZI->maturitas_spip = $maturitas_spip;
                $instansiZI->syarat_bpk = $syarat_bpk;
                $instansiZI->syarat_sakip_wbk = $syarat_sakip_wbk;
                $instansiZI->syarat_sakip_wbbm = $syarat_sakip_wbbm;
                $instansiZI->syarat_indeksrb_wbk = $syarat_indeksrb_wbk;
                $instansiZI->syarat_indeksrb_wbbm = $syarat_indeksrb_wbbm;
                $instansiZI->syarat_maturitas_spip = $syarat_maturitas_spip;
                $instansiZI->syarat_akhir_wbk = $syarat_akhir_wbk;
                $instansiZI->syarat_akhir_wbbm = $syarat_akhir_wbbm;
                $instansiZI->keterangan = $keterangan;
                $instansiZI->status_akhir = $status_akhir;
                $instansiZI->save();
            } else { #belum ada penilaian RB tahun 2023
                $instansiZI = InstansiZI::create(array('instansi_id' => $instansi_id));
                $instansiZI->tahun = "2025";
                $instansiZI->skor_bpk = 0;
                $instansiZI->skor_indeks_rb = 0;
                $instansiZI->skor_sakip = 0;
                $instansiZI->skor_maturitas_spip = 0;
                $instansiZI->opini_bpk = "-";
                $instansiZI->indeks_rb = "-";
                $instansiZI->predikat_sakip = "-";
                $instansiZI->maturitas_spip = "-";
                $instansiZI->syarat_bpk = "GAGAL";
                $instansiZI->syarat_sakip_wbk = "GAGAL";
                $instansiZI->syarat_sakip_wbbm = "GAGAL";
                $instansiZI->syarat_indeksrb_wbk = "GAGAL";
                $instansiZI->syarat_indeksrb_wbbm = "GAGAL";
                $instansiZI->syarat_maturitas_spip = "GAGAL";
                $instansiZI->syarat_akhir_wbk = "GAGAL";
                $instansiZI->syarat_akhir_wbbm = "GAGAL";
                if ($group_kld == "provinsi" || $group_kld == "kabupaten") {
                    $instansiZI->keterangan = "Mohon Maaf anda hanya bisa mendaftarkan unit Afirmasi saja";
                    $instansiZI->status_akhir = 3;
                    $instansiZI->save();
                } else {
                    $instansiZI->keterangan = "Mohon Maaf anda belum melakukan evaluasi RB tahun 2023 sehingga belum bisa mengusulkan unit penerima WBK maupun WBBM";
                    $instansiZI->status_akhir = 0;
                    $instansiZI->save();
                }
            }
            // if ($instansi->id == 8) {
            //     dd("hai");
            // }
        }
        echo "berhasil";
    }

    public function konversi_predikat_rb($skor_indeks_rb)
    {
        if ($skor_indeks_rb > 100) {
            $indeks_rb = "AA";
        } elseif ($skor_indeks_rb > 80) {
            $indeks_rb = "A";
        } elseif ($skor_indeks_rb > 70) {
            $indeks_rb = "BB";
        } elseif ($skor_indeks_rb > 60) {
            $indeks_rb = "B";
        } elseif ($skor_indeks_rb > 50) {
            $indeks_rb = "CC";
        } elseif ($skor_indeks_rb > 30) {
            $indeks_rb = "C";
        } elseif ($skor_indeks_rb > 0) {
            $indeks_rb = "D";
        } else {
            $indeks_rb = "-";
        }
        return $indeks_rb;
    }

    public function konversi_predikat_sakip($skor_sakip)
    {
        if ($skor_sakip > 90) {
            $predikat_sakip = "AA";
        } elseif ($skor_sakip >= 80) {
            $predikat_sakip = "A";
        } elseif ($skor_sakip >= 70) {
            $predikat_sakip = "BB";
        } elseif ($skor_sakip >= 60) {
            $predikat_sakip = "B";
        } elseif ($skor_sakip >= 50) {
            $predikat_sakip = "CC";
        } elseif ($skor_sakip >= 30) {
            $predikat_sakip = "C";
        } elseif ($skor_sakip >= 0) {
            $predikat_sakip = "D";
        } else {
            $predikat_sakip = "-";
        }
        return $predikat_sakip;
    }

    public function konversi_predikat_bpk($skor_opini_bpk)
    {
        if ($skor_opini_bpk > 3.33) {
            $opini_bpk = "WTP"; #Wajar Tanpa Pengecualian
        } elseif ($skor_opini_bpk > 1.67) {
            $opini_bpk = "WDP"; #Wajar Dengan  Pengecualian
        } elseif ($skor_opini_bpk > 0) {
            $opini_bpk = "TW";
        } else {
            $opini_bpk = "TMP";
        }
        return $opini_bpk;
    }

    public function input_nilai_ke_evalrb()
    {
        //$headings = (new HeadingRowImport())->toArray( 'ZI_Meso.xlsx');
        $collections = Excel::toCollection(new ImportRBTematik, 'success_rate_zi.xlsx')[0];
        //dd($collections);
        foreach ($collections as $key => $collection) {
            $user = Auth::User();
            $nama_instansi_excel = trim($collection[0]);
            $instansi = KlpdInstansi::where('name', $nama_instansi_excel)->first();
            if ($instansi) {
                echo "Instansi excel : " . $nama_instansi_excel;
                echo " | Nilai : " . $collection[1] . "<br>";
                echo "Klpd portalrb : " . $instansi->name;
                if ($instansi->group == "kl" or $instansi->group == "provinsi" or $instansi->group == "kabupaten") {
                    //$lke_bobot = LkeBobot::where('lke_parameter_id', 2135)->where('group', $instansi->group)->first();
                    $lke_bobot = LkeBobot::where('lke_parameter_id', 2135)->where('group', $instansi->group)->first();
                    //print_r($lke_bobot);
                    //$test_tp_line = LkeTestTpLine::where('lke_bobot_id', $request->lke_bobot_id)->where('instansi_id', $instansi->id)->first();
                    $test_tp_line = LkeTestTpLine::where('lke_bobot_id', $lke_bobot->id)->where('instansi_id', $instansi->id)->first();
                    if (!$test_tp_line) {
                        $test_tp_line = new LkeTestTpLine();
                        $test_tp_line->penilai_user_id = $user->id;
                    }
                    //print_r("<b>".$lke_bobot->id."</b>");
                    //print_r($test_tp_line->score_index);
                    $test_tp_line->score = str_replace(',', '.', str_replace('.', '', $collection[1]));
                    $test_tp_line->lke_bobot_id = $lke_bobot->id;
                    $test_tp_line->instansi_id = $instansi->id;
                    $test_tp_line->catatan = "";
                    $test_tp_line->rekomendasi = "";
                    $test_tp_line->update_user_id = $user->id;
                    $test_tp_line->score_index = !empty($test_tp_line->lke_bobot->max_value) ? ($test_tp_line->score / $test_tp_line->lke_bobot->max_value) * $test_tp_line->lke_bobot->bobot : $test_tp_line->score;
                    if ($pengali_id = $test_tp_line->lke_bobot->lke_parameter->indikator_pengali_id) {
                        $bobot_pengali_id = LkeBobot::where('lke_parameter_id', $pengali_id)->where('group', $test_tp_line->lke_bobot->group)->first()->id;
                        $test_tp_line_pengali = LkeTestTpLine::where('lke_bobot_id', $bobot_pengali_id)->where('instansi_id', $test_tp_line->instansi_id)->first();
                        if ($test_tp_line_pengali) {
                            if ($test_tp_line_pengali->score_index) {
                                $test_tp_line->score_index = $test_tp_line->score_index * ($test_tp_line_pengali->score_index / $test_tp_line_pengali->lke_bobot->bobot);
                            }
                        }
                    }

                    if ($test_tp_line->save()) {
                        calculateTestTp($test_tp_line->instansi_id, $test_tp_line->lke_bobot->lke_parameter->lke_kegiatan_id);
                        $success = true;
                    } else {
                        $success = false;
                    }
                    echo " | Sukses : " . $success . "<hr>";
                } else {
                    echo " | diexclude karena groupnya bukan kl/provinsi/kabupaten" . "<hr>";
                }
            }
        }
    }

    public function generate_skor_rencana_aksi()
    {
        $user = Auth::User();
        $instansis = KlpdInstansi::all();
        foreach ($instansis as $instansi) {
            if ($instansi) {
                if ($instansi->group == "kl" or $instansi->group == "provinsi" or $instansi->group == "kabupaten") {
                    $lke_bobot = LkeBobot::where('lke_parameter_id', 2130)->where('group', $instansi->group)->first();
                    $test_tp_line = LkeTestTpLine::where('lke_bobot_id', $lke_bobot->id)->where('instansi_id', $instansi->id)->first();
                    if (!$test_tp_line) {
                        $test_tp_line = new LkeTestTpLine();
                        $test_tp_line->penilai_user_id = $user->id;
                    }
                    $instansi_id = $instansi->id;
                    $tahun = '2024';
                    $strategiPelaksanaanRBGeneral = JawabanRenaksi::where('instansi_id', $instansi->id)->where('lke_renaksi_id', 2)->where('tahun', '2024')->first();
                    $test_tp_line->score = $strategiPelaksanaanRBGeneral->jawaban;
                    $test_tp_line->lke_bobot_id = $lke_bobot->id;
                    $test_tp_line->instansi_id = $instansi->id;
                    $penetapanKU =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 4)->where('tahun', $tahun)->first();
                    $penetapanTargetIndikatorKU =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 5)->where('tahun', $tahun)->first();
                    $keabsahanRencanaAksi =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 6)->where('tahun', $tahun)->first();
                    $kelogisanRencanaAksi =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 8)->where('tahun', $tahun)->first();
                    $relevansiKecukupanIndikatorOutput =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 9)->where('tahun', $tahun)->first();
                    $ketetapanPenetapanTargetIndikatorOutput =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 10)->where('tahun', $tahun)->first();
                    $anggaran =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 11)->where('tahun', $tahun)->first();
                    $test_tp_line->catatan = ($penetapanKU->catatan ?? null) . "." . ($penetapanTargetIndikatorKU->catatan ?? null) . "." . ($keabsahanRencanaAksi->catatan ?? null) . "." . ($kelogisanRencanaAksi->catatan ?? null) . "." . ($relevansiKecukupanIndikatorOutput->catatan ?? null) . "." . ($ketetapanPenetapanTargetIndikatorOutput->catatan ?? null) . "." . ($anggaran->catatan ?? null);
                    $test_tp_line->rekomendasi = ($penetapanKU->rekomendasi ?? null) . "." . ($penetapanTargetIndikatorKU->rekomendasi ?? null) . "." . ($keabsahanRencanaAksi->rekomendasi ?? null) . "." . ($kelogisanRencanaAksi->rekomendasi ?? null) . "." . ($relevansiKecukupanIndikatorOutput->rekomendasi ?? null) . "." . ($ketetapanPenetapanTargetIndikatorOutput->rekomendasi ?? null) . "." . ($anggaran->rekomendasi ?? null);
                    $test_tp_line->update_user_id = $user->id;
                    $test_tp_line->score_index = !empty($test_tp_line->lke_bobot->max_value) ? ($test_tp_line->score / $test_tp_line->lke_bobot->max_value) * $test_tp_line->lke_bobot->bobot : $test_tp_line->score;
                    if ($pengali_id = $test_tp_line->lke_bobot->lke_parameter->indikator_pengali_id) {
                        $bobot_pengali_id = LkeBobot::where('lke_parameter_id', $pengali_id)->where('group', $test_tp_line->lke_bobot->group)->first()->id;
                        $test_tp_line_pengali = LkeTestTpLine::where('lke_bobot_id', $bobot_pengali_id)->where('instansi_id', $test_tp_line->instansi_id)->first();
                        if ($test_tp_line_pengali) {
                            if ($test_tp_line_pengali->score_index) {
                                $test_tp_line->score_index = $test_tp_line->score_index * ($test_tp_line_pengali->score_index / $test_tp_line_pengali->lke_bobot->bobot);
                            }
                        }
                    }

                    if ($test_tp_line->save()) {
                        calculateTestTp($test_tp_line->instansi_id, $test_tp_line->lke_bobot->lke_parameter->lke_kegiatan_id);
                        $success = true;
                    } else {
                        $success = false;
                    }
                    echo " | Sukses : " . $success . "<hr>";
                } else {
                    echo " | diexclude karena groupnya bukan kl/provinsi/kabupaten" . "<hr>";
                }
            }
        }
    }

    public function cek_jumlah(): Returntype
    {
        $instansi_zis = InstansiZI::where('tahun', '2025')->get();
        foreach ($instansi_zis as $instansi_zi) {
            $jumlah_unit_wbk_base_inputan = $instansi_zi->jml_wbk;
            $jumlah_unit_real = UnitZI::where('instansi_zi_id', $instansi_zi->id)->where('wbk', 1)->get()->count();

            if ($jumlah_unit_wbk_base_inputan != $jumlah_unit_real) {
                echo "<h1>" . $instansi_zi->klpd_instansi->name . "</h1>";
                echo "Jumlah unit wbk base inputan : " . $jumlah_unit_wbk_base_inputan . "<br>";
                echo "Jumlah unit wbk real : " . $jumlah_unit_real . "<br>";
                echo "Ada perbedaan jumlah unit wbk, silahkan cek kembali inputan anda";
                echo "<h1>" . $instansi_zi->klpd_instansi->name . "</h1>";
            }
        }
    }
}
