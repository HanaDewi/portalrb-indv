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
        $tahap_seleksis = TahapSeleksiZI::where('tahun', $tahun)
            ->where('laporan_wbk_mandiri', true)
            ->get();
        return view(
            'zi.wbk_mandiri.wbk_mandiri',
            compact(
                "title",
                "tahun",
                "tahap_seleksis"
            )
        );
    }

    public function lapor_wbk_mandiri_getDatas()
    {
        $tahun = (request()->get('tahun')) ? request()->get('tahun') : date('Y');
        $instansi_id = Auth::User()->instansi_id;
        $instansiZI = InstansiZI::where('tahun', $tahun)
            ->where('instansi_id', $instansi_id)
            ->first();
        if ($instansiZI) {
            $laporWbkMandiris = LaporWbkMandiri::where('tahun', $tahun)->where('instansi_zi_id', $instansiZI->id)->get();
            foreach ($laporWbkMandiris as $laporWbkMandiri) {
                $output = array(
                    "id" => $laporWbkMandiri->id,
                    "tahap_seleksi" => $laporWbkMandiri->tahap_seleksi->tahap_seleksi,
                    "tahun" => $laporWbkMandiri->tahun,
                    "link" => $laporWbkMandiri->link,
                    "keterangan" => $laporWbkMandiri->keterangan,
                    "updated_at" => $laporWbkMandiri->updated_at->format('d-m-Y H:i:s')
                );
                $datas[] = $output;
            }

            return response()->json(['data' => $datas]);
        }
    }

    public function lapor_wbk_mandiri_getData($id)
    {
        $tahun = (request()->get('tahun')) ? request()->get('tahun') : date('Y');
        $data = LaporWbkMandiri::find($id);
        return $data;
    }
    public function lapor_wbk_mandiri_simpan(Request $request)
    {
        $tahun = ($request->get('tahun')) ? $request->get('tahun') : date('Y');
        $success = false;
        $instansi_id = Auth::User()->instansi_id;
        $instansiZI = InstansiZI::where('tahun', $tahun)
            ->where('instansi_id', $instansi_id)
            ->first();
        $LaporWbkMandiri = new LaporWbkMandiri();
        if ($request->laporan_id) {
            $LaporWbkMandiri = LaporWbkMandiri::find($request->laporan_id);
        }
        $LaporWbkMandiri->tahun = $tahun;
        $LaporWbkMandiri->instansi_zi_id = $instansiZI->id;
        $LaporWbkMandiri->tahap_seleksi_id = $request->tahap_seleksi_id;
        $LaporWbkMandiri->link = $request->link_bukti;
        $LaporWbkMandiri->keterangan = $request->keterangan;
        $LaporWbkMandiri->updated_by = Auth::User()->id;
        if ($LaporWbkMandiri->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function lapor_wbk_mandiri_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $laporWbkMandiri = LaporWbkMandiri::find($request->id);
        if ($laporWbkMandiri->delete()) {
            $success = true;
        } else {
            $success = false;
        }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }
}
