<?php
namespace App\Http\Controllers;

use App\Models\JawabanRenaksi;
use App\Models\KlpdInstansi;
use App\Models\KonversiJawabanRenaksi;
use App\Models\LKERenaksi;
use App\Models\ZI\AnggotaTimEvaluasi;
use App\Models\ZI\InstansiTim;
use App\Models\ZI\UnitTimEvaluasi;
use DB;
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
        $tahun = empty($tahun) ? date('Y'):$request->input('tahun');

        $ins_id = $request->input('instansi');

        if (empty($ins_id)) {

            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                $instansis = InstansiTim::where('tim_id', $atim->tim_id)->get();
                foreach ($instansis as $cc=>&$nn1) {
                    $skor1 = DB::select('SELECT AVG(kjr.skor) rata FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=?', [$nn1->instansi_id, $tahun]);
                    $nn1->skor = number_format($skor1[0]->rata, 2, ',', ' ');
                }
            } else {
                $instansis = KlpdInstansi::get();
                foreach ($instansis as $dd=>&$nn2) {
                    $skor2 = DB::select('SELECT AVG(kjr.skor) rata FROM jawaban_renaksi jr JOIN konversi_jawaban_renaksi kjr ON kjr.jawaban=jr.jawaban WHERE jr.instansi_id=? AND jr.tahun=?', [$nn2->id, $tahun]);
                    $nn2->skor = number_format($skor2[0]->rata, 2, ',', ' ');
                }
            }

            return view('evaluasi.renaksi-rb-general', ['tahun'=>$tahun, 'isadmin'=>$isadmin, 'data'=>$instansis, 'kembali'=>false, 'istpn'=>$istpn, 'check'=>false]);

        } else {

            $lkerenaksi = LKERenaksi::orderBy('id', 'ASC')->get();

            $instansi = KlpdInstansi::where('id', $ins_id)->first();
            $jawaban = JawabanRenaksi::where('tahun', $tahun)->where('instansi_id', $ins_id)->get();
            $check = false;
            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                $check = InstansiTim::where('tim_id', $atim->tim_id)->where('instansi_id', $ins_id)->first();
            }

            $fjawaban = [];
            foreach ($jawaban as $nn=>$oo) {
                $fjawaban[$oo->lke_renaksi_id] = (object) array(
                    'tahun'=>$oo->tahun,
                    'lke_renaksi_id'=>$oo->lke_renaksi_id,
                    'jawaban'=>$oo->jawaban,
                    'catatan'=>$oo->catatan,
                    'rekomendasi'=>$oo->rekomendasi
                );
            }

            $renaksi = DB::select('SELECT id,kriteria,info,tahun FROM lke_renaksi lr WHERE (SELECT COUNT(*) FROM lke_renaksi lr_ WHERE lr_.parent_id=lr.id)=0 AND lr.tahun=?', [$tahun]);
            $ljawaban = KonversiJawabanRenaksi::get();

            return view('evaluasi.renaksi-rb-general', ['tahun'=>$tahun, 'isadmin'=>$isadmin, 'data'=>$jawaban, 'kembali'=>true, 'instansi'=>$instansi, 'check'=>$check, 'renaksi'=>$renaksi, 'list_jawaban'=>$ljawaban, 'lkerenaksi'=>$lkerenaksi, 'fjawaban'=>$fjawaban]);

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
