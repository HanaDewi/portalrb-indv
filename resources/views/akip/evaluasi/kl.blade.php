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
    @include('common.status')
    {{-- Card Penilaian Kementerian Lain --}}
    <div class="intro-y box mt-5">
        <div class="flex items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Hasil Sementara evaluasi SAKIP {{ $instansi->nama_instansi }}
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
            <button class="button border items-center text-gray-700 flex"> <i data-feather="file" class="w-4 h-4 mr-2"></i>
                Download File Surat Pengantar LHE</button>
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
            {{ html()->form('POST', '/akip/evaluasi/sakip/'.$instansi->id.'/simpan')->open() }}
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-12 lg:col-span-6">
                    {{ html()->label('Tahun')->for('tahun2') }}
                    <div class="mt-2">
                        {{ html()->select('tahun', ['2025' => '2025'], null)->id('tahun')->class('select2 w-full hide-search')->placeholder('Pilih Tahun')->required() }}
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <label>Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" id="penanggung_jawab" class="input w-full border mt-2 flex-1" placeholder="Nama Penanggung Jawab" required>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <label>PIC LKE</label>
                    <input type="text" name="pic_lke" id="pic_lke" class="input w-full border mt-2 flex-1" placeholder="Nama PIC LKE" required>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <label>Link LKE</label>
                    <input type="text" name="link_lke" id="link_lke" class="input w-full border mt-2 flex-1" placeholder="Link LKE" required>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <label>File Surat Pengantar LHE</label>
                    <input type="file" name="file_evaluasi" id="file_evaluasi" class="input w-full border mt-2 flex-1" placeholder="Link LKE" required>
                </div>
                <div class="col-span-12">
                    <hr>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Perencanaan Kinerja</div>
                        <div class="ml-auto">
                            <input type="text" name="nilai_komponen_perencanaan_kinerja" id="nilai_komponen_perencanaan_kinerja" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea  name="catatan_komponen_perencanaan_kinerja" id="catatan_komponen_perencanaan_kinerja" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Perencanaan Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                        <div class="ml-auto">
                            <input type="text" name="nilai_komponen_pengukuran_kinerja" id="nilai_komponen_pengukuran_kinerja" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_pengukuran_kinerja" id="catatan_komponen_pengukuran_kinerja" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Pengukuran Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                        <div class="ml-auto">
                            <input type="text" name="nilai_komponen_pelaporan_kinerja" id="nilai_komponen_pelaporan_kinerja" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_pelaporan_kinerja" id="catatan_komponen_pelaporan_kinerja" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Pelaporan Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                        <div class="ml-auto">
                            <input type="text" name="nilai_komponen_evaluasi_internal" id="nilai_komponen_evaluasi_internal" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_evaluasi_internal" id="catatan_komponen_evaluasi_internal" class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Evaluasi Internal"></textarea>
                </div>
                <div class="col-span-12">
                    <div class="flex items-center">
                        <div class="font-medium">Nilai Total Evaluasi AKIP</div>
                        <div class="ml-auto">
                            <input type="text" name="nilai_total_evaluasi_akip" id="nilai_total_evaluasi_akip" class="input w-20 border flex-1 mt-2">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                <button type="submit" class="button w-20 bg-theme-1 text-white">Simpan</button>
            </div>
            {{ html()->form()->close() }}
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
