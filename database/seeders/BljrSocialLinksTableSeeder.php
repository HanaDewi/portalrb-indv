<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrSocialLinksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_social_links')->delete();
        
        \DB::table('bljr_social_links')->insert(array (
            0 => 
            array (
                'id' => 3,
                'icon' => 'fab fa-facebook-f',
                'url' => 'https://www.facebook.com/rbkunwas/?locale=id_ID',
                'status' => 1,
                'created_at' => '2023-06-07 04:43:13',
                'updated_at' => '2023-11-23 07:34:33',
            ),
            1 => 
            array (
                'id' => 4,
                'icon' => 'fab fa-twitter',
                'url' => 'https://twitter.com/rbkunwas',
                'status' => 1,
                'created_at' => '2023-06-07 04:43:23',
                'updated_at' => '2023-11-23 07:35:18',
            ),
            2 => 
            array (
                'id' => 6,
                'icon' => 'fab fa-instagram',
                'url' => 'https://www.instagram.com/rbkunwas/',
                'status' => 1,
                'created_at' => '2023-06-07 04:44:54',
                'updated_at' => '2023-11-23 07:36:18',
            ),
        ));
        
        
    }
}