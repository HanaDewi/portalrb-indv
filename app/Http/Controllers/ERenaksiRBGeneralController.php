<?php

namespace App\Http\Controllers;

use App\Models\JawabanRenaksi;
use App\Models\KlpdInstansi;
use App\Models\KonversiJawabanRenaksi;
use App\Models\LKERenaksi;
use App\Models\AnggotaTimEvaluasiRB as AnggotaTimEvaluasi;
use App\Models\InstansiTimEvaluasi as InstansiTim;
use App\Models\TimEvaluasiRB;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        foreach(KlpdInstansi::all() as $instansi){
            self::kalkulasi_skor($instansi->id, '2024');
        }
        dd("hai");
        $user = Auth::User();
        $isadmin = in_array($user->level, ['admin']);
        $istpn = in_array($user->level, ['tpn']);

        $tahun = $request->input('tahun');
        //$tahun = empty($tahun) ? date('Y') : $request->input('tahun');
        $tahun = empty($tahun) ?  2024 : $request->input('tahun');

        $ins_id = $request->input('instansi');

        $lke_penetapan_kegiatan_id = 4;
        $lke_penetapan_target_id = 5;
        $lke_keabsahan_rencana_id = 6;
        $lke_kelogisan_rencana_id = 8;
        $lke_relevansi_id = 9;
        $lke_ketetapan_id = 10;
        $lke_anggaran_id = 11;

        if (empty($ins_id)) {

            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                if ($atim != null) {
                    $instansis = InstansiTim::where('tim_id', $atim->tim_id)->get();
                } else {
                    $instansis = [];
                }
            } else {
                $instansis = InstansiTim::get();
                
            }

            return view('evaluasi.renaksi-rb-general', ['tahun' => $tahun, 'isadmin' => $isadmin, 'data' => $instansis, 'kembali' => false, 'istpn' => $istpn, 'check' => false]);
        } else {

            $ljawaban = KonversiJawabanRenaksi::get()->all();

            $fjawaban = [];

            $lkerenaksi = LKERenaksi::orderBy('id', 'ASC')->get();

            foreach ($lkerenaksi as $ss => &$nn) {
                $fjawaban[$nn->id] = (object) array(
                    'id' => '',
                    'tahun' => $tahun,
                    'lke_renaksi_id' => $nn->id,
                    'jawaban' => '',
                    'skor' => '',
                    'catatan' => '',
                    'rekomendasi' => ''
                );
            }

            $instansi = KlpdInstansi::where('id', $ins_id)->first();
            $jawaban = JawabanRenaksi::where('tahun', $tahun)->where('instansi_id', $ins_id)->get();
            $check = false;
            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                $check = InstansiTim::where('tim_id', $atim->tim_id)->where('instansi_id', $ins_id)->first();
            }

            foreach ($jawaban as $nn => $oo) {
                $skor = '';
                if ($oo->jawaban) {
                    $checkskor = array_filter($ljawaban, function ($lj) use ($oo) {
                        return $lj->jawaban == $oo->jawaban;
                    });
                    foreach ($checkskor as $cc => $pp) {
                        $skor = $pp['skor'];
                    }
                }
                $fjawaban[$oo->lke_renaksi_id] = (object) array(
                    'id' => $oo->id,
                    'tahun' => $oo->tahun,
                    'lke_renaksi_id' => $oo->lke_renaksi_id,
                    'jawaban' => $oo->jawaban,
                    'skor' => $skor,
                    'catatan' => $oo->catatan,
                    'rekomendasi' => $oo->rekomendasi
                );
            }

            $renaksi = DB::select('SELECT id,kriteria,info,tahun FROM lke_renaksi lr WHERE (SELECT COUNT(*) FROM lke_renaksi lr_ WHERE lr_.parent_id=lr.id)=0 AND lr.tahun=?', [$tahun]);

            return view('evaluasi.renaksi-rb-general', ['tahun' => $tahun, 'isadmin' => $isadmin, 'data' => $jawaban, 'kembali' => true, 'instansi' => $instansi, 'check' => $check, 'renaksi' => $renaksi, 'list_jawaban' => $ljawaban, 'lkerenaksi' => $lkerenaksi, 'fjawaban' => $fjawaban]);
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
        return redirect('/evaluasi/renaksi-rb-general?instansi=' . $request->instansi_id);
    }

    public function dodelete(Request $request)
    {
        $todelete = JawabanRenaksi::find($request->id);
        if ($todelete->delete()) {
            self::kalkulasi_skor($todelete->instansi_id, $todelete->tahun);
            return response()->json(['success' => 'Sukses', 'result' => true]);
            
        } else {
            return response()->json(['success' => 'Gagal', 'result' => false]);

        }
    }

    public function generateEvaluasiRBGenereal(){
        foreach(KlpdInstansi::all() as $instansi){
            self::kalkulasi_skor($instansi->id, '2024');
        }
    }

    public function kalkulasi_skor($instansi_id, $tahun){
        //Penilaian Kegiatan Utama Road Map Reformasi Birokrasi
        $penilaianKU = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',3)->where('tahun', $tahun)->first();
        if(!$penilaianKU){
            $penilaianKU = new JawabanRenaksi();
        }
        $penilaianKU->instansi_id = $instansi_id;
        $penilaianKU->tahun = $tahun;
        $penilaianKU->lke_renaksi_id = 3;
        //get_child
        $penetapanKU =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',4)->where('tahun', $tahun)->first();
        $penetapanTargetIndikatorKU =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',5)->where('tahun', $tahun)->first();
        $keabsahanRencanaAksi =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',6)->where('tahun', $tahun)->first();
        $skorPenetapanKU = ($penetapanKU)?$penetapanKU->konversi_jawaban_renaksi->skor:0;
        $skorPenetapanTargetIndikatorKU = ($penetapanTargetIndikatorKU)?$penetapanTargetIndikatorKU->konversi_jawaban_renaksi->skor:0;
        $skorKeabsahanRencanaAksi = ($keabsahanRencanaAksi)?$keabsahanRencanaAksi->konversi_jawaban_renaksi->skor:0;
        $penilaianKU->jawaban =  round( ($skorPenetapanKU + $skorPenetapanTargetIndikatorKU + $skorKeabsahanRencanaAksi ) / 3,  2);
        $penilaianKU->save();

        //Kriteria Penilaian Penetapan Rencana Aksi
        $penetapanRencanaAksi = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',7)->where('tahun', $tahun)->first();
        if(!$penetapanRencanaAksi){
            $penetapanRencanaAksi = new JawabanRenaksi();
        }
        $penetapanRencanaAksi->instansi_id = $instansi_id;
        $penetapanRencanaAksi->tahun = $tahun;
        $penetapanRencanaAksi->lke_renaksi_id = 7;
        //getchild
        $kelogisanRencanaAksi=  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',8)->where('tahun', $tahun)->first();
        $relevansiKecukupanIndikatorOutput =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',9)->where('tahun', $tahun)->first();
        $ketetapanPenetapanTargetIndikatorOutput =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',10)->where('tahun', $tahun)->first();
        $anggaran =  JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',11)->where('tahun', $tahun)->first(); 
        $skorKelogisanRencanaAksi = ($kelogisanRencanaAksi)?$kelogisanRencanaAksi->konversi_jawaban_renaksi->skor:0;
        $skorRelevansiKecukupanIndikatorOutput = ($relevansiKecukupanIndikatorOutput)? $relevansiKecukupanIndikatorOutput->konversi_jawaban_renaksi->skor:0;
        $skorKetetapanPenetapanTargetIndikatorOutput = ($ketetapanPenetapanTargetIndikatorOutput)?$ketetapanPenetapanTargetIndikatorOutput->konversi_jawaban_renaksi->skor: 0;
        $skorAnggaran = ($anggaran)?$anggaran->konversi_jawaban_renaksi->skor: 0;
        $penetapanRencanaAksi->jawaban = round(2* ($skorKelogisanRencanaAksi + $skorRelevansiKecukupanIndikatorOutput + $skorKetetapanPenetapanTargetIndikatorOutput + $skorAnggaran)/4, 2);
        $penetapanRencanaAksi->save();
        
        
        //SkorTotal
        
        $strategiPelaksanaanRBGeneral = JawabanRenaksi::where('instansi_id', $instansi_id)->where('lke_renaksi_id',2)->where('tahun', $tahun)->first();
        if(!$strategiPelaksanaanRBGeneral){
            $strategiPelaksanaanRBGeneral = new JawabanRenaksi();
        }
        $strategiPelaksanaanRBGeneral->instansi_id = $instansi_id;
        $strategiPelaksanaanRBGeneral->tahun = $tahun;
        $strategiPelaksanaanRBGeneral->lke_renaksi_id = 2;
        $strategiPelaksanaanRBGeneral->jawaban = $penetapanRencanaAksi->jawaban + $penilaianKU->jawaban;
        $strategiPelaksanaanRBGeneral->save();
    }
}
