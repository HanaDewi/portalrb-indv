@extends('lhkan.layout.lhkan_layout')

@section('title', 'Form Pelaporan LHKAN')

@push('css')
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="mt-8 space-y-5">
    <x-bladewind::card>
        <div class="border-b border-slate-200 pb-4 mb-5">
            <!-- <h3 class="text-xl font-semibold text-slate-800">Form Pelaporan Harta Kekayaan Aparatur Negara</h3> -->
            <p class="text-slate-500 text-sm mt-1">Silakan isi data pelaporan instansi sesuai periode yang sedang aktif.</p>
        </div>

            @if(session('success'))
                <div class="rounded-md border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-md border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-md border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 mb-4">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
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

            @if(isset($currentPeriod) && $currentPeriod->status === 'open')
                <form method="POST" action="{{ route('lhkan.store') }}" id="lhkanForm">
                    @csrf
                    <input type="hidden" name="periode_id" value="{{ $currentPeriod->id }}">
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
                            @if(isset($submission) && $submission->pics->count() > 0)
                                @foreach($submission->pics as $index => $pic)
                                    <div class="col-md-6 mb-3 pic-row" data-index="{{ $index }}">
                                        <div class="rounded-lg border border-slate-200 p-3 bg-white">
                                            <div class="form-group mb-3">
                                                <label class="font-medium">Nama PIC {{ $index + 1 }}</label>
                                                <input type="text" name="pics[{{ $index }}][nama]" class="form-control pic-nama"
                                                    value="{{ $pic->nama }}" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="font-medium">Nomor HP PIC {{ $index + 1 }}</label>
                                                <input type="text" name="pics[{{ $index }}][nomor_hp]" class="form-control pic-hp"
                                                    value="{{ $pic->nomor_hp }}" required>
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
                            @else
                                <div class="col-md-6 mb-3 pic-row" data-index="0">
                                    <div class="rounded-lg border border-slate-200 p-3 bg-white">
                                        <div class="form-group mb-3">
                                            <label class="font-medium">Nama PIC 1</label>
                                            <input type="text" name="pics[0][nama]" class="form-control pic-nama" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="font-medium">Nomor HP PIC 1</label>
                                            <input type="text" name="pics[0][nomor_hp]" class="form-control pic-hp" required>
                                        </div>
                                        <x-bladewind::button color="green" has_icon="true" icon="plus" size="small" onclick="addPicRow()">
                                            Tambah PIC
                                        </x-bladewind::button>
                                    </div>
                                </div>
                            @endif
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
                                    <input type="number" id="jml_aparatur" name="jml_aparatur" class="form-control"
                                        value="{{ $submission->jml_aparatur ?? '' }}" min="0" required
                                        onchange="calculateTotalBelumLhkan()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jml_wajib_lhkpn" class="font-medium">Jumlah Wajib LHKPN *</label>
                                    <input type="number" id="jml_wajib_lhkpn" name="jml_wajib_lhkpn" class="form-control"
                                        value="{{ $submission->jml_wajib_lhkpn ?? '' }}" min="0" required
                                        onchange="calculateTotalBelumLhkan()">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jml_non_wajib_lhkpn" class="font-medium">Jumlah Tidak Wajib LHKPN *</label>
                                    <input type="number" id="jml_non_wajib_lhkpn" name="jml_non_wajib_lhkpn"
                                        class="form-control" value="{{ $submission->jml_non_wajib_lhkpn ?? '' }}" min="0"
                                        required onchange="calculateTotalBelumLhkan()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="realisasi_lhkpn" class="font-medium">Realisasi LHKPN (Sudah Lapor) *</label>
                                    <input type="number" id="realisasi_lhkpn" name="realisasi_lhkpn" class="form-control"
                                        value="{{ $submission->realisasi_lhkpn ?? '' }}" min="0" required
                                        onchange="calculateTotalBelumLhkan()">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="realisasi_spt_non_lhkpn" class="font-medium">Realisasi SPT Tahunan - Non Wajib LHKPN (Sudah Lapor)
                                        *</label>
                                    <input type="number" id="realisasi_spt_non_lhkpn" name="realisasi_spt_non_lhkpn"
                                        class="form-control" value="{{ $submission->realisasi_spt_non_lhkpn ?? '' }}" min="0"
                                        required onchange="calculateTotalBelumLhkan()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="belum_spt_non_lhkpn" class="font-medium">Belum Lapor SPT - Non Wajib LHKPN *</label>
                                    <input type="number" id="belum_spt_non_lhkpn" name="belum_spt_non_lhkpn"
                                        class="form-control" value="{{ $submission->belum_spt_non_lhkpn ?? '' }}" min="0"
                                        required onchange="calculateTotalBelumLhkan()">
                                </div>
                            </div>
                        </div>

                        <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="form-group mb-0">
                                <label for="total_belum_lhkan" class="font-medium">Total Belum Lapor LHKAN (Calculated)</label>
                                <input type="number" id="total_belum_lhkan" name="total_belum_lhkan"
                                    class="form-control bg-light" value="{{ $submission->total_belum_lhkan ?? 0 }}"
                                    readonly>
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
                                    <input type="url" id="link_rekap_gdrive" name="link_rekap_gdrive" class="form-control"
                                        value="{{ $submission->link_rekap_gdrive ?? '' }}" required
                                        placeholder="https://drive.google.com/...">
                                    <small class="text-muted">Paste link Google Drive yang berisi file rekapitulasi LHKAN</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <x-bladewind::button color="purple" has_icon="true" icon="save" size="small" type="submit" name="action" value="draft">
                            Simpan Draft
                        </x-bladewind::button>
                        <x-bladewind::button color="blue" has_icon="true" icon="paper-plane" size="small" onclick="return confirm('Apakah Anda yakin ingin mensubmit data? Data tidak dapat diubah setelah submit kecuali ada persetujuan Admin.')">
                            Submit Data
                        </x-bladewind::button>
                        <x-bladewind::button color="cyan" has_icon="true" icon="history" size="small" href="{{ route('lhkan.history') }}">
                            Lihat History
                        </x-bladewind::button>
                    </div>
                </form>
            @endif
    </x-bladewind::card>
</div>

@push('js')
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script>
        let picCount = {{ isset($submission) ? $submission->pics->count() : 1 }};

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
                        <input type="text" name="pics[${newIndex}][nama]" class="form-control pic-nama" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-medium">Nomor HP PIC ${newIndex + 1}</label>
                        <input type="text" name="pics[${newIndex}][nomor_hp]" class="form-control pic-hp" required>
                    </div>
                    <x-bladewind::button color="red" has_icon="true" size="small" onclick="removePicRow(${newIndex})">
                        <i class="fa fa-trash mr-1"></i> Hapus PIC
                    </x-bladewind::button>
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
        });
    </script>
@endpush

@endsection