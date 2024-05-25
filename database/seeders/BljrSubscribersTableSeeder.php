<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrSubscribersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_subscribers')->delete();
        
        \DB::table('bljr_subscribers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'email' => 'test@gmail.com',
                'created_at' => '2023-06-06 07:22:49',
                'updated_at' => '2023-06-06 07:22:49',
            ),
            1 => 
            array (
                'id' => 3,
                'email' => 'test123@gmail.com',
                'created_at' => '2023-06-06 10:25:48',
                'updated_at' => '2023-06-06 10:25:48',
            ),
        ));
        
        
    }
}