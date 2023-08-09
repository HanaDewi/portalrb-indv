<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KegiatanUtamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kegiatan_utama')->insert([
            ['id' => '1', 'nama' => 'Birokrasi (Penyederhanaan Struktur Organisasi)/transformasi organisasi berbasis kinerja dan agile'],
            ['id' => '2', 'nama' => 'Sistem Kerja Baru dengan model fleksibel bagi Pegawai ASN'],
            ['id' => '3', 'nama' => 'Arsitektur SPBE Nasional'],
            ['id' => '4', 'nama' => 'Sistem Akuntabilitas Kinerja Instansi Pemerintah yang terintegrasi'],
            ['id' => '5', 'nama' => 'Pelayanan Publik Digital (khusus Pemerintah Daerah)'],
            ['id' => '6', 'nama' => 'Zona Integritas di unit kerja'],
            ['id' => '7', 'nama' => 'implementasi sistem pengendalian intern pemerintah (SPIP)'],
            ['id' => '8', 'nama' => 'Pengelolaan Pengaduan Masyarakat'],
            ['id' => '9', 'nama' => 'Upaya Pencegahan Korupsi'],
            ['id' => '10', 'nama' => 'Tata Kelola Kebijakan Publik'],
            ['id' => '11', 'nama' => 'Pembentukan Peraturan Perundangan-undangan'],
            ['id' => '12', 'nama' => 'Arsip Digital'],
            ['id' => '13', 'nama' => 'Data Statistik Sektoral'],
            ['id' => '14', 'nama' => 'Pengadaan Barang dan Jasa Pemerintah'],
            ['id' => '15', 'nama' => 'Pengelolaan Keuangan dan Aset'],
            ['id' => '16', 'nama' => 'Jabatan Fungsional'],
            ['id' => '17', 'nama' => 'Manajemen Talenta ASN'],
            ['id' => '18', 'nama' => 'Kinerja Pegawai ASN'],
            ['id' => '19', 'nama' => 'Sistem Merit'],
            ['id' => '20', 'nama' => 'Core Values ASN'],
            ['id' => '21', 'nama' => 'Pelayanan Publik Prima'],
        ]);
    }
}
