@extends('layout.main')

@section('title', 'Form Pelaporan LHKAN')

@section('content')
<div class="content">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Form Pelaporan Harta Kekayaan Aparatur Negara</h3>
        </div>
        <div class="block-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Periode Alert -->
            @if(isset($currentPeriod))
                @if($currentPeriod->status === 'locked')
                    <div class="alert alert-warning">
                        <i class="fa fa-lock"></i> Periode saat ini dalam status <strong>Locked</strong>. Anda tidak dapat
                        mengubah data. Silakan hubungi Admin jika perlu mengubah data.
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Periode: <strong>{{ $currentPeriod->nama }}</strong>
                        ({{ $currentPeriod->tahun }})
                    </div>
                @endif
            @else
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i> Tidak ada periode aktif saat ini. Silakan hubungi Admin.
                </div>
            @endif

            @if(isset($currentPeriod) && $currentPeriod->status === 'open')
                <form method="POST" action="{{ route('lhkan.store') }}" id="lhkanForm">
                    @csrf
                    <!-- Instansi Info (Read Only) -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-light border">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Instansi:</strong> {{ auth()->user()->instansi->nama ?? '-' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Periode:</strong> {{ $currentPeriod->nama }} ({{ $currentPeriod->tahun }})
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PIC Section -->
                    <h5 class="font-w600 mb-3 mt-4">Data PIC (Person In Charge)</h5>
                    <div class="row" id="picContainer">
                        @if(isset($submission) && $submission->pics->count() > 0)
                            @foreach($submission->pics as $index => $pic)
                                <div class="col-md-6 mb-3 pic-row" data-index="{{ $index }}">
                                    <div class="form-group">
                                        <label>Nama PIC {{ $index + 1 }}</label>
                                        <input type="text" name="pics[{{ $index }}][nama]" class="form-control pic-nama"
                                            value="{{ $pic->nama }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor HP PIC {{ $index + 1 }}</label>
                                        <input type="text" name="pics[{{ $index }}][nomor_hp]" class="form-control pic-hp"
                                            value="{{ $pic->nomor_hp }}" required>
                                    </div>
                                    @if($index === 0)
                                        <button type="button" class="btn btn-sm btn-success" onclick="addPicRow()">
                                            <i class="fa fa-plus"></i> Tambah PIC
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removePicRow({{ $index }})">
                                            <i class="fa fa-trash"></i> Hapus PIC
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-6 mb-3 pic-row" data-index="0">
                                <div class="form-group">
                                    <label>Nama PIC 1</label>
                                    <input type="text" name="pics[0][nama]" class="form-control pic-nama" required>
                                </div>
                                <div class="form-group">
                                    <label>Nomor HP PIC 1</label>
                                    <input type="text" name="pics[0][nomor_hp]" class="form-control pic-hp" required>
                                </div>
                                <button type="button" class="btn btn-sm btn-success" onclick="addPicRow()">
                                    <i class="fa fa-plus"></i> Tambah PIC
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Data Aparatur Section -->
                    <h5 class="font-w600 mb-3 mt-4">Data Aparatur Negara</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jml_aparatur">Jumlah Total Aparatur Negara *</label>
                                <input type="number" id="jml_aparatur" name="jml_aparatur" class="form-control"
                                    value="{{ $submission->jml_aparatur ?? '' }}" min="0" required
                                    onchange="calculateTotalBelumLhkan()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jml_wajib_lhkpn">Jumlah Wajib LHKPN *</label>
                                <input type="number" id="jml_wajib_lhkpn" name="jml_wajib_lhkpn" class="form-control"
                                    value="{{ $submission->jml_wajib_lhkpn ?? '' }}" min="0" required
                                    onchange="calculateTotalBelumLhkan()">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jml_non_wajib_lhkpn">Jumlah Tidak Wajib LHKPN *</label>
                                <input type="number" id="jml_non_wajib_lhkpn" name="jml_non_wajib_lhkpn"
                                    class="form-control" value="{{ $submission->jml_non_wajib_lhkpn ?? '' }}" min="0"
                                    required onchange="calculateTotalBelumLhkan()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="realisasi_lhkpn">Realisasi LHKPN (Sudah Lapor) *</label>
                                <input type="number" id="realisasi_lhkpn" name="realisasi_lhkpn" class="form-control"
                                    value="{{ $submission->realisasi_lhkpn ?? '' }}" min="0" required
                                    onchange="calculateTotalBelumLhkan()">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="realisasi_spt_non_lhkpn">Realisasi SPT Tahunan - Non Wajib LHKPN (Sudah Lapor)
                                    *</label>
                                <input type="number" id="realisasi_spt_non_lhkpn" name="realisasi_spt_non_lhkpn"
                                    class="form-control" value="{{ $submission->realisasi_spt_non_lhkpn ?? '' }}" min="0"
                                    required onchange="calculateTotalBelumLhkan()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="belum_spt_non_lhkpn">Belum Lapor SPT - Non Wajib LHKPN *</label>
                                <input type="number" id="belum_spt_non_lhkpn" name="belum_spt_non_lhkpn"
                                    class="form-control" value="{{ $submission->belum_spt_non_lhkpn ?? '' }}" min="0"
                                    required onchange="calculateTotalBelumLhkan()">
                            </div>
                        </div>
                    </div>

                    <!-- Calculated Field -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total_belum_lhkan">Total Belum Lapor LHKAN (Calculated)</label>
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
                    <h5 class="font-w600 mb-3 mt-4">Dokumen Rekapitulasi</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="link_rekap_gdrive">Link Google Drive Rekapitulasi *</label>
                                <input type="url" id="link_rekap_gdrive" name="link_rekap_gdrive" class="form-control"
                                    value="{{ $submission->link_rekap_gdrive ?? '' }}" required
                                    placeholder="https://drive.google.com/...">
                                <small class="text-muted">Paste link Google Drive yang berisi file rekapitulasi
                                    LHKAN</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" name="action" value="draft" class="btn btn-secondary">
                                <i class="fa fa-save"></i> Simpan Draft
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary"
                                onclick="return confirm('Apakah Anda yakin ingin mensubmit data? Data tidak dapat diubah setelah submit kecuali ada persetujuan Admin.')">
                                <i class="fa fa-paper-plane"></i> Submit Data
                            </button>
                            <a href="{{ route('lhkan.history') }}" class="btn btn-info">
                                <i class="fa fa-history"></i> Lihat History
                            </a>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@section('scripts')
    <script>
        let picCount = {{ isset($submission) ? $submission->pics->count() : 1 }};

        function addPicRow() {
            const container = document.getElementById('picContainer');
            const newIndex = picCount;

            const newRow = document.createElement('div');
            newRow.className = 'col-md-6 mb-3 pic-row';
            newRow.dataset.index = newIndex;
            newRow.innerHTML = `
                <div class="form-group">
                    <label>Nama PIC ${newIndex + 1}</label>
                    <input type="text" name="pics[${newIndex}][nama]" class="form-control pic-nama" required>
                </div>
                <div class="form-group">
                    <label>Nomor HP PIC ${newIndex + 1}</label>
                    <input type="text" name="pics[${newIndex}][nomor_hp]" class="form-control pic-hp" required>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removePicRow(${newIndex})">
                    <i class="fa fa-trash"></i> Hapus PIC
                </button>
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
@endsection