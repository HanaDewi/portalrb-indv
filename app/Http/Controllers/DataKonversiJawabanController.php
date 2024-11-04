<?php
namespace App\Http\Controllers;

use App\Models\KonversiJawabanRenaksi;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataKonversiJawabanController extends Controller
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
        $data = KonversiJawabanRenaksi::where('tahun', $tahun)->get();
        return view('evaluasi.data-konversi-jawaban', ['tahun'=>$tahun, 'data'=>$data, 'isadmin'=>$isadmin]);
    }

    public function dosave(Request $request)
    {
        $success = true;
        DB::beginTransaction();
        try {
            $tosave = new KonversiJawabanRenaksi();
            if (isset($request->konversi_jawaban_id)) {
                $tosave = KonversiJawabanRenaksi::find($request->konversi_jawaban_id);
            }
            $tosave->jawaban = $request->jawaban;
            $tosave->skor = $request->skor;
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
        // return response()->json(['success' => $success]);
        return redirect('/evaluasi/data-konversi-jawaban');
    }

    public function dodelete(Request $request)
    {
        $todelete = KonversiJawabanRenaksi::find($request->id);
        if ($todelete->delete()) {
            // return true;
        } else {
            // return false;
        }
        return redirect('/evaluasi/data-konversi-jawaban');
    }

}
