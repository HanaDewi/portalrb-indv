<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('indikator')->insert([
            ['id' => '1', 'kegiatan_utama_id' => '1', 'nama' => 'Tingkat Implementasi Penyederhanaan Birokrasi', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '2', 'kegiatan_utama_id' => '2', 'nama' => 'Tingkat Implementasi Sistem kerja Baru dan Fleksibilitas Berkerja Pegawai', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '3', 'kegiatan_utama_id' => '3', 'nama' => 'Indeks SPBE', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '4', 'kegiatan_utama_id' => '3', 'nama' => 'Tingkat Implementasi Inisiatif Strategi Arsitektur SPBE*', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '5', 'kegiatan_utama_id' => '4', 'nama' => 'Indeks Perencanaan Pembangunan***', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '0'],
            ['id' => '6', 'kegiatan_utama_id' => '4', 'nama' => 'Nilai SAKIP', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '7', 'kegiatan_utama_id' => '4', 'nama' => 'Capaian Prioritas Nasional', 'kl' => '1', 'provinsi' => '0', 'kabupaten' => '0'],
            ['id' => '8', 'kegiatan_utama_id' => '4', 'nama' => 'Capaian IKU Makro', 'kl' => '0', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '9', 'kegiatan_utama_id' => '4', 'nama' => 'Capaian IKU', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '10', 'kegiatan_utama_id' => '5', 'nama' => 'Tingkat Implementasi Kebijakan Transformasi Digital MPP*', 'kl' => '0', 'provinsi' => '0', 'kabupaten' => '0'],
            ['id' => '11', 'kegiatan_utama_id' => '6', 'nama' => 'Tingkat keberhasilan pembangunan ZI', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '12', 'kegiatan_utama_id' => '7', 'nama' => 'Tingkat Maturitas SPIP', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '13', 'kegiatan_utama_id' => '8', 'nama' => 'Tingkat Tindak Lanjut Pengaduan Masyarakat (LAPOR)', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '14', 'kegiatan_utama_id' => '9', 'nama' => 'Survei Penilaian Integritas (SPI)', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '15', 'kegiatan_utama_id' => '10', 'nama' => 'Indeks Kualitas Kebijakan', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '16', 'kegiatan_utama_id' => '11', 'nama' => 'Indeks Reformasi Hukum', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '17', 'kegiatan_utama_id' => '12', 'nama' => 'Tingkat Digitalisasi Arsip', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '18', 'kegiatan_utama_id' => '13', 'nama' => 'Tingkat Kematangan Penyelenggaraan Statistik Sektoral', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '19', 'kegiatan_utama_id' => '14', 'nama' => 'Indeks Tata Kelola Pengadaan', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '20', 'kegiatan_utama_id' => '15', 'nama' => 'Opini BPK', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '21', 'kegiatan_utama_id' => '15', 'nama' => 'Tindak Lanjut Rekomendasi BPK', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '22', 'kegiatan_utama_id' => '15', 'nama' => 'Indikator Kinerja Pelaksanaan Anggaran', 'kl' => '1', 'provinsi' => '0', 'kabupaten' => '0'],
            ['id' => '23', 'kegiatan_utama_id' => '15', 'nama' => 'Indeks Pengelolaan Aset', 'kl' => '1', 'provinsi' => '0', 'kabupaten' => '0'],
            ['id' => '24', 'kegiatan_utama_id' => '16', 'nama' => 'Indeks Sistem Merit', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '25', 'kegiatan_utama_id' => '17', 'nama' => 'Indeks Sistem Merit', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '26', 'kegiatan_utama_id' => '18', 'nama' => 'Indeks Sistem Merit', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '27', 'kegiatan_utama_id' => '19', 'nama' => 'Indeks Sistem Merit', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '28', 'kegiatan_utama_id' => '20', 'nama' => 'Indeks Berakhlak*', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '29', 'kegiatan_utama_id' => '20', 'nama' => 'Employer Branding ASN', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '30', 'kegiatan_utama_id' => '21', 'nama' => 'Survey Kepuasan Masyarakat (SKM)', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
            ['id' => '31', 'kegiatan_utama_id' => '21', 'nama' => 'Indeks Pelayanan Publik', 'kl' => '1', 'provinsi' => '1', 'kabupaten' => '1'],
        ]);
    }
}
