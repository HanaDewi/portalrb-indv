<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdministratifSeeder::class,
            InstansiSeeder::class,
            UserSeeder::class,
            KegiatanUtamaSeeder::class,
            IndikatorSeeder::class,
            TemaSeeder::class,

        ]);
        $this->call(BljrCategoriesTableSeeder::class);
        $this->call(BljrLanguagesTableSeeder::class);
        $this->call(BljrNewsTagsTableSeeder::class);
        $this->call(BljrSettingsTableSeeder::class);
        $this->call(BljrSlidersTableSeeder::class);
        $this->call(BljrSocialCountsTableSeeder::class);
        $this->call(BljrSocialLinksTableSeeder::class);
        $this->call(BljrSubscribersTableSeeder::class);
        $this->call(BljrArticlesTableSeeder::class);
    }
}
