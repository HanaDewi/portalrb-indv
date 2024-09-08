<?php
namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TematikSasaranRoadmap;

class CapaianOutputController extends Controller
{

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

}