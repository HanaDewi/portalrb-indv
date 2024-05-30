<?php


namespace App\Http\Controllers;

use App\Imports\ImportRBTematik;
use App\Models\Tema;
use App\Models\TematikSasaranRoadmap;
use App\Models\TematikIndikatorRoadmap;
use App\Models\TematikIndikatorPermasalahan;
use App\Models\TematikRencanaAksi;
use App\Models\TematikRencanaAksiOutput;
use App\Models\KlpdInstansi;
use App\Models\TematikPermasalahan;
use App\Models\FokusIntervensi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class RBTematikImportController extends Controller
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

    public function rbTematik_downloadTemplate()
    {
        //$template = new ExportRBTematikTemplate();
        //return Excel::download($template, 'template_rb_tematik.xlsx');

        return redirect('rb-tematik/perencanaan');
    }

    public function rbTematik_import(Request $request)
    {
        $user = Auth::User();
        $instansi_id = $user->user_rel->instansi_id;
        $success = true;
        $message = "";
        DB::beginTransaction();
        try {
            $headings = (new HeadingRowImport())->toArray($request->file('file_rbTematik'));
            
            if (
                $headings[0][0][0] == "tema" &&
                $headings[0][0][1] == "sasaran" &&
                $headings[0][0][2] == "indikator_roadmap" &&
                $headings[0][0][3] == "target" &&
                $headings[0][0][4] == "satuan_target"  &&
                $headings[0][0][5] == "realisasi"  &&
                $headings[0][0][6] == "capaian" &&
                $headings[0][0][7] == "catatan" &&
                $headings[0][0][8] == "permasalahan" &&
                $headings[0][0][9] == "sasaran_permasalahan" &&
                $headings[0][0][10] == "indikator_permasalahan" &&
                $headings[0][0][11] == "target_indikator_permasalahan" &&
                $headings[0][0][12] == "satuan_target_indikator_permasalahan" &&
                $headings[0][0][13] == "realisasi_indikator_permasalahan" &&
                $headings[0][0][14] == "capaian_indikator_permasalahan" &&
                $headings[0][0][15] == "catatan_indikator_permasalahan" &&
                $headings[0][0][16] == "rencana_aksi" &&
                $headings[0][0][17] == "indikator_output" &&
                $headings[0][0][18] == "satuan_output" &&
                $headings[0][0][19] == "target_tw1" &&
                $headings[0][0][20] == "target_tw2" &&
                $headings[0][0][21] == "target_tw3" &&
                $headings[0][0][22] == "target_tw4" &&
                $headings[0][0][23] == "target_total" &&
                $headings[0][0][24] == "anggaran" &&
                $headings[0][0][25] == "fokus_intervensi" &&
                $headings[0][0][26] == "koordinator" &&
                $headings[0][0][27] == "pelaksana" &&
                $headings[0][0][28] == "realisasi_tw1" &&
                $headings[0][0][29] == "realisasi_tw2" &&
                $headings[0][0][30] == "realisasi_tw3" &&
                $headings[0][0][31] == "realisasi_tw4" &&
                $headings[0][0][32] == "realisasi_total" &&
                $headings[0][0][33] == "realisasi_anggaran" &&
                $headings[0][0][34] == "catatan_monev"
            ) {
                $collections = Excel::toCollection(new ImportRBTematik, $request->file('file_rbTematik'))[0];
                $message = '';
                $baris = 2;
                foreach ($collections as $key => $collection) {
                    if ($key != '') {
                        $tema = Tema::where('nama',$collection["tema"] )->first();
                        if(!$tema){
                            $success = false;
                            $message = "Tema : " . $collection["tema"] . "Tidak sesuai dengan tema yang ada di sistem, harap menggunakan template yang disediakan ( Baris "  . $baris  . ")";
                        }else{
                            //cek apakah cell di excelnya kosong
                            if(str_replace(' ', '', $collection["sasaran"])){
                                $sasaranRoadmap = TematikSasaranRoadmap::where('instansi_id', $instansi_id)->where('tema_id',$tema->id)->where("nama", $collection["sasaran"] )->first();
                                if (!$sasaranRoadmap) {
                                    $sasaranRoadmap = new TematikSasaranRoadmap();
                                }
                                $sasaranRoadmap->tema_id = $tema->id;
                                $sasaranRoadmap->instansi_id = $instansi_id;
                                $sasaranRoadmap->nama = $collection["sasaran"];
                                if (!$sasaranRoadmap->save()) {
                                    $success = false;
                                    $message = "Sasaran Tidak Bisa disimpan, mohon di cek kembali ( Baris "  . $baris  . " )";
                                }else{
                                    //cek apakah cell di excelnya kosong
                                    if(str_replace(' ', '', $collection["indikator_roadmap"])){
                                        if($collection["target"]!=Null ){
                                            $indikatorRoadmap = TematikIndikatorRoadmap::where('tematik_sasaran_roadmap_id', $sasaranRoadmap->id)->where('nama', $collection["indikator_roadmap"])->first();
                                            if (!$indikatorRoadmap) {
                                                $indikatorRoadmap = new TematikIndikatorRoadmap();
                                            }
                                            $indikatorRoadmap->tematik_sasaran_roadmap_id = $sasaranRoadmap->id;
                                            $indikatorRoadmap->nama = $collection["indikator_roadmap"];
                                            $indikatorRoadmap->target = $collection["target"];
                                            $indikatorRoadmap->satuan = $collection["satuan_target"];
                                            $indikatorRoadmap->realisasi_indikator = $collection["realisasi"];
                                            $indikatorRoadmap->capaian_indikator = $collection["capaian"];
                                            $indikatorRoadmap->catatan = $collection["catatan"];
                                            if (!$indikatorRoadmap->save()) {
                                                $success = false;
                                            }else{
                                                if(str_replace(' ', '', $collection["permasalahan"])){
                                                    $permasalahan = TematikPermasalahan::where('tematik_indikator_roadmap_id', $indikatorRoadmap->id)->where('nama', $collection["permasalahan"])->first();
                                                    if (!$permasalahan) {
                                                        $permasalahan = new TematikPermasalahan();
                                                    }
                                                    $permasalahan->tematik_indikator_roadmap_id	= $indikatorRoadmap->id;
                                                    $permasalahan->nama = $collection["permasalahan"];
                                                    $permasalahan->sasaran_permasalahan = $collection["sasaran_permasalahan"];
                                                    if (!$permasalahan->save()) {
                                                        $success = false;
                                                    }else{
                                                        if(str_replace(' ', '', $collection["indikator_permasalahan"]) && str_replace(' ', '', $collection["target_indikator_permasalahan"])){
                                                            $indikatorPermasalahan = TematikIndikatorPermasalahan::where('tematik_permasalahan_id', $permasalahan->id)->where('nama', $collection["indikator_permasalahan"])->first();
                                                            if (!$indikatorPermasalahan) {
                                                                $indikatorPermasalahan = new TematikIndikatorPermasalahan();
                                                            }
                                                            $indikatorPermasalahan->tematik_permasalahan_id = $permasalahan->id;
                                                            $indikatorPermasalahan->nama = $collection["indikator_permasalahan"];
                                                            $indikatorPermasalahan->target = $collection["target_indikator_permasalahan"];
                                                            $indikatorPermasalahan->satuan = $collection["satuan_target_indikator_permasalahan"];
                                                            $indikatorPermasalahan->realisasi_indikator = $collection["realisasi_indikator_permasalahan"];
                                                            $indikatorPermasalahan->capaian_indikator = $collection["capaian_indikator_permasalahan"];
                                                            $indikatorPermasalahan->catatan = $collection["catatan_indikator_permasalahan"];
                                                            if (!$indikatorPermasalahan->save()) {
                                                                $success = false;
                                                            }else{
                                                                if(str_replace(' ', '', $collection["rencana_aksi"]) && str_replace(' ', '', $collection["indikator_output"])&& str_replace(' ', '', $collection["satuan_output"])){
                                                                    $rencanaAksi = TematikRencanaAksi::where('tematik_indikator_permasalahan_id', $indikatorPermasalahan->id)->where('nama', $collection["rencana_aksi"])->first();
                                                                    if (!$rencanaAksi) {
                                                                        $rencanaAksi = new TematikRencanaAksi();
                                                                    }
                                                                    $rencanaAksi->tematik_indikator_permasalahan_id = $indikatorPermasalahan->id ;
                                                                    $rencanaAksi->nama = $collection["rencana_aksi"];
                                                                    if (!$rencanaAksi->save()) {
                                                                        $success = false;
                                                                    }else{
                                                                        $fokus_intervensi = FokusIntervensi::where("nama", $collection["fokus_intervensi"])->first();
                                                                        if(!$fokus_intervensi){
                                                                            $success = false;
                                                                        }else{
                                                                            $rencanaAksiOutput = TematikRencanaAksiOutput::where('tematik_rencana_aksi_id', $rencanaAksi->id)->where('indikator_output', $collection["indikator_output"])->first();
                                                                            
                                                                            if (!$rencanaAksiOutput) {
                                                                                $rencanaAksiOutput = new TematikRencanaAksiOutput();
                                                                            }
                                                                            $target_tw1 = str_replace(',', '.', str_replace('.', '', $collection["target_tw1"])); 
                                                                            $target_tw2 = str_replace(',', '.', str_replace('.', '', $collection["target_tw2"]));
                                                                            $target_tw3 = str_replace(',', '.', str_replace('.', '', $collection['target_tw3']));
                                                                            $target_tw4 = str_replace(',', '.', str_replace('.', '', $collection['target_tw4']));
                                                                            $target_total = str_replace(',', '.', str_replace('.', '', $collection['target_total']));
                                                                            $realisasi_tw1 = str_replace(',', '.', str_replace('.', '', $collection["realisasi_tw1"])); 
                                                                            $realisasi_tw2 = str_replace(',', '.', str_replace('.', '', $collection["realisasi_tw2"])); 
                                                                            $realisasi_tw3 = str_replace(',', '.', str_replace('.', '', $collection["realisasi_tw3"])); 
                                                                            $realisasi_tw4 = str_replace(',', '.', str_replace('.', '', $collection["realisasi_tw4"])); 
                                                                            $realisasi_total = str_replace(',', '.', str_replace('.', '', $collection["realisasi_total"])); 
                                                                            $anggaran_total = str_replace(',', '.', str_replace('.', '', $collection["anggaran"]));
                                                                            $realisasi_anggaran = str_replace(',', '.', str_replace('.', '', $collection["realisasi_anggaran"]));
                                                                            $rencanaAksiOutput->tematik_rencana_aksi_id = $rencanaAksi->id;
                                                                            $rencanaAksiOutput->satuan_output = $collection["satuan_output"];
                                                                            $rencanaAksiOutput->indikator_output = $collection["indikator_output"];
                                                                            $rencanaAksiOutput->target_tw1 = $target_tw1;
                                                                            $rencanaAksiOutput->target_tw2 = $target_tw2;
                                                                            $rencanaAksiOutput->target_tw3 = $target_tw3;
                                                                            $rencanaAksiOutput->target_tw4 = $target_tw4;
                                                                            $rencanaAksiOutput->target_total = $target_total;
                                                                            $rencanaAksiOutput->anggaran_total = $anggaran_total;
                                                                            $rencanaAksiOutput->pelaksana = $collection["pelaksana"];
                                                                            $rencanaAksiOutput->koordinator = $collection["koordinator"];
                                                                            $rencanaAksiOutput->realisasi_output_tw1 = $realisasi_tw1;
                                                                            $rencanaAksiOutput->realisasi_output_tw2 = $realisasi_tw2;
                                                                            $rencanaAksiOutput->realisasi_output_tw3 = $realisasi_tw3;
                                                                            $rencanaAksiOutput->realisasi_output_tw4 = $realisasi_tw4;
                                                                            $rencanaAksiOutput->realisasi_output_total = $realisasi_total;
                                                                            $rencanaAksiOutput->realisasi_anggaran_total = $realisasi_anggaran;
                                                                            $rencanaAksiOutput->catatan = $collection["catatan_monev"];

                                                                            #hitung capaian
                                                                            $rencanaAksiOutput->capaian_output_tw1 = $realisasi_tw1*100/$target_tw1;
                                                                            $rencanaAksiOutput->capaian_output_tw2 = $realisasi_tw2*100/$target_tw2;
                                                                            $rencanaAksiOutput->capaian_output_tw3 = $realisasi_tw3*100/$target_tw3;
                                                                            $rencanaAksiOutput->capaian_output_tw4 = $realisasi_total*100/$target_tw4;;
                                                                            $rencanaAksiOutput->capaian_output_total = $realisasi_total*100/$target_total;
                                                                            $rencanaAksiOutput->capaian_anggaran_total =  $realisasi_anggaran*100/$anggaran_total;
                                                                            $rencanaAksiOutput->fokus_intervensi= $fokus_intervensi->id;
                                                                            if (!$rencanaAksiOutput->save()) {
                                                                                $success = false;
                                                                            }
                                                                        }
                                                                    }
                                                                }else{
                                                                    $success = false;
                                                                    $message = "Jika Anda mengisi rencana aksi maka indikator output dan seterusnya harus diisi dan tidak boleh kosong ( Baris "  . $baris  . " )";
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }else{
                                            $success = false;
                                            $message = "Jika Anda mengisi indikator Roadmap maka target tidak boleh kosong ( Baris "  . $baris  . " )";
                                            break;
                                        }
                                    }
                                }
                            }else{
                                $success = false;
                                $message = "Sasarannya tidak boleh kosong ( Baris "  . $baris  . " )";
                                break;
                            }
                        }
                    } else {
                        $success = false;
                        session()->flash('error', $message);
                    }
                    $baris++;
                }
            }
        } catch (\Throwable $th) {
            $success = false;
            throw $th;
        }
        
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data RB Tematik Rencana Aksi berhasil diimport!');
        } else {
            DB::rollBack();
            session()->flash('error', $message);
        }

        return redirect('rencana_aksi/rb-tematik/rekap_data');
    }
}
