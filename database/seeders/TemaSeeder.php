<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tema')->insert([
            ['id' => '1', 'nama' => 'Pengentasan Kemiskinan'],
            ['id' => '2', 'nama' => 'Realisasi Investasi'],
            ['id' => '3', 'nama' => 'Digitalisasi Pemerintahan'],
            ['id' => '4', 'nama' => 'Penggunaan Produk Dalam Negeri'],
            ['id' => '5', 'nama' => ' Pengendalian Inflasi'],
        ]);
    }
}
