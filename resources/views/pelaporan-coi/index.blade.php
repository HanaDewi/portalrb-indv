@extends('layout.rubick')
@section('title', 'Pelaporan COI')

@section('content')
    @php
        $isFinal = $data && $data->finalized_at;
    @endphp
    <div class="intro-y col-span-12 lg:col-span-12">
        @include('common.status')
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible show flex items-start gap-2 mb-3" role="alert">
                <i data-lucide="alert-circle" class="w-6 h-6 mt-0.5"></i>
                <div>
                    <div class="font-semibold">Periksa kembali isian:</div>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
        <div class="intro-y box">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center p-5 border-b border-slate-200/60">
                <div class="mr-auto">
                    <h2 class="font-bold text-base">Pelaporan Konflik Kepentingan (COI)</h2>
                    <div class="text-slate-500 text-sm">Isi kuesioner berikut sesuai kondisi instansi Anda.</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold">Instansi</div>
                    <div class="text-slate-600 text-sm">
                        {!! $instansi->nama_instansi ?? $instansi->name ?? '-' !!}
                    </div>
                    @if ($isFinal)
                        <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full bg-success text-white text-xs">
                            Sudah Final
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Final pada: {{ optional($data->finalized_at)->format('d M Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="p-5">
                <div class="alert alert-secondary-soft border-slate-200 text-slate-700 mb-5">
                    <div class="font-semibold">Petunjuk</div>
                    <ul class="list-disc ml-5 text-sm leading-relaxed">
                        <li>Pilih jawaban Ya/Tidak untuk setiap pertanyaan.</li>
                        <li>Isian lanjutan akan muncul sesuai jawaban Anda.</li>
                        <li>Anda dapat menyimpan draft kapan saja. Tombol <span class="font-semibold">Simpan Final</span>
                            hanya aktif jika seluruh pertanyaan wajib terisi.</li>
                        @if ($isFinal)
                            <li class="text-danger font-semibold">Data sudah final dan tidak dapat diperbarui.</li>
                        @endif
                    </ul>
                </div>
                <form id="pelaporan-coi-form" method="POST" action="{{ url('pelaporan-coi/simpan') }}">
                    @csrf
                    <div class="grid gap-6">
                        <div class="question-block">
                            <div class="font-semibold mb-2">1. Apakah Instansi Bapak/Ibu sudah memiliki aturan internal
                                mengenai pengelolaan konflik kepentingan?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q1_peraturan_internal" value="1"
                                        {{ old('q1_peraturan_internal', $data->q1_peraturan_internal ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q1_peraturan_internal" value="0"
                                        {{ old('q1_peraturan_internal', $data->q1_peraturan_internal ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <div class="mt-3 follow-up" data-parent="q1_peraturan_internal" data-follow-up="1">
                                <label class="form-label">1.1. Nomor Permen/Kepmen/Pergub/Perbub/Perwali?</label>
                                <input type="text" name="q11_nomor_peraturan" class="form-control"
                                    value="{{ old('q11_nomor_peraturan', $data->q11_nomor_peraturan ?? '') }}">
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">2. Apakah peraturan mengenai konflik kepentingan yang berlaku
                                sudah diselaraskan dengan Peraturan Menteri PANRB Nomor 17 Tahun 2024?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q2_selaras_permepan" value="1"
                                        {{ old('q2_selaras_permepan', $data->q2_selaras_permepan ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q2_selaras_permepan" value="0"
                                        {{ old('q2_selaras_permepan', $data->q2_selaras_permepan ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <div class="mt-3 follow-up" data-parent="q2_selaras_permepan" data-follow-up="0">
                                <label class="form-label">2.1. Apakah instansi Bapak/Ibu sudah mulai menyusun revisi
                                    peraturan pengelolaan konflik kepentingan sesuai dengan Permen PANRB Nomor 17 Tahun
                                    2024?</label>
                                <div class="flex gap-5 mt-2">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="q21_susun_revisi" value="1"
                                            {{ old('q21_susun_revisi', $data->q21_susun_revisi ?? null) == 1 ? 'checked' : '' }}>
                                        <span>Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="q21_susun_revisi" value="0"
                                            {{ old('q21_susun_revisi', $data->q21_susun_revisi ?? null) == 0 ? 'checked' : '' }}>
                                        <span>Tidak</span>
                                    </label>
                                </div>
                                <div class="mt-3 follow-up" data-parent="q21_susun_revisi" data-follow-up="0">
                                    <label class="form-label">2.1.1. Jika belum, kapan aturan eksisting akan disesuaikan
                                        dengan Permen PANRB Nomor 17 Tahun 2024?</label>
                                    <input type="text" name="q211_rencana_penyesuaian" class="form-control"
                                        value="{{ old('q211_rencana_penyesuaian', $data->q211_rencana_penyesuaian ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">3. Apakah peraturan internal di instansi Bapak/Ibu diturunkan
                                lagi dalam bentuk pedoman teknis?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q3_pedoman_teknis" value="1"
                                        {{ old('q3_pedoman_teknis', $data->q3_pedoman_teknis ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q3_pedoman_teknis" value="0"
                                        {{ old('q3_pedoman_teknis', $data->q3_pedoman_teknis ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <div class="mt-3 follow-up" data-parent="q3_pedoman_teknis" data-follow-up="1">
                                <label class="form-label">3.1. Nomor pedoman?</label>
                                <input type="text" name="q31_nomor_pedoman" class="form-control"
                                    value="{{ old('q31_nomor_pedoman', $data->q31_nomor_pedoman ?? '') }}">
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">4. Apakah di instansi Bapak/Ibu sudah ditunjuk Pejabat Pengelola
                                Konflik Kepentingan?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q4_penunjukan_pejabat" value="1"
                                        {{ old('q4_penunjukan_pejabat', $data->q4_penunjukan_pejabat ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q4_penunjukan_pejabat" value="0"
                                        {{ old('q4_penunjukan_pejabat', $data->q4_penunjukan_pejabat ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">5. Apakah di instansi Bapak/Ibu terdapat sistem atau aplikasi
                                yang digunakan untuk mengelola konflik kepentingan?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q5_sistem_aplikasi" value="1"
                                        {{ old('q5_sistem_aplikasi', $data->q5_sistem_aplikasi ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q5_sistem_aplikasi" value="0"
                                        {{ old('q5_sistem_aplikasi', $data->q5_sistem_aplikasi ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">6. Apakah di instansi Bapak/Ibu sudah dilakukan pencatatan daftar
                                kepentingan pribadi/register?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q6_pencatatan_register" value="1"
                                        {{ old('q6_pencatatan_register', $data->q6_pencatatan_register ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q6_pencatatan_register" value="0"
                                        {{ old('q6_pencatatan_register', $data->q6_pencatatan_register ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <div class="mt-3 follow-up" data-parent="q6_pencatatan_register" data-follow-up="1">
                                <label class="form-label">6.1. Total ASN Wajib Melaporkan Deklarasi Benturan
                                    Kepentingan?</label>
                                <input type="text" name="q61_total_wajib" class="form-control digit"
                                    value="{{ old('q61_total_wajib', $data->q61_total_wajib ?? '') }}">
                                <label class="form-label mt-3">6.2. Total ASN Telah Melaporkan?</label>
                                <input type="text" name="q62_total_lapor" class="form-control digit"
                                    value="{{ old('q62_total_lapor', $data->q62_total_lapor ?? '') }}">
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">7. Apakah di instansi Bapak/Ibu sudah dilakukan deklarasi untuk
                                konflik kepentingan aktual?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q7_deklarasi_aktual" value="1"
                                        {{ old('q7_deklarasi_aktual', $data->q7_deklarasi_aktual ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q7_deklarasi_aktual" value="0"
                                        {{ old('q7_deklarasi_aktual', $data->q7_deklarasi_aktual ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <div class="mt-3 follow-up" data-parent="q7_deklarasi_aktual" data-follow-up="1">
                                <label class="form-label">7.1. Jumlah deklarasi yang disampaikan?</label>
                                <input type="text" name="q71_jumlah_deklarasi" class="form-control"
                                    value="{{ old('q71_jumlah_deklarasi', $data->q71_jumlah_deklarasi ?? '') }}">
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">8. Apakah di instansi Bapak/Ibu sudah memiliki lini aduan yang
                                dapat dimanfaatkan untuk menyampaikan pengaduan jika terjadi konflik kepentingan?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q8_lini_aduan" value="1"
                                        {{ old('q8_lini_aduan', $data->q8_lini_aduan ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q8_lini_aduan" value="0"
                                        {{ old('q8_lini_aduan', $data->q8_lini_aduan ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <div class="mt-3 follow-up" data-parent="q8_lini_aduan" data-follow-up="1">
                                <label class="form-label">8.1. Nama lini pengaduan? (LAPOR, WBS, Hotline Telp/WA, dll)</label>
                                <input type="text" name="q81_nama_lini" class="form-control"
                                    value="{{ old('q81_nama_lini', $data->q81_nama_lini ?? '') }}">
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">9. Apakah di instansi Bapak/Ibu terdapat mekanisme monitoring dan
                                evaluasi atas pengelolaan konflik kepentingan?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q9_monev" value="1"
                                        {{ old('q9_monev', $data->q9_monev ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q9_monev" value="0"
                                        {{ old('q9_monev', $data->q9_monev ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="question-block">
                            <div class="font-semibold mb-2">10. Apakah instansi Bapak/Ibu telah menyusun laporan atas
                                implementasi pengelolaan konflik kepentingan?</div>
                            <div class="flex gap-5">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q10_laporan" value="1"
                                        {{ old('q10_laporan', $data->q10_laporan ?? null) == 1 ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="q10_laporan" value="0"
                                        {{ old('q10_laporan', $data->q10_laporan ?? null) == 0 ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-8 gap-3">
                        <div class="text-xs text-slate-500">
                            Simpan draft untuk melanjutkan nanti. Setelah disimpan final, jawaban terkunci.
                        </div>
                        <div class="flex gap-2">
                            @unless ($isFinal)
                                <button type="submit" class="btn btn-secondary w-36">Simpan Draft</button>
                                <button type="submit" class="btn btn-primary w-36" id="btn-final"
                                    formaction="{{ url('pelaporan-coi/final') }}">Simpan Final</button>
                            @else
                                <button type="button" class="btn btn-secondary w-36" disabled>Form Terkunci</button>
                            @endunless
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('ext') }}/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script>
        (function() {
            const parentValue = (name) => $('input[name="' + name + '"]:checked').val();

            function toggleFollowUps() {
                $('[data-follow-up]').each(function() {
                    const $el = $(this);
                    const expected = $el.data('follow-up').toString();
                    const parent = $el.data('parent');
                    const current = parentValue(parent);
                    const shouldShow = current === expected;

                    $el.toggleClass('hidden', !shouldShow);
                    $el.find('input, textarea').prop('disabled', !shouldShow);
                    if (!shouldShow) {
                        $el.find('input[type="text"], textarea').val('');
                        $el.find('input[type="radio"]').prop('checked', false);
                    }
                });
            }

            function isComplete() {
                const requiredRadios = [
                    'q1_peraturan_internal',
                    'q2_selaras_permepan',
                    'q3_pedoman_teknis',
                    'q4_penunjukan_pejabat',
                    'q5_sistem_aplikasi',
                    'q6_pencatatan_register',
                    'q7_deklarasi_aktual',
                    'q8_lini_aduan',
                    'q9_monev',
                    'q10_laporan',
                ];

                for (const name of requiredRadios) {
                    if (!parentValue(name)) {
                        return false;
                    }
                }

                if (parentValue('q1_peraturan_internal') === '1' && !$('input[name="q11_nomor_peraturan"]').val()
                    .trim()) {
                    return false;
                }

                if (parentValue('q2_selaras_permepan') === '0') {
                    if (!parentValue('q21_susun_revisi')) {
                        return false;
                    }
                    if (parentValue('q21_susun_revisi') === '0' && !$('input[name="q211_rencana_penyesuaian"]').val()
                        .trim()) {
                        return false;
                    }
                }

                if (parentValue('q3_pedoman_teknis') === '1' && !$('input[name="q31_nomor_pedoman"]').val().trim()) {
                    return false;
                }

                if (parentValue('q6_pencatatan_register') === '1') {
                    if (!$('input[name="q61_total_wajib"]').val().trim() || !$('input[name="q62_total_lapor"]').val()
                        .trim()) {
                        return false;
                    }
                }

                if (parentValue('q7_deklarasi_aktual') === '1' && !$('input[name="q71_jumlah_deklarasi"]').val().trim()) {
                    return false;
                }

                if (parentValue('q8_lini_aduan') === '1' && !$('input[name="q81_nama_lini"]').val().trim()) {
                    return false;
                }

                return true;
            }

            function syncFinalButton() {
                const $finalBtn = $('#btn-final');
                if ($finalBtn.length) {
                    $finalBtn.prop('disabled', !isComplete());
                }
            }

            $(document).ready(function() {
                $('.digit').inputmask("decimal", {
                    rightAlign: false,
                    groupSeparator: ".",
                    autoGroup: true,
                    digits: 0,
                });

                toggleFollowUps();
                syncFinalButton();

                $('#pelaporan-coi-form input, #pelaporan-coi-form textarea').on('change keyup', function() {
                    toggleFollowUps();
                    syncFinalButton();
                });

                @if ($isFinal)
                    $('#pelaporan-coi-form input, #pelaporan-coi-form textarea').prop('disabled', true);
                    $('#pelaporan-coi-form button[type="submit"]').prop('disabled', true);
                @endif
            });
        })();
    </script>
@endpush
