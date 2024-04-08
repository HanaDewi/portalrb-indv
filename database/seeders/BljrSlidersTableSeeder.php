<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrSlidersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_sliders')->delete();
        
        \DB::table('bljr_sliders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'home_top_bar_slider' => 'assets/img/slider.png',
                'home_top_bar_slider_status' => 1,
                'created_at' => '2023-06-06 05:14:15',
                'updated_at' => '2023-06-22 10:33:43',
            ),
            1 => 
            array (
                'id' => 2,
                'home_top_bar_slider' => 'assets/img/slider.png',
                'home_top_bar_slider_status' => 1,
                'created_at' => '2023-06-06 05:14:15',
                'updated_at' => '2023-06-22 10:33:43',
            ),
        ));
        
        
    }
}