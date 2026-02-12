<?php

namespace App\Http\Controllers;


use App\Models\LkeTestTp;
use App\Models\KlpdInstansi;
use App\Models\LkeTestTpLine;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LKE\LkeTestTp as LkeTestTpNew;
use App\Models\LKE\LkeBobot;
use App\Models\LKE\LkeTestTpLine as LkeTestTpLineNew;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IndeksSimpleExport;



class GenerateController extends Controller
{
    #generate indeks-indeks di RB 2023 untuk kebutuhan website 1data.menpan

    public function generate_simple()
    {
        $user = Auth::user();
        if (!in_array($user->level, ['admin', 'tpn'])) {
            abort(403);
        }

        $rows = $this->buildDataset();

        return Excel::download(
            new IndeksSimpleExport($rows),
            'indeks_1data.xlsx'
        );
    }

    private function buildDataset(): array
    {
        $rows = [];
        $instansis = KlpdInstansi::get();
        $indeksPenting = $this->indeksPenting();

        foreach ($instansis as $instansi) {

            $kodeLama = optional($instansi->mapping_kode_instansi)->old_klpd_code ?? '-';
            $kodeBaru = optional($instansi->mapping_kode_instansi)->new_klpd_code;
            $instansiId = $instansi->id;

            /*
            |--------------------------------------------------------------------------
            | 2023
            |--------------------------------------------------------------------------
            */

            // RB 2023
            $rows[] = [
                $kodeLama,
                2023,
                53,
                optional($instansi->lke_test_tp_old)->index_rb_penyesuaian,
                'Indeks RB 2023',
                $instansi->name,
                $kodeBaru,
                $instansiId
            ];

            // Indeks lain 2023
            $lkeTestTP = LkeTestTp::where('lke_instansi_id', $instansiId)->first();

            if ($lkeTestTP) {

                $lines = LkeTestTpLine::where('test_tp_id', $lkeTestTP->id)->get();

                foreach ($lines as $line) {

                    $namaIndeks = $line->paramL4->name;
                    $kodeIndeks = $indeksPenting[$namaIndeks] ?? $namaIndeks;

                    $rows[] = [
                        $kodeLama,
                        2023,
                        $kodeIndeks,
                        $line->score,
                        $namaIndeks,
                        $instansi->name,
                        $kodeBaru,
                        $instansiId
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2024
            |--------------------------------------------------------------------------
            */

            $tp2024 = LkeTestTpNew::where('instansi_id', $instansiId)
                ->where('lke_kegiatan_id', 1)
                ->first();

            // RB 2024
            $rows[] = [
                $kodeLama,
                2024,
                53,
                optional($tp2024)->index_rb,
                'Indeks RB 2024',
                $instansi->name,
                $kodeBaru,
                $instansiId
            ];

            // Parameter 2024
            $parameters = LkeBobot::where('group', $instansi->group)->get();

            foreach ($parameters as $param) {
                if (!$param->lke_parameter->lke_kegiatan_id == 1) {
                    $indikator = $param->lke_parameter->nama;

                    $tpLine = LkeTestTpLineNew::where('lke_bobot_id', $param->id)
                        ->where('instansi_id', $instansiId)
                        ->first();

                    $score = optional($tpLine)->score;
                    $kodeIndeks = $indeksPenting[$indikator] ?? $indikator;

                    $rows[] = [
                        $kodeLama,
                        2024,
                        $kodeIndeks,
                        $score,
                        $indikator,
                        $instansi->name,
                        $kodeBaru,
                        $instansiId
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2025
            |--------------------------------------------------------------------------
            */

            $tp2025 = LkeTestTpNew::where('instansi_id', $instansiId)
                ->where('lke_kegiatan_id', 2)
                ->first();

            // RB 2025
            $rows[] = [
                $kodeLama,
                2025,
                53,
                optional($tp2025)->index_rb,
                'Indeks RB 2025',
                $instansi->name,
                $kodeBaru,
                $instansiId
            ];

            // Parameter 2025
            $parameters = LkeBobot::where('group', $instansi->group)->get();

            foreach ($parameters as $param) {
                if (!$param->lke_parameter->lke_kegiatan_id == 2) {
                    $indikator = $param->lke_parameter->nama;
                    $tpLine = LkeTestTpLineNew::where('lke_bobot_id', $param->id)
                        ->where('instansi_id', $instansiId)
                        ->first();

                    $score = optional($tpLine)->score;
                    $kodeIndeks = $indeksPenting[$indikator] ?? $indikator;

                    $rows[] = [
                        $kodeLama,
                        2025,
                        $kodeIndeks,
                        $score,
                        $indikator,
                        $instansi->name,
                        $kodeBaru,
                        $instansiId
                    ];
                }
            }
        }


        return $rows;
    }

    private function indeksPenting(): array
    {
        return [
            "Indeks BerAkhlak" => 1,
            "Indeks Kualitas Kebijakan" => 2,
            "Indeks Pelayanan Publik" => 3,
            "Indeks Pengelolaan Aset" => 4,
            "Indeks Perencanaan Pembangunan" => 5,
            "Indeks Reformasi Hukum" => 6,
            "Indeks Sistem Merit" => 7,
            "Indeks Sistem Pemerintahan Berbasis Elektronik (SPBE)" => 8,
            "Indeks SPBE" => 8,
            "Indeks Tata Kelola Pengadaan" => 9,
            "Indikator Kinerja Pelaksanaan Anggaran" => 10,
            "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah" => 11,
            "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)" => 11,
            "Nilai Sitem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)" => 11,
            "Nilai SAKIP" => 11,
            "Opini BPK" => 12,
            "Persentase Penyderhanaan Struktur Organisasi" => 13,
            "Persentase Penyederhanaan Struktur Organisasi" => 13,
            "Survei Kepuasan Masyarakat" => 14,
            "Survei Penilaian Integritas" => 15,
            "Tindak Lanjut Rekomendasi" => 16,
            "Tingkat Capaian Sistem Kerja untuk Penyderhanaan Birokrasi" => 17,
            "Tingkat Digitalisasi Arsip" => 18,
            "Tingkat Implementasi Kebijakan Arsitektur Sistem Pemerintahan Berbasis Elektronik" => 19,
            "Tingkat Implementasi Kebijakan Arsitektur Sistem Pemerintahan Berbasis Elektronik (SPBE)" => 19,
            "Tingkat Implementasi Kebijakan Arsitektur SPBE" => 19,
            "Tingkat Keberhasilan Pembangunan Zona Integritas" => 20,
            "Tingkat Keberhasilan Pembangunan ZI" => 20,
            "Tingkat Kematangan Penyelenggaraan Statistik Sektoral" => 21,
            "Indeks Pembangunan Statistik" => 21,
            "Tingkat Kepatuhan Standar Pelayanan Publik" => 22,
            "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah" => 23,
            "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah (SPIP)" => 23,
            "Tingkat Maturitas SPIP" => 23,
            "Tingkat Tindak Lanjut Pengaduan Masyarakat (LAPOR) yang Sudah Diselesaikan" => 24,
            "Tingkat tindak lanjut pengaduan masyarakat (LAPOR) yang sudah diselesaikan" => 24,
            "Rencana Aksi Pembangunan RB General" => 25,
            "TIngkat Implementasi Rencana Aksi RB General" => 26,
            "Tingkat Implementasi Rencana Aksi Pembangunan RB General" => 26,
            "Tingkat Implementasi Rencana Aksi RB General" => 26,
            "Tingkat Capaian Sistem Kerja untuk Penyederhanaan Birokrasi" => 27,
            "Capaian Prioritas Nasional" => 28,
            "Capaian IKU" => 29,
            "Capaian IKU Kementerian/Lembaga" => 29,
            "Capaian IKU Non Makro" => 29,
            "Capaian Indikator Kinerja Non Makro" => 29,
            "Capaian Indikator Kinerja Utama Makro" => 30,
            "Capaian IKU Makro" => 30,
            "Net Koefisien" => 31,
            "Koefisien" => 31,
            "Pengentasan Kemiskinan (Strategi Pembangunan)" => 32,
            "Pengentasan Kemiskinan (Rencana Aksi)" => 33,
            "Pengentasan Kemiskinan (Capaian Output)" => 34,
            "Pengentasan Kemiskinan (Capaian Dampak)" => 35,
            "Penurunan Tingkat Kemiskinan (Capaian Dampak)" => 35,
            "Pengentasan Kemiskinan (Kementerian/Lembaga)"  => 35,
            "Realisasi Investasi (Strategi Pembangunan)" => 36,
            "Realisasi Investasi (Rencana Aksi)" => 37,
            "Realisasi Investasi (Rencana Aksi" => 37,
            "Realisasi Investasi (Capaian Output)" => 38,
            "Realisasi Investasi (Capaian Dampak)" => 39,
            "Peningkatan Realisasi Investasi (Capaian Dampak)" => 39,
            "Realisasi Investasi (Kementerian Lembaga)" => 39,
            "Digitalisasi Administrasi Pemerintahan Berfokus pada Penanganan Stunting (Strategi Pembangunan)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Strategi Pembangunan)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Kementerian/Lembaga)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Rencana Aksi)" => 41,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Capaian Output)" => 42,
            "Digitalisasi Administrasi Pemerintahan Berfokus Penanganan Stunting (Capaian Dampak)" => 43,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Capaian Dampak)" => 43,
            "Penggunaan Produk Dalam Negeri (Strategi Pembangunan)" => 44,
            "Penggunaan Produk Dalam Negeri (Rencana Aksi)" => 45,
            "Penggunaan Produk Dalam Negeri (Capaian Output)" => 46,
            "Penggunaan Produk Dalam Negeri (Capaian Dampak)" => 47,
            "Tingkat Penggunaan Produk Dalam Negeri (Capaian Dampak)" => 47,
            "Penggunaan Produk Dalam Negeri (Kementerian/Lembaga)" => 47,
            "Laju Inflasi (Strategi Pembangunan)" => 48,
            "Laju Inflasi (Rencana Aksi)" => 49,
            "Pengendalian Inflasi (Rencana Aksi)" => 49,
            "Laju Inflasi (Kementerian/Lembaga)" => 49,
            "Laju Inflasi (Capaian Output)" => 50,
            "Pengendalian Inflasi (Capaian Output)" => 50,
            "Laju Inflasi (Capaian Dampak)" => 51,
            "Pengendalian Inflasi (Capaian Dampak)" => 51,
            "Pengendalian Inflasi (Strategi Pembangunan)" => 51,
            "Tingkat Inflasi (Capaian Dampak)" => 51,
            "Tindak Lanjut Rekomendasi BPK" => 52,
        ];
    }
    public function generate_simple_lama()
    {
        $user = Auth::User();
        if (!in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            abort(403);
        }

        $instansis = KlpdInstansi::get();

        $indeks_penting = array(
            "Indeks BerAkhlak" => 1,
            "Indeks Kualitas Kebijakan" => 2,
            "Indeks Pelayanan Publik" => 3,
            "Indeks Pengelolaan Aset" => 4,
            "Indeks Perencanaan Pembangunan" => 5,
            "Indeks Reformasi Hukum" => 6,
            "Indeks Sistem Merit" => 7,
            "Indeks Sistem Pemerintahan Berbasis Elektronik (SPBE)" => 8,
            "Indeks SPBE" => 8,
            "Indeks Tata Kelola Pengadaan" => 9,
            "Indikator Kinerja Pelaksanaan Anggaran" => 10,
            "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah" => 11,
            "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)" => 11,
            "Nilai Sitem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)" => 11,
            "Nilai SAKIP" => 11,
            "Opini BPK" => 12,
            "Persentase Penyderhanaan Struktur Organisasi" => 13,
            "Persentase Penyederhanaan Struktur Organisasi" => 13,
            "Survei Kepuasan Masyarakat" => 14,
            "Survei Penilaian Integritas" => 15,
            "Tindak Lanjut Rekomendasi" => 16,
            "Tingkat Capaian Sistem Kerja untuk Penyderhanaan Birokrasi" => 17,
            "Tingkat Digitalisasi Arsip" => 18,
            "Tingkat Implementasi Kebijakan Arsitektur Sistem Pemerintahan Berbasis Elektronik" => 19,
            "Tingkat Implementasi Kebijakan Arsitektur Sistem Pemerintahan Berbasis Elektronik (SPBE)" => 19,
            "Tingkat Implementasi Kebijakan Arsitektur SPBE" => 19,
            "Tingkat Keberhasilan Pembangunan Zona Integritas" => 20,
            "Tingkat Keberhasilan Pembangunan ZI" => 20,
            "Tingkat Kematangan Penyelenggaraan Statistik Sektoral" => 21,
            "Indeks Pembangunan Statistik" => 21,
            "Tingkat Kepatuhan Standar Pelayanan Publik" => 22,
            "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah" => 23,
            "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah (SPIP)" => 23,
            "Tingkat Maturitas SPIP" => 23,
            "Tingkat Tindak Lanjut Pengaduan Masyarakat (LAPOR) yang Sudah Diselesaikan" => 24,
            "Tingkat tindak lanjut pengaduan masyarakat (LAPOR) yang sudah diselesaikan" => 24,
            "Rencana Aksi Pembangunan RB General" => 25,
            "TIngkat Implementasi Rencana Aksi RB General" => 26,
            "Tingkat Implementasi Rencana Aksi Pembangunan RB General" => 26,
            "Tingkat Implementasi Rencana Aksi RB General" => 26,
            "Tingkat Capaian Sistem Kerja untuk Penyederhanaan Birokrasi" => 27,
            "Capaian Prioritas Nasional" => 28,
            "Capaian IKU" => 29,
            "Capaian IKU Kementerian/Lembaga" => 29,
            "Capaian IKU Non Makro" => 29,
            "Capaian Indikator Kinerja Non Makro" => 29,
            "Capaian Indikator Kinerja Utama Makro" => 30,
            "Capaian IKU Makro" => 30,
            "Net Koefisien" => 31,
            "Koefisien" => 31,
            "Pengentasan Kemiskinan (Strategi Pembangunan)" => 32,
            "Pengentasan Kemiskinan (Rencana Aksi)" => 33,
            "Pengentasan Kemiskinan (Capaian Output)" => 34,
            "Pengentasan Kemiskinan (Capaian Dampak)" => 35,
            "Penurunan Tingkat Kemiskinan (Capaian Dampak)" => 35,
            "Pengentasan Kemiskinan (Kementerian/Lembaga)"  => 35,
            "Realisasi Investasi (Strategi Pembangunan)" => 36,
            "Realisasi Investasi (Rencana Aksi)" => 37,
            "Realisasi Investasi (Rencana Aksi" => 37,
            "Realisasi Investasi (Capaian Output)" => 38,
            "Realisasi Investasi (Capaian Dampak)" => 39,
            "Peningkatan Realisasi Investasi (Capaian Dampak)" => 39,
            "Realisasi Investasi (Kementerian Lembaga)" => 39,
            "Digitalisasi Administrasi Pemerintahan Berfokus pada Penanganan Stunting (Strategi Pembangunan)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Strategi Pembangunan)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Kementerian/Lembaga)" => 40,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Rencana Aksi)" => 41,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Capaian Output)" => 42,
            "Digitalisasi Administrasi Pemerintahan Berfokus Penanganan Stunting (Capaian Dampak)" => 43,
            "Digitalisasi Administrasi Pemerintahan Fokus Penanganan Stunting (Capaian Dampak)" => 43,
            "Penggunaan Produk Dalam Negeri (Strategi Pembangunan)" => 44,
            "Penggunaan Produk Dalam Negeri (Rencana Aksi)" => 45,
            "Penggunaan Produk Dalam Negeri (Capaian Output)" => 46,
            "Penggunaan Produk Dalam Negeri (Capaian Dampak)" => 47,
            "Tingkat Penggunaan Produk Dalam Negeri (Capaian Dampak)" => 47,
            "Penggunaan Produk Dalam Negeri (Kementerian/Lembaga)" => 47,
            "Laju Inflasi (Strategi Pembangunan)" => 48,
            "Laju Inflasi (Rencana Aksi)" => 49,
            "Pengendalian Inflasi (Rencana Aksi)" => 49,
            "Laju Inflasi (Kementerian/Lembaga)" => 49,
            "Laju Inflasi (Capaian Output)" => 50,
            "Pengendalian Inflasi (Capaian Output)" => 50,
            "Laju Inflasi (Capaian Dampak)" => 51,
            "Pengendalian Inflasi (Capaian Dampak)" => 51,
            "Pengendalian Inflasi (Strategi Pembangunan)" => 51,
            "Tingkat Inflasi (Capaian Dampak)" => 51,
            "Tindak Lanjut Rekomendasi BPK" => 52,
        );



        echo '<table border=2>';
        echo '<tr>';
        echo '<th>Kode Instansi 1data lama</th>';
        echo '<th>Tahun</th>';
        echo '<th>Kode Indeks</th>';
        echo '<th>Nilai</th>';
        echo '<th>Nama Indeks</th>';
        echo '<th>Instansi</th>';
        echo '<th>Kode Instansi 1data baru</th>';
        echo '<th>Kode Instansi portalrb </th>';
        echo '</tr>';
        foreach ($instansis as $instansi) {
            if ($instansi->mapping_kode_instansi) {
                $instansi_code_lama = $instansi->mapping_kode_instansi->old_klpd_code;
            } else {
                $instansi_code_lama = "-";
            }
            $instansi_id = $instansi->id;
            $lkeTestTP = LkeTestTp::where("lke_instansi_id", $instansi_id)->first();
            $lkeTestTPLine = "";
            if (isset($lkeTestTP)) {
                $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
            }

            //2023
            //indeksRB 2023
            echo '<tr>';
            echo '<td>' . $instansi_code_lama . '</td>';
            echo '<td> 2023 </td>';
            echo '<td>' . "53" . '</td>';
            if (isset($instansi->lke_test_tp_old->index_rb_penyesuaian)) {
                echo '<td>' . $instansi->lke_test_tp_old->index_rb_penyesuaian . '</td>';
            } else {
                echo "<td></td>";
            }

            echo '<td>' . "Indek RB 2023" . '</td>';
            echo '<td>' . $instansi->name . '</td>';
            if (isset($instansi->mapping_kode_instansi->new_klpd_code)) {
                echo '<td>' . $instansi->mapping_kode_instansi->new_klpd_code . '</td>';
            } else {
                echo "<td></td>";
            }
            echo '<td>' . $instansi->id . '</td>';
            echo '</tr>';

            //Indeks Lainnya 2023
            if ($lkeTestTPLine) {
                foreach ($lkeTestTPLine as $testTPLine) {
                    if (array_key_exists($testTPLine->paramL4->name, $indeks_penting)) {
                        $indeks_id = $indeks_penting[$testTPLine->paramL4->name];
                    } else {
                        $indeks_id = $testTPLine->paramL4->name;
                    }
                    //if (in_array($testTPLine->paramL4->name, $indeks_penting)){
                    echo '<tr>';
                    echo '<td>' . $instansi_code_lama . '</td>';
                    echo '<td> 2023 </td>';
                    echo '<td>' . $indeks_id . '</td>';
                    echo '<td>' . $testTPLine->score . '</td>';
                    echo '<td>' . $testTPLine->paramL4->name . '</td>';
                    echo '<td>' . $instansi->name . '</td>';
                    if (isset($instansi->mapping_kode_instansi->new_klpd_code)) {
                        echo '<td>' . $instansi->mapping_kode_instansi->new_klpd_code . '</td>';
                    } else {
                        echo "<td></td>";
                    }
                    echo '<td>' . $instansi->id . '</td>';
                    echo '</tr>';
                    //}
                }
            }

            //2024
            //indeksRB 2024
            $test_tp_2024 = LkeTestTpNew::where('instansi_id', $instansi->id)->where('lke_kegiatan_id', 1)->first();
            echo '<tr>';
            echo '<td>' . $instansi_code_lama . '</td>';
            echo '<td> 2024 </td>';
            echo '<td>' . "53" . '</td>';
            if (isset($test_tp_2024->index_rb)) {
                echo '<td>' . $test_tp_2024->index_rb . '</td>';
            } else {
                echo "<td></td>";
            }
            echo '<td>' . "Indek RB 2024" . '</td>';
            echo '<td>' . $instansi->name . '</td>';
            if (isset($instansi->mapping_kode_instansi->new_klpd_code)) {
                echo '<td>' . $instansi->mapping_kode_instansi->new_klpd_code . '</td>';
            } else {
                echo "<td></td>";
            }
            echo '<td>' . $instansi->id . '</td>';
            echo '</tr>';

            $parameters = LkeBobot::where('group', $instansi->group)->get();
            foreach ($parameters as $parameter) {
                $parameter->indikator = $parameter->lke_parameter->nama;
                $tp_line = LkeTestTpLineNew::where('lke_bobot_id', $parameter->id)->where('instansi_id', $instansi_id)->first();
                if (!$tp_line) {
                    $tp_line = new LkeTestTpLineNew();
                }
                $parameter->score = $tp_line->score;



                if (array_key_exists($parameter->indikator, $indeks_penting)) {
                    $indeks_id = $indeks_penting[$parameter->indikator];
                } else {
                    $indeks_id = $parameter->indikator;
                }
                echo '<tr>';
                echo '<td>' . $instansi_code_lama . '</td>';
                echo '<td> 2024 </td>';
                echo '<td>' . $indeks_id . '</td>';
                echo '<td>' . $parameter->score . '</td>';
                echo '<td>' . $parameter->indikator . '</td>';
                echo '<td>' . $instansi->name . '</td>';
                if (isset($instansi->mapping_kode_instansi->new_klpd_code)) {
                    echo '<td>' . $instansi->mapping_kode_instansi->new_klpd_code . '</td>';
                } else {
                    echo "<td></td>";
                }
                echo '<td>' . $instansi->id . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }

    #generate nilai RB untuk kebutuhan website 1data.menpan
    public function generateRB2023()
    {
        $instansis = KlpdInstansi::get();
        foreach ($instansis as $instansi) {
            if (isset($instansi->lke_test_tp_old->index_rb_penyesuaian)) {
                echo $instansi->name . " = " . $instansi->lke_test_tp_old->index_rb_penyesuaian . "<br/>";
            }
        }
    }

    public function generateRB2024()
    {
        $instansis = KlpdInstansi::get();
        foreach ($instansis as $instansi) {
            $test_tp = LkeTestTp::where('instansi_id', $instansi->id)->where('lke_kegiatan_id', 1)->first();
            echo $instansi->name . " = " . $instansi->lke_test_tp_old->index_rb_penyesuaian . "<br/>";
        }
    }
}
