<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrLanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_languages')->delete();
        
        \DB::table('bljr_languages')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'Indonesia',
                'lang' => 'id',
                'slug' => 'id',
                'default' => 1,
                'status' => 1,
                'created_at' => '2023-05-27 07:54:48',
                'updated_at' => '2023-05-29 13:35:52',
            ),
            1 => 
            array (
                'id' => 10,
                'name' => 'english',
                'lang' => 'en',
                'slug' => 'en',
                'default' => 0,
                'status' => 1,
                'created_at' => '2023-06-22 04:39:11',
                'updated_at' => '2023-06-22 04:39:11',
            ),
        ));
        
        
    }
}