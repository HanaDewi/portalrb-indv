@extends('layout.midone', ['akip' => true])
@section('title', 'Hasil Evaluasi SAKIP')

@section('content')
    <div class="intro-y flex items-center">
        <h2 class="text-lg font-medium mr-auto">
            Hasil Evaluasi SAKIP
        </h2>
        <a href="javascript:;" data-toggle="modal" data-target="#modal-form-evaluasi"
            class="button inline-block bg-theme-1 text-white" onclick="resetForm();">Tambah Penilaian</a>
    </div>
    @include('common.status_midone')
    @if (count($evaluasi_sakip) == 0)
        <div class="intro-y box p-5 mt-5">
            <div class="text-center">
                <p class="text-gray-600">Belum ada penilaian SAKIP untuk instansi ini.</p>
                <p class="text-gray-600">Silakan tambahkan penilaian SAKIP terlebih dahulu.</p>
            </div>
        </div>
    @else
        @foreach ($evaluasi_sakip as $evaluasi)
            {{-- Card Penilaian Provinsi --}}
            <div class="intro-y box mt-5">
                <div class="flex items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Hasil sementara evaluasi SAKIP {{ $evaluasi->instansi->nama_instansi }}
                    </h2>
                    <button type="button" class="button button--sm block bg-theme-6 text-white mr-3"
                        onclick="hapusEvaluasi('{{ $evaluasi->id }}');">Hapus Hasil Evaluasi</button>
                    <button type="button" class="button button--sm block bg-theme-12 text-white"
                        onclick="editEvaluasi('{{ $evaluasi->id }}');">Edit Penilaian</button>
                </div>
                @if ($instansi->group == 'kl')
                    <div class="intro-y p-5 flex items-center justify-between flex-col sm:flex-row">
                        <div>
                        @else
                            <div class="intro-y p-5">
                @endif
                <div class="w-full">
                    <span class="font-medium">Tahun : </span>{{ $evaluasi->tahun }}
                </div>
                <div class="w-full">
                    <span class="font-medium">Periode : </span>{{ $evaluasi->periode }}
                </div>
                <div class="w-full">
                    <span class="font-medium">Penanggung Jawab : </span>{{ $evaluasi->penanggung_jawab }}
                </div>
                <div class="w-full">
                    <span class="font-medium">PIC LKE : </span>{{ $evaluasi->pic_lke }}
                </div>
                <div class="w-full">
                    <span class="font-medium">Link LKE : </span><a href="{{ $evaluasi->link_lke }}" target="_blank"
                        class="text-blue-500">{{ $evaluasi->link_lke }}</a>
                </div>
                @if ($instansi->group == 'kl')
                    </div>
                    <button class="button border items-center text-gray-700 flex"> <i data-feather="file" class="w-4 h-4 mr-2"></i>
                        Download File Surat Pengantar LHE</button>
                @endif
        </div>
        <hr>
        <div class="intro-y grid grid-cols-12 gap-5 p-5">
            <div class="intro-y col-span-12 {{ $instansi->group != 'kl' ? 'lg:col-span-8' : '' }}">
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Perencanaan Kinerja</div>
                        <div class="text-gray-600">Catatan : {{ $evaluasi->catatan_komponen_perencanaan_kinerja }}
                        </div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">
                            {{ $evaluasi->nilai_komponen_perencanaan_kinerja }}</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                        <div class="text-gray-600">Catatan : {{ $evaluasi->catatan_komponen_pengukuran_kinerja }}
                        </div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">
                            {{ $evaluasi->nilai_komponen_pengukuran_kinerja }}</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                        <div class="text-gray-600">Catatan : {{ $evaluasi->catatan_komponen_pelaporan_kinerja }}
                        </div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">
                            {{ $evaluasi->nilai_komponen_pelaporan_kinerja }}</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                        <div class="text-gray-600">Catatan : {{ $evaluasi->catatan_komponen_evaluasi_internal }}
                        </div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-9 rounded px-2 py-2">
                            {{ $evaluasi->nilai_komponen_evaluasi_internal }}</div>
                    </div>
                </div>
                <div class="flex flex-row items-center mb-2 py-2 border-t border-gray-200">
                    <div class="mr-auto border-l-2 border-theme-1 pl-4">
                        <div class="font-medium">Nilai Total Evaluasi AKIP TW 2</div>
                    </div>
                    <div class="flex items-center mt-0">
                        <div class="bg-theme-18 text-theme-10 rounded px-2 py-2">
                            {{ $evaluasi->nilai_total_evaluasi_akip }}</div>
                    </div>
                </div>
            </div>
            @if ($instansi->group != 'kl')
            <div class="intro-y col-span-12 lg:col-span-4 border-l-2 border-theme-2">
                <div class="font-medium text-base mr-auto p-3 border-b border-gray-200">
                    Hasil Capaian Indikator Makro
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Angka Kemiskinan</div>
                    <div class="ml-auto">{{ $evaluasi->angka_kemiskinan }}</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Laju Pertumbuhan Ekonomi</div>
                    <div class="ml-auto">{{ $evaluasi->laju_pertumbuhan_ekonomi }}</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Tingkat Pengangguran terbuka</div>
                    <div class="ml-auto">{{ $evaluasi->tingkat_pengangguran_terbuka }}</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Penurunan emisi GRK</div>
                    <div class="ml-auto">{{ $evaluasi->penurunan_emisi_grk }}</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Indeks Pembangunan Manusia</div>
                    <div class="ml-auto">{{ $evaluasi->indeks_pembangunan_manusia }}</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Indeks Gini Ratio</div>
                    <div class="ml-auto">{{ $evaluasi->indeks_gini_ratio }}</div>
                </div>
                <div class="flex items-center px-3 pt-3">
                    <div>Pendapatan Perkapita</div>
                    <div class="ml-auto">{{ $evaluasi->pendapatan_perkapita }}</div>
                </div>
                <hr class="my-5">
                @if (!empty($evaluasi->file_evaluasi))
                    <a href="{{ asset('storage/akip/' . $evaluasi->file_evaluasi) }}" target="_blank"
                        class="button border items-center text-gray-700 hidden sm:flex ml-3"> <i data-feather="file"
                            class="w-4 h-4 mr-2"></i>
                        {{ $evaluasi->periode != 'Final' ? 'Download File Catatan Evaluasi' : 'Download File Surat Pengantar LHE' }}</a>
                @endif
            </div>
            @endif
        </div>
        </div>
        {{-- End Card Penilaian Provinsi --}}
    @endforeach
    @endif

    {{-- Modal Tambah --}}
    <div class="modal" id="modal-form-evaluasi">
        <div class="modal__content modal__content--xl">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto" id="modal-title-evaluasi">
                    Tambah Penilaian
                </h2>
            </div>
            {{ html()->form('POST', '/akip/evaluasi/sakip/' . $instansi->id . '/simpan')->id('form-sakip')->acceptsFiles()->open() }}
            {{ html()->hidden('id_evaluasi')->id('id_evaluasi') }}
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        {{ html()->label('Tahun')->for('tahun') }} <span class="text-theme-6">*</span>
                        <div class="mt-2">
                            {{ html()->select('tahun', ['2025' => '2025'], 2025)->id('tahun')->class('select2 w-full hide-search')->placeholder('Pilih Tahun')->attributes(['onchange' => 'cekPeriode();'])->required() }}
                        </div>
                    </div>
                </div>
                @if ($instansi->group != 'kl')
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        {{ html()->label('Periode')->for('periode') }} <span class="text-theme-6">*</span>
                        <div class="mt-2">
                            {{ html()->select('periode', ['TW 1' => 'TW 1', 'TW 2' => 'TW 2', 'TW 3' => 'TW 3', 'Final' => 'Final'], null)->id('periode')->class('select2 w-full no-search')->placeholder('Pilih Periode')->required()->attributes(['onchange' => 'cekPeriode();']) }}
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        {{ html()->label('Penanggung Jawab')->for('penanggung_jawab') }} <span
                            class="text-theme-6">*</span>
                        <input type="text" name="penanggung_jawab" id="penanggung_jawab"
                            class="input w-full border mt-2 flex-1" placeholder="Nama Penanggung Jawab" required>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        {{ html()->label('PIC LKE')->for('pic_lke') }} <span class="text-theme-6">*</span>
                        <input type="text" name="pic_lke" id="pic_lke" class="input w-full border mt-2 flex-1"
                            placeholder="Nama PIC LKE" required>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        {{ html()->label('Link LKE')->for('link_lke') }} <span class="text-theme-6">*</span>
                        <input type="url" name="link_lke" id="link_lke" class="input w-full border mt-2 flex-1"
                            placeholder="Link LKE" required>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        {{ html()->label('File Surat Pengantar LHE')->for('file_evaluasi')->id('label_file_evaluasi') }} <span
                            class="text-theme-6">*</span>
                        <input type="file" name="file_evaluasi" id="file_evaluasi"
                            class="input w-full border mt-2 flex-1" placeholder="Link LKE" accept="application/pdf"
                            required>
                    </div>
                    <span class="italic text-sm" id="note_file_evaluasi">Pilih file jika ingin mengganti file
                        sebelumnya.</span>
                </div>
                <div class="col-span-12">
                    <hr>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        <div class="flex items-center">
                            <div class="font-medium">Nilai Komponens Perencanaan Kinerja</div>
                            <div class="ml-auto">
                                <input type="text" name="nilai_komponen_perencanaan_kinerja"
                                    id="nilai_komponen_perencanaan_kinerja" class="input w-20 digit border flex-1 mt-2"
                                    required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_perencanaan_kinerja" id="catatan_komponen_perencanaan_kinerja"
                        class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Perencanaan Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        <div class="flex items-center">
                            <div class="font-medium">Nilai Komponen Pengukuran Kinerja</div>
                            <div class="ml-auto">
                                <input type="text" name="nilai_komponen_pengukuran_kinerja"
                                    id="nilai_komponen_pengukuran_kinerja" class="input w-20 digit border flex-1 mt-2"
                                    required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_pengukuran_kinerja" id="catatan_komponen_pengukuran_kinerja"
                        class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Pengukuran Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        <div class="flex items-center">
                            <div class="font-medium">Nilai Komponen Pelaporan Kinerja</div>
                            <div class="ml-auto">
                                <input type="text" name="nilai_komponen_pelaporan_kinerja"
                                    id="nilai_komponen_pelaporan_kinerja" class="input w-20 digit border flex-1 mt-2"
                                    required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_pelaporan_kinerja" id="catatan_komponen_pelaporan_kinerja"
                        class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Pelaporan Kinerja"></textarea>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="input-group">
                        <div class="flex items-center">
                            <div class="font-medium">Nilai Komponen Evaluasi Internal</div>
                            <div class="ml-auto">
                                <input type="text" name="nilai_komponen_evaluasi_internal"
                                    id="nilai_komponen_evaluasi_internal" class="input w-20 digit border flex-1 mt-2"
                                    required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <label>Catatan : </label>
                    <textarea name="catatan_komponen_evaluasi_internal" id="catatan_komponen_evaluasi_internal"
                        class="input w-full border mt-2 flex-1" cols="30" rows="3"
                        placeholder="Catatan Nilai Komponen Evaluasi Internal"></textarea>
                </div>
                <div class="col-span-12">
                    <div class="input-group">
                        <div class="flex items-center">
                            <div class="font-medium">Nilai Total Evaluasi AKIP</div>
                            <div class="ml-auto">
                                <input type="text" name="nilai_total_evaluasi_akip" id="nilai_total_evaluasi_akip"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($instansi->group != 'kl')
                <div class="col-span-12">
                    <hr>
                </div>
                <div class="col-span-12">
                    <div class="font-medium">Input Hasil Capaian Indikator Makro</div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Angka Kemiskinan</div>
                            <div class="ml-auto">
                                <input type="text" name="angka_kemiskinan" id="angka_kemiskinan"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Laju Pertumbuhan Ekonomi</div>
                            <div class="ml-auto">
                                <input type="text" name="laju_pertumbuhan_ekonomi" id="laju_pertumbuhan_ekonomi"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Tingkat Pengangguran terbuka</div>
                            <div class="ml-auto">
                                <input type="text" name="tingkat_pengangguran_terbuka"
                                    id="tingkat_pengangguran_terbuka" class="input w-20 digit border flex-1 mt-2"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Penurunan emisi GRK</div>
                            <div class="ml-auto">
                                <input type="text" name="penurunan_emisi_grk" id="penurunan_emisi_grk"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Indeks Pembangunan Manusia</div>
                            <div class="ml-auto">
                                <input type="text" name="indeks_pembangunan_manusia" id="indeks_pembangunan_manusia"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Indeks Gini Ratio</div>
                            <div class="ml-auto">
                                <input type="text" name="indeks_gini_ratio" id="indeks_gini_ratio"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="flex items-center">
                            <div>Pendapatan Perkapita</div>
                            <div class="ml-auto">
                                <input type="text" name="pendapatan_perkapita" id="pendapatan_perkapita"
                                    class="input w-20 digit border flex-1 mt-2" required>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="px-5 py-3 text-right border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                <button type="submit" class="button w-20 bg-theme-1 text-white saveButton">Simpan</button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js_file')
    <script src="{{ asset('ext/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('ext/jquery-validation/localization/messages_id.min.js') }}"></script>
    <script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script>
        $(".digit").inputmask("decimal", {
            radixPoint: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            min: 0,
            max: 100,
        });

        resetForm = function() {
            $('#form-sakip').trigger('reset');
            $('#form-sakip').find('.select2').val(2025).trigger('change');
            $('#id_evaluasi').val('');
            $('#file_evaluasi').prop('required', true);
            $('#note_file_evaluasi').hide();
            $('#tahun').prop('disabled', false);
            $('#periode').prop('disabled', false);
            $('.saveButton').prop('disabled', false);
        }

        cekPeriode = function() {
            var tahun = $('#tahun').val();
            var periode = $('#periode').val();
            var id_evaluasi = $('#id_evaluasi').val();
            if (periode && periode != "Final") {
                $("#label_file_evaluasi").text("File Catatan Evaluasi");
            } else {
                $("#label_file_evaluasi").text("File Surat Pengantar LHE");
            }
            if (tahun && periode && !id_evaluasi) {
                $.ajax({
                    url: "{{ url('akip/evaluasi/sakip/' . $instansi->id . '/cekPeriode') }}",
                    type: 'POST',
                    data: {
                        tahun: tahun,
                        periode: periode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.exists) {
                            Swal.fire('Error!', 'Penilaian untuk tahun ' + tahun + ' dan periode ' +
                                periode +
                                ' sudah ada.', 'error');
                            $('.saveButton').prop('disabled', true);
                        } else {
                            $('.saveButton').prop('disabled', false);
                            if (response.evaluasi_sakip) {
                                $('#catatan_komponen_perencanaan_kinerja').val(response.evaluasi_sakip
                                    .catatan_komponen_perencanaan_kinerja);
                                $('#catatan_komponen_pengukuran_kinerja').val(response.evaluasi_sakip
                                    .catatan_komponen_pengukuran_kinerja);
                                $('#catatan_komponen_pelaporan_kinerja').val(response.evaluasi_sakip
                                    .catatan_komponen_pelaporan_kinerja);
                                $('#catatan_komponen_evaluasi_internal').val(response.evaluasi_sakip
                                    .catatan_komponen_evaluasi_internal);
                            }
                        }
                    }
                });
            }
        }

        $('#form-sakip').validate({
            ignore: [],
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element.closest('.input-group'));
                }
            },
            highlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2-container')
                        .find('.select2-selection')
                        .addClass('border border-red-500');
                } else {
                    $(element).addClass('border-red-500');
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2-container')
                        .find('.select2-selection')
                        .removeClass('border border-red-500');
                } else {
                    $(element).removeClass('border-red-500');
                }
            },
            submitHandler: function(form) {
                $('.saveButton').prop('disabled', true);
                form.submit();
            }
        });

        editEvaluasi = function(id) {
            resetForm();
            $('#modal-title-evaluasi').text('Edit Penilaian');
            $('#id_evaluasi').val(id);
            $('#note_file_evaluasi').show();
            $('#file_evaluasi').prop('required', false);
            $.ajax({
                url: "{{ url('akip/evaluasi/sakip/' . $instansi->id . '/getData') }}/" + id,
                type: 'GET',
                success: function(response) {
                    $('#form-sakip').trigger('reset');
                    $('#form-sakip').find('.select2').val(null).trigger('change');
                    $('#tahun').val(response.evaluasi_sakip.tahun).trigger('change');
                    $('#tahun').prop('disabled', true);
                    $('#periode').val(response.evaluasi_sakip.periode).trigger('change');
                    $('#periode').prop('disabled', true);
                    $('#penanggung_jawab').val(response.evaluasi_sakip.penanggung_jawab);
                    $('#pic_lke').val(response.evaluasi_sakip.pic_lke);
                    $('#link_lke').val(response.evaluasi_sakip.link_lke);
                    $('#nilai_komponen_perencanaan_kinerja').val(response.evaluasi_sakip
                        .nilai_komponen_perencanaan_kinerja);
                    $('#catatan_komponen_perencanaan_kinerja').val(response.evaluasi_sakip
                        .catatan_komponen_perencanaan_kinerja);
                    $('#nilai_komponen_pengukuran_kinerja').val(response.evaluasi_sakip
                        .nilai_komponen_pengukuran_kinerja);
                    $('#catatan_komponen_pengukuran_kinerja').val(response.evaluasi_sakip
                        .catatan_komponen_pengukuran_kinerja);
                    $('#nilai_komponen_pelaporan_kinerja').val(response.evaluasi_sakip
                        .nilai_komponen_pelaporan_kinerja);
                    $('#catatan_komponen_pelaporan_kinerja').val(response.evaluasi_sakip
                        .catatan_komponen_pelaporan_kinerja);
                    $('#nilai_komponen_evaluasi_internal').val(response.evaluasi_sakip
                        .nilai_komponen_evaluasi_internal);
                    $('#catatan_komponen_evaluasi_internal').val(response.evaluasi_sakip
                        .catatan_komponen_evaluasi_internal);
                    $('#nilai_total_evaluasi_akip').val(response.evaluasi_sakip.nilai_total_evaluasi_akip);
                    $('#angka_kemiskinan').val(response.evaluasi_sakip.angka_kemiskinan);
                    $('#laju_pertumbuhan_ekonomi').val(response.evaluasi_sakip.laju_pertumbuhan_ekonomi);
                    $('#tingkat_pengangguran_terbuka').val(response.evaluasi_sakip
                        .tingkat_pengangguran_terbuka);
                    $('#penurunan_emisi_grk').val(response.evaluasi_sakip.penurunan_emisi_grk);
                    $('#indeks_pembangunan_manusia').val(response.evaluasi_sakip
                        .indeks_pembangunan_manusia);
                    $('#indeks_gini_ratio').val(response.evaluasi_sakip.indeks_gini_ratio);
                    $('#pendapatan_perkapita').val(response.evaluasi_sakip.pendapatan_perkapita);
                }
            });
            $('#modal-form-evaluasi').modal('show');
        }

        hapusEvaluasi = function(id) {
            Swal.fire({
                title: "Yakin?",
                text: "Hapus hasil evaluasi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, hapus aja!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('akip/evaluasi/sakip/' . $instansi->id . '/hapus') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', 'Hasil evaluasi berhasil dihapus.', 'success')
                                .then(() => {
                                    location.reload();
                                });
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Gagal menghapus hasil evaluasi.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
