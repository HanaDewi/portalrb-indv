<?php
namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TematikSasaranRoadmap;
use Illuminate\Support\Facades\DB;

class CapaianOutputController extends Controller
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

    public function rbTematikCapaianOutput(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn'])) {
            $instansis = KlpdInstansi::all();
            foreach ($instansis as &$mm) {
                $sasaran = TematikSasaranRoadmap::where('instansi_id', $mm->id)->get();
                $mm->{'target'} = [
                    'tw1' => 0,
                    'tw2' => 0,
                    'tw3' => 0,
                    'tw4' => 0
                ];
                $mm->{'realisasi'} = [
                    'tw1' => 0,
                    'tw2' => 0,
                    'tw3' => 0,
                    'tw4' => 0
                ];
                $mm->{'capaian_jumlah'} = [
                    'tw1' => 0,
                    'tw2' => 0,
                    'tw3' => 0,
                    'tw4' => 0
                ];
                $mm->{'capaian_total'} = [
                    'tw1' => 0,
                    'tw2' => 0,
                    'tw3' => 0,
                    'tw4' => 0
                ];
                foreach ($sasaran as $sraw) {
                    if (count($sraw->indikator_roadmap)) {
                        foreach ($sraw->indikator_roadmap as $indikator) {
                            if (count($indikator->permasalahan)) {
                                foreach ($indikator->permasalahan as $masalah) {
                                    if (count($masalah->indikator_permasalahan)) {
                                        foreach ($masalah->indikator_permasalahan as $imasalah) {
                                            if (count($imasalah->rencana_aksi)) {
                                                foreach ($imasalah->rencana_aksi as $aksi){
                                                    if (count($aksi->output)) {
                                                        foreach ($aksi->output as $output) {
                                                            $mm->target = [
                                                                'tw1' => intval($output->target_tw1)>0 ? $mm->target['tw1'] + 1 : $mm->target['tw1'],
                                                                'tw2' => intval($output->target_tw2)>0 ? $mm->target['tw2'] + 1 : $mm->target['tw2'],
                                                                'tw3' => intval($output->target_tw3)>0 ? $mm->target['tw3'] + 1 : $mm->target['tw3'],
                                                                'tw4' => intval($output->target_tw4)>0 ? $mm->target['tw4'] + 1 : $mm->target['tw4']
                                                            ];

                                                            $mm->realisasi = [
                                                                'tw1' => intval($output->realisasi_output_tw1)>0 ? $mm->realisasi['tw1'] + 1 : $mm->realisasi['tw1'],
                                                                'tw2' => intval($output->realisasi_output_tw2)>0 ? $mm->realisasi['tw2'] + 1 : $mm->realisasi['tw2'],
                                                                'tw3' => intval($output->realisasi_output_tw3)>0 ? $mm->realisasi['tw3'] + 1 : $mm->realisasi['tw3'],
                                                                'tw4' => intval($output->realisasi_output_tw4)>0 ? $mm->realisasi['tw4'] + 1 : $mm->realisasi['tw4']
                                                            ];

                                                            $mm->capaian_jumlah = [
                                                                'tw1' => intval($output->capaian_output_tw1)>0 ? $mm->capaian_jumlah['tw1'] + 1 : $mm->capaian_jumlah['tw1'],
                                                                'tw2' => intval($output->capaian_output_tw2)>0 ? $mm->capaian_jumlah['tw2'] + 1 : $mm->capaian_jumlah['tw2'],
                                                                'tw3' => intval($output->capaian_output_tw3)>0 ? $mm->capaian_jumlah['tw3'] + 1 : $mm->capaian_jumlah['tw3'],
                                                                'tw4' => intval($output->capaian_output_tw4)>0 ? $mm->capaian_jumlah['tw4'] + 1 : $mm->capaian_jumlah['tw4']
                                                            ];

                                                            $mm->capaian_total = [
                                                                'tw1' => intval($output->capaian_output_tw1) + $mm->capaian_total['tw1'],
                                                                'tw2' => intval($output->capaian_output_tw2) + $mm->capaian_total['tw2'],
                                                                'tw3' => intval($output->capaian_output_tw3) + $mm->capaian_total['tw3'],
                                                                'tw4' => intval($output->capaian_output_tw4) + $mm->capaian_total['tw4']
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $mm->{'capaian_prosentase'} = [
                    'tw1' => $mm->capaian_jumlah['tw1']>0 ? round($mm->capaian_total['tw1'] / $mm->capaian_jumlah['tw1']) . '%':'',
                    'tw2' => $mm->capaian_jumlah['tw2']>0 ? round($mm->capaian_total['tw2'] / $mm->capaian_jumlah['tw2']) . '%':'',
                    'tw3' => $mm->capaian_jumlah['tw3']>0 ? round($mm->capaian_total['tw3'] / $mm->capaian_jumlah['tw3']) . '%':'',
                    'tw4' => $mm->capaian_jumlah['tw4']>0 ? round($mm->capaian_total['tw4'] / $mm->capaian_jumlah['tw4']) . '%':''
                ];
            }
            return view('webdashboard.rb-tematik-capaianoutput', compact('instansis'));
        }
    }

    public function rbGeneralCapaianOutput()
    {
        $capaians = DB::table('klpd_instansi as ki')
            ->leftJoin('general_perencanaan as gp', 'gp.instansi_id', '=', 'ki.id')
            ->leftJoin('general_perencanaan_target as gpt', 'gpt.general_perencanaan_id', '=', 'gp.id')
            ->leftJoin('general_rencana_aksi as gra', 'gra.general_perencanaan_target_id', '=', 'gpt.id')
            ->leftJoin('general_rencana_aksi_output as grao', 'grao.general_rencana_aksi_id', '=', 'gra.id')
            ->select('ki.name', 'ki.group',
                DB::raw('SUM(CASE WHEN grao.target_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw1'),
                DB::raw('SUM(CASE WHEN grao.target_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw2'),
                DB::raw('SUM(CASE WHEN grao.target_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw3'),
                DB::raw('SUM(CASE WHEN grao.target_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw4'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw1'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw2'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw3'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw4'),
                DB::raw('SUM(grao.capaian_output_tw1) as jumlah_capaian_output_tw1'),
                DB::raw('SUM(grao.capaian_output_tw2) as jumlah_capaian_output_tw2'),
                DB::raw('SUM(grao.capaian_output_tw3) as jumlah_capaian_output_tw3'),
                DB::raw('SUM(grao.capaian_output_tw4) as jumlah_capaian_output_tw4'),
                DB::raw('SUM(grao.capaian_output_total) as jumlah_capaian_output_total'),
                DB::raw('SUM(case when grao.target_tw1 > 0 or grao.target_tw2 > 0 or grao.target_tw3 > 0 or grao.target_tw4 > 0 then 1 else 0 end) as jumlah_target_total')
            )
            ->groupBy('ki.name', 'ki.group')
            ->orderBy(DB::raw('SUM(CASE WHEN grao.target_tw1 > 0 THEN 1 ELSE 0 END)'), 'desc')
            ->get();
        
        foreach ($capaians as $capaian) {
            $capaian->realisasi_tw1 = $capaian->jumlah_target_tw1 > 0 ? round(($capaian->jumlah_realisasi_output_tw1 / $capaian->jumlah_target_tw1) * 100) . '%' : '';
            $capaian->realisasi_tw2 = $capaian->jumlah_target_tw2 > 0 ? round(($capaian->jumlah_realisasi_output_tw2 / $capaian->jumlah_target_tw2) * 100) . '%' : '';
            $capaian->realisasi_tw3 = $capaian->jumlah_target_tw3 > 0 ? round(($capaian->jumlah_realisasi_output_tw3 / $capaian->jumlah_target_tw3) * 100) . '%' : '';
            $capaian->realisasi_tw4 = $capaian->jumlah_target_tw4 > 0 ? round(($capaian->jumlah_realisasi_output_tw4 / $capaian->jumlah_target_tw4) * 100) . '%' : '';
            $capaian->output_tw1 = $capaian->jumlah_capaian_output_tw1 > 0 ? round($capaian->jumlah_capaian_output_tw1 / $capaian->jumlah_target_total) . '%' : '';
            $capaian->output_tw2 = $capaian->jumlah_capaian_output_tw2 > 0 ? round($capaian->jumlah_capaian_output_tw2 / $capaian->jumlah_target_total) . '%' : '';
            $capaian->output_tw3 = $capaian->jumlah_capaian_output_tw3 > 0 ? round($capaian->jumlah_capaian_output_tw3 / $capaian->jumlah_target_total) . '%' : '';
            $capaian->output_tw4 = $capaian->jumlah_capaian_output_tw4 > 0 ? round($capaian->jumlah_capaian_output_tw4 / $capaian->jumlah_target_total) . '%' : '';
            $capaian->output_total = $capaian->jumlah_target_total > 0 ? round($capaian->jumlah_capaian_output_total / $capaian->jumlah_target_total) . '%' : '';
        }
        
        return view('webdashboard.rb-general-capaianoutput', compact('capaians'));
    }

}