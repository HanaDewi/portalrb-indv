<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_settings')->delete();
        
        \DB::table('bljr_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'site_name',
                'value' => 'Top News',
                'created_at' => '2023-06-11 05:51:50',
                'updated_at' => '2023-06-11 05:51:50',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'site_logo',
                'value' => 'uploads/PLptCMbjJY3ysW2L26gqE5Kx2X5nsO.png',
                'created_at' => '2023-06-11 05:51:50',
                'updated_at' => '2023-06-11 05:51:50',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'site_favicon',
                'value' => 'uploads/TDEfYu0SAHEAbaDvb6jMQ6vGyxl53a.png',
                'created_at' => '2023-06-11 05:51:50',
                'updated_at' => '2023-06-11 05:51:50',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'site_seo_title',
                'value' => 'Carson Moran',
                'created_at' => '2023-06-11 08:05:31',
                'updated_at' => '2023-06-11 08:05:31',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'site_seo_description',
                'value' => 'Quaerat vitae nostru',
                'created_at' => '2023-06-11 08:05:31',
                'updated_at' => '2023-06-11 08:05:31',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'site_seo_keywords',
                'value' => 'Chantale Dickson,test',
                'created_at' => '2023-06-11 08:05:31',
                'updated_at' => '2023-06-11 08:10:07',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'site_color',
                'value' => '#0073ff',
                'created_at' => '2023-06-11 10:45:30',
                'updated_at' => '2023-06-24 10:25:35',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'site_microsoft_api_host',
                'value' => 'microsoft-translator-text.p.rapidapi.com',
                'created_at' => '2023-06-21 10:44:20',
                'updated_at' => '2023-06-21 10:49:24',
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'site_microsoft_api_key',
                'value' => '9644c1868amsh7d7ad4b2feb85afp1973f8jsneb5a65f1a736',
                'created_at' => '2023-06-21 10:44:20',
                'updated_at' => '2023-06-21 10:49:24',
            ),
        ));
        
        
    }
}