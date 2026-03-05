@extends('lhkan.layout.lhkan_layout')

@section('title', 'Form Pelaporan LHKAN')

@push('css')
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php
    $picRows = is_array(old('pics')) && count(old('pics')) > 0
        ? old('pics')
        : (isset($submission) && $submission->pics->count() > 0 ? $submission->pics->values()->all() : [['nama' => '', 'nomor_hp' => '']]);
@endphp
<div class="mt-8 space-y-5">
    <x-bladewind::card>
            @if(session('success'))
                <div class="rounded-md border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 mb-4">
                    <x-bladewind::alert type="success">{{ session('success') }}</x-bladewind::alert>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-md border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 mb-4">
                    <x-bladewind::alert type="error">{{ session('error') }}</x-bladewind::alert>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-md border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 mb-4">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <x-bladewind::alert type="warning">{{ $error }}</x-bladewind::alert>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Periode Alert -->
            @if(isset($currentPeriod))
                @if($currentPeriod->status === 'locked')
                    <div class="rounded-md border border-amber-200 bg-amber-50 text-amber-700 px-4 py-3 mb-4">
                        <i class="fa fa-lock mr-1"></i> Periode saat ini dalam status <strong>Locked</strong>. Anda tidak dapat
                        mengubah data. Silakan hubungi Admin jika perlu mengubah data.
                    </div>
                @else
                    <div class="rounded-md border border-sky-200 bg-sky-50 text-sky-700 px-4 py-3 mb-4">
                        <i class="fa fa-info-circle mr-1"></i> Periode: <strong>{{ $currentPeriod->nama }}</strong>
                        ({{ $currentPeriod->tahun }})
                    </div>
                @endif
            @else
                <div class="rounded-md border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 mb-4">
                    <i class="fa fa-exclamation-triangle mr-1"></i> Tidak ada periode aktif saat ini. Silakan hubungi Admin.
                </div>
            @endif

            <!-- Already Submitted Alert -->
            @if(isset($isEditRequested) && $isEditRequested)
                <div class="rounded-md border border-sky-200 bg-sky-50 text-sky-700 px-4 py-3 mb-4">
                    <i class="fa fa-clock mr-1"></i> Pengajuan edit data Anda sedang menunggu persetujuan Admin/TPN.
                    Silakan cek <a href="{{ route('lhkan.history') }}" class="font-semibold underline hover:text-sky-900">riwayat pelaporan</a> untuk melihat status.
                </div>
            @elseif(isset($canEdit) && $canEdit)
                <x-bladewind::alert type="success" class="mb-4">Permohonan edit Anda telah disetujui. Silakan edit data dan submit ulang.</x-bladewind::alert>
            @elseif(isset($hasSubmitted) && $hasSubmitted)
                <div class="rounded-md border border-amber-200 bg-amber-50 text-amber-700 px-4 py-3 mb-4">
                    <i class="fa fa-check-circle mr-1"></i> Anda sudah melakukan submit data untuk periode ini. 
                    Jika ingin melakukan edit data, silakan masuk ke <a href="{{ route('lhkan.history') }}" class="font-semibold underline hover:text-amber-900">riwayat pelaporan</a>.
                </div>
            @endif

            @if(isset($currentPeriod) && $currentPeriod->status === 'open' && (!isset($hasSubmitted) || !$hasSubmitted || (isset($canEdit) && $canEdit)))
                    <form method="POST" action="{{ route('lhkan.store') }}" id="lhkanForm">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $currentPeriod->id }}">
                        <input type="hidden" name="action" id="formAction" value="draft">
                        <!-- Instansi Info (Read Only) -->
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 mb-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                <div><span class="font-semibold">Instansi:</span> {{ auth()->user()->instansi->name ?? '-' }}</div>
                                <div><span class="font-semibold">Periode:</span> {{ $currentPeriod->nama }} ({{ $currentPeriod->tahun }})</div>
                            </div>
                        </div>

                        <!-- PIC Section -->
                        <div class="mb-5">
                            <h5 class="text-base font-semibold mb-4">Data PIC (Person In Charge)</h5>
                            <div class="row" id="picContainer">
                                @foreach($picRows as $index => $pic)
                                    <div class="col-md-6 mb-3 pic-row" data-index="{{ $index }}">
                                        <div class="rounded-lg border border-slate-200 p-3 bg-white">
                                            <div class="form-group mb-3">
                                                <label class="font-medium">Nama PIC {{ $index + 1 }}</label>
                                                <x-bladewind::input type="text" name="pics[{{ $index }}][nama]" class="pic-nama"
                                                    value="{{ is_array($pic) ? ($pic['nama'] ?? '') : ($pic->nama ?? '') }}" required />
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="font-medium">Nomor HP PIC {{ $index + 1 }}</label>
                                                <x-bladewind::input type="text" name="pics[{{ $index }}][nomor_hp]" class="pic-hp"
                                                    value="{{ is_array($pic) ? ($pic['nomor_hp'] ?? '') : ($pic->nomor_hp ?? '') }}" required />
                                            </div>
                                            @if($index === 0)
                                                <x-bladewind::button color="green" has_icon="true" icon="plus" size="small" onclick="addPicRow()">
                                                    Tambah PIC
                                                </x-bladewind::button>
                                            @else
                                                <x-bladewind::button color="red" has_icon="true" icon="trash" size="small" onclick="removePicRow({{ $index }})">
                                                    Hapus PIC
                                                </x-bladewind::button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr />

                        <!-- Data Aparatur Section -->
                        <div class="rounded-lg border border-slate-200 p-4 my-5">
                            <h5 class="text-base font-semibold mb-4">Data Aparatur Negara</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jml_aparatur" class="font-medium">Jumlah Total Aparatur Negara *</label>
                                        <x-bladewind::input numeric="true" id="jml_aparatur" name="jml_aparatur" class="form-control"
                                            value="{{ old('jml_aparatur', $submission->jml_aparatur ?? '') }}" min="0" required
                                            onchange="calculateTotalBelumLhkan()" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jml_wajib_lhkpn" class="font-medium">Jumlah Wajib LHKPN *</label>
                                        <x-bladewind::input numeric="true" id="jml_wajib_lhkpn" name="jml_wajib_lhkpn" class="form-control"
                                            value="{{ old('jml_wajib_lhkpn', $submission->jml_wajib_lhkpn ?? '') }}" min="0" required
                                            onchange="calculateTotalBelumLhkan()" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jml_non_wajib_lhkpn" class="font-medium">Jumlah Tidak Wajib LHKPN *</label>
                                        <x-bladewind::input numeric="true" id="jml_non_wajib_lhkpn" name="jml_non_wajib_lhkpn"
                                            class="form-control" value="{{ old('jml_non_wajib_lhkpn', $submission->jml_non_wajib_lhkpn ?? '') }}" min="0"
                                            required onchange="calculateTotalBelumLhkan()" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="realisasi_lhkpn" class="font-medium">Realisasi LHKPN (Sudah Lapor) *</label>
                                        <x-bladewind::input numeric="true" id="realisasi_lhkpn" name="realisasi_lhkpn" class="form-control"
                                            value="{{ old('realisasi_lhkpn', $submission->realisasi_lhkpn ?? '') }}" min="0" required
                                            onchange="calculateTotalBelumLhkan()" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="realisasi_spt_non_lhkpn" class="font-medium">Realisasi SPT Tahunan - Non Wajib LHKPN (Sudah Lapor)
                                            *</label>
                                        <x-bladewind::input numeric="true" id="realisasi_spt_non_lhkpn" name="realisasi_spt_non_lhkpn"
                                            class="form-control" value="{{ old('realisasi_spt_non_lhkpn', $submission->realisasi_spt_non_lhkpn ?? '') }}" min="0"
                                            required onchange="calculateTotalBelumLhkan()" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="belum_spt_non_lhkpn" class="font-medium">Belum Lapor SPT - Non Wajib LHKPN *</label>
                                        <x-bladewind::input numeric="true" id="belum_spt_non_lhkpn" name="belum_spt_non_lhkpn"
                                            class="form-control" value="{{ old('belum_spt_non_lhkpn', $submission->belum_spt_non_lhkpn ?? '') }}" min="0"
                                            required onchange="calculateTotalBelumLhkan()" />
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3">
                                <div class="form-group mb-0">
                                    <label for="total_belum_lhkan" class="font-medium">Total Belum Lapor LHKAN (Calculated)</label>
                                    <x-bladewind::input numeric="true" id="total_belum_lhkan" name="total_belum_lhkan"
                                        class="form-control bg-light" value="{{ old('total_belum_lhkan', $submission->total_belum_lhkan ?? 0) }}"
                                        readonly />
                                    <small class="text-muted">
                                        Formula: (Jumlah Wajib LHKPN - Realisasi LHKPN) + Belum Lapor SPT Non Wajib LHKPN
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Document Section -->
                        <div class="rounded-lg border border-slate-200 p-4 mb-5">
                            <h5 class="text-base font-semibold mb-4">Dokumen Rekapitulasi</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="link_rekap_gdrive" class="font-medium">Link Google Drive Rekapitulasi *</label>
                                        <x-bladewind::input type="url" id="link_rekap_gdrive" name="link_rekap_gdrive" class="form-control"
                                            value="{{ old('link_rekap_gdrive', $submission->link_rekap_gdrive ?? '') }}" required
                                            placeholder="https://drive.google.com/..." />
                                        <small class="text-muted">Paste link Google Drive yang berisi file rekapitulasi LHKAN</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            @if(isset($canEdit) && $canEdit)
                                <x-bladewind::button color="blue" has_icon="true" icon="paper-plane" size="small" type="button" id="btnSubmitData">
                                    Submit Ulang Data
                                </x-bladewind::button>
                            @else
                                <!-- <x-bladewind::button color="purple" has_icon="true" icon="save" size="small" type="submit" name="action" value="draft">
                                    Simpan Draft
                                </x-bladewind::button> -->
                                <x-bladewind::button color="blue" has_icon="true" icon="paper-plane" size="small" type="button" id="btnSubmitData">
                                    Submit Data
                                </x-bladewind::button>
                            @endif
                            <!-- <x-bladewind::button color="cyan" has_icon="true" icon="history" size="small" href="{{ route('lhkan.history') }}">
                                Lihat History
                            </x-bladewind::button> -->
                        </div>
                    </form>
            @endif

    </x-bladewind::card>
</div>

@push('js')
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script>
        let picCount = {{ count($picRows) }};

        function addPicRow() {
            const container = document.getElementById('picContainer');
            const newIndex = picCount;

            const newRow = document.createElement('div');
            newRow.className = 'col-md-6 mb-3 pic-row';
            newRow.dataset.index = newIndex;
            newRow.innerHTML = `
                <div class="rounded-lg border border-slate-200 p-3 bg-white">
                    <div class="form-group mb-3">
                        <label class="font-medium">Nama PIC ${newIndex + 1}</label>
                        <div class="relative w-full mb-2">
                            <input type="text" name="pics[${newIndex}][nama]" value="" placeholder="" class="bw-input peer pic-nama w-full rounded border border-slate-300 focus:border-primary-500 focus:outline-primary-500 px-3 py-2" required />
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-medium">Nomor HP PIC ${newIndex + 1}</label>
                        <div class="relative w-full mb-2">
                            <input type="text" name="pics[${newIndex}][nomor_hp]" value="" placeholder="" class="bw-input peer pic-hp w-full rounded border border-slate-300 focus:border-primary-500 focus:outline-primary-500 px-3 py-2" required />
                        </div>
                    </div>
                    <button type="button" class="bw-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm inline-flex items-center" onclick="removePicRow(${newIndex})">
                        <i class="fa fa-trash mr-1"></i> Hapus PIC
                    </button>
                </div>
            `;

            container.appendChild(newRow);
            picCount++;
        }

        function removePicRow(index) {
            const row = document.querySelector(`.pic-row[data-index="${index}"]`);
            if (row) {
                row.remove();
            }
        }

        function calculateTotalBelumLhkan() {
            const jmlWajibLhkpn = parseInt(document.getElementById('jml_wajib_lhkpn').value) || 0;
            const realisasiLhkpn = parseInt(document.getElementById('realisasi_lhkpn').value) || 0;
            const belumSptNonLhkpn = parseInt(document.getElementById('belum_spt_non_lhkpn').value) || 0;

            const totalBelumLhkan = (jmlWajibLhkpn - realisasiLhkpn) + belumSptNonLhkpn;

            document.getElementById('total_belum_lhkan').value = totalBelumLhkan;
        }

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', function () {
            calculateTotalBelumLhkan();

            // Submit Data: SweetAlert confirm then submit form with action=submit
            const btnSubmitData = document.getElementById('btnSubmitData');
            const form = document.getElementById('lhkanForm');
            const formActionInput = document.getElementById('formAction');
            if (btnSubmitData && form && formActionInput) {
                btnSubmitData.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Konfirmasi Submit',
                        text: 'Apakah Anda yakin ingin mensubmit data? Data tidak dapat diubah setelah submit kecuali ada persetujuan Admin.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Submit',
                        cancelButtonText: 'Batal'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            formActionInput.value = 'submit';
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush

@endsection