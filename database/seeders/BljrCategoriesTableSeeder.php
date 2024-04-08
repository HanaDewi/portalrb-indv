<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_categories')->delete();
        
        \DB::table('bljr_categories')->insert(array (
            0 => 
            array (
                'id' => 34,
                'language' => 'id',
                'name' => 'Tata Kelola Pemerintahan',
                'icon' => 'ruangbelajar/category/icon1.png',
                'slug' => 'tata-kelola-pemerintahan',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 03:10:46',
                'updated_at' => '2023-11-23 04:19:15',
            ),
            1 => 
            array (
                'id' => 39,
                'language' => 'id',
                'name' => 'Penanggulangan Kemiskinan',
                'icon' => 'ruangbelajar/category/68a5UN0dkV1bPfDwHSdHgOjQGMXjHn.png',
                'slug' => 'penganggulangan-kemiskinan',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 04:27:38',
                'updated_at' => '2023-11-23 04:27:38',
            ),
            2 => 
            array (
                'id' => 40,
                'language' => 'id',
                'name' => 'Peningkatan Realisasi Investasi',
                'icon' => 'ruangbelajar/category/zXU8o58Mybrked1IrSn2FboM75muhQ.png',
                'slug' => 'peningkatan-realisasi-investasi',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 04:29:29',
                'updated_at' => '2023-11-23 04:29:29',
            ),
            3 => 
            array (
                'id' => 41,
                'language' => 'id',
                'name' => 'Penanganan Stunting',
                'icon' => 'ruangbelajar/category/PG3jUXvMQTACsuLTtXfuRXnOkGZZmL.png',
                'slug' => 'penanganan-stunting',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 04:30:52',
                'updated_at' => '2023-11-23 04:30:52',
            ),
            4 => 
            array (
                'id' => 42,
                'language' => 'id',
                'name' => 'Pengendalian Inflasi',
                'icon' => 'ruangbelajar/category/Y8y31PLi6x5NjwPn0TGREw5SHhudjm.png',
                'slug' => 'pengendalian-inflasi',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 04:32:22',
                'updated_at' => '2023-11-23 04:32:22',
            ),
            5 => 
            array (
                'id' => 43,
                'language' => 'id',
                'name' => 'Penggunaan Produk Dalam Negeri',
                'icon' => 'ruangbelajar/category/grjecoRqSsEOvBtkV0JTK6X23t0Jr2.png',
                'slug' => 'penggunaan-produk-dalam-negeri',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 04:33:24',
                'updated_at' => '2023-11-23 04:33:24',
            ),
            6 => 
            array (
                'id' => 44,
                'language' => 'id',
                'name' => 'Inovasi Lainnya',
                'icon' => 'ruangbelajar/category/Tnl4ilEe23YGgMEJgeHU1aH4MV4Hl7.png',
                'slug' => 'inovasi-lainnya',
                'show_at_nav' => 0,
                'status' => 1,
                'created_at' => '2023-11-23 04:34:13',
                'updated_at' => '2023-11-23 04:34:13',
            ),
        ));
        
        
    }
}