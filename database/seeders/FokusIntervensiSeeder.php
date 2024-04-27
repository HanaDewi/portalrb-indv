<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FokusIntervensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fokus_intervensi')->insert([
            ['id' => '1', 'nama' => 'Perencanaan dan Penganggaran'],
            ['id' => '2', 'nama' => 'Proses Bisnis dan SOP'],
            ['id' => '3', 'nama' => 'Sumber Daya Manusia'],
            ['id' => '4', 'nama' => 'Pengawasan'],
            ['id' => '5', 'nama' => 'Teknologi dan Informasi'],
            ['id' => '6', 'nama' => 'Inovasi'],
            ['id' => '7', 'nama' => 'Lain-lain'],
        ]);
    }
}
