<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrSocialCountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_social_counts')->delete();
        
        \DB::table('bljr_social_counts')->insert(array (
            0 => 
            array (
                'id' => 2,
                'language' => 'id',
                'icon' => 'fab fa-linkedin-in',
                'fan_count' => '200k',
                'fan_type' => 'Likes',
                'button_text' => 'Likes',
                'color' => '#0a66c2',
                'url' => 'https://www.linkedin.com/',
                'status' => 1,
                'created_at' => '2023-06-04 07:11:06',
                'updated_at' => '2023-06-22 07:15:16',
            ),
            1 => 
            array (
                'id' => 6,
                'language' => 'en',
                'icon' => 'fab fa-facebook-f',
                'fan_count' => '300k',
                'fan_type' => 'Followers',
                'button_text' => 'Likes',
                'color' => '#0b84ee',
                'url' => 'https://www.facebook.com/',
                'status' => 1,
                'created_at' => '2023-06-22 07:16:59',
                'updated_at' => '2023-06-22 07:19:18',
            ),
            2 => 
            array (
                'id' => 9,
                'language' => 'en',
                'icon' => 'fab fa-youtube',
                'fan_count' => '100k',
                'fan_type' => 'Fans',
                'button_text' => 'Subscribe',
                'color' => '#ff0000',
                'url' => 'https://www.facebook.com/',
                'status' => 1,
                'created_at' => '2023-06-22 07:24:25',
                'updated_at' => '2023-06-22 07:24:25',
            ),
        ));
        
        
    }
}