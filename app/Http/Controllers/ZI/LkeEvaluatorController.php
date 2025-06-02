<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\Unit;

class LkeEvaluatorController extends Controller
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
        $tahun = $request->tahun ?? date('Y');
        $title = "LKE Evaluator";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $unit_ZIs = UnitZI::whereHas('instansiZI', function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })->orderBy('instansi_zi_id', 'ASC')->get();
            $wbbm_count = $unit_ZIs->where('wbbm', 1)->count();
            $wbk_all_count = $unit_ZIs->where('wbk', 1)->count();
            $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) use ($tahun) {
                $query->where('instansi_wbk_mandiri', 1)->where('tahun', $tahun);
            })->where('wbk', 1)->count();
            $wbk_non_mandiri_count = $wbk_all_count - $wbk_mandiri_count;
            $total_unit = $wbk_all_count + $wbbm_count;

            return view('zi.lke_evaluator', compact(
                "title",
                "unit_ZIs",
                "wbbm_count",
                "wbk_mandiri_count",
                "wbk_non_mandiri_count",
                'total_unit'
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function template_lke()
    {
        $title = "Template LKE Evaluator";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {

            return view('zi.template_lke_evaluator', compact(
                "title"
            ));
        } else {
            return (URL::to('/'));
        }
    }

    public function lke_evaluator_update(Request $request)
    {
        $success = false;
        $unitZi = UnitZI::find($request->unit_id);
        $unitZi->lke_evaluator = $request->link_lke_evaluator;
        if ($unitZi->save()) {
            $success = true;
        };
        return redirect()->route('lke_evaluator');
    }

    public function download_template_lke()
    {
        $tahun = date('Y');
        // Fetch all units with their associated instansi
        $units = UnitZI::with('instansiZI')
            ->whereHas('instansiZI', function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })->get();
        // Path to the original file
        //$originalFilePath = storage_path('app/public/template-zi/LKEZI2024.xlsx');
        $originalFilePath = storage_path('app/public/template-zi/Template_LKE_2025.xlsx');

        // Temporary directory to store copied files
        $tempDir = storage_path('app/temp-files/');

        // Ensure the temp directory exists
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // Copy the file for each unit, grouped by instansi
        foreach ($units as $unit) {
            if ($unit->seleksi_administrasi_unit) {
                if ($unit->seleksi_administrasi_unit->status_final == 1 || $unit->sanggah_unit->status_final == 1) {
                    $instansiName = $unit->instansiZI->klpd_instansi->name;
                    $instansiName = str_replace("/", "-", $instansiName);
                    $instansiDir = $tempDir . $instansiName . '/';

                    // Ensure the instansi directory exists
                    if (!File::exists($instansiDir)) {
                        File::makeDirectory($instansiDir, 0755, true);
                    }
                    $unit_nama = str_replace('/', "-", $unit->nama);
                    $newFileName = $unit_nama . '-' . $unit->id . '.xlsx'; // Modify as needed
                    $newFilePath = $instansiDir . $newFileName;
                    File::copy($originalFilePath, $newFilePath);
                }
            }
        }

        // Create a zip file containing all the grouped files
        $zipFileName = 'instansi-files.zip';
        $zipDir = storage_path('app/public/');

        if (!File::exists($zipDir)) {
            File::makeDirectory($zipDir, 0755, true);
        }

        $zipFilePath = $zipDir . $zipFileName;

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            $folders = File::directories($tempDir);
            foreach ($folders as $folder) {
                $files = File::files($folder);
                foreach ($files as $file) {
                    $relativeName = basename($folder) . '/' . basename($file);
                    $zip->addFile($file, $relativeName);
                }
            }
            $zip->close();
        }

        // Delete the temp files and directory
        File::deleteDirectory($tempDir);

        // Download the zip file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
