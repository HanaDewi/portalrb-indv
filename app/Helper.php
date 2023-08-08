<?php

use App\Models\KegiatanUtama;
use Carbon\Carbon;

function menus()
{
    $menu = [
        [
            'levels' => ['admin', 'evaluator', 'instansi'],
            'title' => 'Dashboard',
            'icon' => 'home',
            'url' => 'dashboard',
        ],
        [
            'levels' => ['admin', 'evaluator', 'instansi'],
            'title' => 'RB General',
            'icon' => 'pie-chart',
            'url' => 'rb_general',
            'items' => [
                [
                    'levels' => ['admin', 'evaluator', 'instansi'],
                    'title' => 'Perencanaan',
                    'icon' => 'bar-chart',
                    'url' => 'rb_general/perencanaan',
                ],
                [
                    'levels' => ['admin', 'evaluator', 'instansi'],
                    'title' => 'Rekap Data',
                    'icon' => 'clipboard',
                    'url' => 'rb_general/rekap_data',
                ],
            ]
        ],
        [
            'levels' => 'devider',
        ],
        [
            'levels' => ['admin'],
            'title' => 'Master Data',
            'icon' => 'database',
            'url' => 'master-data',
            'items' => [
                [
                    'levels' => ['admin', 'evaluator', 'instansi'],
                    'title' => 'Kegiatan Utama',
                    'icon' => 'award',
                    'url' => 'master-data/kegiatan_utama',
                ],
                [
                    'levels' => ['admin', 'evaluator', 'instansi'],
                    'title' => 'Indikator',
                    'icon' => 'command',
                    'url' => 'master-data/indikator',
                ],
            ]
        ],
        [
            'levels' => ['admin', 'evaluator', 'instansi'],
            'title' => 'Profil',
            'icon' => 'user',
            'url' => 'profil',
        ],
    ];
    return $menu;
}

function allowed_url()
{
    $role_id = session('level');
    $allowed_url = [];
    $base_url = config('app.client_url') == 'localhost' ? url('/') . '/' : config('app.client_url');
    foreach (menus() as $menu) {
        if (in_array($role_id, $menu['levels'])) {
            if (isset($menu['items'])) {
                foreach ($menu['items'] as $item) {
                    if (in_array($role_id, $item['levels'])) {
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
    return Carbon::parse($date)->diffForHumans().' pada '.Carbon::parse($date)->isoFormat('dddd, D MMMM Y HH:mm:ss');
}

function kegiatanUtama()
{
    return KegiatanUtama::pluck('nama', 'id');
}