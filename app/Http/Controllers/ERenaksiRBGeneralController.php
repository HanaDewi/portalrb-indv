<?php

namespace App\Http\Controllers;

use App\Models\LKERenaksi;
use App\Models\KlpdInstansi;
use App\Models\LKE\LkeBobot;
use Illuminate\Http\Request;
use App\Models\TimEvaluasiRB;
use App\Models\JawabanRenaksi;
use App\Models\LKE\LkeTestTpLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KonversiJawabanRenaksi;
use App\Models\InstansiTimEvaluasi as InstansiTim;
use App\Models\AnggotaTimEvaluasiRB as AnggotaTimEvaluasi;
use App\Models\LKE\LkeKegiatan;

class ERenaksiRBGeneralController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            foreach (allowed_url() as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    public function index(Request $request)
    {
        $user = Auth::User();
        $isadmin = in_array($user->level, ['admin']);
        $istpn = in_array($user->level, ['tpn']);

        $kegiatanId = $request->input('kegiatan_id');
        $kegiatan = LkeKegiatan::orderByDesc('tahun')->find($kegiatanId);
        if (!$kegiatan) {
            $kegiatan = LkeKegiatan::orderByDesc('tahun')->first();
        }

        $ins_id = $request->input('instansi');

        if (empty($ins_id)) {
            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                $instansis = $atim ? InstansiTim::where('tim_id', $atim->tim_id)->get() : [];
            } else {
                $instansis = InstansiTim::get();
            }

            $skor_id = LKERenaksi::where('tahun', $kegiatan->tahun)->where('kriteria', 'Strategi Pelaksanaan RB General')->first()->id;

            return view('evaluasi.renaksi-rb-general', [
                'isadmin' => $isadmin,
                'data' => $instansis,
                'kembali' => false,
                'istpn' => $istpn,
                'check' => false,
                'skor_id' => $skor_id,
                'kegiatan' => $kegiatan
            ]);
        } else {
            $jawabanSkor = KonversiJawabanRenaksi::all()->keyBy('jawaban');
            $lkerenaksi = LKERenaksi::where('tahun', $kegiatan->tahun)->orderBy('id', 'ASC')->get();
            $fjawaban = $lkerenaksi->mapWithKeys(function ($renaksi) use ($kegiatan) {
                return [$renaksi->id => (object) [
                    'id' => '',
                    'tahun' => $kegiatan->tahun,
                    'lke_renaksi_id' => $renaksi->id,
                    'jawaban' => '',
                    'skor' => '',
                    'catatan' => '',
                    'rekomendasi' => ''
                ]];
            })->all();

            $instansi = KlpdInstansi::where('id', $ins_id)->withTrashed()->first();
            $jawaban = JawabanRenaksi::with('konversi_jawaban_renaksi')->where('tahun', $kegiatan->tahun)->where('instansi_id', $ins_id)->get();
            $check = false;
            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                $check = InstansiTim::where('tim_id', $atim->tim_id)->where('instansi_id', $ins_id)->first();
            }

            foreach ($jawaban as $nn => $oo) {
                $skor = $oo->jawaban ? data_get($oo, "konversi_jawaban_renaksi.skor", '') : '';
                $fjawaban[$oo->lke_renaksi_id] = (object) [
                    'id' => $oo->id,
                    'tahun' => $oo->tahun,
                    'lke_renaksi_id' => $oo->lke_renaksi_id,
                    'jawaban' => $oo->jawaban,
                    'skor' => $skor,
                    'catatan' => $oo->catatan,
                    'rekomendasi' => $oo->rekomendasi
                ];
            }

            $renaksi = DB::select('SELECT id,kriteria,info,tahun FROM lke_renaksi lr WHERE (SELECT COUNT(*) FROM lke_renaksi lr_ WHERE lr_.parent_id=lr.id)=0 AND lr.tahun=?', [$kegiatan->tahun]);

            return view('evaluasi.renaksi-rb-general', [
                'tahun' => $kegiatan->tahun,
                'isadmin' => $isadmin,
                'data' => $jawaban,
                'kembali' => true,
                'instansi' => $instansi,
                'check' => $check,
                'renaksi' => $renaksi,
                'list_jawaban' => $jawabanSkor->values(),
                'lkerenaksi' => $lkerenaksi,
                'fjawaban' => $fjawaban,
                'kegiatan' => $kegiatan
            ]);
        }
    }

    public function dosave(Request $request)
    {
        $user = Auth::User();
        $success = true;
        DB::beginTransaction();
        try {
            $tosave = new JawabanRenaksi();
            if (isset($request->jawaban_renaksi_id)) {
                $tosave = JawabanRenaksi::find($request->jawaban_renaksi_id);
                $tosave->updated_by = $user->id;
            } else {
                $tosave->created_by = $user->id;
            }
            $tosave->instansi_id = $request->instansi_id;
            $tosave->tahun = $request->tahun;
            $tosave->lke_renaksi_id = $request->lke_renaksi_id;
            $tosave->jawaban = $request->jawaban;
            $tosave->catatan = $request->catatan;
            $tosave->rekomendasi = $request->rekomendasi;


            if (!$tosave->save()) {
                $success = false;
            }

            //============calculate=================
            self::kalkulasi_skor($request->instansi_id, $request->tahun);
        } catch (\Throwable $th) {
            $success = false;
            throw $th;
        }
        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        $query = http_build_query([
            'instansi' => $request->instansi_id,
            'kegiatan_id' => $request->kegiatan_id
        ]);
        return redirect('/evaluasi/renaksi-rb-general?' . $query);
    }

    public function dodelete(Request $request)
    {
        $todelete = JawabanRenaksi::find($request->id);
        dd($todelete, $request->id);

        if ($todelete->delete()) {
            self::kalkulasi_skor($todelete->instansi_id, $todelete->tahun);
            return response()->json(['success' => 'Sukses', 'result' => true]);
        } else {
            return response()->json(['success' => 'Gagal', 'result' => false]);
        }
    }

    public function generateEvaluasiRBGenereal()
    {
        foreach (KlpdInstansi::all() as $instansi) {
            self::kalkulasi_skor($instansi->id, '2024');
        }
    }

    public function kalkulasi_skor($instansi_id, $tahun)
    {
        $lkeRenaksiIds = $tahun == 2024 ? [4, 5, 6, 8, 9, 10, 11] : [15, 16, 17, 19, 20, 21, 22];
        
        $jawabanByLke = JawabanRenaksi::with('konversi_jawaban_renaksi')->where('instansi_id', $instansi_id)
            ->whereIn('lke_renaksi_id', $lkeRenaksiIds)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('lke_renaksi_id');

        $penetapanKU = $jawabanByLke->get($lkeRenaksiIds[0]);
        $penetapanTargetIndikatorKU = $jawabanByLke->get($lkeRenaksiIds[1]);
        $keabsahanRencanaAksi = $jawabanByLke->get($lkeRenaksiIds[2]);
        $kelogisanRencanaAksi = $jawabanByLke->get($lkeRenaksiIds[3]);
        $relevansiKecukupanIndikatorOutput = $jawabanByLke->get($lkeRenaksiIds[4]);
        $ketetapanPenetapanTargetIndikatorOutput = $jawabanByLke->get($lkeRenaksiIds[5]);
        $anggaran = $jawabanByLke->get($lkeRenaksiIds[6]);

        //Penilaian Kegiatan Utama Road Map Reformasi Birokrasi
        if ($tahun == 2024) {
            $penilaianKU = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 3)->where('tahun', $tahun)->first();
        } else if ($tahun == 2025) {
            $penilaianKU = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 14)->where('tahun', $tahun)->first();
        }
        if (!$penilaianKU) {
            $penilaianKU = new JawabanRenaksi();
        }
        $penilaianKU->instansi_id = $instansi_id;
        $penilaianKU->tahun = $tahun;
        if ($tahun == 2024) {
            $penilaianKU->lke_renaksi_id = 3;
        } else if ($tahun == 2025) {
            $penilaianKU->lke_renaksi_id = 14;
        }
        $skorPenetapanKU = data_get($penetapanKU, 'konversi_jawaban_renaksi.skor', 0);
        $skorPenetapanTargetIndikatorKU = data_get($penetapanTargetIndikatorKU, 'konversi_jawaban_renaksi.skor', 0);
        $skorKeabsahanRencanaAksi = data_get($keabsahanRencanaAksi, 'konversi_jawaban_renaksi.skor', 0);

        $penilaianKU->jawaban =  round(($skorPenetapanKU + $skorPenetapanTargetIndikatorKU + $skorKeabsahanRencanaAksi) / 3,  2);
        $penilaianKU->save();

        //Kriteria Penilaian Penetapan Rencana Aksi
        $penetapanRencanaAksi = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 7)->where('tahun', $tahun)->first();
        if (!$penetapanRencanaAksi) {
            $penetapanRencanaAksi = new JawabanRenaksi();
        }
        $penetapanRencanaAksi->instansi_id = $instansi_id;
        $penetapanRencanaAksi->tahun = $tahun;
        if ($tahun == 2024) {
            $penetapanRencanaAksi->lke_renaksi_id = 7;
        } else if ($tahun == 2025) {
            $penetapanRencanaAksi->lke_renaksi_id = 18;
        }

        $skorKelogisanRencanaAksi = data_get($kelogisanRencanaAksi, 'konversi_jawaban_renaksi.skor', 0);
        $skorRelevansiKecukupanIndikatorOutput = data_get($relevansiKecukupanIndikatorOutput, 'konversi_jawaban_renaksi.skor', 0);
        $skorKetetapanPenetapanTargetIndikatorOutput = data_get($ketetapanPenetapanTargetIndikatorOutput, 'konversi_jawaban_renaksi.skor', 0);
        $skorAnggaran = data_get($anggaran, 'konversi_jawaban_renaksi.skor', 0);
        $penetapanRencanaAksi->jawaban = round(2 * ($skorKelogisanRencanaAksi + $skorRelevansiKecukupanIndikatorOutput + $skorKetetapanPenetapanTargetIndikatorOutput + $skorAnggaran) / 4, 2);
        $penetapanRencanaAksi->save();


        //SkorTotal

        if ($tahun == 2024) {
            $strategiPelaksanaanRBGeneral = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 2)->where('tahun', $tahun)->first();
        } else if ($tahun == 2025) {
            $strategiPelaksanaanRBGeneral = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id', 13)->where('tahun', $tahun)->first();
        }
        if (!$strategiPelaksanaanRBGeneral) {
            $strategiPelaksanaanRBGeneral = new JawabanRenaksi();
        }
        $strategiPelaksanaanRBGeneral->instansi_id = $instansi_id;
        $strategiPelaksanaanRBGeneral->tahun = $tahun;
        if ($tahun == 2024) {
            $strategiPelaksanaanRBGeneral->lke_renaksi_id = 2;
        } else if ($tahun == 2025) {
            $strategiPelaksanaanRBGeneral->lke_renaksi_id = 13;
        }
        $strategiPelaksanaanRBGeneral->jawaban = ($penetapanRencanaAksi->jawaban ?? 0) + ($penilaianKU->jawaban ?? 0);
        $strategiPelaksanaanRBGeneral->save();


        //Masukin ke table indeksrb
        $user = Auth::User();
        $instansi = KlpdInstansi::where('id', $instansi_id)->first();
        if ($instansi) {
            if (in_array($instansi->group, ['kl', 'provinsi', 'kabupaten'])) {
                $kegiatan = LkeKegiatan::where('tahun', $tahun)->first();
                $lke_bobot = LkeBobot::whereHas('lke_parameter', function($q) use ($kegiatan) {
                        $q->where('rencana_aksi', '1')->where('lke_kegiatan_id', $kegiatan->id);
                    })
                    ->where('group', $instansi->group)
                    ->first();
                $test_tp_line = LkeTestTpLine::where('lke_bobot_id', $lke_bobot->id)->where('instansi_id', $instansi->id)->first();
                if (!$test_tp_line) {
                    $test_tp_line = new LkeTestTpLine();
                    $test_tp_line->penilai_user_id = $user->id;
                }
                $test_tp_line->score = $strategiPelaksanaanRBGeneral->jawaban;
                $test_tp_line->lke_bobot_id = $lke_bobot->id;
                $test_tp_line->instansi_id = $instansi->id;
                $catatanParts = array_filter([
                    data_get($penetapanKU, 'catatan'),
                    data_get($penetapanTargetIndikatorKU, 'catatan'),
                    data_get($keabsahanRencanaAksi, 'catatan'),
                    data_get($kelogisanRencanaAksi, 'catatan'),
                    data_get($relevansiKecukupanIndikatorOutput, 'catatan'),
                    data_get($ketetapanPenetapanTargetIndikatorOutput, 'catatan'),
                    data_get($anggaran, 'catatan'),
                ]);
                $rekomendasiParts = array_filter([
                    data_get($penetapanKU, 'rekomendasi'),
                    data_get($penetapanTargetIndikatorKU, 'rekomendasi'),
                    data_get($keabsahanRencanaAksi, 'rekomendasi'),
                    data_get($kelogisanRencanaAksi, 'rekomendasi'),
                    data_get($relevansiKecukupanIndikatorOutput, 'rekomendasi'),
                    data_get($ketetapanPenetapanTargetIndikatorOutput, 'rekomendasi'),
                    data_get($anggaran, 'rekomendasi'),
                ]);
                $test_tp_line->catatan = implode("\n", array_map(function ($item, $index) {
                    return ($index + 1) . '. ' . $item;
                }, $catatanParts, array_keys($catatanParts)));
                
                $test_tp_line->rekomendasi = implode("\n", array_map(function ($item, $index) {
                    return ($index + 1) . '. ' . $item;
                }, $rekomendasiParts, array_keys($rekomendasiParts)));
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
