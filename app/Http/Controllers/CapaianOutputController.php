<?php
namespace App\Http\Controllers;

use App\Models\Tema;
use App\Models\KlpdInstansi;
use App\Models\GeneralPerencanaanTarget;
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
        $temaIds = Tema::orderBy('id')->pluck('id');
        $klpdinstansis = KlpdInstansi::select('id', 'name', 'group')->get();
        $instansiIds = $klpdinstansis->pluck('id');

        $rekaps = TematikRekapCapaianOutput::select(
                'instansi_id',
                'tema_id',
                'capaian_output_tw1',
                'capaian_output_tw2',
                'capaian_output_tw3',
                'capaian_output_tw4',
                'capaian_output_total',
                'pengisian_tw1',
                'pengisian_tw2',
                'pengisian_tw3',
                'pengisian_tw4'
            )
            ->whereIn('instansi_id', $instansiIds)
            ->whereIn('tema_id', $temaIds)
            ->get()
            ->groupBy('instansi_id');

        $instansis = [];
        foreach ($klpdinstansis as $instansi) {
            $instansiData = [
                'nama' => $instansi->name,
                'group' => $instansi->group,
            ];

            foreach ($temaIds as $temaId) {
                $instansiData[$temaId] = [
                    'capaian_output_tw1' => 0,
                    'capaian_output_tw2' => 0,
                    'capaian_output_tw3' => 0,
                    'capaian_output_tw4' => 0,
                    'capaian_output_total' => 0,
                    'pengisian_tw1' => 0,
                    'pengisian_tw2' => 0,
                    'pengisian_tw3' => 0,
                    'pengisian_tw4' => 0,
                ];
            }

            foreach ($rekaps->get($instansi->id, []) as $rekap) {
                $instansiData[$rekap->tema_id] = [
                    'capaian_output_tw1' => $rekap->capaian_output_tw1,
                    'capaian_output_tw2' => $rekap->capaian_output_tw2,
                    'capaian_output_tw3' => $rekap->capaian_output_tw3,
                    'capaian_output_tw4' => $rekap->capaian_output_tw4,
                    'capaian_output_total' => $rekap->capaian_output_total,
                    'pengisian_tw1' => $rekap->pengisian_tw1,
                    'pengisian_tw2' => $rekap->pengisian_tw2,
                    'pengisian_tw3' => $rekap->pengisian_tw3,
                    'pengisian_tw4' => $rekap->pengisian_tw4,
                ];
            }

            $instansis[$instansi->id] = $instansiData;
        }

        $data_pertama = TematikRekapCapaianOutput::orderBy('updated_at', 'desc')->first();

        return view('webdashboard.rb-tematik-capaianoutput', compact('instansis', 'data_pertama'));
    }

    public function rbTematikCapaianOutputGenerate($pilihan, Request $request)
    {
        if($pilihan == 1){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[1,50])->get();
            $i = 1;
        }elseif($pilihan == 2){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[51,100])->get();
            $i = 51;
        }elseif($pilihan == 3){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[101,150])->get();
            $i = 101;
        }elseif($pilihan == 4){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[151,200])->get();
            $i = 151;
        }elseif($pilihan == 5){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[201,250])->get();
            $i = 201;
        }elseif($pilihan == 6){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[251,300])->get();
            $i = 251;
        }elseif($pilihan == 7){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[301,350])->get();
            $i = 301;
        }elseif($pilihan == 8){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[351,400])->get();
            $i = 351;
        }elseif($pilihan == 9){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[401,450])->get();
            $i = 401;
        }elseif($pilihan == 10){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[451,500])->get();
            $i = 451;
        }elseif($pilihan == 11){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[501,550])->get();
            $i = 501;
        }elseif($pilihan == 12){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[551,600])->get();
            $i = 551;
        }elseif($pilihan == 13){
            $klpdinstansis = KlpdInstansi::whereBetween('id',[601,655])->get();
            $i = 601;
        }elseif($pilihan == 99){
            $klpdinstansis = KlpdInstansi::where('id', $request->id )->get();
            $i = 1;
        }
        
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
                $rekap_capaian_output->updated_at = now(); //butuh ini karena kalau value tidak berubah makan updated_at tidak akan berubah
                if($rekap_capaian_output->save()){
                    echo $rekap_capaian_output->tema_id . "-" ;
                }
                
            }
            
            // echo "finish satu";
            echo $i ." ". $instansi->name . "<br/>";
            $i++;

            //dd("pause");
        }
        
        //return view('webdashboard.rb-tematik-capaianoutput', compact('instansis'));
        
    }

    public function rbGeneralCapaianOutput(Request $request)
    {
        $years = GeneralPerencanaanTarget::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $selectedYear = $request->get('tahun', $years->first());

        $capaians = DB::table('klpd_instansi as ki')
            ->leftJoin('general_perencanaan as gp', 'gp.instansi_id', '=', 'ki.id')
            ->leftJoin('general_perencanaan_target as gpt', 'gpt.general_perencanaan_id', '=', 'gp.id')
            ->leftJoin('general_rencana_aksi as gra', 'gra.general_perencanaan_target_id', '=', 'gpt.id')
            ->leftJoin('general_rencana_aksi_output as grao', 'grao.general_rencana_aksi_id', '=', 'gra.id')
            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->where('gpt.tahun', $selectedYear);
            })
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
            $capaian->realisasi_tw1 = $capaian->jumlah_target_tw1 > 0 ? round(($capaian->jumlah_realisasi_output_tw1 / $capaian->jumlah_target_tw1) * 100) : '';
            $capaian->realisasi_tw2 = $capaian->jumlah_target_tw2 > 0 ? round(($capaian->jumlah_realisasi_output_tw2 / $capaian->jumlah_target_tw2) * 100) : '';
            $capaian->realisasi_tw3 = $capaian->jumlah_target_tw3 > 0 ? round(($capaian->jumlah_realisasi_output_tw3 / $capaian->jumlah_target_tw3) * 100) : '';
            $capaian->realisasi_tw4 = $capaian->jumlah_target_tw4 > 0 ? round(($capaian->jumlah_realisasi_output_tw4 / $capaian->jumlah_target_tw4) * 100) : '';
            $capaian->output_tw1 = $capaian->jumlah_capaian_output_tw1 > 0 ? round($capaian->jumlah_capaian_output_tw1 / $capaian->jumlah_target_total) : '';
            $capaian->output_tw2 = $capaian->jumlah_capaian_output_tw2 > 0 ? round($capaian->jumlah_capaian_output_tw2 / $capaian->jumlah_target_total) : '';
            $capaian->output_tw3 = $capaian->jumlah_capaian_output_tw3 > 0 ? round($capaian->jumlah_capaian_output_tw3 / $capaian->jumlah_target_total) : '';
            $capaian->output_tw4 = $capaian->jumlah_capaian_output_tw4 > 0 ? round($capaian->jumlah_capaian_output_tw4 / $capaian->jumlah_target_total) : '';
            $capaian->output_total = $capaian->jumlah_target_total > 0 ? round($capaian->jumlah_capaian_output_total / $capaian->jumlah_target_total) : '';
            
            $capaian->realisasi_tw1 = $capaian->realisasi_tw1 != '' ? ($capaian->realisasi_tw1 > 100 ? 100 . '%' : $capaian->realisasi_tw1 . '%') : '';
            $capaian->realisasi_tw2 = $capaian->realisasi_tw2 != '' ? ($capaian->realisasi_tw2 > 100 ? 100 . '%' : $capaian->realisasi_tw2 . '%') : '';
            $capaian->realisasi_tw3 = $capaian->realisasi_tw3 != '' ? ($capaian->realisasi_tw3 > 100 ? 100 . '%' : $capaian->realisasi_tw3 . '%') : '';
            $capaian->realisasi_tw4 = $capaian->realisasi_tw4 != '' ? ($capaian->realisasi_tw4 > 100 ? 100 . '%' : $capaian->realisasi_tw4 . '%') : '';
            $capaian->output_tw1 = $capaian->output_tw1 != '' ? ($capaian->output_tw1 > 100 ? 100 . '%' : $capaian->output_tw1 . '%') : '';
            $capaian->output_tw2 = $capaian->output_tw2 != '' ? ($capaian->output_tw2 > 100 ? 100 . '%' : $capaian->output_tw2 . '%') : '';
            $capaian->output_tw3 = $capaian->output_tw3 != '' ? ($capaian->output_tw3 > 100 ? 100 . '%' : $capaian->output_tw3 . '%') : '';
            $capaian->output_tw4 = $capaian->output_tw4 != '' ? ($capaian->output_tw4 > 100 ? 100 . '%' : $capaian->output_tw4 . '%') : '';
            $capaian->output_total = $capaian->output_total != '' ? ($capaian->output_total > 100 ? 100 . '%' : $capaian->output_total . '%') : '';
        }
        
        return view('webdashboard.rb-general-capaianoutput', compact('capaians', 'years', 'selectedYear'));
    }

}
