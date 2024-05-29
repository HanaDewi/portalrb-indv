<?php

use App\Models\DokumenKategori;
use App\Models\KegiatanUtama;
use App\Models\KlpdInstansi;
use App\Models\LkeTP;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function menus()
{
    $menu = [
        [
            'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'tpm'],
            'title' => 'Dashboard',
            'icon' => 'home',
            'url' => 'dashboard',
        ],
        [
            'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'tpm'],
            'title' => 'Dokumen',
            'icon' => 'file-text',
            'url' => 'dokumen',
        ],
        // [
        //     'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn'],
        //     'title' => 'RB General',
        //     'icon' => 'pie-chart',
        //     'url' => 'rb-general',
        //     'items' => [
        //         [
        //             'levels' => ['provinsi', 'kabupaten', 'kl'],
        //             'title' => 'Perencanaan dan Monev',
        //             'icon' => 'clipboard-list',
        //             'url' => 'rb-general/perencanaan',
        //         ],
        //         [
        //             'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn'],
        //             'title' => 'Rekap Data',
        //             'icon' => 'clipboard',
        //             'url' => 'rb-general/rekap_data',
        //         ],
        //     ]
        // ],
        // [
        //     'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn'],
        //     'title' => 'RB Tematik',
        //     'icon' => 'bookmark',
        //     'url' => 'rb-tematik',
        //     'items' => [
        //         [
        //             'levels' => ['provinsi', 'kabupaten', 'kl'],
        //             'title' => 'Tema dan Sasaran Tematik',
        //             'icon' => 'clipboard-list',
        //             'url' => 'rb-tematik/perencanaan',
        //         ],
        //         [
        //             'levels' => ['provinsi', 'kabupaten', 'kl'],
        //             'title' => 'Permasalahan dan Rencana Aksi',
        //             'icon' => 'check-circle',
        //             'url' => 'rb-tematik/permasalahan',
        //         ],
        //         [
        //             'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn'],
        //             'title' => 'Rekap Data',
        //             'icon' => 'clipboard',
        //             'url' => 'rb-tematik/rekap_data',
        //         ],
        //     ]
        // ],
        [
            'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'tpm'],
            'title' => 'Hasil',
            'icon' => 'database',
            'url' => 'hasil',
        ],
        [
            'levels' => ['devider'],
        ],
        [
            'levels' => ['admin'],
            'title' => 'Master Data',
            'icon' => 'database',
            'url' => 'master-data',
            'items' => [
                [
                    'levels' => ['admin'],
                    'title' => 'Kegiatan Utama',
                    'icon' => 'award',
                    'url' => 'master-data/kegiatan_utama',
                ],
                [
                    'levels' => ['admin'],
                    'title' => 'Indikator',
                    'icon' => 'command',
                    'url' => 'master-data/indikator',
                ],
                [
                    'levels' => ['admin'],
                    'title' => 'Tema',
                    'icon' => 'bookmark',
                    'url' => 'master-data/tema',
                ],
                [
                    'levels' => ['admin'],
                    'title' => 'Dokumen',
                    'icon' => 'file-text',
                    'url' => 'master-data/dokumen',
                ],
            ]
        ],
        [
            'levels' => ['admin', 'provinsi', 'kabupaten', 'kl'],
            'title' => 'Profil',
            'icon' => 'user',
            'url' => 'profil',
        ],
        [
            'levels' => ['admin'],
            'title' => 'Buka Akses',
            'icon' => 'eye-off',
            'url' => 'access',
        ],
        [
            'levels' => ['admin'],
            'title' => 'Activity Log',
            'icon' => 'at-sign',
            'url' => 'activitylog',
        ],
        [
            'levels' => ['admin'],
            'title' => 'Kelola User',
            'icon' => 'users',
            'url' => 'manage-user'
        ],
    ];
    return $menu;
}

function allowed_url()
{
    $level = auth()->user()->level;
    $allowed_url = [];
    $base_url = config('app.client_url') == 'localhost' ? url('/') . '/' : config('app.client_url');
    foreach (menus() as $menu) {
        if (in_array($level, $menu['levels'])) {
            if (isset($menu['items'])) {
                foreach ($menu['items'] as $item) { 
                    if (in_array($level, $item['levels'])) {
                        if (isset($item['url'])) {
                            array_push($allowed_url, str_replace($base_url, '', $item['url']) . '*');
                        }
                    }
                }
            } else {
                array_push($allowed_url, str_replace($base_url, '', $menu['url']) . '*');
            }
        }
    }
    return $allowed_url;
}

function fdate($date, $time = false)
{
    if ($time) {
        return Carbon::parse($date)->isoFormat('dddd, D MMMM Y HH:mm:ss');
    } else {
        return Carbon::parse($date)->isoFormat('dddd, D MMMM Y');
    }
}

function humanDate($date)
{
    return Carbon::parse($date)->diffForHumans() . ' pada ' . Carbon::parse($date)->isoFormat('dddd, D MMMM Y HH:mm:ss');
}

function kegiatanUtama()
{
    return KegiatanUtama::pluck('nama', 'id')->toArray();
}

function indikators()
{
    $indikators = [];
    $kegiatans = KegiatanUtama::all();
    foreach ($kegiatans as $kegiatan) {
        foreach ($kegiatan->indikators as $indikator) {
            $indikators[$indikator->id] = '[' . $kegiatan->nama . '] ' . $indikator->nama;
        }
    }
    return $indikators;
}

function currency($number)
{
    if (gettype($number)=='integer' || gettype($number)=='double') { 
        return $number > 0 ? 'Rp. ' . number_format($number, 0, ',', '.') : '';
    } else {
        return $number;
    }
}

function fnumber($number, $digit = 0)
{
    if (gettype($number)=='integer' || gettype($number)=='double') { 
        return number_format($number, $digit, ',', '.');
    } else {
        $number = (double)str_replace('.', '', $number);
        return number_format($number, $digit, ',', '.');
    }
}

function instansis()
{
    $inslist = KlpdInstansi::orderBy('id')->pluck('name', 'id');
    $result = ['-'=>' -- Pilih instansi -- '];
    foreach ($inslist as $kk=>$lst) {
        $result[$kk] = $lst;
    }
    return $result;
}

function timpenilai()
{
    $ltp = LkeTP::orderBy('id')->pluck('name', 'id');
    $result = ['-'=>' -- Pilih tim penilai -- '];
    foreach ($ltp as $kk=>$lst) {
        $result[$kk] = $lst;
    }
    return $result;
}

function exts($ext)
{
    $exts = [
        'pdf' => 'pdf.png',
        'xlsx' => 'excel.png',
        'xls' => 'excel.png',
        'docx' => 'word.png',
        'doc' => 'word.png',
        'pptx' => 'ppt.png',
        'ppt' => 'ppt.png',
    ];
    return $exts[$ext];
}

function readInstansi($request)
{
    $user = Auth::User();
    if ($request->instansi_id && in_array($user->level, ['admin', 'tpn'])) {
        $instansi_id = $request->instansi_id;
    } else if ($user->user_rel->instansi_id) {
        $instansi_id = $user->user_rel->instansi_id;
    } else {
        $instansi_id = KlpdInstansi::orderBy('id')->first()->id;
    }
    if ($instansi_id) {
        $nama_instansi = KlpdInstansi::find($instansi_id)->name;
        return [
            'id' => $instansi_id,
            'nama' => $nama_instansi
        ];
    } else {
        return false;
    }
}

function heading_template_rb_general_rencana_aksi()
{
    return [
        0 => "rencana_aksi",
        1 => "satuan_output",
        2 => "indikator_output",
        3 => "target_tw1",
        4 => "target_tw2",
        5 => "target_tw3",
        6 => "target_tw4",
        7 => "target_total",
        8 => "anggaran_total",
        9 => "pelaksana",
        10 => "koordinator",
    ];
}

function tahun()
{
    return Tahun::pluck('tahun', 'tahun');
}

function dokumen_kategori()
{
    return DokumenKategori::pluck('nama', 'id');
}