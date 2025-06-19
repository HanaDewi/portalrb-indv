<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Http\Controllers\Controller;
use App\Models\ZI\TahapSeleksiZI;
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
        $tahun = date('Y');
        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Pengusulan')->first();
        $date_now = new \DateTime();
        $date_buka_zi    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup_zi    = new \DateTime($tahap_seleksi->tanggal_selesai);
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
                    return redirect('zi-tinjau?instansi_id=' . Auth::User()->instansi_id);
                }
                if (Auth::User()->user_rel) {
                    $instansi_obj = Auth::User()->user_rel->instansi;
                } else {
                    $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
                }

                $instansi_id = $instansi_obj->id;
            }
            (Auth::User()->level == "admin" || Auth::User()->level == "tpn") ? $instansis = KlpdInstansi::orderBy('name')->get() : $instansis = "";

            $instansi = $instansi_obj->name;
            $group_kld = $instansi_obj->group;
            $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();

            //butuh biar user gak balik lagi ke halaman pengusulan kalau sudah nyimpen
            if ($instansiZI->final == 1) {
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
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansi_id = $request->get("instansi_id");
        } else {
            $instansi_id = Auth::User()->instansi_id;
        }
        $tahun = date('Y');
        $instansiZI = InstansiZI::where('instansi_id', $instansi_id)->where('tahun', $tahun)->first();
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Pengusulan')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
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
            $instansiZI->final = 1;
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
        } else {
            return redirect('zi-tinjau?instansi_id=' . $instansi_id)
                ->with('error', 'Maaf, saat ini bukan waktu untuk mengisi evaluasi administrasi. Silakan tunggu hingga periode yang ditentukan yaitu ' . $tahap_seleksi->tanggal_mulai . ' hingga' . $tahap_seleksi->tanggal_selesai . '.');
        }
    }

    public function tinjau(Request $request)
    {
        $tahun = 2025;
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansi_id = $request->get("instansi_id");
            $instansi_obj = KlpdInstansi::find($instansi_id);
        } else {
            $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            $instansi_id = $instansi_obj->id;
        }
        $instansi = $instansi_obj->name;
        $group_kld = $instansi_obj->group;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();

        if ($instansiZI->tahap_seleksi === 0 || $instansiZI->final != 1) {
            return redirect('zi/pengusulan');
        }

        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Pengusulan')->first();
        $date_now = new \DateTime();
        $date_buka_zi    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup_zi    = new \DateTime($tahap_seleksi->tanggal_selesai);
        $editable = false;
        if ($date_now >= $date_buka_zi && $date_now <= $date_tutup_zi) {
            $editable = true;
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
                'status_akhir',
                'editable'
            ));
        } else {
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }
    public function updateField(Request $request, $id, $field)
    {
        $tahun = date('Y');
        $instansi = InstansiZI::findOrFail($id);
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Pengusulan')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            if (Auth::User()->instansi_id != $instansi->instansi_id) {
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
        } else {
            return redirect()->route('evaluasi_administrasi', $instansiZIid)
                ->with('error', 'Maaf, saat ini bukan waktu untuk mengisi evaluasi administrasi. Silakan tunggu hingga periode yang ditentukan yaitu ' . $tahap_seleksi->tanggal_mulai . ' hingga' . $tahap_seleksi->tanggal_selesai . '.');
        }
    }

    public function updateUnit(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lke' => 'required',
        ]);

        $tahun = date('Y');
        $unit = UnitZI::findOrFail($id);
        $instansi = $unit->instansiZI;
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Pengusulan')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            if (Auth::User()->instansi_id != $instansi->instansi_id) {
                return back()->with('error', 'Anda tidak memiliki izin untuk mengubah data ini.');
            }
            $unit->nama = $request->nama;
            $unit->lke = $request->lke;
            if ($request->kategori == 'WBK') {
                if ($unit->wbbm) {
                    $unit->wbk = 1;
                    $unit->wbbm = 0;
                    $instansi->jml_wbk += 1; // menambah jumlah wbk jika unit ini sebelumnya adalah WBBM
                    $instansi->jml_wbbm -= 1; // Mengurangi jumlah WBBM jika unit ini sebelumnya adalah WBBM
                    $instansi->save();
                }
            } elseif ($request->kategori == 'WBBM') {
                if ($unit->wbk) {
                    $unit->wbbm = 1;
                    $unit->wbk = 0;
                    $instansi->jml_wbbm += 1; // Menambah jumlah WBBM jika unit ini sebelumnya adalah WBK
                    $instansi->jml_wbk -= 1; // Mengurangi jumlah WBK jika unit ini sebelumnya adalah WBK
                    $instansi->save();
                }
            }
            $unit->save();

            return redirect()->back()->with('success', 'Data Unit ' . $unit->name . ' telah berhasil diperbarui');
        } else {
            return redirect()->route('evaluasi_administrasi', $instansiZIid)
                ->with('error', 'Maaf, saat ini bukan waktu untuk mengisi evaluasi administrasi. Silakan tunggu hingga periode yang ditentukan yaitu ' . $tahap_seleksi->tanggal_mulai . ' hingga' . $tahap_seleksi->tanggal_selesai . '.');
        }
    }

    public function deleteUnit(Request $request, $id)
    {
        $tahun = date('Y');
        $unit = UnitZI::findOrFail($id);
        $instansi = $unit->instansiZI;
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Pengusulan')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            if (Auth::User()->instansi_id != $instansi->instansi_id) {
                return back()->with('error', 'Anda tidak memiliki izin untuk mengubah data ini.');
            }

            if ($unit->wbbm) {
                $instansi->jml_wbbm -= 1; // Mengurangi jumlah WBBM jika unit ini sebelumnya adalah WBBM
            } elseif ($unit->wbk) {
                $instansi->jml_wbk -= 1; // Mengurangi jumlah WBK jika unit ini sebelumnya adalah WBK
            }
            $instansi->save();
            $unit->delete();

            return redirect()->back()->with('success', 'Data Unit ' . $unit->name . ' telah berhasil di hapus ');
        } else {
            return redirect()->route('evaluasi_administrasi', $instansiZIid)
                ->with('error', 'Maaf, saat ini bukan waktu untuk mengisi evaluasi administrasi. Silakan tunggu hingga periode yang ditentukan yaitu ' . $tahap_seleksi->tanggal_mulai . ' hingga' . $tahap_seleksi->tanggal_selesai . '.');
        }
    }

    public function addUnit(Request $request, $id)
    {
        $tahun = date('Y');
        $request->validate([
            'nama' => 'required|string|max:255',
            'lke' => 'required',
        ]);

        $instansiZI = InstansiZI::find($id);
        $tahap_seleksi = TahapSeleksiZI::where('tahap_seleksi', 'Pengusulan')->where('tahun', $tahun)->first();
        if (!$tahap_seleksi) {
            dd("Tahap Seleksi untuk tahun $tahun belum ditentukan. Silakan hubungi admin.");
        }
        $date_now = new \DateTime();
        $date_buka    = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup  = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            if (Auth::User()->instansi_id != $instansiZI->instansi_id) {
                return back()->with('error', 'Anda tidak memiliki izin untuk mengubah data ini.');
            }

            $unit_zi = new UnitZI;
            $unit_zi->instansi_zi_id = $id;
            $unit_zi->nama = $request->nama;
            $unit_zi->lke = 'http://' . preg_replace('#^.*://#', '', $request->lke);
            if ($request->kategori == 'WBK') {
                //Cek apakah bisa menjadi WBK
                if ($instansiZI->syarat_akhir_wbk == "LULUS") {
                    $unit_zi->wbk = 1;
                    $instansiZI->jml_wbk += 1; // menambah jumlah wbk jika unit ini adalah WBK    
                } else {
                    return redirect()->back()->with('error', 'Data Unit ' . $unit_zi->nama . ' gagal untuk ditambah karena anda tidak bisa mengusulkan WBK, Pilih WBK AFIRMASI untuk mendaftarkan unit-unit yang berada pada kategori afirmasi');
                }
            } elseif ($request->kategori == 'WBBM') {
                //Cek apakah bisa menjadi WBBM
                if ($instansiZI->syarat_akhir_wbbm == "LULUS") {
                    $unit_zi->wbbm = 1;
                    $instansiZI->jml_wbbm += 1; // menambah jumlah wbbm jika unit ini adalah WBBM
                } else {
                    return redirect()->back()->with('error', 'Data Unit ' . $unit_zi->nama . ' gagal untuk ditambah karena anda tidak bisa mengusulkan WBBM, Pilih WBK AFIRMASI untuk mendaftarkan unit-unit yang berada pada kategori afirmasi');
                }
            } elseif ($request->kategori == 'WBK-AFIRMASI') {
                $unit_zi->wbk = 1;
                $instansiZI->jml_wbk += 1; // menambah jumlah wbk jika unit ini adalah ZI
                $unit_zi->afirmasi = 1; // Menandai unit ini sebagai afirmasi
                $unit_zi->save();
            } else {
                return redirect()->back()->with('error', 'Error');
            }
            $unit_zi->save();
            $instansiZI->save();
            return redirect()->back()->with('success', 'Data Unit ' . $unit_zi->nama . ' telah berhasil ditambah :');
        } else {
            return redirect()->route('evaluasi_administrasi', $instansiZIid)
                ->with('error', 'Maaf, saat ini bukan waktu untuk mengisi evaluasi administrasi. Silakan tunggu hingga periode yang ditentukan yaitu ' . $tahap_seleksi->tanggal_mulai . ' hingga' . $tahap_seleksi->tanggal_selesai . '.');
        }
    }
}
