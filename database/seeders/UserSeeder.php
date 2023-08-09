<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['instansi_id' => 1, 'id' => 1, 'kode' => 'd110', 'username' => 'Trial_d110', 'nama' => 'Pemerintah Daerah Kabupaten Tasikmalaya', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 2, 'id' => 2, 'kode' => 'd115', 'username' => 'Trial_d115', 'nama' => 'Pemerintah Daerah Kabupaten Bekasi', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 3, 'id' => 3, 'kode' => 'd116', 'username' => 'Trial_d116', 'nama' => 'Pemerintah Daerah Kabupaten Ciamis', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 4, 'id' => 4, 'kode' => 'd117', 'username' => 'Trial_d117', 'nama' => 'Pemerintah Daerah Kabupaten Cianjur', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 5, 'id' => 5, 'kode' => 'd118', 'username' => 'Trial_d118', 'nama' => 'Pemerintah Daerah Kabupaten Sumedang', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 6, 'id' => 6, 'kode' => 'd12', 'username' => 'Trial_d12', 'nama' => 'Pemerintah Daerah Kabupaten Pidie', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 7, 'id' => 7, 'kode' => 'd120', 'username' => 'Trial_d120', 'nama' => 'Pemerintah Daerah Kabupaten Subang', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 8, 'id' => 8, 'kode' => 'd121', 'username' => 'Trial_d121', 'nama' => 'Pemerintah Daerah Kabupaten Sukabumi', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 9, 'id' => 9, 'kode' => 'd123', 'username' => 'Trial_d123', 'nama' => 'Pemerintah Daerah Kabupaten Brebes', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 10, 'id' => 10, 'kode' => 'd124', 'username' => 'Trial_d124', 'nama' => 'Pemerintah Daerah Kabupaten Banyumas', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 11, 'id' => 11, 'kode' => 'd125', 'username' => 'Trial_d125', 'nama' => 'Pemerintah Daerah Kabupaten Karanganyar', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 12, 'id' => 12, 'kode' => 'd126', 'username' => 'Trial_d126', 'nama' => 'Pemerintah Daerah Kabupaten Blora', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 13, 'id' => 13, 'kode' => 'd128', 'username' => 'Trial_d128', 'nama' => 'Pemerintah Daerah Kabupaten Kebumen', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 14, 'id' => 14, 'kode' => 'd13', 'username' => 'Trial_d13', 'nama' => 'Pemerintah Daerah Kabupaten Pidie Jaya', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 15, 'id' => 15, 'kode' => 'd130', 'username' => 'Trial_d130', 'nama' => 'Pemerintah Daerah Kabupaten Purworejo', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 16, 'id' => 16, 'kode' => 'd131', 'username' => 'Trial_d131', 'nama' => 'Pemerintah Daerah Kabupaten Banjarnegara', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 17, 'id' => 17, 'kode' => 'd132', 'username' => 'Trial_d132', 'nama' => 'Pemerintah Daerah Kabupaten Pekalongan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 18, 'id' => 18, 'kode' => 'd133', 'username' => 'Trial_d133', 'nama' => 'Pemerintah Daerah Kabupaten Pemalang', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 19, 'id' => 19, 'kode' => 'k17', 'username' => 'Trial_k17', 'nama' => 'Kementerian Luar Negeri', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 20, 'id' => 20, 'kode' => 'k18', 'username' => 'Trial_k18', 'nama' => 'Kementerian Pekerjaan Umum dan Perumahan Rakyat', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 21, 'id' => 21, 'kode' => 'k19', 'username' => 'Trial_k19', 'nama' => 'Kementerian Desa Pembangunan Daerah Tertinggal dan Transmigrasi RI', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 22, 'id' => 22, 'kode' => 'k2', 'username' => 'Trial_k2', 'nama' => 'Kementerian Badan Usaha Milik Negara', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 23, 'id' => 23, 'kode' => 'k23', 'username' => 'Trial_k23', 'nama' => 'Kementerian Pendidikan dan Kebudayaan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 24, 'id' => 24, 'kode' => 'k24', 'username' => 'Trial_k24', 'nama' => 'Kementerian Perdagangan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 25, 'id' => 25, 'kode' => 'k25', 'username' => 'Trial_k25', 'nama' => 'Kementerian Perencanaan Pembangunan Nasional', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 26, 'id' => 26, 'kode' => 'k26', 'username' => 'Trial_k26', 'nama' => 'Kementerian Perhubungan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 27, 'id' => 27, 'kode' => 'k27', 'username' => 'Trial_k27', 'nama' => 'Kementerian Perindustrian', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 28, 'id' => 28, 'kode' => 'k28', 'username' => 'Trial_k28', 'nama' => 'Kementerian Pertahanan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 29, 'id' => 29, 'kode' => 'k29', 'username' => 'Trial_k29', 'nama' => 'Kementerian Pertanian', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 30, 'id' => 30, 'kode' => 'k3', 'username' => 'Trial_k3', 'nama' => 'Kementerian Dalam Negeri', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 31, 'id' => 31, 'kode' => 'k32', 'username' => 'Trial_k32', 'nama' => 'Kementerian Sekretariat Negara', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 32, 'id' => 32, 'kode' => 'k33', 'username' => 'Trial_k33', 'nama' => 'Kementerian Sosial', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 33, 'id' => 33, 'kode' => 'k34', 'username' => 'Trial_k34', 'nama' => 'Kementerian Ketenagakerjaan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 34, 'id' => 34, 'kode' => 'k35', 'username' => 'Trial_k35', 'nama' => 'Kementerian Pariwisata', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 35, 'id' => 35, 'kode' => 'k37', 'username' => 'Trial_k37', 'nama' => 'Kementerian Lingkungan Hidup dan Kehutanan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 36, 'id' => 36, 'kode' => 'k38', 'username' => 'Trial_k38', 'nama' => 'Kementerian Agraria dan Tata Ruang/BPN', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 37, 'id' => 37, 'kode' => 'd135', 'username' => 'Trial_d135', 'nama' => 'Pemerintah Daerah Kabupaten Cilacap', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 38, 'id' => 38, 'kode' => 'd136', 'username' => 'Trial_d136', 'nama' => 'Pemerintah Daerah Kabupaten Batang', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 39, 'id' => 39, 'kode' => 'd137', 'username' => 'Trial_d137', 'nama' => 'Pemerintah Daerah Kabupaten Boyolali', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 40, 'id' => 40, 'kode' => 'd138', 'username' => 'Trial_d138', 'nama' => 'Pemerintah Daerah Kabupaten Semarang', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 41, 'id' => 41, 'kode' => 'd139', 'username' => 'Trial_d139', 'nama' => 'Pemerintah Daerah Kabupaten Sragen', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 42, 'id' => 42, 'kode' => 'd140', 'username' => 'Trial_d140', 'nama' => 'Pemerintah Daerah Kabupaten Temanggung', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 43, 'id' => 43, 'kode' => 'd141', 'username' => 'Trial_d141', 'nama' => 'Pemerintah Daerah Kabupaten Klaten', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 44, 'id' => 44, 'kode' => 'd142', 'username' => 'Trial_d142', 'nama' => 'Pemerintah Daerah Kabupaten Tegal', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 45, 'id' => 45, 'kode' => 'd144', 'username' => 'Trial_d144', 'nama' => 'Pemerintah Daerah Kabupaten Grobogan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 46, 'id' => 46, 'kode' => 'd145', 'username' => 'Trial_d145', 'nama' => 'Pemerintah Daerah Kabupaten Kudus', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 47, 'id' => 47, 'kode' => 'd146', 'username' => 'Trial_d146', 'nama' => 'Pemerintah Daerah Kabupaten Magelang', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 48, 'id' => 48, 'kode' => 'd149', 'username' => 'Trial_d149', 'nama' => 'Pemerintah Daerah Kabupaten Purbalingga', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 49, 'id' => 49, 'kode' => 'd35', 'username' => 'Trial_d35', 'nama' => 'Pemerintah Daerah Provinsi Kepulauan Bangka Belitung', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 50, 'id' => 50, 'kode' => 'd364', 'username' => 'Trial_d364', 'nama' => 'Pemerintah Daerah Provinsi Papua Barat', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 51, 'id' => 51, 'kode' => 'd376', 'username' => 'Trial_d376', 'nama' => 'Pemerintah Daerah Provinsi Riau', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 52, 'id' => 52, 'kode' => 'd389', 'username' => 'Trial_d389', 'nama' => 'Pemerintah Daerah Provinsi Sulawesi Barat', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 53, 'id' => 53, 'kode' => 'd395', 'username' => 'Trial_d395', 'nama' => 'Pemerintah Daerah Provinsi Sulawesi Selatan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 54, 'id' => 54, 'kode' => 'd421', 'username' => 'Trial_d421', 'nama' => 'Pemerintah Daerah Provinsi Sulawesi Tengah', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 55, 'id' => 55, 'kode' => 'd43', 'username' => 'Trial_d43', 'nama' => 'Pemerintah Daerah Provinsi Banten', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 56, 'id' => 56, 'kode' => 'd433', 'username' => 'Trial_d433', 'nama' => 'Pemerintah Daerah Provinsi Sulawesi Tenggara', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 57, 'id' => 57, 'kode' => 'd446', 'username' => 'Trial_d446', 'nama' => 'Pemerintah Daerah Provinsi Sulawesi Utara', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 58, 'id' => 58, 'kode' => 'd462', 'username' => 'Trial_d462', 'nama' => 'Pemerintah Daerah Provinsi Sumatera Barat', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 59, 'id' => 59, 'kode' => 'd482', 'username' => 'Trial_d482', 'nama' => 'Pemerintah Daerah Provinsi Sumatera Selatan', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => 60, 'id' => 60, 'kode' => 'd498', 'username' => 'Trial_d498', 'nama' => 'Pemerintah Daerah Provinsi Sumatera Utara', 'password' => bcrypt('123'), 'level' => 'instansi'],
            ['instansi_id' => null, 'id' => 61, 'kode' => 'admin', 'username' => 'admin', 'nama' => 'Administrator', 'password' => bcrypt('rahasiasuper'), 'level' => 'admin'],
            ['instansi_id' => null, 'id' => 62, 'kode' => 'evaluator', 'username' => 'eva', 'nama' => 'Evaluator', 'password' => bcrypt('rahasiaeva'), 'level' => 'eva'],
        ]);
    }
}
