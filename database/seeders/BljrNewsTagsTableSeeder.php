<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BljrNewsTagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bljr_news_tags')->delete();
        
        \DB::table('bljr_news_tags')->insert(array (
            0 => 
            array (
                'id' => 423,
                'article_id' => 76,
                'tag_id' => 423,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 424,
                'article_id' => 76,
                'tag_id' => 424,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 425,
                'article_id' => 77,
                'tag_id' => 425,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 426,
                'article_id' => 78,
                'tag_id' => 426,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 427,
                'article_id' => 79,
                'tag_id' => 427,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}