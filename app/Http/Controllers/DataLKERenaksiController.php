<?php
namespace App\Http\Controllers;

use App\Models\LKERenaksi;
use DB;
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


    public function dosave(Request $request)
    {
        $success = true;
        DB::beginTransaction();
        try {
            $tosave = new LKERenaksi();
            if (isset($request->lke_renaksi_id)) {
                $tosave = LKERenaksi::find($request->lke_renaksi_id);
            }
            $tosave->kriteria = $request->kriteria;
            $tosave->parent_id = $request->parent_id;
            $tosave->info = $request->info;
            $tosave->tahun = $request->tahun;
            if (!$tosave->save()) {
                $success = false;
            }
        } catch (\Throwable $th) {
            $success = false;
            throw $th;
        }
        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        return redirect('/master-data/data-lke-renaksi?tahun=' . $request->tahun);
    }

    public function dodelete(Request $request)
    {
        $check = LKERenaksi::where('parent_id', '=', $request->id)->first();
        if ($check!=NULL) {
            return response()->json(['success' => 'Gagal', 'result' => false]);
        }
        $todelete = LKERenaksi::find($request->id);
        if ($todelete->delete()) {
            return response()->json(['success' => 'Sukses', 'result' => true]);
        } else {
            return response()->json(['success' => 'Gagal', 'result' => false]);
        }
    }
}
