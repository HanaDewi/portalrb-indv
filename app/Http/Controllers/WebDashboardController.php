<?php

namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebDashboardController extends Controller
{
    public function __construct()
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

    public function rbGeneral(Request $request)
    {
        $instansis = KlpdInstansi::all();

        return view('webdashboard.rb-general', compact('instansis'));
    }

    public function rbTematik(Request $request)
    {
        $tematiks = DB::select("WITH
            baseline_check AS (
                SELECT
                    tsr.instansi_id,
                    'yes' AS tematik,
                    COUNT(DISTINCT tsr.tema_id) AS tema_id_count
                FROM tematik_sasaran_roadmap tsr
                GROUP BY tsr.instansi_id
            ),
            distinct_themes AS (
                SELECT
                    tsr.instansi_id,
                    tsr.tema_id
                FROM tematik_sasaran_roadmap tsr
                GROUP BY tsr.instansi_id, tsr.tema_id
            ),
            permasalahan_check AS (
                SELECT
                    tsr.instansi_id,
                    'yes' AS permasalahan
                FROM tematik_sasaran_roadmap tsr
                JOIN tematik_indikator_roadmap tir ON tir.tematik_sasaran_roadmap_id = tsr.id
                JOIN tematik_permasalahan tp ON tp.tematik_indikator_roadmap_id = tir.id
                GROUP BY tsr.instansi_id
            ),
            rencana_aksi_check AS (
                SELECT
                    tsr.instansi_id,
                    'yes' AS rencana_aksi
                FROM tematik_sasaran_roadmap tsr
                JOIN tematik_indikator_roadmap tir ON tir.tematik_sasaran_roadmap_id = tsr.id
                JOIN tematik_permasalahan tp ON tp.tematik_indikator_roadmap_id = tir.id
                JOIN tematik_indikator_permasalahan tip ON tip.tematik_permasalahan_id = tp.id
                JOIN tematik_rencana_aksi tra ON tra.tematik_indikator_permasalahan_id = tip.id
                GROUP BY tsr.instansi_id
            )
        SELECT
            bc.instansi_id,
            bc.tematik,
            bc.tema_id_count,
            MAX(CASE WHEN dt.tema_id = 1 THEN 1 ELSE 0 END) AS tema1,
            MAX(CASE WHEN dt.tema_id = 2 THEN 1 ELSE 0 END) AS tema2,
            MAX(CASE WHEN dt.tema_id = 3 THEN 1 ELSE 0 END) AS tema3,
            MAX(CASE WHEN dt.tema_id = 4 THEN 1 ELSE 0 END) AS tema4,
            MAX(CASE WHEN dt.tema_id = 5 THEN 1 ELSE 0 END) AS tema5,
            COALESCE(pc.permasalahan, '---') AS permasalahan,
            COALESCE(rac.rencana_aksi, '---') AS rencana_aksi
        FROM baseline_check bc
        LEFT JOIN distinct_themes dt ON dt.instansi_id = bc.instansi_id
        LEFT JOIN permasalahan_check pc ON pc.instansi_id = bc.instansi_id
        LEFT JOIN rencana_aksi_check rac ON rac.instansi_id = bc.instansi_id
        GROUP BY
            bc.instansi_id, bc.tematik, bc.tema_id_count,
            pc.permasalahan, rac.rencana_aksi");
        $tematiks = collect($tematiks)->keyBy('instansi_id');
        $instansis = KlpdInstansi::all();

        return view('webdashboard.rb-tematik', compact('instansis', 'tematiks'));
    }

    public function hasilEvaluasi()
    {
        $instansis = KlpdInstansi::all();

        return view('webdashboard.hasil-evaluasi', compact('instansis'));
    }
}
