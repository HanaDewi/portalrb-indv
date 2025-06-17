@extends('layout.midone', ['akip' => true])
@section('title', 'Hasil Evaluasi SAKIP')

@section('content')
    <div class="intro-y flex items-center">
        <h2 class="text-lg font-medium mr-auto">
            Hasil Evaluasi SAKIP
        </h2>
        <a href="javascript:;" data-toggle="modal" data-target="#header-footer-modal-preview"
            class="button inline-block bg-theme-1 text-white">Tambah Penilaian</a>
    </div>

    {{-- Card Penilaian Provinsi --}}
    <div class="intro-y box mt-5">
        <div class="flex items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Hasil Sementara evaluasi SAKIP Pemerintah Daerah Provinsi Jawa Barat
            </h2>
            <button type="button" class="button button--sm block bg-theme-12 text-white">Edit Penilaian</button>
        </div>
        <div class="intro-y p-5">
            <div class="w-full">
                <span class="font-medium">Tahun : </span>2025
            </div>
            <div class="w-full">
                <span class="font-medium">Periode : </span>TW 2
            </div>
            <div class="w-full">
                <span class="font-medium">Penanggung Jawab : </span>Pak Amir
            </div>
            <div class="w-full">
                <span class="font-medium">PIC LKE : </span>Pak Asep
            </div>
            <div class="w-full">
                <span class="font-medium">Link LKE : </span><a href="javascript:;"
                    class="text-blue-500">http://link.lke/lke_jabar</a>
            </div>
        </div>
        <div class="intro-y grid grid-cols-12 gap-5 p-5">
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Perencanaan Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                            sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                            sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                            sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                            sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2 border-t border-gray-200">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Total Evaluasi AKIP TW 2</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-10 rounded px-2 py-2">80</div>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 lg:col-span-4 border-l-2 border-theme-2">
                <div class="font-medium text-base mr-auto p-3 border-b border-gray-200">
                    Hasil Capaian Indikator Makro
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Angka Kemiskinan</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Laju Pertumbuhan Ekonomi</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Tingkat Pengangguran terbuka</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Penurunan emisi GRK</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Indeks Pembangunan Manusia</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Indeks Gini Ratio</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Pendapatan Perkapita</div>
                    <div class="ml-auto">76</div>
                </div>
                <hr class="my-5">
                <button class="button border items-center text-gray-700 hidden sm:flex ml-3"> <i data-feather="file"
                        class="w-4 h-4 mr-2"></i> Download File Catatan Evaluasi</button>
            </div>
        </div>
    </div>
    {{-- End Card Penilaian Provinsi --}}

    {{-- Card Penilaian Kabupaten/Kota --}}
    <div class="intro-y box mt-5">
        <div class="flex items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Hasil Sementara evaluasi SAKIP Pemerintah Daerah Kota Bandung
            </h2>
            <button type="button" class="button button--sm block bg-theme-12 text-white">Edit Penilaian</button>
        </div>
        <div class="intro-y p-5">
            <div class="w-full">
                <span class="font-medium">Tahun : </span>2025
            </div>
            <div class="w-full">
                <span class="font-medium">Periode : </span>TW 2
            </div>
            <div class="w-full">
                <span class="font-medium">Penanggung Jawab : </span>Pak Ujang
            </div>
            <div class="w-full">
                <span class="font-medium">PIC LKE : </span>Pak Abdul
            </div>
            <div class="w-full">
                <span class="font-medium">Link LKE : </span><a href="javascript:;"
                    class="text-blue-500">http://link.lke/lke_jabar</a>
            </div>
        </div>
        <div class="intro-y grid grid-cols-12 gap-5 p-5">
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Perencanaan Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2 border-t border-gray-200">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Total Evaluasi AKIP TW 2</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-10 rounded px-2 py-2">80</div>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 lg:col-span-4 border-l-2 border-theme-2">
                <div class="font-medium text-base mr-auto p-3 border-b border-gray-200">
                    Hasil Capaian Indikator Makro
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Angka Kemiskinan</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Laju Pertumbuhan Ekonomi</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Tingkat Pengangguran terbuka</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Penurunan emisi GRK</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Indeks Pembangunan Manusia</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Indeks Gini Ratio</div>
                    <div class="ml-auto">76</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Pendapatan Perkapita</div>
                    <div class="ml-auto">76</div>
                </div>
                <hr class="my-5">
                <button class="button border items-center text-gray-700 hidden sm:flex ml-3"> <i data-feather="file"
                        class="w-4 h-4 mr-2"></i> Download File Catatan Evaluasi</button>
            </div>
        </div>
    </div>
    {{-- End Card Penilaian Kabupaten/Kota --}}

    {{-- Card Penilaian Kementerian Lain --}}
    <div class="intro-y box mt-5">
        <div class="flex items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Hasil Sementara evaluasi SAKIP Kementerian Keuangan
            </h2>
            <button type="button" class="button button--sm block bg-theme-12 text-white">Edit Penilaian</button>
        </div>
        <div class="intro-y p-5 flex items-center justify-between flex-col sm:flex-row">
            <div>
                <div class="w-full">
                    <span class="font-medium">Tahun : </span>2025
                </div>
                <div class="w-full">
                    <span class="font-medium">Penanggung Jawab : </span>Ibu Sri
                </div>
                <div class="w-full">
                    <span class="font-medium">PIC LKE : </span>Pak Slamet
                </div>
                <div class="w-full">
                    <span class="font-medium">Link LKE : </span><a href="javascript:;"
                        class="text-blue-500">http://link.lke/lke_jabar</a>
                </div>
            </div>
            <button class="button border items-center text-gray-700 flex"> <i data-feather="file"
                    class="w-4 h-4 mr-2"></i> Download File Surat Pengantar LHE</button>
        </div>
        <div class="intro-y grid grid-cols-12 gap-5 p-5">
            <div class="intro-y col-span-12">
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Perencanaan Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                        <div class="text-gray-600">Catatan : Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Aenean sit amet egestas felis. Cras imperdiet nec augue quis vehicula. Vivamus blandit vehicula
                            bibendum. Proin a justo rutrum, molestie tellus ut, elementum odio. Duis lobortis dictum
                            consectetur. Pellentesque ultrices velit eu sollicitudin commodo. Pellentesque quis odio nec leo
                            condimentum mollis at vel velit. Proin vitae dapibus magna.</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">80</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2 border-t border-gray-200">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Total Evaluasi AKIP TW 2</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-10 rounded px-2 py-2">80</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Card Penilaian Kementerian Lain --}}


    {{-- Modal Tambah --}}
    <div class="modal" id="header-footer-modal-preview">
        <div class="modal__content modal__content--xl">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    Tambah Penilaian
                </h2>
            </div>
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-12">
                    <label>Instansi</label>
                    <div class="mt-2 mb-2">
                        {!! Form::select('instansi_id', instansi_tim(), null, [
                            'class' => 'select2 w-full',
                            'id' => 'instansi_id',
                            'data-placeholder' => 'Pilih Instansi',
                            'required',
                            'multiple',
                        ]) !!}
                    </div>
                    <label>Tahun</label>
                    <div class="mt-2 mb-2">
                        {!! Form::select('tahun', ['2025' => '2025'], null, [
                            'class' => 'select2 w-full no-search',
                            'id' => 'tahun',
                            'data-placeholder' => 'Pilih Periode',
                            'required',
                        ]) !!}
                    </div>
                    <label>Periode</label>
                    <div class="mt-2 mb-2">
                        {!! Form::select(
                            'periode',
                            ['TW 1' => 'TW 1', 'TW 2' => 'TW 2', 'TW 3' => 'TW 3', 'TW 4' => 'TW 4', 'Final' => 'Final'],
                            null,
                            [
                                'class' => 'select2 w-full no-search',
                                'id' => 'periode',
                                'data-placeholder' => 'Pilih Periode',
                                'required',
                            ],
                        ) !!}
                    </div>
                    <label>Penanggung Jawab</label>
                    <input type="text" class="input w-full border mt-2 mb-2 flex-1"
                        placeholder="Nama Penanggung Jawab">
                    <label>PIC LKE</label>
                    <input type="text" class="input w-full border mt-2 mb-2 flex-1" placeholder="Nama PIC LKE">
                    <label>Link LKE</label>
                    <input type="text" class="input w-full border mt-2 mb-2 flex-1" placeholder="Nama PIC LKE">
                </div>
                <div class="col-span-12">
                    <hr>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Perencanaan Kinerja</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan" id="catatan" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Perencanaan Kinerja"></textarea>
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan" id="catatan" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Pengukuran Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan" id="catatan" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Pelaporan Kinerja"></textarea>
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan" id="catatan" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Evaluasi Internal"></textarea>
                </div>
                <div class="col-span-12">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Total Evaluasi AKIP</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                </div>
                <div class="col-span-12">
                    <hr>
                </div>
                <div class="col-span-12">
                    <div class="font-medium">Input Hasil Capaian Indikator Makro</div>
                    <div class="flex items-center">
                        <div>Angka Kemiskinan</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div>Laju Pertumbuhan Ekonomi</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div>Tingkat Pengangguran terbuka</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div>Penurunan emisi GRK</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div>Indeks Pembangunan Manusia</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div>Indeks Gini Ratio</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div>Pendapatan Perkapita</div>
                        <div class="ml-auto">
                            <input type="text" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                <button type="button" class="button w-20 bg-theme-1 text-white">Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush
@push('js')
    <script>
        $(".select2").select2({
            escapeMarkup: function(markup) {
                return markup;
            },
        });

        $(".no-search").select2({
            minimumResultsForSearch: -1
        });
    </script>
@endpush
