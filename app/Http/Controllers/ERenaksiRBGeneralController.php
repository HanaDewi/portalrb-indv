<?php
namespace App\Http\Controllers;

use App\Models\JawabanRenaksi;
use App\Models\KlpdInstansi;
use App\Models\KonversiJawabanRenaksi;
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

    public function index2(Request $request)
    {
        $user = Auth::User();
        $isadmin = in_array($user->level, ['admin']);
        $tahun = $request->input('tahun');
        $tahun = empty($tahun) ? date('Y'):$request->input('tahun');
        $jawaban = JawabanRenaksi::where('tahun', $tahun)->get();
        return view('evaluasi.renaksi-rb-general', ['tahun'=>$tahun, 'isadmin'=>$isadmin, 'jawaban'=>$jawaban]);
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
                foreach ($instansis as $jj=>&$nn) {
                    $nn->skor = 100;
                }
            } else {
                $instansis = KlpdInstansi::get();
                foreach ($instansis as $jj=>&$nn) {
                    $nn->skor = 100;
                }
            }

            return view('evaluasi.renaksi-rb-general', ['tahun'=>$tahun, 'isadmin'=>$isadmin, 'data'=>$instansis, 'kembali'=>false, 'istpn'=>$istpn, 'check'=>false]);

        } else {

            $instansi = KlpdInstansi::where('id', $ins_id)->first();
            $jawaban = JawabanRenaksi::where('tahun', $tahun)->where('instansi_id', $ins_id)->get();
            $check = false;
            if ($istpn) {
                $atim = AnggotaTimEvaluasi::where('user_id', $user->id)->first();
                $check = InstansiTim::where('tim_id', $atim->tim_id)->where('instansi_id', $ins_id)->first();
            }

            $renaksi = DB::select('SELECT id,kriteria,info,tahun FROM lke_renaksi lr WHERE (SELECT COUNT(*) FROM lke_renaksi lr_ WHERE lr_.parent_id=lr.id)=0 AND lr.tahun=?', [$tahun]);
            $ljawaban = KonversiJawabanRenaksi::get();

            return view('evaluasi.renaksi-rb-general', ['tahun'=>$tahun, 'isadmin'=>$isadmin, 'data'=>$jawaban, 'kembali'=>true, 'instansi'=>$instansi, 'check'=>$check, 'renaksi'=>$renaksi, 'list_jawaban'=>$ljawaban]);

        }
    }

}
