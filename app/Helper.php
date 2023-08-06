<?php

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
            'levels' => 'devider',
        ],
        [
            'levels' => ['admin', 'evaluator', 'instansi'],
            'title' => 'Rencana Aksi',
            'icon' => 'pie-chart',
            'url' => 'javascript:;',
            'items' => [
                [
                    'levels' => ['admin', 'evaluator', 'instansi'],
                    'title' => 'RB General',
                    'icon' => 'bar-chart',
                    'url' => 'rb_general',
                ],
                [
                    'levels' => ['admin', 'evaluator', 'instansi'],
                    'title' => 'RB Tematik',
                    'icon' => 'star',
                    'url' => 'rb_tematik',
                ],
            ]
        ],
        [
            'levels' => ['pt'],
            'title' => 'Pencapaian IKU',
            'icon' => 'fa fa-trophy',
            'url' => 'pencapaian_pt',
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