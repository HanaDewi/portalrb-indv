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
                    foreach ($instansis as $cc => &$nn1) {
                        $skora1 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_penetapan_kegiatan_id]);
                        $skora2 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_penetapan_target_id]);
                        $skora3 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_keabsahan_rencana_id]);
                        $skorb1 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_kelogisan_rencana_id]);
                        $skorb2 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_relevansi_id]);
                        $skorb3 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_ketetapan_id]);
                        $skorb4 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn1->instansi_id, $tahun, $lke_anggaran_id]);
                        $skor1 = ((floatval($skora1[0]->skor) + floatval($skora2[0]->skor) + floatval($skora3[0]->skor)) / 3) * 1.5;
                        $skor2 = ((floatval($skorb1[0]->skor) + floatval($skorb2[0]->skor) + floatval($skorb3[0]->skor) + floatval($skorb4[0]->skor)) / 4) * 1.5;
                        $total = $skor1 + $skor2;
                        $nn1->skor = number_format($total, 2, ',', ' ');
                    }
                } else {
                    $instansis = [];
                }
            } else {
                $instansis = KlpdInstansi::get();
                foreach ($instansis as $dd => &$nn2) {
                    $skora1 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_penetapan_kegiatan_id]);
                    $skora2 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_penetapan_target_id]);
                    $skora3 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_keabsahan_rencana_id]);
                    $skorb1 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_kelogisan_rencana_id]);
                    $skorb2 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_relevansi_id]);
                    $skorb3 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_ketetapan_id]);
                    $skorb4 = DB::select('SELECT SUM(kjr.skor) skor FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=? AND jr.lke_renaksi_id=?', [$nn2->instansi_id, $tahun, $lke_anggaran_id]);
                    $skor1 = ((floatval($skora1[0]->skor) + floatval($skora2[0]->skor) + floatval($skora3[0]->skor)) / 3) * 1.5;
                    $skor2 = ((floatval($skorb1[0]->skor) + floatval($skorb2[0]->skor) + floatval($skorb3[0]->skor) + floatval($skorb4[0]->skor)) / 4) * 1.5;
                    $total = $skor1 + $skor2;
                    $nn2->skor = number_format($total, 2, ',', ' ');
                }
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
            return response()->json(['success' => 'Sukses', 'result' => true]);
        } else {
            return response()->json(['success' => 'Gagal', 'result' => false]);
        }
    }
}
