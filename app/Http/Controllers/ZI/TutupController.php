<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\Panel;
use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\TimEvaluasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;

class TutupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::User()->level =="admin" || Auth::User()->level =="tpn"){
                    return $next($request);     
            }
            abort('403');
        });
    }
    public function index(Request $request){
        abort('403');
    }


   
    
}
