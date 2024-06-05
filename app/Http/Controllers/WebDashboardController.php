<?php

namespace App\Http\Controllers;
use App\Exports\ExportGeneralRencanaAksiTemplate;
use App\Imports\ImportGeneralRencanaAksi;
use App\Models\GeneralPerencanaan;
use App\Models\GeneralPerencanaanTarget;
use App\Models\GeneralPerencanaanTargetDokumen;
use App\Models\GeneralRencanaAksi;
use App\Models\GeneralRencanaAksiOutput;
use App\Models\Indikator;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\ExportRBTematikTemplate;
use App\Imports\ImportRBTematik;
use App\Models\FokusIntervensi;
use App\Models\Tema;
use App\Models\TematikSasaranRoadmap;
use App\Models\TematikIndikatorRoadmap;
use App\Models\TematikIndikatorPermasalahan;
use App\Models\TematikRencanaAksi;
use App\Models\TematikRencanaAksiOutput;
use App\Models\TematikPermasalahan;
use Illuminate\Support\Facades\Gate;


class WebDashboardController extends Controller
{

    public function rbGeneral(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('webdashboard.rb-general', compact('instansis'));
        }
    }

public function rbTematik(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('webdashboard.rb-tematik', compact('instansis'));
        }
        
    }


public function hasilEvaluasi()
    {

        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('webdashboard.hasil-evaluasi', compact('instansis'));
        }

    }

    
}