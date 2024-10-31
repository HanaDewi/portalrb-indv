<?php
namespace App\Http\Controllers;

use App\Models\LKERenaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataLKERenaksiController extends Controller
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

    public function index(Request $request)
    {
        $user = Auth::User();
        $isadmin = in_array($user->level, ['admin']);
        $tahun = $request->input('tahun');
        $tahun = empty($tahun) ? date('Y'):$request->input('tahun');
        $datalke = LKERenaksi::where('tahun', $tahun)->get();
        return view('evaluasi.data-lke-renaksi', ['tahun'=>$tahun, 'datalke'=>$datalke, 'isadmin'=>$isadmin]);
    }

}
