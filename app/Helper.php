<?php
use App\Models\DokumenKategori;
use App\Models\FokusIntervensi;
use App\Models\KegiatanUtama;
use App\Models\KlpdInstansi;
use App\Models\LKE\LkeKegiatan;
use App\Models\LKE\LkeParameter;
use App\Models\LkeTP;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if(! function_exists('menus')) 
{
    function menus()
    {
        $menu = [
            [
                'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'tpm', 'viewer'],
                'title' => 'Beranda',
                'icon' => 'home',
                'url' => 'dashboard',
            ],
            [
                'levels' => ['tpn', 'admin', 'viewer'],
                'title' => 'Dashboard',
                'icon' => 'pie-chart',
                'url' => 'webdashboard',
                'items' => [ 
                    [
                        'levels' => ['tpn', 'admin', 'viewer'],
                        'title' => 'RB General',
                        'icon' => 'clipboard-list',
                        'url' => 'webdashboard/rb-general',
                        'items' => [
                            [
                                'levels' => ['tpn', 'admin', 'viewer'],
                                'title' => 'Rencana Aksi',
                                'icon' => 'inbox',
                                'url' => 'webdashboard/rb-general/rencana-aksi',
                            ],
                            [
                                'levels' => ['tpn', 'admin', 'viewer'],
                                'title' => 'Capaian Output',
                                'icon' => 'bar-chart',
                                'url' => 'webdashboard/rb-general/capaian-output',
                            ],
                        ]
                    ],
                    [
                        'levels' => ['tpn', 'admin', 'viewer'],
                        'title' => 'RB Tematik',
                        'icon' => 'clipboard',
                        'url' => 'webdashboard/rb-tematik',
                        'items' => [
                            [
                                'levels' => ['tpn', 'admin', 'viewer'],
                                'title' => 'Rencana Aksi',
                                'icon' => 'inbox',
                                'url' => 'webdashboard/rb-tematik/rencana-aksi',
                            ],
                            [
                                'levels' => ['tpn', 'admin', 'viewer'],
                                'title' => 'Capaian Output',
                                'icon' => 'bar-chart',
                                'url' => 'webdashboard/rb-tematik/capaian-output',
                            ],
                        ]
                    ],
                    [
                        'levels' => ['tpn', 'admin', 'viewer'],
                        'title' => 'Hasil Evaluasi',
                        'icon' => 'target',
                        'url' => 'webdashboard/hasil-evaluasi'
                    ],
                ],
            ],
            [
                'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'tpm'],
                'title' => 'Dokumen',
                'icon' => 'file-text',
                'url' => 'dokumen',
            ],
            [
                'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'viewer'],
                'title' => 'Rencana Aksi',
                'icon' => 'inbox',
                'url' => 'rencana_aksi',
                'items' => [ 
                    [
                        'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'viewer'],
                        'title' => 'RB General',
                        'icon' => 'activity',
                        'url' => 'rencana_aksi/rb-general',
                        'items' => [
                            [
                                'levels' => ['provinsi', 'kabupaten', 'kl'],
                                'title' => 'Perencanaan dan Monev',
                                'icon' => 'clipboard-list',
                                'url' => 'rencana_aksi/rb-general/perencanaan',
                            ],
                            [
                                'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'viewer'],
                                'title' => 'Rekap Data',
                                'icon' => 'clipboard',
                                'url' => 'rencana_aksi/rb-general/rekap_data',
                            ],
                        ]
                    ],
                    [
                        'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'viewer'],
                        'title' => 'RB Tematik',
                        'icon' => 'bookmark',
                        'url' => 'rencana_aksi/rb-tematik',
                        'items' => [
                            [
                                'levels' => ['provinsi', 'kabupaten', 'kl'],
                                'title' => 'Tema dan Sasaran Tematik',
                                'icon' => 'clipboard-list',
                                'url' => 'rencana_aksi/rb-tematik/perencanaan',
                            ],
                            [
                                'levels' => ['provinsi', 'kabupaten', 'kl'],
                                'title' => 'Permasalahan dan Rencana Aksi',
                                'icon' => 'check-circle',
                                'url' => 'rencana_aksi/rb-tematik/permasalahan',
                            ],
                            [
                                'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'viewer'],
                                'title' => 'Rekap Data',
                                'icon' => 'clipboard',
                                'url' => 'rencana_aksi/rb-tematik/rekap_data',
                            ],
                        ]
                    ],
                ],
            ],
            [
                'levels' => ['admin', 'provinsi', 'kabupaten', 'kl', 'tpn', 'tpm', 'viewer'],
                'title' => 'Hasil',
                'icon' => 'database',
                'url' => 'hasil',
            ],
            [
                'levels' => ['admin', 'tpn', 'tpm'],
                'title' => 'Evaluasi',
                'icon' => 'pencil',
                'url' => 'evaluasi',
                'items' => [ 
                    [
                        'levels' => ['admin', 'tpn', 'tpm'],
                        'title' => 'LKE Utama',
                        'icon' => 'book-open',
                        'url' => 'evaluasi/lke-utama'
                    ],
                    [
                        'levels' => ['admin', 'tpn'],
                        'title' => 'Renaksi RB General',
                        'icon' => 'activity',
                        'url' => 'evaluasi/renaksi-rb-general'
                    ]
                ],
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
                    [
                        'levels' => ['admin'],
                        'title' => 'LKE Kegiatan',
                        'icon' => 'list',
                        'url' => 'master-data/lke_kegiatan',
                    ],
                    [
                        'levels' => ['admin'],
                        'title' => 'LKE Parameter',
                        'icon' => 'list',
                        'url' => 'master-data/lke_parameter',
                    ],
                    [
                        'levels' => ['admin'],
                        'title' => 'Data LKE Renaksi',
                        'icon' => 'clipboard-list',
                        'url' => 'master-data/data-lke-renaksi'
                    ],
                    [
                        'levels' => ['admin'],
                        'title' => 'Data Konversi Jawaban',
                        'icon' => 'clipboard-list',
                        'url' => 'master-data/data-konversi-jawaban'
                    ]
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
            [
                'levels' => ['admin'],
                'title' => 'Kelola Tim',
                'icon' => 'clipboard-list',
                'url' => 'kelola-tim',
                'items' => [
                    [
                        'levels' => ['admin'],
                        'title' => 'Nama Tim',
                        'icon' => 'award',
                        'url' => 'kelola-tim',
                    ],
                    [
                        'levels' => ['admin'],
                        'title' => 'Anggota Tim',
                        'icon' => 'command',
                        'url' => 'kelola-anggota-tim',
                    ],
                    [
                        'levels' => ['admin'],
                        'title' => 'Instansi',
                        'icon' => 'bookmark',
                        'url' => 'kelola-instansi-tim',
                    ]
                ]
            ]
        ];
        return $menu;
    }
};

if(! function_exists('allowed_url')) 
{
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
};

if(! function_exists('fdate')) {
    function fdate($date, $time = false)
    {
        if ($time) {
            return Carbon::parse($date)->isoFormat('dddd, D MMMM Y HH:mm:ss');
        } else {
            return Carbon::parse($date)->isoFormat('dddd, D MMMM Y');
        }
    }
}

if(! function_exists('humanDate')) {
    function humanDate($date)
    {
        return Carbon::parse($date)->diffForHumans() . ' pada ' . Carbon::parse($date)->isoFormat('dddd, D MMMM Y HH:mm:ss');
    }
}

if(! function_exists('kegiatanUtama')) {
    function kegiatanUtama()
    {
        return KegiatanUtama::pluck('nama', 'id')->toArray();
    }
}

if(! function_exists('fokusIntervensi')) {
    function fokusIntervensi()
    {
        return FokusIntervensi::pluck('nama', 'id')->toArray();
    }
}

if(! function_exists('indikators')) {
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
}

if(! function_exists('currency')) {
    function currency($number)
    {
        if (gettype($number)=='integer' || gettype($number)=='double') { 
            return $number > 0 ? 'Rp. ' . number_format($number, 0, ',', '.') : '';
        } else {
            return $number;
        }
    }
}

if(! function_exists('fnumber')) {
    function fnumber($number, $digit = 0)
    {
        if (gettype($number)=='integer' || gettype($number)=='double') { 
            return number_format($number, $digit, ',', '.');
        } else {
            $number = (double)str_replace('.', '', $number);
            return number_format($number, $digit, ',', '.');
        }
    }
}

if(! function_exists('fnumber2')) {
    function fnumber2($number, $digit = 0)
    {
        $number = (double) $number;
        return number_format($number, $digit, ',', '.');
    }
}

if(! function_exists('instansis')) {
    function instansis()
    {
        $inslist = KlpdInstansi::orderBy('id')->pluck('name', 'id');
        $result = ['-'=>' -- Pilih instansi -- '];
        foreach ($inslist as $kk=>$lst) {
            $result[$kk] = $lst;
        }
        return $result;
    }
}

if(! function_exists('timpenilai')) {
    function timpenilai()
    {
        $ltp = LkeTP::orderBy('id')->pluck('name', 'id');
        $result = ['-'=>' -- Pilih Tim Penilai -- '];
        foreach ($ltp as $kk=>$lst) {
            $result[$kk] = $lst;
        }
        return $result;
    }
}

if(! function_exists('exts')) {
    function exts($ext = null)
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
        if ($ext) {
            $ext = strtolower($ext);
            return $exts[$ext];
        } else {
            return array_keys($exts);
        }
    }
}

if(! function_exists('readInstansi')) {
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
}

if(! function_exists('heading_template_rb_general_rencana_aksi')) {
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
}

if(! function_exists('tahun')) {
    function tahun()
    {
        return Tahun::pluck('tahun', 'tahun');
    }
}

if(! function_exists('dokumen_kategori')) {
    function dokumen_kategori()
    {
        return DokumenKategori::pluck('nama', 'id');
    }
}

if(! function_exists('fiturs')) {
    function fiturs($fitur = null)
    {
        $fiturs = [
            'hasil_evaluasi' => 'Hasil Evaluasi',
            'rencana_aksi' => 'Rencana Aksi'
        ];
        
        return $fitur ? $fiturs[$fitur] : $fiturs;
    }
}

if(! function_exists('group_instansi')) {
    function group_instansi($group = null)
    {
        $groups = [
            'kl' => 'Kementerian',
            'provinsi' => 'Provinsi',
            'kabupaten' => 'Kabupaten/Kota',
            'lain' => 'Lainnya',
            'kab' => 'Kabupaten/Kota',
            'prov' => 'Provinsi',
            'pemda' => 'Kabupaten/Kota',
        ];
        
        return $group ? $groups[$group] : $groups;
    }
}

if(! function_exists('level')) {
    function level($level = null)
    {
        $levels = [
            'Komponen' => 'Komponen',
            'Sub Komponen' => 'Sub Komponen',
            'Indikator' => 'Indikator',
        ];
        
        return $level ? $levels[$level] : $levels;
    }
}

if(! function_exists('parameter')) {
    function parameter($level, $parent_id = null)
    {
        if ($level == 'komponen') {
            return LkeParameter::where('level', 'Komponen')->pluck('nama', 'id');
        }
        if ($level == 'subkomponen') {
            return LkeParameter::where('level', 'Sub Komponen')->where('parent_id', $parent_id)->pluck('nama', 'id');
        }
        if ($level == 'indikator') {
            return LkeParameter::where('level', 'Indikator')->where('parent_id', $parent_id)->pluck('nama', 'id');
        }
    }
}

if(! function_exists('indikator')) {
    function indikator($komponen_id, $indikator_id = null)
    {
        $subkomponen_ids = LkeParameter::where('level', 'Sub Komponen')->where('parent_id', $komponen_id)->pluck('id');
        return LkeParameter::where('level', 'Indikator')->whereIn('parent_id', $subkomponen_ids)->where('id', '!=', $indikator_id)->pluck('nama', 'id');
    }
}

if(! function_exists('kegiatan')) {
    function kegiatan()
    {
        return LkeKegiatan::all()->pluck('nama_tahun', 'id');
    }
}

if(! function_exists('set_options')) {
    function set_options($datas, $placeholder = null)
    {
        if (count($datas)) {
            $options = $placeholder ? '<option value="">'.$placeholder.'</option>' : '';
            foreach ($datas as $id => $nama) {
                $options .= '<option value="'.$id.'">'.$nama.'</option>';
            }
            return $options;
        }
    }
}

if(! function_exists('heading_template_lke')) {
    function heading_template_lke()
    {
        return [
            0 => 'instansi_id',
            1 => 'lke_bobot_id',
            2 => 'no',
            3 => 'group_instansi',
            4 => 'nama_instansi',
            5 => 'bobot',
            6 => 'min_value',
            7 => 'max_value',
            8 => 'target_baik',
            9 => 'score',
            10 => 'catatan',
            11 => 'rekomendasi'
        ];
    }
}