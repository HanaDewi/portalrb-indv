<?php

namespace App\Http\Controllers;

use App\Models\PelaporanCoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelaporanCoiReportController extends Controller
{
    private $questions = [
        'q1_peraturan_internal' => 'Apakah Instansi Bapak/Ibu sudah memiliki aturan internal mengenai pengelolaan konflik kepentingan?',
        'q2_selaras_permepan' => 'Apakah peraturan mengenai konflik kepentingan yang berlaku sudah diselaraskan dengan Peraturan Menteri PANRB Nomor 17 Tahun 2024?',
        'q3_pedoman_teknis' => 'Apakah peraturan internal di instansi Bapak/Ibu diturunkan lagi dalam bentuk pedoman teknis?',
        'q4_penunjukan_pejabat' => 'Apakah di instansi Bapak/Ibu sudah ditunjuk Pejabat Pengelola Konflik Kepentingan?',
        'q5_sistem_aplikasi' => 'Apakah di instansi Bapak/Ibu terdapat sistem atau aplikasi yang digunakan untuk mengelola konflik kepentingan?',
        'q6_pencatatan_register' => 'Apakah di instansi Bapak/Ibu sudah dilakukan pencatatan daftar kepentingan pribadi/register?',
        'q7_deklarasi_aktual' => 'Apakah di instansi Bapak/Ibu sudah dilakukan deklarasi untuk konflik kepentingan aktual?',
        'q8_lini_aduan' => 'Apakah di instansi Bapak/Ibu sudah memiliki lini aduan yang dapat dimanfaatkan untuk menyampaikan pengaduan jika terjadi konflik kepentingan?',
        'q9_monev' => 'Apakah di instansi Bapak/Ibu terdapat mekanisme monitoring dan evaluasi atas pengelolaan konflik kepentingan?',
        'q10_laporan' => 'Apakah instansi Bapak/Ibu telah menyusun laporan atas implementasi pengelolaan konflik kepentingan?',
    ];

    private $children = [
        'q1_peraturan_internal' => ['field' => 'q11_nomor_peraturan', 'label' => 'Nomor Permen/Kepmen/Pergub/Perbub/Perwali?', 'when' => 1],
        'q2_selaras_permepan' => ['field' => 'q21_susun_revisi', 'label' => 'Apakah instansi sudah mulai menyusun revisi?', 'when' => 0],
        'q21_susun_revisi' => ['field' => 'q211_rencana_penyesuaian', 'label' => 'Kapan aturan akan disesuaikan?', 'when' => 0],
        'q3_pedoman_teknis' => ['field' => 'q31_nomor_pedoman', 'label' => 'Nomor pedoman?', 'when' => 1],
        'q6_pencatatan_register' => ['field' => 'q61_total_wajib', 'label' => 'Total ASN Wajib Melaporkan', 'when' => 1],
        'q7_deklarasi_aktual' => ['field' => 'q71_jumlah_deklarasi', 'label' => 'jumlah deklarasi yang disampaikan?', 'when' => 1],
        'q8_lini_aduan' => ['field' => 'q81_nama_lini', 'label' => 'nama lini pengaduan?', 'when' => 1],
    ];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->level !== 'tpn') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $query = PelaporanCoi::query()
            ->select('pelaporan_coi.*', 'klpd_instansi_new.group')
            ->leftJoin('klpd_instansi_new', 'klpd_instansi_new.id', '=', 'pelaporan_coi.instansi_id');

        $data = $query->get();

        $grouped = [];
        foreach ($this->questions as $field => $label) {
            $grouped[$field] = [
                'label' => $label,
                'counts' => [
                    'kl' => [
                        'yes' => $data->where('group', 'kl')->where($field, true)->count(),
                        'no' => $data->where('group', 'kl')->where($field, false)->count(),
                    ],
                    'provinsi' => [
                        'yes' => $data->where('group', 'provinsi')->where($field, true)->count(),
                        'no' => $data->where('group', 'provinsi')->where($field, false)->count(),
                    ],
                    'kabupaten' => [
                        'yes' => $data->where('group', 'kabupaten')->where($field, true)->count(),
                        'no' => $data->where('group', 'kabupaten')->where($field, false)->count(),
                    ],
                ],
            ];
        }

        return view('pelaporan-coi.report.index', [
            'questions' => $grouped,
        ]);
    }

    public function detail($question, $answer, Request $request)
    {
        abort_unless(isset($this->questions[$question]), 404);

        $group = $request->get('group');

        $records = PelaporanCoi::with('instansi')
            ->when($group, function ($q) use ($group) {
                $q->whereHas('instansi', fn($iq) => $iq->where('group', $group));
            })
            ->where($question, (int) $answer)
            ->get();

        $child = null;
        if (isset($this->children[$question]) && (int) $answer === (int) $this->children[$question]['when']) {
            $child = $this->children[$question];
        }

        return view('pelaporan-coi.report.detail', [
            'questionKey' => $question,
            'questionLabel' => $this->questions[$question],
            'answer' => (int) $answer,
            'records' => $records,
            'child' => $child,
        ]);
    }

    public function detailChild($question, Request $request)
    {
        // khusus q2 level kedua
        if ($question !== 'q2_selaras_permepan') {
            abort(404);
        }

        $group = $request->get('group');

        $records = PelaporanCoi::with('instansi')
            ->when($group, function ($q) use ($group) {
                $q->whereHas('instansi', fn($iq) => $iq->where('group', $group));
            })
            ->where('q2_selaras_permepan', 0)
            ->get();

        return view('pelaporan-coi.report.detail-q2', [
            'records' => $records,
            'questionLabel' => $this->questions['q2_selaras_permepan'],
        ]);
    }
}
