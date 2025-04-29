<?php

namespace App\Http\Controllers;

use App\Models\LkeTestTp;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\LkeTestTpLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LkeTestTpFile;
use App\Models\OpenAccessSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class HasilController extends Controller
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

    public function hasil_seluruh()
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl', 'tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('hasil.hasil_semua', compact('instansis'));
        } else {
            return redirect('evaluasi/hasil-2023/'.$user->instansi_id);
        }
    }

    public function hasil($instansi_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl', 'tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        if (!in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            if ($user->instansi_id != $instansi_id) {
                abort(403);
            }
        }
        $instansi = KlpdInstansi::find($instansi_id);
        $lkeTestTP = LkeTestTp::where("lke_instansi_id", $instansi_id)->first();
        if (!$lkeTestTP) {
            abort('404');
        }
        $lkeTestTPLine = "";
        if (isset($lkeTestTP)) {
            $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
            if ($user->penilai_id && $user->level == 'tpm') {
                $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->where('penilai_id', $user->penilai_id)->get();
            }
        }
        return view('hasil.hasil', compact('instansi', 'lkeTestTPLine'));
    }

    public function get_test_tp_line($id)
    {
        $tp_line = LkeTestTpLine::find($id);
        $tp_line->min = $tp_line->pertanyaan->min_value;
        $tp_line->max = $tp_line->pertanyaan->max_value;
        return response()->json($tp_line);
    }

    public function get_test_tp($id)
    {
        $tp = LkeTestTp::find($id);
        $tp->berkas_list = '';
        foreach ($tp->files as $berkas) {
            $ext = pathinfo($berkas->file, PATHINFO_EXTENSION);
            $src = exts(strtolower($ext));
            $tp->berkas_list .= '<div class="col-span-12 lg:col-span-4" id="berkasdiv'.$berkas->id.'" style="position:relative;">
                <div style="height: 100px;">
                    <img class="img-fluid card-img-top" src="'.asset('images').'/'.$src.'" alt="Berkas'.$berkas->id.'" style="max-height: 100px; max-width:100%; padding: 5px 0;">
                </div>
                <div class="form-group mb-0">
                    <input type="text" name="deskripsi_existing['.$berkas->id.']" class="form-control" id="deskripsi'.$berkas->id.'" placeholder="Deskripsi" value="'.$berkas->deskripsi.'">
                    <input type="hidden" name="berkas_existing['.$berkas->id.']" value="'.$berkas->file.'">
                </div>
                <a href="javascript:void(0);" onclick="removeBerkas('.$berkas->id.')" class="remove-button text-danger">
                    <div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </div>
                </a>
            </div>';
        }
        return response()->json($tp);
    }

    public function simpan_test_tp_line(Request $request)
    {
        DB::beginTransaction();
        $success = false;
        try {
            $tp_line = LkeTestTpLine::find($request->test_tp_line_id);
            if ($request->score) {
                $tp_line->score = $request->score;
            }
            $tp_line->note = $request->note;
            $tp_line->todo = $request->todo;
            $score_index = ($tp_line->score/$tp_line->pertanyaan->max_value) * $tp_line->pertanyaan->weight;
            if ($tp_line->pertanyaan->indikator_pengali_id) {
                $tp_line_pengali = LkeTestTpLine::where('test_tp_id', $tp_line->test_tp_id)->where('test_line', $tp_line->pertanyaan->indikator_pengali_id)->first();
                $score_index = $score_index * ($tp_line_pengali->score_index/$tp_line_pengali->pertanyaan->weight);
            }
            $tp_line->score_index = round($score_index, 2);
            if ($tp_line->save()) {
                $success = $this->hitung_score_index($tp_line->test_tp_id);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data Hasil berhasil disimpan.');
        } else {
            DB::rollBack();
            session()->flash('error', 'Data Hasil gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('hasil/'.$tp_line->lke_test_tp->lke_instansi_id);
    }

    public function simpan_test_tp(Request $request)
    {
        DB::beginTransaction();
        $success = false;
        try {
            $tp = LkeTestTp::find($request->test_tp_id);
            $tp->bobot_rb_general_penyesuaian = $request->bobot_rb_general_penyesuaian;
            if ($tp->save()) {
                $success = $this->hitung_score_index($tp->id);
                foreach ($tp->files as $berkas) {
                    if (!isset($request->berkas_existing[$berkas->id])) {
                        Storage::disk('public')->delete('berkas/' . $berkas->file);
                        $berkas->delete();
                    } else {
                        $berkas->deskripsi = $request->deskripsi_existing[$berkas->id];
                        $berkas->save();
                    }
                }
                if ($request->hasFile('berkas')) {
                    foreach ($request->file('berkas') as $key => $file_berkas) {
                        $berkas = new LkeTestTpFile();
                        $berkas->test_tp_id = $tp->id;
                        $berkas->deskripsi = $request->deskripsi[$key];
                        $time = time();
                        $filename = $berkas->deskripsi."_$time." . $file_berkas->getClientOriginalExtension();
                        $file_berkas->storeAs('berkas', $filename, 'public');
                        $berkas->file = $filename;
                        if ($berkas->save()) {
                            $success = true;
                        } else {
                            $success = false;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data Test TP berhasil disimpan.');
        } else {
            DB::rollBack();
            session()->flash('error', 'Data Test TP gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('hasil/'.$tp->lke_instansi_id);
    }

    protected function hitung_score_index($test_tp_id)
    {
        $tp = LkeTestTp::find($test_tp_id);
        $rb_general = LkeTestTpLine::where('test_tp_id', $test_tp_id)->whereIn('lke_param_l0', [2124, 2213, 2352])->sum('score_index');
        $rb_tematik = LkeTestTpLine::where('test_tp_id', $test_tp_id)->whereIn('lke_param_l0', [2126, 2217, 2353])->sum('score_index');
        $index_rb = LkeTestTpLine::where('test_tp_id', $test_tp_id)->sum('score_index');
        $rb_general_penyesuaian = $rb_general/$tp->bobot_rb_general_penyesuaian * 100;
        $tp->rb_general = $rb_general;
        $tp->rb_tematik = $rb_tematik;
        $tp->index_rb = $index_rb;
        $tp->rb_general_penyesuaian = $rb_general_penyesuaian;
        $tp->index_rb_penyesuaian = $rb_tematik + $rb_general_penyesuaian;
        if ($tp->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function access()
    {
        $access = OpenAccessSetting::all();
        return view('access', compact('access'));
    }

    public function access_simpan(Request $request)
    {
        $access = OpenAccessSetting::where('user_level', $request->user_level)->where('fitur', $request->fitur)->first();
        if (!$access) {
            $access = new OpenAccessSetting();
        }
        $access->user_level = $request->user_level;
        $access->fitur = $request->fitur;
        $access->waktu_awal = $request->waktu_awal;
        $access->waktu_akhir = $request->waktu_akhir;
        if ($access->save()) {
            session()->flash('success', 'Data Akses berhasil diperbaharui.');
        } else {
            session()->flash('error', 'Data Akses gagal diperbaharui.');
        }
        return redirect('access');
    }

    public function activitylog()
    {
        return view('activitylog');
    }

    public function activitylog_getData()
    {
        $activities = Activity::latest()->get();
        foreach ($activities as $activity) {
            $activity->pretty = '<pre>'.json_encode(json_decode($activity->properties), JSON_PRETTY_PRINT).'</pre>';
            $activity->pelaku = ($activity->causer)->nama.' ('.($activity->causer)->level.')';
            $activity->pada = Carbon::parse($activity->created_at)->diffForHumans().' pada '.Carbon::parse($activity->created_at)->isoFormat('dddd, D MMMM Y HH:mm');
            if ($activity->subject_type == 'App\Models\LkeTestTp') {
                $activity->instansi = $activity->subject->klpd_instansi->name;
            } else if ($activity->subject_type == 'App\Models\LkeTestTpFile') {
                $activitynya = json_decode($activity->properties);
                if ($activity->event == 'deleted') {
                    $test_tp_id = $activitynya->old->test_tp_id;
                } else {
                    $test_tp_id = $activitynya->attributes->test_tp_id;
                }
                $test_tp = LkeTestTp::find($test_tp_id);
                $activity->instansi = $test_tp->klpd_instansi->name;
            } else {
                $activity->instansi = $activity->subject->lke_test_tp->klpd_instansi->name;
            }
        }
        return response()->json(['data' => $activities]);
    }


    public function generate_simple()
    {
        $user = Auth::User();
        // if (in_array($user->level, ['kabupaten', 'provinsi', 'kl', 'tpn', 'tpm'])) {
        //     $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
        //     if ($access) {
        //         $today = date('Y-m-d');
        //         if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
        //             return view('belumbuka');
        //         }
        //     }
        // }
        // if (!in_array($user->level, ['admin', 'tpn', 'tpm'])) {
        //     if ($user->instansi_id != $instansi_id) {
        //         abort(403);
        //     }
        // }

        $instansis = KlpdInstansi::get();

        $indeks_penting = array(
            "Indeks BerAkhlak"                                                  => 1,
            "Indeks Kualitas Kebijakan"                                         => 2,
            "Indeks Pelayanan Publik"                                           => 3,
            "Indeks Pengelolaan Aset"                                           => 4,
            "Indeks Perencanaan Pembangunan"                                    => 5,
            "Indeks Reformasi Hukum"                                            => 6,
            "Indeks Sistem Merit"                                               => 7,
            "Indeks Sistem Pemerintahan Berbasis Elektronik (SPBE)"             => 8,
            "Indeks SPBE"                                                       => 8,
            "Indeks Tata Kelola Pengadaan"                                      => 9,
            "Indikator Kinerja Pelaksanaan Anggaran"                            => 10,
            "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah"            => 11,
            "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)"    => 11,
            "Nilai Sitem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)"     => 11,
            "Opini BPK"                                                         => 12,
            "Persentase Penyderhanaan Struktur Organisasi"                      => 13,
            "Persentase Penyederhanaan Struktur Organisasi"                     => 13,
            "Survei Kepuasan Masyarakat"                                        => 14,
            "Survei Penilaian Integritas"                                       => 15,
            "Tindak Lanjut Rekomendasi"                                         => 16,
            "Tingkat Capaian Sistem Kerja untuk Penyderhanaan Birokrasi"        => 17,
            "Tingkat Digitalisasi Arsip"                                        => 18,
            "Tingkat Implementasi Kebijakan Arsitektur Sistem Pemerintahan Berbasis Elektronik"             =>19,
            "Tingkat Implementasi Kebijakan Arsitektur Sistem Pemerintahan Berbasis Elektronik (SPBE)"      =>19,
            "Tingkat Keberhasilan Pembangunan Zona Integritas"                  => 20,
            "Tingkat Kematangan Penyelenggaraan Statistik Sektoral"             => 21,
            "Tingkat Kepatuhan Standar Pelayanan Publik"                        => 22,
            "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah"           => 23,
            "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah (SPIP)"    => 23,
            "Tingkat Tindak Lanjut Pengaduan Masyarakat (LAPOR) yang Sudah Diselesaikan"                    =>24,
            "Rencana Aksi Pembangunan RB General"                               => 25,
            "TIngkat Implementasi Rencana Aksi RB General"                      => 26,
            "Tingkat Implementasi Rencana Aksi Pembangunan RB General"          => 26,
            "Tingkat Capaian Sistem Kerja untuk Penyederhanaan Birokrasi"       => 27,
            "Capaian Prioritas Nasional"                                        => 28,
            "Capaian IKU"                                                       => 29,
            "Capaian IKU Non Makro"                                             => 29,
            "Capaian Indikator Kinerja Non Makro"                               => 29,
            "Capaian Indikator Kinerja Utama Makro"                             => 30,
            "Capaian IKU Makro"                                                 => 30,
            "Net Koefisien"                                                     => 31,
            "Koefisien"                                                         => 31,
            "Pengentasan Kemiskinan (Strategi Pembangunan)"                     => 32,
            "Pengentasan Kemiskinan (Rencana Aksi)"                             => 33,
            "Pengentasan Kemiskinan (Capaian Output)"                           => 34,
            "Pengentasan Kemiskinan (Capaian Dampak)"                           => 35,
            "Penurunan Tingkat Kemiskinan (Capaian Dampak)"                     => 35,
            "Realisasi Investasi (Strategi Pembangunan)"                        => 36,
            "Realisasi Investasi (Rencana Aksi)"                                => 37,
            "Realisasi Investasi (Rencana Aksi"                                 => 37,
            "Realisasi Investasi (Capaian Output)"                              => 38,
            "Realisasi Investasi (Capaian Dampak)"                              => 39,
            "Peningkatan Realisasi Investasi (Capaian Dampak)"                  => 39,
            "Digitalisasi Administrasi Pemerintahan Berfokus pada Penanganan Stunting (Strategi Pembangunan)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Rencana Aksi)"                 => 41,  
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Capaian Output)"               => 42,
            "Digitalisasi Administrasi Pemerintahan Berfokus Penanganan Stunting (Capaian Dampak)"            => 43,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Capaian Dampak)"               => 43,
            "Penggunaan Produk Dalam Negeri (Strategi Pembangunan)"             => 44,
            "Penggunaan Produk Dalam Negeri (Rencana Aksi)"                     => 45,
            "Penggunaan Produk Dalam Negeri (Capaian Output)"                   => 46,
            "Penggunaan Produk Dalam Negeri (Capaian Dampak)"                   => 47,
            "Tingkat Penggunaan Produk Dalam Negeri (Capaian Dampak)"           => 47, 
            "Laju Inflasi (Strategi Pembangunan)"                               => 48,
            "Laju Inflasi (Rencana Aksi)"                                       => 49,
            "Pengendalian Inflasi (Rencana Aksi)"                               => 49,
            "Laju Inflasi (Capaian Output)"                                     => 50, 
            "Pengendalian Inflasi (Capaian Output)"                             => 50, 
            "Laju Inflasi (Capaian Dampak)"                                     => 51,
            "Pengendalian Inflasi (Capaian Dampak)"                             => 51,
            "Tingkat Inflasi (Capaian Dampak)"                                  => 51,
            "Tindak Lanjut Rekomendasi BPK"                                     => 52,
        );

        //pritn kategori
        /*
        $var = "";
        foreach($indeks_penting as $key => $ind){
            if($var != $ind ){
                echo $ind. ", ". $key ."<br/>";
                $var = $ind;
            }
        }
        die();
        */
        
        echo '<table>';
        foreach ($instansis as $instansi) {
            if($instansi->mapping_kode_instansi){
                $instansi_code_lama =  $instansi->mapping_kode_instansi->old_klpd_code;
            }else{
                $instansi_code_lama = "-";
            }
            $instansi_id  = $instansi->id;
            $lkeTestTP = LkeTestTp::where("lke_instansi_id", $instansi_id)->first();
            $lkeTestTPLine = "";
            if (isset($lkeTestTP)) {
                $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
                if ($user->penilai_id && $user->level == 'tpm') {
                    $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->where('penilai_id', $user->penilai_id)->get();
                }
            }
            if ($lkeTestTPLine){
                foreach ($lkeTestTPLine as $testTPLine){
                    if(array_key_exists($testTPLine->paramL4->name, $indeks_penting)){
                        $indeks_id = $indeks_penting[$testTPLine->paramL4->name] ;
                    }else{
                        $indeks_id = $testTPLine->paramL4->name;
                    }
                    //if (in_array($testTPLine->paramL4->name, $indeks_penting)){
                        echo '<tr>';
                            echo '<td>'. $instansi_code_lama. '</td>';
                            echo '<td> 2023 </td>';
                            //echo '<td>'. $instansi->name. '</td>';
                            echo '<td>'. $testTPLine->paramL0->name. '</td>';
                            echo '<td>'. $indeks_id .'</td>';
                            echo '<td>'. $testTPLine->score . '</td>';
                        echo '</tr>';
                    //}
                }
            }
        }
        echo '</table>';
        
    }

  
}
