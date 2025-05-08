<?php

namespace App\Http\Controllers\ZI;

use App\Http\Controllers\Controller;
use App\Models\ZI\UnitZI;
use App\Models\ZI\InstansiZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
                return $next($request);
            }
            abort('403');
        });
    }


    public function index(Request $request)
    {
        $title = "Dashboard";
        return view('zi.dashboard', compact('title'));
    }


    public function rekap_total(Request $request)
    {
        $title = "Rekap Total";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //Pengusulan
            $instansi_ZIs = InstansiZI::where('final', 1)->orderBy('updated_at', 'DESC')->get();
            $total_instansi = $instansi_ZIs->count();
            $total_wbk = UnitZI::where('wbk', 1)->count();
            $total_wbbm = UnitZI::where('wbbm', 1)->count();

            //Administrasi dan sanggah
            $total_instansi_administrasi = $instansiZis = InstansiZi::with(['unit_zi.seleksi_administrasi_unit'])->whereHas('unit_zi.seleksi_administrasi_unit', function ($query) {
                $query->where('seleksi_administrasi_unit.status_final', 1);
            })->count();
            $total_wbk_administrasi =  UnitZI::with(['seleksi_administrasi_unit'])->where('wbk', 1)->whereHas('seleksi_administrasi_unit', function ($query) {
                $query->where('seleksi_administrasi_unit.status_final', 1);
            })->count();
            $total_wbbm_administrasi =  UnitZI::with(['seleksi_administrasi_unit'])->where('wbbm', 1)->whereHas('seleksi_administrasi_unit', function ($query) {
                $query->where('seleksi_administrasi_unit.status_final', 1);
            })->count();

            //Sanggah
            $total_instansi_sanggah = $instansiZis = InstansiZi::with(['unit_zi.sanggah_unit'])->whereHas('unit_zi.sanggah_unit', function ($query) {
                $query->where('sanggah_unit.status_final', 1);
            })->count();
            $total_wbk_sanggah =  UnitZI::with(['sanggah_unit'])->where('wbk', 1)->whereHas('sanggah_unit', function ($query) {
                $query->where('sanggah_unit.status_final', 1);
            })->count();
            $total_wbbm_sanggah =  UnitZI::with(['sanggah_unit'])->where('wbbm', 1)->whereHas('sanggah_unit', function ($query) {
                $query->where('sanggah_unit.status_final', 1);
            })->count();

            //Analsis Dokumen
            $total_instansi_analisis_dokumen = $instansiZis = InstansiZi::with(['unit_zi.analisis_dokumen'])->whereHas('unit_zi.analisis_dokumen', function ($query) {
                $query->where('analisis_dokumen.status', 1);
            })->count();
            $total_wbk_analisis_dokumen =  UnitZI::with(['analisis_dokumen'])->where('wbk', 1)->whereHas('analisis_dokumen', function ($query) {
                $query->where('analisis_dokumen.status', 1);
            })->count();
            $total_wbbm_analisis_dokumen =  UnitZI::with(['analisis_dokumen'])->where('wbbm', 1)->whereHas('analisis_dokumen', function ($query) {
                $query->where('analisis_dokumen.status', 1);
            })->count();

            //Wawancara
            $total_instansi_seleksi_wawancara = $instansiZis = InstansiZi::with(['unit_zi.wawancara'])->whereHas('unit_zi.wawancara', function ($query) {
                $query->where('wawancara.status', 1);
            })->count();
            $total_wbk_seleksi_wawancara =  UnitZI::with(['wawancara'])->where('wbk', 1)->whereHas('wawancara', function ($query) {
                $query->where('wawancara.status', 1);
            })->count();
            $total_wbbm_seleksi_wawancara =  UnitZI::with(['wawancara'])->where('wbbm', 1)->whereHas('wawancara', function ($query) {
                $query->where('wawancara.status', 1);
            })->count();

            //Verlap
            $total_instansi_verifikasi_lapangan = $instansiZis = InstansiZi::with(['unit_zi.verifikasi_lapangan'])->whereHas('unit_zi.verifikasi_lapangan', function ($query) {
                $query->where('verifikasi_lapangan.status', 1)->orWhere('verifikasi_lapangan.status', 2);
            })->count();
            $total_wbk_verifikasi_lapangan =  UnitZI::with(['verifikasi_lapangan'])->where('wbk', 1)->whereHas('verifikasi_lapangan', function ($query) {
                $query->where('verifikasi_lapangan.status', 1)->orWhere('verifikasi_lapangan.status', 2);
            })->count();
            $total_wbbm_verifikasi_lapangan =  UnitZI::with(['verifikasi_lapangan'])->where('wbbm', 1)->whereHas('verifikasi_lapangan', function ($query) {
                $query->where('verifikasi_lapangan.status', 1)->orWhere('verifikasi_lapangan.status', 2);
            })->count();

            //Final
            $total_instansi_panel = $instansiZis = InstansiZi::with(['unit_zi.panel'])->whereHas('unit_zi.panel', function ($query) {
                $query->where('panel.status', 1);
            })->count();
            $total_wbk_panel =  UnitZI::with(['panel'])->where('wbk', 1)->whereHas('panel', function ($query) {
                $query->where('panel.status', 1);
            })->count();
            $total_wbbm_panel =  UnitZI::with(['panel'])->where('wbbm', 1)->whereHas('panel', function ($query) {
                $query->where('panel.status', 1);
            })->count();


            //Final
            $total_instansi_final = $instansiZis = InstansiZi::with(['unit_zi.panel'])->whereHas('unit_zi.panel', function ($query) {
                $query->where('panel.status', 1);
            })->count();
            $total_wbk_final =  UnitZI::with(['panel'])->where('wbk', 1)->whereHas('panel', function ($query) {
                $query->where('panel.status', 1);
            })->count();
            $total_wbbm_final =  UnitZI::with(['panel'])->where('wbbm', 1)->whereHas('panel', function ($query) {
                $query->where('panel.status', 1);
            })->count();

            return view('zi.rekap.rekap_total', compact(
                "title",
                "instansi_ZIs",
                "total_instansi",
                "total_wbk",
                "total_wbbm",
                "total_instansi_final",
                "total_wbk_final",
                "total_wbbm_final",

                'total_instansi_administrasi',
                'total_wbk_administrasi',
                'total_wbbm_administrasi',
                'total_instansi_sanggah',
                'total_wbk_sanggah',
                'total_wbbm_sanggah',
                'total_instansi_analisis_dokumen',
                'total_wbk_analisis_dokumen',
                'total_wbbm_analisis_dokumen',
                'total_instansi_seleksi_wawancara',
                'total_wbk_seleksi_wawancara',
                'total_wbbm_seleksi_wawancara',
                'total_instansi_verifikasi_lapangan',
                'total_wbk_verifikasi_lapangan',
                'total_wbbm_verifikasi_lapangan',
                'total_instansi_panel',
                'total_wbk_panel',
                'total_wbbm_panel',
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_pengusulan(Request $request)
    {
        $tahun = date('Y');
        $title = "Rekap Pengusulan Instansi";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansi_ZIs = InstansiZI::where('tahun', $tahun)
                ->whereHas('unit_zi', function ($query) {
                    $query->where('wbk', 1)->orWhere('wbbm', 1);
                })
                ->orderBy('updated_at', 'DESC')
                ->get();
            $instansi_non_mandiri = $instansi_ZIs->where("instansi_wbk_mandiri", '!=', 1);
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = $instansi_ZIs->where("instansi_wbk_mandiri", 1);
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)
                ->whereHas('instansiZI', function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                })
                ->count();
            $wbk_all_count = UnitZI::where('wbk', 1)
                ->whereHas('instansiZI', function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                })
                ->count();
            $wbk_mandiri_count = UnitZI::where('wbk', 1)
                ->whereHas('instansiZI', function ($query) use ($tahun) {
                    $query->where('instansi_wbk_mandiri', 1)->where('tahun', $tahun);
                })
                ->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;


            return view('zi.rekap.rekap_pengusulan', compact(
                "title",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_pengusulan_detail($id)
    {
        $title = "Rekap Pengusulan";
        $instansi_ZI = InstansiZI::find($id);
        $unit_wbk_ZIs = UnitZI::where("instansi_zi_id", $id)->where("wbk", 1)->get();
        $unit_wbbm_ZIs = UnitZI::where("instansi_zi_id", $id)->where("wbbm", 1)->get();
        return view('zi.rekap.rekap_pengusulan_detail', compact("title", "instansi_ZI", "unit_wbk_ZIs", "unit_wbbm_ZIs"));
    }

    public function rekap_unit(Request $request)
    {
        $title = "Rekap Pengusulan Unit";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $unit_ZIs = UnitZI::orderBy('instansi_zi_id', 'ASC')->get();
            $instansi_ZIs = InstansiZI::orderBy('updated_at', 'DESC')->get();
            $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri", '!=', 1)->orWhereNull('instansi_wbk_mandiri')->where("final", 1)->get();
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri", 1)->where("final", 1)->get();
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)->count();
            $wbk_all_count = UnitZI::where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
                $query->where('instansi_wbk_mandiri', 1);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.rekap.rekap_unit', compact(
                "title",
                "unit_ZIs",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_administrasi(Request $request)
    {
        $title = "Rekap Administrasi Unit";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $unit_ZIs = UnitZI::orderBy('instansi_zi_id', 'ASC')->get();
            $instansi_ZIs = InstansiZI::orderBy('updated_at', 'DESC')->get();
            $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri", '!=', 1)->orWhereNull('instansi_wbk_mandiri')->where("final", 1)->get();
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri", 1)->where("final", 1)->get();
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)->count();
            $wbk_all_count = UnitZI::where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
                $query->where('instansi_wbk_mandiri', 1);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.rekap.rekap_administrasi', compact(
                "title",
                "unit_ZIs",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_sanggah(Request $request)
    {
        $title = "Rekap Sanggah Unit";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //$unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
            $unit_ZIs = UnitZI::whereHas('seleksi_administrasi_unit', function ($query) {
                $query->where('status_final', 0);
            })->get();
            $instansi_ZIs = InstansiZI::orderBy('updated_at', 'DESC')->get();
            $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri", '!=', 1)->orWhereNull('instansi_wbk_mandiri')->where("final", 1)->get();
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri", 1)->where("final", 1)->get();
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)->count();
            $wbk_all_count = UnitZI::where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
                $query->where('instansi_wbk_mandiri', 1);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.rekap.rekap_sanggah', compact(
                "title",
                "unit_ZIs",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_dokumen(Request $request)
    {
        $title = "Rekap Analisis Dokumen";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //$unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
            $unit_ZIs =  UnitZI::where(function ($q) {
                $q->whereHas('seleksi_administrasi_unit', function ($query) {
                    $query->where('status_final', 1);
                })
                    ->orWhereHas('sanggah_unit', function ($query) {
                        $query->where('status_final', 1);
                    });
            })->orderBy('wbk', 'desc')->get();
            $instansi_ZIs = InstansiZI::orderBy('updated_at', 'DESC')->get();
            $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri", '!=', 1)->orWhereNull('instansi_wbk_mandiri')->where("final", 1)->get();
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri", 1)->where("final", 1)->get();
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)->count();
            $wbk_all_count = UnitZI::where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
                $query->where('instansi_wbk_mandiri', 1);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.rekap.rekap_dokumen', compact(
                "title",
                "unit_ZIs",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_wawancara(Request $request)
    {
        $title = "Rekap Wawancara";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //$unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
            $unit_ZIs =  UnitZI::whereHas('analisis_dokumen',  function ($query) {
                $query->where('status', 1);
            })->orderBy('instansi_zi_id', 'asc')->orderBy('wbk', 'desc')->get();
            $instansi_ZIs = InstansiZI::orderBy('updated_at', 'DESC')->get();
            $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri", '!=', 1)->orWhereNull('instansi_wbk_mandiri')->where("final", 1)->get();
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri", 1)->where("final", 1)->get();
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)->count();
            $wbk_all_count = UnitZI::where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
                $query->where('instansi_wbk_mandiri', 1);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.rekap.rekap_wawancara', compact(
                "title",
                "unit_ZIs",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_verlap(Request $request)
    {
        $title = "Rekap Verlap";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //$unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
            $unit_ZIs =  UnitZI::whereHas('wawancara',  function ($query) {
                $query->where('status', 1);
            })->orderBy('instansi_zi_id', 'asc')->orderBy('wbk', 'desc')->get();
            $instansi_ZIs = InstansiZI::orderBy('updated_at', 'DESC')->get();
            $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri", '!=', 1)->orWhereNull('instansi_wbk_mandiri')->where("final", 1)->get();
            $instansi_non_mandiri_count = $instansi_non_mandiri->count();
            $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri", 1)->where("final", 1)->get();
            $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
            $wbbm_count = UnitZI::where('wbbm', 1)->count();
            $wbk_all_count = UnitZI::where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
                $query->where('instansi_wbk_mandiri', 1);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.rekap.rekap_verlap', compact(
                "title",
                "unit_ZIs",
                "instansi_ZIs",
                "instansi_non_mandiri_count",
                "instansi_wbk_mandiri_count",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }
    public function rekap_panel(Request $request)
    {
        $title = "Rekap Panel";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //$unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
            $unit_ZIs =  UnitZI::whereHas('verifikasi_lapangan',  function ($query) {
                $query->where('status', 1);
            })->orderBy('instansi_zi_id', 'asc')->orderBy('wbk', 'desc')->get();

            return view('zi.rekap.rekap_panel', compact(
                "title",
                "unit_ZIs"
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function rekap_final(Request $request)
    {
        $title = "Rekap Final";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            //$unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
            $unit_ZIs =  UnitZI::orderBy('instansi_zi_id', 'asc')->orderBy('wbk', 'desc')->get();
            return view('zi.rekap.rekap_final', compact(
                "title",
                "unit_ZIs"
            ));
        } else {
            return (URL::to('/'));
        }
    }
}
