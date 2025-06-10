<?php

namespace App\Http\Controllers\ZI;


use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\TahapSeleksiZI;
use App\Models\ZI\LaporWbkMandiri;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WbkMandiriController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            // Gita 10059, Wahyu 10060 Rheza  10046 Arina 10053 Canggih 10056 Afif 10048 Auffi 10052
            if (Auth::User()->level == "admin" || in_array(Auth::User()->id, [
                10060,
                10209,
                10059,
                10053,
                10052
            ])) {
                return $next($request);
            } else {
                $year = date('Y');
                $instansi_id = Auth::User()->instansi_id;
                $instansiZI = InstansiZI::where('tahun', $year)
                    ->where('instansi_id', $instansi_id)
                    ->first();
                if ($instansiZI) {
                    return $next($request);
                } else {
                    abort('403');
                }
            }
            abort('403');
        });
    }

    public function index(Request $request)
    {
        $tahun = ($request->get('tahun')) ? $request->get('tahun') : date('Y');
        $title = "Laporan WBK Mandiri";
        return view(
            'zi.wbk_mandiri.wbk_mandiri',
            compact(
                "title",
                "tahun"
            )
        );
    }

    public function lapor_wbk_mandiri_getDatas()
    {
        dd("hai");
        $tahun = (request()->get('tahun')) ? request()->get('tahun') : date('Y');
        $instansi_id = Auth::User()->instansi_id;
        $instansiZI = InstansiZI::where('tahun', $year)
            ->where('instansi_id', $instansi_id)
            ->first();
        if ($instansiZI) {
            $datas = LaporWbkMandiri::where('tahun', $tahun)->where("instansi_zi_id")->get();
            return response()->json(['data' => $datas]);
        }
    }

    public function lapor_wbk_mandiri_getData($id)
    {
        $data = TahapSeleksiZI::find($id);
        return $data;
    }
    public function lapor_wbk_mandiri_simpan(Request $request)
    {
        $success = false;
        $jadwalZI = new TahapSeleksiZI();
        if ($request->jadwal_id) {
            $jadwalZI = TahapSeleksiZI::find($request->jadwal_id);
        }
        $jadwalZI->tahap_seleksi = $request->tahap_seleksi;
        $jadwalZI->tanggal_mulai = $request->tanggal_mulai;
        $jadwalZI->tanggal_selesai = $request->tanggal_selesai;
        $jadwalZI->tahun = ($request->tahun) ? $request->tahun : date('Y');
        if ($jadwalZI->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function lapor_wbk_mandiri_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $jadwalZI = TahapSeleksiZI::find($request->id);
        #if (count($timEvaluasi->indikators) > 0) {
        #    $pesan = 'Tim Evaluasi tidak bisa dihapus, silahkan hapus dulu Anggota yang terhubung dengan Tim Evaluasi ini!';
        #    $success = false;
        #} else {
        if ($jadwalZI->delete()) {
            $success = true;
        } else {
            $success = false;
        }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }
}
