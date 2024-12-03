<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lke_renaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria', 100)->nullable();
            $table->integer('parent_id')->nullable();
            $table->mediumText('info')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();
        });
        DB::table('lke_renaksi')->truncate();
        DB::table('lke_renaksi')->insert(array(
            [ 'kriteria' => 'Komponen/Kriteria', 'parent_id' => NULL, 'info' => NULL, 'tahun' => 2024 ],
            [ 'kriteria' => 'Strategi Pelaksanaan RB General', 'parent_id' => 1, 'info' => NULL, 'tahun' => 2024 ],
            [ 'kriteria' => 'Penilaian Kegiatan Utama Road Map Reformasi Birokrasi', 'parent_id' => 2, 'info' => NULL, 'tahun' => 2024 ],
            [ 'kriteria' => 'Penetapan Kegiatan Utama', 'parent_id' => 3, 'info' => 'KONDISI
Seluruh kegiatan utama yang diamanatkan dalam Road Map RB Nasional telah ditetapkan pada Road Map RB kementerian/lembaga/pemerintah daerah.

GRADING PENILAIAN
A. apabila 100% kegiatan utama dalam roadmap RB Nasional telah ditetapkan dalam Roadmap RB instansional;
B. apabila 75% ≤ kegiatan utama dalam roadmap RB Nasional  telah ditetapkan dalam Roadmap RB instansional; < 100%;
C. apabila 50% ≤ kegiatan utama dalam roadmap RB Nasional  telah ditetapkan dalam Roadmap RB instansional;  <75%;
D. apabila 10% ≤ kegiatan utama dalam roadmap RB Nasional  telah ditetapkan dalam Roadmap RB instansional; ; < 50%
E. apabila kegiatan utama dalam roadmap RB Nasional  telah ditetapkan dalam Roadmap RB instansional; < 10% 

PENJELASAN
- Dasar penilaian adalah dari portal RB nasional. 
- Evaluator perlu memberikan catatan apabila ditemukan kondisi sebagai berikut: 
a. IP belum menyusun Rencana Aksi', 'tahun' => 2024 ],

            [ 'kriteria' => 'Penetapan Target Indikator Kegiatan Utama', 'parent_id' => 3, 'info' => 'KONDISI
Seluruh indikator kegiatan utama yang ditetapkan telah dikawal dengan target yang logis, realistis, dan berorientasi peningkatan kinerja

GRADING PENIALAIAN
A. apabila 100% indikator kegiatan utama yang ditetapkan telah dikawal dengan target yang logis, realistis, dan berorientasi peningkatan kinerja;
B. apabila 75% ≤ indikator kegiatan utama yang ditetapkan telah dikawal dengan target yang logis, realistis, dan berorientasi peningkatan kinerja; < 100%;
C. apabila 50% ≤ indikator kegiatan utama yang ditetapkan telah dikawal dengan target yang logis, realistis, dan berorientasi peningkatan kinerja; <75%;
D. apabila 10% ≤ indikator kegiatan utama yang ditetapkan telah dikawal dengan target yang logis, realistis, dan berorientasi peningkatan kinerja; < 50%
E. apabila indikator kegiatan utama yang ditetapkan telah dikawal dengan target yang logis, realistis, dan berorientasi peningkatan kinerja; < 10% 

PENJELASAN
- Target yang logis, realistis, dan berorientasi pada peningkatan kinerja adalah target yang penetapannya didasarkan pada baseline atas tahun sebelumnya, mempertimbangkan target nasional, serta berorientasi pada pencapaian kondisi yang lebih baik dari tahun-tahun sebelumnya. Cara mengetahui apakah sebuah target logis, realistis, dan berorientasi pada peningkatan kinerja adalah dengan membandingkan antara target tahun ini dengan realisasi tahun sebelumnya, serta bandingkan dengan target nasional yang ditetapkan dalam Kepmen 739 Tahun 2023. 
- Penetapan target "Baik" sesuai dengan Kepmen 
- Jika K/L tidak mencantumkan target tahun 2024 maka penilaian fokus pada tahun 2023 saja dengan membandingkan dengan baseline tahun sebelumnya.', 'tahun' => 2024 ],

            [ 'kriteria' => 'Keabsahan Rencana Aksi', 'parent_id' => 3, 'info' => 'KONDISI
Telah terdapat pernyataan keabsahan atas rencana aksi yang ditetapkan.

GRADING PENILAIAN
Ya: Telah terdapat pernyataan keabsahan atas rencana aksi yang ditetapkan
Tidak: Tidak terdapat pernyataan keabsahan atas rencana aksi yang ditetapkan

PENJELASAN
Pernyataan keabsahan harus ditandatangani oleh pejabat yang bertanggung jawab atas pelaksanaan RB internal, antara lain: 
1. Sekjen/Sesmen/Sesma/Sestama/Asrenum/Asrena
2. Irjen/Inspektur/Irtama/pejabat yang mengkoordinatori evaluasi internal', 'tahun' => 2024 ],

            [ 'kriteria' => 'Kriteria Penilaian Penetapan Rencana Aksi', 'parent_id' => 2, 'info' => NULL, 'tahun' => 2024 ],

            [ 'kriteria' => 'Kelogisan Rencana Aksi', 'parent_id' => 7, 'info' => 'KONDISI
Penetapan rencana aksi memperhatikan kelogisan aksi dengan kebutuhan. Penetapan rencana aksi juga telah melalui proses analisis, termasuk memperhatikan kondisi baseline/eksisting yang direpresentasikan melalui indikator immediate outcome.

GRADING PENILAIAN
A. apabila 100% rencana aksi telah logis untuk memenuhi kebutuhan pencapaian sasaran RB general; 
B. apabila 75% ≤ rencana aksi telah logis untuk memenuhi kebutuhan pencapaian sasaran RB general;  < 100%;
C. apabila 50% ≤ rencana aksi telah logis untuk memenuhi kebutuhan pencapaian sasaran RB general;  <75%;
D. apabila 10% ≤ rencana aksi telah logis untuk memenuhi kebutuhan pencapaian sasaran RB general;  < 50%
E. apabila rencana aksi telah logis untuk memenuhi kebutuhan pencapaian sasaran RB general;  < 10% 

PENJELASAN
Rencana aksi disusun berdasarkan kebutuhan untuk mencapai target yang telah ditetapkan. Evaluator perlu menganalisis relevansi serta kecukupan rencana aksi yang ditetapkan jika dibandingkan dengan sasaran dan target yang telah ditetapkan.', 'tahun' => 2024 ],
            
            [ 'kriteria' => 'Relevansi dan Kecukupan Indikator Output', 'parent_id' => 7, 'info' => 'KONDISI
Penetapan indikator output pada setiap aksi perlu memenuhi kriteria relevansi dan cukup dalam menjawab aksi yang ditetapkan.

GRADING PENILAIAN
A. apabila 100% rencana aksi telah memiliki indikator yang relevan dan cukup untuk mengukur keberhasilan pencapaian rencana aksi; 
B. apabila 75% ≤ rencana aksi telah memiliki indikator yang relevan dan cukup untuk mengukur keberhasilan pencapaian rencana aksi;  < 100%;
C. apabila 50% ≤ rencana aksi telah memiliki indikator yang relevan dan cukup untuk mengukur keberhasilan pencapaian rencana aksi;  <75%;
D. apabila 10% ≤ rencana aksi telah memiliki indikator yang relevan dan cukup untuk mengukur keberhasilan pencapaian rencana aksi;  < 50%
E. apabila rencana aksi telah memiliki indikator yang relevan dan cukup untuk mengukur keberhasilan pencapaian rencana aksi;  < 10% 

PENJELASAN
A. yang dimaksud dengan indikator yang relevan adalah indikator yang jika digunakan menggambarkan sedekat mungkin realisasi sasaran yang telah ditetapkan
B. yang dimaksud dengan indikator yang penting adalah indikator yang mendesak atau harus digunakan dan tidak bisa digantikan dengan indikator lainnya. 
C. yang dimaksud dengan indikator yang cukup adalah indikator tersebut dapat menggambarkan secara cukup atau memenuhi aspek-aspek yang perlu diukur dari sebuah statement sasaran/kondisi', 'tahun' => 2024 ],

            [ 'kriteria' => 'Ketetapan Penetapan Target Indikator Output', 'parent_id' => 7, 'info' => 'KONDISI
Ketepatan penetapan target pada setiap indikator output mencakup aspek kejelasan, kelayakan, dan keterukuran dari target yang ditetapkan untuk mengukur hasil atau capaian dari suatu aksi.

GRADING PENILAIAN
A. apabila 100% indikator pada rencana aksi telah memiliki target yang ditetapkan berdasarkan baseline tahun sebelumnya, serta berorientasi pada peningkatan kinerja; 
B. apabila 75% ≤ indikator pada rencana aksi telah memiliki target yang ditetapkan berdasarkan baseline tahun sebelumnya, serta berorientasi pada peningkatan kinerja;  < 100%;
C. apabila 50% ≤ indikator pada rencana aksi telah memiliki target yang ditetapkan berdasarkan baseline tahun sebelumnya, serta berorientasi pada peningkatan kinerja;  <75%;
D. apabila 10% ≤ indikator pada rencana aksi telah memiliki target yang ditetapkan berdasarkan baseline tahun sebelumnya, serta berorientasi pada peningkatan kinerja;  < 50%
E. apabila indikator pada rencana aksi telah memiliki target yang ditetapkan berdasarkan baseline tahun sebelumnya, serta berorientasi pada peningkatan kinerja;  < 10% 

PENJELASAN
A. Target yang logis, realistis, dan berorientasi pada peningkatan kinerja adalah target yang penetapannya didasarkan pada baseline atas tahun sebelumnya, mempertimbangkan target nasional, serta berorientasi pada pencapaian kondisi yang lebih baik dari tahun-tahun sebelumnya. Cara mengetahui apakah sebuah target logis, realistis, dan berorientasi pada peningkatan kinerja adalah dengan membandingkan antara target tahun ini dengan realisasi tahun sebelumnya, serta bandingkan dengan kebutuhan mencapai target pada indikator level sasaran.', 'tahun' => 2024 ],
            
            [ 'kriteria' => 'Anggaran', 'parent_id' => 7, 'info' => 'KONDISI
Ketersediaan anggaran yang memadai dalam mendukung pelaksanaan aksi yang telah ditetapkan.

GRADING PENILAIAN
A. apabila 100% rencana aksi telah didukung oleh anggaran yang memadai; 
B. apabila 75% ≤ rencana aksi telah didukung oleh anggaran yang memadai; < 100%;
C. apabila 50% ≤rencana aksi telah didukung oleh anggaran yang memadai; <75%;
D. apabila 10% ≤rencana aksi telah didukung oleh anggaran yang memadai; < 50%
E. apabila rencana aksi telah didukung oleh anggaran yang memadai;  < 10% 

PENJELASAN
Yang dimaksud dengan anggaran yang memadai adalah anggaran yang secara common sense mampu memenuhi kebutuhan aksi namun tetap tidak mengabaikan unsur efektivitas, efisiensi, dan ekonomi', 'tahun' => 2024 ],
            
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_renaksi');
    }
};
