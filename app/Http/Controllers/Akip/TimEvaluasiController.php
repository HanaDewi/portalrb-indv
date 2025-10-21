<?php

namespace App\Http\Controllers\Akip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimEvaluasiController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth()->user();
            foreach (allowed_url('akip') as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    /**
     * Display the tim evaluasi page
     */
    public function index()
    {
        // Check akses hanya untuk TPN
        if ($this->currentUser->level !== 'tpn') {
            abort(403, 'Akses ditolak. Hanya TPN yang dapat mengakses halaman ini.');
        }

        $anggota = $this->currentUser->anggota;

        if (!$anggota || !$anggota->tim) {
            return view('akip.tim-evaluasi.index', [
                'tim' => null,
                'message' => 'Anda belum terdaftar sebagai anggota tim evaluasi.'
            ]);
        }

        $tim = $anggota->tim;

        return view('akip.tim-evaluasi.index', compact('tim'));
    }

    /**
     * Get data tim evaluasi untuk DataTables
     */
    public function getDatas()
    {
        // Check akses hanya untuk TPN
        if ($this->currentUser->level !== 'tpn') {
            abort(403, 'Akses ditolak. Hanya TPN yang dapat mengakses data ini.');
        }

        try {
            $anggota = $this->currentUser->anggota;

            if (!$anggota || !$anggota->tim) {
                return response()->json([
                    'data' => [],
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'debug' => 'No anggota or tim found'
                ]);
            }

            $tim_id = $anggota->tim->id;

            // Query anggota tim dari tim user yang login
            $anggota_tim = \App\Models\AnggotaTimEvaluasiRB::where('tim_id', $tim_id)
                ->with('user')
                ->get();

            // Debug: Log the count
            \Log::info('Anggota tim count: ' . $anggota_tim->count());

            $data = [];
            foreach ($anggota_tim as $anggota) {
                // Get user data directly
                $user_data = \App\Models\User::where('id', $anggota->user_id)->first();

                // Use user_data if available
                if ($user_data) {
                    $nama = $user_data->nama ?? '-';
                    $email = $user_data->email ?? '-';
                } else {
                    // Fallback: use user_id as name if user not found
                    $nama = 'User ID: ' . $anggota->user_id;
                    $email = '-';
                }

                // Count instansi yang dipegang oleh anggota ini
                // Berdasarkan input_user_id di evaluasi_sakip
                $jumlah_instansi = \App\Models\Akip\EvaluasiSakip::where('input_user_id', $anggota->user_id)
                    ->distinct('instansi_id')
                    ->count('instansi_id');

                $data[] = [
                    'user_id' => $anggota->user_id,
                    'nama' => $nama,
                    'email' => $email,
                    'jumlah_instansi' => $jumlah_instansi
                ];
            }

            return response()->json([
                'data' => $data,
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daftar instansi yang dievaluasi oleh anggota tim
     */
    public function getInstansi($user_id)
    {
        // Check akses hanya untuk TPN
        if ($this->currentUser->level !== 'tpn') {
            abort(403, 'Akses ditolak.');
        }

        try {
            // Get daftar instansi yang dievaluasi oleh user dengan informasi update terakhir
            $evaluasi = \App\Models\Akip\EvaluasiSakip::where('input_user_id', $user_id)
                ->with('instansi')
                ->select('instansi_id', 'tahun', 'periode', 'updated_at')
                ->distinct()
                ->orderBy('updated_at', 'desc')  // Urutkan dari yang paling terbaru
                ->get();

            $data = [];
            foreach ($evaluasi as $eval) {
                if ($eval->instansi) {
                    // Format tanggal dan waktu terakhir update
                    $last_updated = $eval->updated_at ?
                        \Carbon\Carbon::parse($eval->updated_at)->format('d F Y H:i') :
                        '-';

                    $data[] = [
                        'instansi_id' => $eval->instansi_id,
                        'nama_instansi' => $eval->instansi->nama_instansi ?? '-',
                        'group' => $eval->instansi->group ?? '-',
                        'tahun' => $eval->tahun,
                        'periode' => $eval->periode,
                        'last_updated' => $last_updated,
                        'url' => url('akip/evaluasi/sakip/' . $eval->instansi_id)
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
