<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class PengusulanZIController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::User()) {
                return $next($request);
            }
            abort('403');
        });
    }


    public function index(Request $request)
    {
        $tahun = 2025;
        $date_now = new \DateTime();
        $date_buka_zi    = new \DateTime("2025/04/01");
        $date_tutup_zi    = new \DateTime("2025/06/01");
        if ($date_now >= $date_buka_zi) {
            $instansi_id = $request->get("instansi_id");
            if ($instansi_id && (Auth::User()->level == "admin" || Auth::User()->level == "tpn")) {
                $instansi_obj = KlpdInstansi::find($instansi_id);
                $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
                if ($instansiZI) {
                    if ($instansiZI->final) {
                        return redirect('zi-tinjau?instansi_id=' . $instansi_id);
                    }
                }
            } elseif (Auth::User()->level == "tpn" || Auth::User()->level == "admin") {
                $instansi_obj = KlpdInstansi::find(2); #jangan di delete ini untuk pengujian pengusulan via admin
                $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
                #return redirect()->route('dashboard_zi');
            } else {
                if ($date_now >= $date_tutup_zi) {
                    return redirect('zi-tinjau?instansi_id=' . Auth::User()->user_rel->instansi->id);
                }
                $instansi_obj = Auth::User()->user_rel->instansi;
                $instansi_id = $instansi_obj->id;
            }
            (Auth::User()->level == "admin" || Auth::User()->level == "tpn") ? $instansis = KlpdInstansi::get() : $instansis = "";

            $instansi = $instansi_obj->name;
            $group_kld = $instansi_obj->group;
            $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
            //butuh biar user gak balik lagi ke halaman pengusulan kalau sudah nyimpen
            if ($instansiZI->tahap_seleksi == 1) {
                return redirect('zi-tinjau?instansi_id=' . $instansi_id);
            }

            if ($instansiZI) {
                if ($instansiZI->final && !(Auth::User()->level == "admin" || Auth::User()->level == "tpn")) {
                    return redirect('zi-tinjau?instansi_id=' . $instansi_id);
                }
                $skor_opini_bpk = $instansiZI->skor_bpk;
                $skor_indeks_rb = $instansiZI->skor_indeks_rb;
                $skor_predikat_sakip = $instansiZI->skor_sakip;
                $skor_maturitas_spip = $instansiZI->skor_maturitas_spip;
                $opini_bpk = $instansiZI->opini_bpk;
                $indeks_rb = $instansiZI->indeks_rb;
                $predikat_sakip = $instansiZI->predikat_sakip;
                $maturitas_spip = $instansiZI->maturitas_spip;
                $syarat_bpk = $instansiZI->syarat_bpk;
                $syarat_sakip_wbk = $instansiZI->syarat_sakip_wbk;
                $syarat_sakip_wbbm = $instansiZI->syarat_sakip_wbbm;
                $syarat_indeksrb_wbk = $instansiZI->syarat_indeksrb_wbk;
                $syarat_indeksrb_wbbm = $instansiZI->syarat_indeksrb_wbbm;
                $syarat_maturitas_spip = $instansiZI->syarat_maturitas_spip;
                $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
                $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
                $keterangan = $instansiZI->keterangan;
                $status_akhir = $instansiZI->status_akhir;

                return view('zi.evaluatan.pengusulan', compact(
                    'instansis',
                    'instansi_id',
                    'instansi',
                    'instansiZI',
                    'group_kld',
                    'skor_opini_bpk',
                    'skor_indeks_rb',
                    'skor_predikat_sakip',
                    'skor_maturitas_spip',
                    'opini_bpk',
                    'indeks_rb',
                    'predikat_sakip',
                    'maturitas_spip',
                    'syarat_bpk',
                    'syarat_sakip_wbk',
                    'syarat_sakip_wbbm',
                    'syarat_indeksrb_wbk',
                    'syarat_indeksrb_wbbm',
                    'syarat_maturitas_spip',
                    'syarat_akhir_wbk',
                    'syarat_akhir_wbbm',
                    'keterangan',
                    'status_akhir'
                ));
            } else {
                echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
            }
        } else {
            return view('zi.zibelumbuka');
        }
    }
    public function store_bukti_dukung(Request $request)
    {
        $tahun = 2025;
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansi_id = $request->get("instansi_id");
        } else {
            $instansi_obj = Auth::User()->user_rel->instansi;
            $instansi_id = $instansi_obj->id;
        }

        $instansiZI = InstansiZI::where('instansi_id', $instansi_id)->where('tahun', 2025)->first();
        $instansiZI->tahap_seleksi = 1;
        $instansiZI->pic = $request->get("pic");
        $instansiZI->email = $request->get("email");
        $instansiZI->nomor_kontak = $request->get("nomor_kontak");
        $instansiZI->surat_usulan = 'http://' . preg_replace('#^.*://#', '', $request->get("surat_usulan")); #kalau gak ada http:// jadinya relatif link yang buka di halaman portalrb bukan di google drivenya
        $instansiZI->sptjm = 'http://' . preg_replace('#^.*://#', '', $request->get("sptjm"));
        $instansiZI->tlhp = 'http://' . preg_replace('#^.*://#', '', $request->get("tlhp"));
        $instansiZI->survei_mandiri = 'http://' . preg_replace('#^.*://#', '', $request->get("survei_mandiri"));
        $instansiZI->jml_wbk = $request->get("jml_wbk");
        $instansiZI->jml_wbbm = $request->get("jml_wbbm");
        //$instansiZI->final = 1;
        $instansiZI->update_by = Auth::User()->id;
        $instansiZI->save();

        $unit_wbks = $request->get("unit_wbk");
        $i = 0;
        if ($unit_wbks) {
            foreach ($unit_wbks as $key => $unit_wbk) {
                $unit_zi = new UnitZI;
                $unit_zi->instansi_zi_id = $instansiZI->id;
                $unit_zi->nama = $unit_wbk;
                $unit_zi->lke = 'http://' . preg_replace('#^.*://#', '', $request->get("lke_wbk")[$key]);
                if ($request->get("afirmasi")) {
                    if (array_key_exists($key, $request->get("afirmasi"))) {
                        $unit_zi->afirmasi = $request->get("afirmasi")[$key];
                    }
                }
                $unit_zi->wbk = 1;
                $unit_zi->save();
                $i++;
            }
        }
        #simpan jumlah unit wbk
        $instansiZI->jml_wbk = $i;



        $unit_wbbms = $request->get("unit_wbbm");
        $i = 0;

        if ($unit_wbbms) {
            foreach ($unit_wbbms as $key2 => $unit_wbbm) {
                $unit_zi = new UnitZI;
                $unit_zi->instansi_zi_id = $instansiZI->id;
                $unit_zi->nama = $unit_wbbm;
                $unit_zi->lke = 'http://' . preg_replace('#^.*://#', '', $request->get("lke_wbbm")[$key2]);
                $unit_zi->wbbm = 1;
                $unit_zi->save();
                $i++;
            }
        }

        #simpan jumlah unit wbk
        $instansiZI->jml_wbbm = $i;
        $instansiZI->save();

        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            return redirect('zi-tinjau?instansi_id=' . $instansi_id);
        } else {
            return redirect('zi-tinjau');
        }
    }

    public function tinjau(Request $request)
    {
        $tahun = 2025;
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansi_id = $request->get("instansi_id");
            $instansi_obj = KlpdInstansi::find($instansi_id);
        } else {
            $instansi_obj = Auth::User()->user_rel->instansi;
            $instansi_id = $instansi_obj->id;
        }
        $instansi = $instansi_obj->name;
        $group_kld = $instansi_obj->group;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();

        if ($instansiZI->tahap_seleksi === 0) {
            return redirect('zi/pengusulan');
        }

        if ($instansiZI) {
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $units = UnitZI::where("instansi_zi_id", $instansiZI->id)->orderBy('wbbm', 'desc')->get();
            return view('zi.evaluatan.tinjau_zi', compact(
                'instansi_id',
                'instansi',
                'group_kld',
                'instansiZI',
                'units',
                'syarat_akhir_wbk',
                'syarat_akhir_wbbm',
                'status_akhir'
            ));
        } else {
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }
    public function updateField(Request $request, $id, $field)
    {
        $instansi = InstansiZI::findOrFail($id);
        if (Auth::User()->user_rel->instansi->id != $instansi->instansi_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah data ini.');
        }
        // Pastikan hanya field tertentu yang bisa diubah
        $allowedFields = ['pic', 'email', 'nomor_kontak', 'surat_usulan', 'sptjm', 'tlhp', 'survei_mandiri'];
        if (!in_array($field, $allowedFields)) {
            return back()->with('error', 'Field tidak valid.');
        }



        $instansi->$field = $request->input('value');
        $instansi->save();

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function updateUnit(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lke' => 'required',
        ]);

        $unit = UnitZI::findOrFail($id);
        $instansi = $unit->instansiZI;
        if (Auth::User()->user_rel->instansi->id != $instansi->instansi_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah data ini.');
        }
        $unit->nama = $request->nama;
        $unit->lke = $request->lke;
        $unit->save();

        return redirect()->back()->with('success', 'Data Unit ' . $unit->name) . ' telah berhasil diperbarui :';
    }

    public function store_final(Request $request)
    {
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansi_id = $request->get("instansi_id");
        } else {
            $instansi_obj = Auth::User()->user_rel->instansi;
            $instansi_id = $instansi_obj->id;
        }
        $instansiZI = InstansiZI::where('instansi_id', $instansi_id)->first();
        $instansiZI->final = $request->get("final");
        $instansiZI->save();



        $unit_wbks = $request->get("unit_wbk");
        $i = 0;
        foreach ($unit_wbks as $unit_wbk) {
            $unit_zi = new UnitZI;
            $unit_zi->instansi_zi_id = $instansiZI->id;
            $unit_zi->nama = $unit_wbk;
            $unit_zi->lke = $request->get("lke_wbk")[$i];
            $unit_zi->afirmasi = $request->get("afirmasi")[$i];
            $unit_zi->wbk = 1;
            $unit_zi->save();
            $i++;
        }

        $unit_wbbms = $request->get("unit_wbbm");
        $i = 0;

        foreach ($unit_wbbms as $unit_wbbm) {
            $unit_zi = new UnitZI;
            $unit_zi->instansi_zi_id = $instansiZI->id;
            $unit_zi->nama = $unit_wbbm;
            $unit_zi->lke = $request->get("lke_wbbm")[$i];
            $unit_zi->wbbm = 1;
            $unit_zi->save();
            $i++;
        }

        return redirect('zi-tinjau');
    }
}
