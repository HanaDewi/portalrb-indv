<?php
namespace App\Http\Controllers;

use App\Models\Tema;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TematikSasaranRoadmap;
use App\Models\TematikRekapCapaianOutput;

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
        $klpdinstansis = KlpdInstansi::get();
        $instansis = [];
        $data_pertama = TematikRekapCapaianOutput::first();
        foreach($klpdinstansis as $instansi){
            foreach(Tema::get() as $tema){
                $rekap_capaian_output = TematikRekapCapaianOutput::where("instansi_id", $instansi->id)->where("tema_id", $tema->id)->first();
                if($rekap_capaian_output){
                    $instansis[$instansi->id]["nama"] = $instansi->name;
                    $instansis[$instansi->id]["group"] = $instansi->group;
                    $instansis[$instansi->id][$tema->id] = [
                        "capaian_output_tw1"    => $rekap_capaian_output->capaian_output_tw1,
                        "capaian_output_tw2"    => $rekap_capaian_output->capaian_output_tw2,
                        "capaian_output_tw3"    => $rekap_capaian_output->capaian_output_tw3,
                        "capaian_output_tw4"    => $rekap_capaian_output->capaian_output_tw4,
                        "capaian_output_total"  => $rekap_capaian_output->capaian_output_total,
                        "pengisian_tw1"         => $rekap_capaian_output->pengisian_tw1,
                        "pengisian_tw2"         => $rekap_capaian_output->pengisian_tw2,
                        "pengisian_tw3"         => $rekap_capaian_output->pengisian_tw3,
                        "pengisian_tw4"         => $rekap_capaian_output->pengisian_tw4,

                    ];
                }
            }
        }
        
        return view('webdashboard.rb-tematik-capaianoutput', compact('instansis', 'data_pertama'));
        
    }
    public function rbTematikCapaianOutputGenerate(Request $request)
    {
        $klpdinstansis = KlpdInstansi::get();
        foreach($klpdinstansis as $instansi){
            foreach(Tema::get() as $tema){
                $capaian_output_tw1[$tema->id] = 0 ;
                $capaian_output_tw2[$tema->id] = 0 ;
                $capaian_output_tw3[$tema->id] = 0 ;
                $capaian_output_tw4[$tema->id] = 0 ;
                $capaian_output_total[$tema->id] = 0 ;
                $count_capaian_output_tw1[$tema->id] = 0 ;
                $count_capaian_output_tw2[$tema->id] = 0 ;
                $count_capaian_output_tw3[$tema->id] = 0 ;
                $count_capaian_output_tw4[$tema->id] = 0 ;
                $count_capaian_output_total[$tema->id] = 0 ;
                $count_pengisian_pembagi_tw1[$tema->id] = 0 ;
                $count_pengisian_pembagi_tw2[$tema->id] = 0 ;
                $count_pengisian_pembagi_tw3[$tema->id] = 0 ;
                $count_pengisian_pembagi_tw4[$tema->id] = 0 ;
                $count_pengisian_pembilang_tw1[$tema->id] = 0 ;
                $count_pengisian_pembilang_tw2[$tema->id] = 0 ;
                $count_pengisian_pembilang_tw3[$tema->id] = 0 ;
                $count_pengisian_pembilang_tw4[$tema->id] = 0 ;
            }
            foreach($instansi->tematik_sasaran_roadmap as $sasaran_roadmap){
                foreach($sasaran_roadmap->indikator_roadmap as $indikator_roadmap){
                    foreach($indikator_roadmap->permasalahan as $permasalahan ){
                        foreach($permasalahan->indikator_permasalahan as $indikator_permasalahan){
                            foreach($indikator_permasalahan->rencana_aksi as $rencana_aksi){
                                foreach($rencana_aksi->output as $rencana_aksi_output){
                                    if( $rencana_aksi_output->target_tw1 > 0){
                                        $count_pengisian_pembagi_tw1[$tema->id]++;
                                        if((float)$rencana_aksi_output->capaian_output_tw1 > 0){
                                            $count_pengisian_pembilang_tw1[$tema->id]++;   
                                            if((float)$rencana_aksi_output->capaian_output_tw1 < (float)"300%"){
                                                $capaian_output_tw1[$sasaran_roadmap->tema->id] += (float) $rencana_aksi_output->capaian_output_tw1;
                                                $count_capaian_output_tw1[$sasaran_roadmap->tema->id]++;
                                            }
                                        }
                                    }
                                    if($rencana_aksi_output->target_tw2 > 0){
                                        $count_pengisian_pembagi_tw2[$tema->id]++;
                                        if((float)$rencana_aksi_output->capaian_output_tw2 > 0){
                                            $count_pengisian_pembilang_tw2[$tema->id]++ ;   
                                            if((float)$rencana_aksi_output->capaian_output_tw2 < (float)"300%"){
                                                $capaian_output_tw2[$sasaran_roadmap->tema->id] += (float)$rencana_aksi_output->capaian_output_tw2;
                                                $count_capaian_output_tw2[$sasaran_roadmap->tema->id]++;
                                            }
                                        }
                                    }
                                    if($rencana_aksi_output->target_tw3 > 0){
                                        $count_pengisian_pembagi_tw3[$tema->id]++;
                                        if((float)$rencana_aksi_output->capaian_output_tw3 > 0){
                                            $count_pengisian_pembilang_tw3[$tema->id]++;   
                                            if((float)$rencana_aksi_output->capaian_output_tw3 < (float)"300%"){
                                                $capaian_output_tw3[$sasaran_roadmap->tema->id] += (float)$rencana_aksi_output->capaian_output_tw3;
                                                $count_capaian_output_tw3[$sasaran_roadmap->tema->id]++;
                                            }
                                        }
                                    }
                                    if($rencana_aksi_output->target_tw4 > 0){
                                        $count_pengisian_pembagi_tw4[$tema->id]++;
                                        if((float)$rencana_aksi_output->capaian_output_tw4 > 0){
                                            $count_pengisian_pembilang_tw4[$tema->id]++;   
                                            if((float)$rencana_aksi_output->capaian_output_tw4 < (float)"300%"){
                                                $capaian_output_tw4[$sasaran_roadmap->tema->id] += (float)$rencana_aksi_output->capaian_output_tw4;
                                                $count_capaian_output_tw4[$sasaran_roadmap->tema->id]++;
                                            }
                                        }
                                    }
                                    if((float)$rencana_aksi_output->capaian_output_total < (float)"300%" && $rencana_aksi_output->target_total > 0){
                                        $capaian_output_total[$sasaran_roadmap->tema->id] += (float)$rencana_aksi_output->capaian_output_total;
                                        $count_capaian_output_total[$sasaran_roadmap->tema->id]++;
                                    }
                                }    
                            }
                        }
                    }

                };
            }
            foreach(Tema::get() as $tema){
                $rekap_capaian_output = TematikRekapCapaianOutput::where("instansi_id", $instansi->id)->where("tema_id", $tema->id)->first();
                if(!$rekap_capaian_output){
                    $rekap_capaian_output = new TematikRekapCapaianOutput;
                    $rekap_capaian_output->instansi_id = $instansi->id;
                    $rekap_capaian_output->tema_id = $tema->id;
                }
                ($count_capaian_output_tw1[$tema->id] >0)?$rekap_capaian_output->capaian_output_tw1 = $capaian_output_tw1[$tema->id]/$count_capaian_output_tw1[$tema->id]: $rekap_capaian_output->capaian_output_tw1 = 0;
                ($count_capaian_output_tw2[$tema->id] >0)?$rekap_capaian_output->capaian_output_tw2 = $capaian_output_tw2[$tema->id]/$count_capaian_output_tw2[$tema->id]: $rekap_capaian_output->capaian_output_tw2 = 0; 
                ($count_capaian_output_tw3[$tema->id] >0)?$rekap_capaian_output->capaian_output_tw3 = $capaian_output_tw3[$tema->id]/$count_capaian_output_tw3[$tema->id]: $rekap_capaian_output->capaian_output_tw3 = 0; 
                ($count_capaian_output_tw4[$tema->id] >0)?$rekap_capaian_output->capaian_output_tw4 = $capaian_output_tw4[$tema->id]/$count_capaian_output_tw4[$tema->id]: $rekap_capaian_output->capaian_output_tw4 = 0;
                ($count_capaian_output_total[$tema->id] >0)?$rekap_capaian_output->capaian_output_total = $capaian_output_total[$tema->id]/$count_capaian_output_total[$tema->id]: $rekap_capaian_output->capaian_output_total = 0 ; 

                ($count_pengisian_pembagi_tw1[$tema->id])?$rekap_capaian_output->pengisian_tw1 = 100 * $count_pengisian_pembilang_tw1[$tema->id] / $count_pengisian_pembagi_tw1[$tema->id]  : $rekap_capaian_output->pengisian_tw1 = 0;
                ($count_pengisian_pembagi_tw2[$tema->id])?$rekap_capaian_output->pengisian_tw2 = 100 * $count_pengisian_pembilang_tw2[$tema->id] / $count_pengisian_pembagi_tw2[$tema->id]   : $rekap_capaian_output->pengisian_tw2 = 0;
                ($count_pengisian_pembagi_tw3[$tema->id])?$rekap_capaian_output->pengisian_tw3 = 100 * $count_pengisian_pembilang_tw3[$tema->id] / $count_pengisian_pembagi_tw3[$tema->id]  : $rekap_capaian_output->pengisian_tw3 = 0;
                ($count_pengisian_pembagi_tw4[$tema->id])?$rekap_capaian_output->pengisian_tw4 = 100 * $count_pengisian_pembilang_tw4[$tema->id] / $count_pengisian_pembagi_tw4[$tema->id]  : $rekap_capaian_output->pengisian_tw4 = 0;
                
                $rekap_capaian_output->updated_by = Auth::User()->id;
                $rekap_capaian_output->save();
                
            }
            // echo "finish satu";
             echo $instansi->name . "<br/>";
            // dd("pause");
        }
        
        //return view('webdashboard.rb-tematik-capaianoutput', compact('instansis'));
        
    }

    public function rbTematikCapaianOutput2(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'viewer'])) {
            $capaians ="";
            // $capaians = DB::table('klpd_instansi as ki')
            //     ->leftJoin('tematik_sasaran_roadmap as sasaran', 'sasaran.instansi_id', '=', 'ki.id')
            //     ->leftJoin('tematik_indikator_roadmap as indikator', 'indikator.tematik_sasaran_roadmap_id', '=', 'sasaran.id')
            //     ->leftJoin('tematik_permasalahan as masalah', 'masalah.tematik_indikator_roadmap_id', '=', 'indikator.id')
            //     ->leftJoin('tematik_indikator_permasalahan as indikatormasalah', 'indikatormasalah.tematik_permasalahan_id', '=', 'masalah.id')
            //     ->leftJoin('tematik_rencana_aksi as rencana', 'rencana.tematik_indikator_permasalahan_id', '=', 'indikatormasalah.id')
            //     ->leftJoin('tematik_rencana_aksi_output as routput', 'routput.tematik_rencana_aksi_id', '=', 'rencana.id')
            //     ->select('ki.name', 'ki.group',
            //         DB::raw('SUM(CASE WHEN routput.target_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw1'),
            //         DB::raw('SUM(CASE WHEN routput.target_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw2'),
            //         DB::raw('SUM(CASE WHEN routput.target_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw3'),
            //         DB::raw('SUM(CASE WHEN routput.target_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw4'),
            //         DB::raw('SUM(CASE WHEN routput.realisasi_output_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw1'),
            //         DB::raw('SUM(CASE WHEN routput.realisasi_output_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw2'),
            //         DB::raw('SUM(CASE WHEN routput.realisasi_output_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw3'),
            //         DB::raw('SUM(CASE WHEN routput.realisasi_output_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw4'),
            //         DB::raw('SUM(CASE WHEN routput.capaian_output_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_capaian_output_tw1'),
            //         DB::raw('SUM(CASE WHEN routput.capaian_output_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_capaian_output_tw2'),
            //         DB::raw('SUM(CASE WHEN routput.capaian_output_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_capaian_output_tw3'),
            //         DB::raw('SUM(CASE WHEN routput.capaian_output_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_capaian_output_tw4'),
            //         DB::raw('SUM(routput.capaian_output_tw1) as total_capaian_output_tw1'),
            //         DB::raw('SUM(routput.capaian_output_tw2) as total_capaian_output_tw2'),
            //         DB::raw('SUM(routput.capaian_output_tw3) as total_capaian_output_tw3'),
            //         DB::raw('SUM(routput.capaian_output_tw4) as total_capaian_output_tw4')
            //     )
            //     ->groupBy('ki.name', 'ki.group')
            //     ->orderBy(DB::raw('SUM(CASE WHEN routput.target_tw1 > 0 THEN 1 ELSE 0 END)'), 'desc')
            //     ->get();
            
            // foreach ($capaians as $capaian) {
            //     $capaian->realisasi_tw1 = $capaian->jumlah_target_tw1 > 0 ? round(($capaian->jumlah_realisasi_output_tw1 / $capaian->jumlah_target_tw1) * 100) . '%': '';
            //     $capaian->realisasi_tw2 = $capaian->jumlah_target_tw2 > 0 ? round(($capaian->jumlah_realisasi_output_tw2 / $capaian->jumlah_target_tw2) * 100) . '%': '';
            //     $capaian->realisasi_tw3 = $capaian->jumlah_target_tw3 > 0 ? round(($capaian->jumlah_realisasi_output_tw3 / $capaian->jumlah_target_tw3) * 100) . '%': '';
            //     $capaian->realisasi_tw4 = $capaian->jumlah_target_tw4 > 0 ? round(($capaian->jumlah_realisasi_output_tw4 / $capaian->jumlah_target_tw4) * 100) . '%': '';
            //     $capaian->output_tw1 = $capaian->jumlah_capaian_output_tw1 > 0 ? round($capaian->total_capaian_output_tw1 / $capaian->jumlah_capaian_output_tw1) : 0;
            //     $capaian->output_tw2 = $capaian->jumlah_capaian_output_tw2 > 0 ? round($capaian->total_capaian_output_tw2 / $capaian->jumlah_capaian_output_tw2) : 0;
            //     $capaian->output_tw3 = $capaian->jumlah_capaian_output_tw3 > 0 ? round($capaian->total_capaian_output_tw3 / $capaian->jumlah_capaian_output_tw3) : 0;
            //     $capaian->output_tw4 = $capaian->jumlah_capaian_output_tw4 > 0 ? round($capaian->total_capaian_output_tw4 / $capaian->jumlah_capaian_output_tw4) : 0;
            //     $total = $capaian->output_tw1 + $capaian->output_tw2 + $capaian->output_tw3 + $capaian->output_tw4;
            //     $jumlah_capaian_output = $capaian->output_tw1>0 ? 1:0;
            //     $jumlah_capaian_output += $capaian->output_tw2>0 ? 1:0;
            //     $jumlah_capaian_output += $capaian->output_tw3>0 ? 1:0;
            //     $jumlah_capaian_output += $capaian->output_tw4>0 ? 1:0;
            //     $capaian->prosentase_capaian_output = $jumlah_capaian_output>0 ? round( $total / $jumlah_capaian_output ) . '%': '';
            // }
            return view('webdashboard.rb-tematik-capaianoutput', compact('capaians'));
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