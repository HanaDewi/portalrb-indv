<?php

use App\Models\Instansi;
use App\Models\KegiatanUtama;
use Carbon\Carbon;

function menus()
{
    $menu = [
        [
            'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
            'title' => 'Dashboard',
            'icon' => 'home',
            'url' => 'dashboard',
        ],
        [
            'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
            'title' => 'RB General',
            'icon' => 'pie-chart',
            'url' => 'rb-general',
            'items' => [
                [
                    'levels' => ['provinsi', 'kabupaten', 'kl'],
                    'title' => 'Perencanaan dan Monev',
                    'icon' => 'bar-chart',
                    'url' => 'rb-general/perencanaan',
                ],
                [
                    'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
                    'title' => 'Rekap Data',
                    'icon' => 'clipboard',
                    'url' => 'rb-general/rekap_data',
                ],
            ]
        ],
        [
            'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
            'title' => 'RB Tematik',
            'icon' => 'bookmark',
            'url' => 'rb-tematik',
            'items' => [
                [
                    'levels' => ['provinsi', 'kabupaten', 'kl'],
                    'title' => 'Perencanaan dan Monev',
                    'icon' => 'bar-chart',
                    'url' => 'rb-tematik/perencanaan',
                ],
                [
                    'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
                    'title' => 'Rekap Data',
                    'icon' => 'clipboard',
                    'url' => 'rb-tematik/rekap_data',
                ],
            ]
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
                    'levels' => ['admin', 'evaluator'],
                    'title' => 'Kegiatan Utama',
                    'icon' => 'award',
                    'url' => 'master-data/kegiatan_utama',
                ],
                [
                    'levels' => ['admin', 'evaluator'],
                    'title' => 'Indikator',
                    'icon' => 'command',
                    'url' => 'master-data/indikator',
                ],
                [
                    'levels' => ['admin', 'evaluator'],
                    'title' => 'Tema',
                    'icon' => 'bookmark',
                    'url' => 'master-data/tema',
                ],
            ]
        ],
        [
            'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
            'title' => 'Profil',
            'icon' => 'user',
            'url' => 'profil',
        ],

        [
            'levels' => ['admin', 'evaluator', 'provinsi', 'kabupaten', 'kl'],
            'title' => 'Hasil',
            'icon' => 'database',
            'url' => 'hasil-seluruh',
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
    return KegiatanUtama::pluck('nama', 'id');
}

function currency($number)
{
    return $number > 0 ? 'Rp. ' . number_format($number, 0, ',', '.') : '';
}

function fnumber($number, $digit = 0)
{
    return number_format($number, $digit, ',', '.');
}

function instansis()
{
    return Instansi::orderBy('id')->pluck('nama', 'id');
}
