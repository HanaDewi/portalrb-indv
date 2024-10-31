<?php
namespace App\Http\Controllers;

use App\Models\JawabanRenaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ERenaksiRBGeneralController extends Controller
{
    public function __construct(Request $request)
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
        $jawaban = JawabanRenaksi::where('tahun', $tahun)->get();
        return view('evaluasi.renaksi-rb-general', ['tahun'=>$tahun, 'isadmin'=>$isadmin, 'jawaban'=>$jawaban]);
    }

}
