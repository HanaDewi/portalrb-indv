<?php

namespace App\Http\Controllers\Akip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth()->user();
            foreach (allowed_url('akip') as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    /**
     * Display the AKIP dashboard
     */
    public function index(Request $request)
    {
        // Get level user
        $level = $this->currentUser->level;

        if ($level == 'tpn') {
            // Use distinct names to pass into view
            $selectedYear = $request->input('tahun', date('Y'));
            $selectedPeriode = $request->input('tw') ?? 1;
            $selectedYearK = $request->input('tahunK', date('Y'));

            // Get all tims and their evaluasi status in klpd group (provinsi, kabupaten)
            $tims = DB::table('instansi_tim')
                ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
                ->leftJoin('evaluasi_sakip', function ($join) use ($selectedYear, $selectedPeriode) {
                    $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                        ->where('evaluasi_sakip.tahun', $selectedYear)
                        ->where('evaluasi_sakip.periode', 'TW ' . $selectedPeriode);
                })
                ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
                ->where(function ($query) {
                    $query->where('klpd_instansi_new.group', '=', 'provinsi')
                        ->orWhere('klpd_instansi_new.group', '=', 'kabupaten');
                })
                ->select(
                    'instansi_tim.tim_id',
                    'tim_evaluasi.nama',
                    'tim_evaluasi.keterangan',
                    DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                    DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
                )
                ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
                ->get();

            // Get all tims and their evaluasi status in klpd group (kl, lain)
            $tims_kl = DB::table('instansi_tim')
                ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
                ->leftJoin('evaluasi_sakip', function ($join) use ($selectedYear) {
                    $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                        ->where('evaluasi_sakip.tahun', $selectedYear)
                        ->where('evaluasi_sakip.periode', 'Final');
                })
                ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
                ->where(function ($query) {
                    $query->where('klpd_instansi_new.group', '=', 'kl')
                        ->orWhere('klpd_instansi_new.group', '=', 'lain');
                })
                ->select(
                    'tim_evaluasi.nama',
                    'tim_evaluasi.keterangan',
                    DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                    DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
                )
                ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
                ->get();

            return view('akip.dashboard.index', compact('tims_kl', 'tims', 'selectedYear', 'selectedPeriode', 'selectedYearK', 'level'));
        } else {
            return view('akip.dashboard.index', compact('level'));
        }
    }

    /**
     * Filter dashboard data
     */
    public function filter(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:' . date('Y'),
            'periode' => 'required|integer|min:1|max:4'
        ]);

        $tahun = $request->tahun;
        $periode = $request->periode;

        // Your existing logic to get teams data
        $tims = DB::table('instansi_tim')
            ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
            ->leftJoin('evaluasi_sakip', function ($join) use ($tahun, $periode) {
                $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                    ->where('evaluasi_sakip.tahun', $tahun)
                    ->where('evaluasi_sakip.periode', 'TW ' . $periode);
            })
            ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
            ->where(function ($query) {
                $query->where('klpd_instansi_new.group', '=', 'provinsi')
                    ->orWhere('klpd_instansi_new.group', '=', 'kabupaten');
            })
            ->select(
                'instansi_tim.tim_id',
                'tim_evaluasi.nama',
                'tim_evaluasi.keterangan',
                DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
            )
            ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
            ->get();

        return response()->json([
            'tims' => $tims,
            'tahun' => $tahun,
            'periode' => $periode
        ]);
    }

    /**
     * Filter dashboard data for K/L
     */
    public function filterKl(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:' . date('Y')
        ]);

        $tahun = $request->tahun;

        // Get all tims and their evaluasi status in klpd group (kl, lain)
        $tims_kl = DB::table('instansi_tim')
            ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
            ->leftJoin('evaluasi_sakip', function ($join) use ($tahun) {
                $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                    ->where('evaluasi_sakip.tahun', $tahun)
                    ->where('evaluasi_sakip.periode', 'Final');
            })
            ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
            ->where(function ($query) {
                $query->where('klpd_instansi_new.group', '=', 'kl')
                    ->orWhere('klpd_instansi_new.group', '=', 'lain');
            })
            ->select(
                'tim_evaluasi.nama',
                'tim_evaluasi.keterangan',
                DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
            )
            ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
            ->get();

        return response()->json([
            'tims_kl' => $tims_kl,
            'tahun' => $tahun
        ]);
    }
}
