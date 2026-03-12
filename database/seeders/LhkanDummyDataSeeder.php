<?php

namespace Database\Seeders;

use App\Models\KlpdInstansi;
use App\Models\LhkanPeriod;
use App\Models\LhkanPic;
use App\Models\LhkanSubmission;
use Illuminate\Database\Seeder;

class LhkanDummyDataSeeder extends Seeder
{
    /**
     * Mengisi data dummy LHKAN: >10 submission dan >10 PIC dengan instansi berbeda
     * untuk uji tampilan (dashboard admin, halaman PIC) sebagai pemda/kl.
     */
    public function run(): void
    {
        $periode = LhkanPeriod::firstOrCreate(
            ['tahun' => 2024],
            [
                'nama' => 'Periode 2024',
                'status' => 'open',
                'deskripsi' => 'Periode LHKAN tahun 2024 (dummy)',
            ]
        );

        $instansiIds = KlpdInstansi::query()
            ->limit(20)
            ->pluck('id')
            ->toArray();

        if (empty($instansiIds)) {
            $this->command->warn('Tidak ada instansi di klpd_instansi_new. Data dummy LHKAN tidak dibuat. Isi instansi terlebih dahulu.');
            return;
        }

        $instansiForSubmissions = array_slice($instansiIds, 0, 12);
        $statuses = ['draft', 'submitted', 'submitted', 'approved', 'approved', 'submitted', 'approved', 'draft', 'submitted', 'approved', 'draft', 'submitted'];

        foreach ($instansiForSubmissions as $i => $instansiId) {
            $jmlAparatur = rand(50, 500);
            $jmlWajib = (int) ($jmlAparatur * 0.4) + rand(0, 20);
            $jmlNonWajib = $jmlAparatur - $jmlWajib;
            $realisasiLhkpn = min($jmlWajib, rand(0, $jmlWajib));
            $realisasiSptNon = min($jmlNonWajib, rand(0, (int)($jmlNonWajib * 0.8)));
            $belumSptNon = max(0, $jmlNonWajib - $realisasiSptNon);
            $totalBelum = ($jmlWajib - $realisasiLhkpn) + $belumSptNon;

            LhkanSubmission::firstOrCreate(
                [
                    'instansi_id' => $instansiId,
                    'periode_id' => $periode->id,
                ],
                [
                    'status' => $statuses[$i],
                    'jml_aparatur' => $jmlAparatur,
                    'jml_wajib_lhkpn' => $jmlWajib,
                    'jml_non_wajib_lhkpn' => $jmlNonWajib,
                    'realisasi_lhkpn' => $realisasiLhkpn,
                    'realisasi_spt_non_lhkpn' => $realisasiSptNon,
                    'belum_spt_non_lhkpn' => $belumSptNon,
                    'total_belum_lhkan' => $totalBelum,
                    'link_rekap_gdrive' => 'https://drive.google.com/dummy/' . $instansiId,
                    'catatan' => 'Data dummy LHKAN',
                    'submitted_at' => in_array($statuses[$i], ['submitted', 'approved']) ? now()->subDays(rand(1, 30)) : null,
                    'approved_at' => $statuses[$i] === 'approved' ? now()->subDays(rand(0, 20)) : null,
                ]
            );
        }

        $instansiForPics = array_slice($instansiIds, 0, 15);
        $namaDummy = [
            'Budi Santoso', 'Siti Rahayu', 'Ahmad Wijaya', 'Dewi Lestari', 'Eko Prasetyo',
            'Fitri Handayani', 'Gilang Ramadhan', 'Hesti Utami', 'Irfan Nugroho', 'Joko Susilo',
            'Kartika Sari', 'Lukman Hakim', 'Maya Kusuma', 'Nanda Putra', 'Oki Pratama',
        ];

        foreach ($instansiForPics as $idx => $instansiId) {
            $nama = $namaDummy[$idx % count($namaDummy)] . ' - Instansi ' . $instansiId;
            $nomor = '0812' . str_pad((string) (1000000 + $instansiId * 1111), 8, '0');

            LhkanPic::firstOrCreate(
                [
                    'instansi_id' => $instansiId,
                    'nama' => $nama,
                    'nomor_hp' => $nomor,
                ],
                [
                    'status' => 'approved',
                    'added_by' => 'admin',
                ]
            );

            if ($idx < 5) {
                LhkanPic::firstOrCreate(
                    [
                        'instansi_id' => $instansiId,
                        'nama' => 'PIC 2 Instansi ' . $instansiId,
                        'nomor_hp' => '0813' . str_pad((string) (2000000 + $instansiId), 8, '0'),
                    ],
                    [
                        'status' => 'approved',
                        'added_by' => 'admin',
                    ]
                );
            }
        }

        $this->command->info('Data dummy LHKAN berhasil dibuat: ' . count($instansiForSubmissions) . ' submission, banyak PIC dengan instansi berbeda.');
    }
}
