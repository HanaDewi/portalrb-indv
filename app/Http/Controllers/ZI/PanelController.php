<?php

namespace App\Http\Controllers\ZI;


use App\Models\ZI\Panel;
use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\TimEvaluasi;
use App\Models\ZI\TahapSeleksiZI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ZI\VerifikasiLapangan;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;

class PanelController extends Controller
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
        $tahun = ($request->get('tahun')) ? $request->get('tahun') : date('Y');
        $title = "Panel";

        $instansiZis = InstansiZi::with(['unit_zi.seleksi_administrasi_unit'])
            ->where('final', 1)
            ->where('tahun', $tahun)
            ->get();
        $jumlah_instansi = 0;
        $jumlah_unit_wbk = 0;
        $jumlah_unit_wbbm = 0;

        $jumlah_lolos_wbk = 0;
        $jumlah_lolos_wbbm = 0;
        $jumlah_instansi_lolos = 0;
        $teams = TimEvaluasi::where('tahun', $tahun)->get();
        $progress_teams = [];
        foreach ($teams as $tim) {
            $progress_teams[$tim->nama] = [
                "id" => $tim->id,
                "jumlah_instansi" => 0,
                "jumlah_wbk" => 0,
                "jumlah_wbbm" => 0,
                "jumlah_wbk_completed" => 0,
                "jumlah_wbbm_completed" => 0,
                "jumlah_instansi_lulus" => 0,
                "jumlah_wbk_final_total" => 0,
                "jumlah_wbbm_final_total" => 0
            ];
        }

        $datas = $instansiZis
            ->map(function ($instansiZi) use (&$jumlah_unit_wbk, &$jumlah_unit_wbbm, &$jumlah_lolos_wbk, &$jumlah_lolos_wbbm, &$jumlah_instansi, &$jumlah_instansi_lolos, &$progress_teams) {
                $wbkCount = $instansiZi->unit_zi->where('wbk', true)
                    ->filter(function ($unitZi) {
                        return optional($unitZi->verifikasi_lapangan)->status >= 1;
                    })->count();
                $wbbmCount = $instansiZi->unit_zi->where('wbbm', true)
                    ->filter(function ($unitZi) {
                        return optional($unitZi->verifikasi_lapangan)->status >= 1;
                    })->count();

                $total_unit = $wbkCount + $wbbmCount;

                if ($total_unit > 0) {
                    $jumlah_instansi += 1;
                    $jumlah_unit_wbk += $wbkCount;
                    $jumlah_unit_wbbm += $wbbmCount;
                    $nama_teams = $instansiZi->unit_zi->flatMap->unit_tim->map->tim->unique()->pluck('nama')->toArray();

                    $wbkFinalCount = $instansiZi->unit_zi->where('wbk', true)
                        ->filter(function ($unitZi) {
                            return (optional($unitZi->panel)->status == 1);
                        })->count();
                    $jumlah_lolos_wbk += $wbkFinalCount;

                    $wbbmFinalCount = $instansiZi->unit_zi->where('wbbm', true)
                        ->filter(function ($unitZi) {
                            return (optional($unitZi->panel)->status == 1);
                        })->count();
                    $jumlah_lolos_wbbm += $wbbmFinalCount;

                    $wbkCompletedCount = $instansiZi->unit_zi->where('wbk', true)
                        ->filter(function ($unitZi) {
                            return  optional($unitZi->panel)->status > -1;
                        })->count();

                    $wbbmCompletedCount = $instansiZi->unit_zi->where('wbbm', true)
                        ->filter(function ($unitZi) {
                            return  optional($unitZi->panel)->status > -1;
                        })->count();

                    if ($wbkFinalCount > 0 || $wbbmFinalCount > 0) {
                        $jumlah_instansi_lolos += 1;
                    }

                    foreach ($nama_teams as $tim) {
                        if (array_key_exists($tim, $progress_teams)) {
                            $progress_teams[$tim]["jumlah_instansi"] += 1;
                            $progress_teams[$tim]["jumlah_wbk"] += $wbkCount;
                            $progress_teams[$tim]["jumlah_wbbm"] += $wbbmCount;
                            if ($wbkFinalCount > 0 || $wbbmFinalCount > 0) {
                                $progress_teams[$tim]["jumlah_instansi_lulus"] += 1;
                                if ($wbkFinalCount > 0) {
                                    $progress_teams[$tim]["jumlah_wbk_final_total"] += $wbkFinalCount;
                                }

                                if ($wbbmFinalCount > 0) {
                                    $progress_teams[$tim]["jumlah_wbbm_final_total"] += $wbbmFinalCount;
                                }
                            }

                            if ($wbkCompletedCount > 0 || $wbbmCompletedCount > 0) {
                                if ($wbkCompletedCount > 0) {
                                    $progress_teams[$tim]["jumlah_wbk_completed"] += $wbkCompletedCount;
                                }

                                if ($wbbmCompletedCount > 0) {
                                    $progress_teams[$tim]["jumlah_wbbm_completed"] += $wbbmCompletedCount;
                                }
                            }
                        }
                    }

                    if ($total_unit < 1) { //mendhindari division by zer0
                        $total_unit = 1;
                    }


                    return [
                        'instansi_nama' => $instansiZi->klpd_instansi->name,
                        'instansi_zi_id' => $instansiZi->id,
                        'instansi_wbk_mandiri' => $instansiZi->instansi_wbk_mandiri,
                        'nama_teams' => $nama_teams,
                        'wbk_count' => $wbkCount,
                        'wbbm_count' => $wbbmCount,
                        'total_unit' => $wbkCount + $wbbmCount,
                        'wbk_final_count' => $wbkFinalCount,
                        'wbbm_final_count' => $wbbmFinalCount,
                        'total_unit_lulus' => $wbkFinalCount + $wbbmFinalCount,
                        'persentase' => floor(100 * ($wbkCompletedCount + $wbbmCompletedCount) / $total_unit)
                    ];
                } else {
                    return false;
                }
            })
            ->reject(function ($value) {
                return $value === false;
            });

        $jumlah_unit_total = $jumlah_unit_wbk + $jumlah_unit_wbbm;

        return view('zi.panel.administrasi', compact(
            "title",
            "jumlah_instansi",
            "jumlah_unit_wbk",
            "jumlah_unit_wbbm",
            'jumlah_lolos_wbk',
            'jumlah_lolos_wbbm',
            'progress_teams',
            "jumlah_unit_total",
            "datas",
            "jumlah_instansi_lolos",
            "tahun"
        ));
    }

    public function panel($id)
    {
        $title = "Panel";
        $instansi_ZI = InstansiZI::find($id);
        $tim_ids = [];

        foreach ($instansi_ZI->unit_zi as $unit_zi) {
            foreach ($unit_zi->unit_tim as $unitTim) {
                if (!in_array($unitTim->tim_id, $tim_ids)) {
                    array_push($tim_ids, $unitTim->tim_id);
                }
            }
        }
        $status = "Tidak Berhak";
        $tahun = $instansi_ZI->tahun;
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Panel Final')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            //if (Auth::User()->userTimZI) {
            //foreach (Auth::User()->userTimZI as $anggotaTim) {
            //if (in_array($anggotaTim->tim_id, $tim_ids)) {
            if (in_array(Auth::User()->id, [10060, 10059, 10046])) { // TEMPORARY ACCESS FOR mas wahyu, mba gita & mas rheza
                $status = "Berhak";
            }
            //}
            //}
            //}
        }

        $unit_ZIs = UnitZI::where("instansi_zi_id", $id)->where(function ($q) {
            $q->whereHas('verifikasi_lapangan', function ($query) {
                $query->where('status', '>=', 1);
            });
        })->orderBy('wbk', 'desc')->get();

        return view('zi.panel.evaluasi', compact(
            "status",
            "title",
            "instansi_ZI",
            "unit_ZIs",
        ));
    }

    public function panel_simpan(Request $request)
    {
        $instansiZIid = $request->get('instansiZIId');
        $instansi_ZI = InstansiZI::find($instansiZIid);
        $tim_ids = [];
        foreach ($instansi_ZI->unit_zi as $unit_zi) {
            foreach ($unit_zi->unit_tim as $unitTim) {
                if (!in_array($unitTim->tim_id, $tim_ids)) {
                    array_push($tim_ids, $unitTim->tim_id);
                }
            }
        }
        $status = "Tidak Berhak";
        $tahun = $instansi_ZI->tahun;
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Panel Final')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            // if (Auth::User()->userTimZI) {
            //     foreach (Auth::User()->userTimZI as $anggotaTim) {
            //         if (in_array($anggotaTim->tim_id, $tim_ids)) {
            if (in_array(Auth::User()->id, [10060, 10059, 10046])) { // TEMPORARY ACCESS FOR mas wahyu, mba gita & mas rheza
                $status = "Berhak";
            }
            //         }
            //     }
            // }
        }

        if ($status == "Tidak Berhak") {
            dd("hanya mas wahyu dan rheza yang bisa simpan di panel kali ini");
            abort('403');
        }

        foreach ($instansi_ZI->unit_zi as $unit_zi) {
            $panel = Panel::where('unit_zi_id', $unit_zi->id)->first();
            if ($unit_zi->verifikasi_lapangan) {
                if ($unit_zi->verifikasi_lapangan->status >= 1) {
                    if (!$panel) {
                        $panel = new Panel();
                        $panel->unit_zi_id = $unit_zi->id;
                    }
                    if (!is_null($request->get('jadwal-' . $unit_zi->id))) $panel->jadwal = $request->get('jadwal-' . $unit_zi->id);
                    if (!is_null($request->get('bukti-dukung-' . $unit_zi->id))) $panel->bukti_dukung = $request->get('bukti-dukung-' . $unit_zi->id);
                    if (!is_null($request->get('kondisi-' . $unit_zi->id))) $panel->kondisi = $request->get('kondisi-' . $unit_zi->id);
                    if (!is_null($request->get('rekomendasi-' . $unit_zi->id))) $panel->rekomendasi = $request->get('rekomendasi-' . $unit_zi->id);
                    //if (!is_null($request->get('status-' . $unit_zi->id))) $panel->status = $request->get('status-' . $unit_zi->id);
                    $panel->updated_by = Auth::User()->id;
                    $panel->save();
                };
            };
        };


        return redirect()->route('proses_panel', $instansiZIid);
    }
}
