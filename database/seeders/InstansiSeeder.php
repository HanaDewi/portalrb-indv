<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instansi')->insert([
            ['id' => 1, 'kode' => 'D110', 'nama' => 'Daerah Kabupaten Tasikmalaya', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 2, 'kode' => 'D115', 'nama' => 'Daerah Kabupaten Bekasi', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 3, 'kode' => 'D116', 'nama' => 'Daerah Kabupaten Ciamis', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 4, 'kode' => 'D117', 'nama' => 'Daerah Kabupaten Cianjur', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 5, 'kode' => 'D118', 'nama' => 'Daerah Kabupaten Sumedang', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 6, 'kode' => 'D12', 'nama' => 'Daerah Kabupaten Pidie', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 7, 'kode' => 'D120', 'nama' => 'Daerah Kabupaten Subang', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 8, 'kode' => 'D121', 'nama' => 'Daerah Kabupaten Sukabumi', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 9, 'kode' => 'D123', 'nama' => 'Daerah Kabupaten Brebes', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 10, 'kode' => 'D124', 'nama' => 'Daerah Kabupaten Banyumas', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 11, 'kode' => 'D125', 'nama' => 'Daerah Kabupaten Karanganyar', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 12, 'kode' => 'D126', 'nama' => 'Daerah Kabupaten Blora', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 13, 'kode' => 'D128', 'nama' => 'Daerah Kabupaten Kebumen', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 14, 'kode' => 'D13', 'nama' => 'Daerah Kabupaten Pidie Jaya', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 15, 'kode' => 'D130', 'nama' => 'Daerah Kabupaten Purworejo', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 16, 'kode' => 'D131', 'nama' => 'Daerah Kabupaten Banjarnegara', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 17, 'kode' => 'D132', 'nama' => 'Daerah Kabupaten Pekalongan', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 18, 'kode' => 'D133', 'nama' => 'Daerah Kabupaten Pemalang', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 19, 'kode' => 'D135', 'nama' => 'Daerah Kabupaten Cilacap', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 20, 'kode' => 'D136', 'nama' => 'Daerah Kabupaten Batang', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 21, 'kode' => 'D137', 'nama' => 'Daerah Kabupaten Boyolali', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 22, 'kode' => 'D138', 'nama' => 'Daerah Kabupaten Semarang', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 23, 'kode' => 'D139', 'nama' => 'Daerah Kabupaten Sragen', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 24, 'kode' => 'D140', 'nama' => 'Daerah Kabupaten Temanggung', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 25, 'kode' => 'D141', 'nama' => 'Daerah Kabupaten Klaten', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 26, 'kode' => 'D142', 'nama' => 'Daerah Kabupaten Tegal', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 27, 'kode' => 'D144', 'nama' => 'Daerah Kabupaten Grobogan', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 28, 'kode' => 'D145', 'nama' => 'Daerah Kabupaten Kudus', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 29, 'kode' => 'D146', 'nama' => 'Daerah Kabupaten Magelang', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 30, 'kode' => 'D149', 'nama' => 'Daerah Kabupaten Purbalingga', 'jenis_klpd' => 'KABUPATEN', 'kelompok' => 'pemda'],
            ['id' => 31, 'kode' => 'K17', 'nama' => 'Luar Negeri', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 32, 'kode' => 'K18', 'nama' => 'Pekerjaan Umum dan Perumahan Rakyat', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 33, 'kode' => 'K19', 'nama' => 'Desa Pembangunan Daerah Tertinggal dan Transmigrasi RI', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 34, 'kode' => 'K2', 'nama' => 'Badan Usaha Milik Negara', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 35, 'kode' => 'K23', 'nama' => 'Pendidikan dan Kebudayaan', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 36, 'kode' => 'K24', 'nama' => 'Perdagangan', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 37, 'kode' => 'K25', 'nama' => 'Perencanaan Pembangunan Nasional', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 38, 'kode' => 'K26', 'nama' => 'Perhubungan', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 39, 'kode' => 'K27', 'nama' => 'Perindustrian', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 40, 'kode' => 'K28', 'nama' => 'Pertahanan', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 41, 'kode' => 'K29', 'nama' => 'Pertanian', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 42, 'kode' => 'K3', 'nama' => 'Dalam Negeri', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 43, 'kode' => 'K32', 'nama' => 'Sekretariat Negara', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 44, 'kode' => 'K33', 'nama' => 'Sosial', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 45, 'kode' => 'K34', 'nama' => 'Ketenagakerjaan', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 46, 'kode' => 'K35', 'nama' => 'Pariwisata', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 47, 'kode' => 'K37', 'nama' => 'Lingkungan Hidup dan Kehutanan', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 48, 'kode' => 'K38', 'nama' => 'Agraria dan Tata Ruang/BPN', 'jenis_klpd' => 'KEMENTERIAN', 'kelompok' => 'kl'],
            ['id' => 49, 'kode' => 'D35', 'nama' => 'Daerah Provinsi Kepulauan Bangka Belitung', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 50, 'kode' => 'D364', 'nama' => 'Daerah Provinsi Papua Barat', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 51, 'kode' => 'D376', 'nama' => 'Daerah Provinsi Riau', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 52, 'kode' => 'D389', 'nama' => 'Daerah Provinsi Sulawesi Barat', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 53, 'kode' => 'D395', 'nama' => 'Daerah Provinsi Sulawesi Selatan', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 54, 'kode' => 'D421', 'nama' => 'Daerah Provinsi Sulawesi Tengah', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 55, 'kode' => 'D43', 'nama' => 'Daerah Provinsi Banten', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 56, 'kode' => 'D433', 'nama' => 'Daerah Provinsi Sulawesi Tenggara', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 57, 'kode' => 'D446', 'nama' => 'Daerah Provinsi Sulawesi Utara', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 58, 'kode' => 'D462', 'nama' => 'Daerah Provinsi Sumatera Barat', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 59, 'kode' => 'D482', 'nama' => 'Daerah Provinsi Sumatera Selatan', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
            ['id' => 60, 'kode' => 'D498', 'nama' => 'Daerah Provinsi Sumatera Utara', 'jenis_klpd' => 'PROVINSI', 'kelompok' => 'pemda'],
        ]);
    }
}
