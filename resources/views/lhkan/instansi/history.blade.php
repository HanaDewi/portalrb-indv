@extends('layout.main')

@section('title', 'History Pelaporan LHKAN')

@section('content')
<div class="content">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">History Pelaporan Harta Kekayaan Aparatur Negara</h3>
        </div>
        <div class="block-content">
            <!-- Info Card -->
            <div class="alert alert-light border">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Instansi:</strong> {{ auth()->user()->instansi->nama ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <div class="text-right">
                            <a href="{{ route('lhkan.form') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Input Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th width="150">Periode</th>
                            <th width="100">Status</th>
                            <th class="text-center" width="120">Total Aparatur</th>
                            <th class="text-center" width="120">Wajib LHKPN</th>
                            <th class="text-center" width="120">Realisasi LHKPN</th>
                            <th class="text-center" width="120">Belum LHKAN</th>
                            <th width="200">Tanggal Submit</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($submissions->count() > 0)
                            @foreach($submissions as $index => $submission)
                                <tr>
                                    <td class="text-center">{{ ($submissions->currentPage() - 1) * $submissions->perPage() + $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $submission->period->nama ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $submission->period->tahun ?? '' }}</small>
                                    </td>
                                    <td>
                                        @switch($submission->status)
                                            @case('draft')
                                                <span class="badge badge-secondary">Draft</span>
                                                @break
                                            @case('submitted')
                                                <span class="badge badge-info">Submitted</span>
                                                @break
                                            @case('approved')
                                                <span class="badge badge-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">{{ $submission->jml_aparatur ?? 0 }}</td>
                                    <td class="text-center">{{ $submission->jml_wajib_lhkpn ?? 0 }}</td>
                                    <td class="text-center">{{ $submission->realisasi_lhkpn ?? 0 }}</td>
                                    <td class="text-center font-weight-bold text-danger">{{ $submission->total_belum_lhkan ?? 0 }}</td>
                                    <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        @if($submission->status === 'draft')
                                            <a href="{{ route('lhkan.form') }}?submission_id={{ $submission->id }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @elseif(in_array($submission->status, ['submitted', 'approved']) && $submission->period && $submission->period->status === 'open')
                                            <button type="button" class="btn btn-sm btn-warning" onclick="showChangeRequestModal({{ $submission->id }})" title="Ajukan Perubahan">
                                                <i class="fa fa-exchange-alt"></i>
                                            </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-sm btn-info" onclick="showDetailModal({{ $submission->id }})" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada data pelaporan</p>
                                        <a href="{{ route('lhkan.form') }}" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Mulai Pelaporan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $submissions->firstItem() }} sampai {{ $submissions->lastItem() }} dari {{ $submissions->total() }} data
                </div>
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelaporan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Request Modal -->
<div class="modal fade" id="changeRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajukan Perubahan Data</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('lhkan.change-requests.store') }}" id="changeRequestForm">
                    @csrf
                    <input type="hidden" name="submission_id" id="cr_submission_id">
                    
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Perubahan yang diajukan memerlukan persetujuan Admin sebelum diterapkan.
                    </div>

                    <div class="form-group">
                        <label for="cr_field_name">Field yang Ingin Diubah *</label>
                        <select id="cr_field_name" name="field_name" class="form-control" required onchange="showCurrentValue()">
                            <option value="">Pilih Field</option>
                            <option value="realisasi_lhkpn">Realisasi LHKPN</option>
                            <option value="realisasi_spt_non_lhkpn">Realisasi SPT Tahunan (Non Wajib LHKPN)</option>
                            <option value="belum_spt_non_lhkpn">Belum Lapor SPT (Non Wajib LHKPN)</option>
                            <option value="pics">Data PIC</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nilai Saat Ini:</label>
                        <input type="text" id="cr_old_value" class="form-control bg-light" readonly>
                    </div>

                    <div class="form-group">
                        <label for="cr_new_value">Nilai Baru *</label>
                        <input type="text" id="cr_new_value" name="new_value" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="cr_reason">Alasan Perubahan *</label>
                        <textarea id="cr_reason" name="reason" class="form-control" rows="3" required placeholder="Jelaskan alasan perubahan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('changeRequestForm').submit()">Ajukan</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
let submissions = @json($submissions->items());

function showDetailModal(submissionId) {
    const submission = submissions.find(s => s.id === submissionId);
    if (!submission) return;

    const html = `
        <table class="table table-bordered">
            <tr>
                <th width="40%">Periode</th>
                <td>${submission.period ? submission.period.nama + ' (' + submission.period.tahun + ')' : '-'}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge badge-${submission.status === 'approved' ? 'success' : (submission.status === 'submitted' ? 'info' : 'secondary')}">${submission.status.toUpperCase()}</span></td>
            </tr>
            <tr>
                <th>Total Aparatur</th>
                <td>${submission.jml_aparatur || 0}</td>
            </tr>
            <tr>
                <th>Jumlah Wajib LHKPN</th>
                <td>${submission.jml_wajib_lhkpn || 0}</td>
            </tr>
            <tr>
                <th>Jumlah Tidak Wajib LHKPN</th>
                <td>${submission.jml_non_wajib_lhkpn || 0}</td>
            </tr>
            <tr>
                <th>Realisasi LHKPN</th>
                <td>${submission.realisasi_lhkpn || 0}</td>
            </tr>
            <tr>
                <th>Realisasi SPT Tahunan (Non Wajib LHKPN)</th>
                <td>${submission.realisasi_spt_non_lhkpn || 0}</td>
            </tr>
            <tr>
                <th>Belum Lapor SPT (Non Wajib LHKPN)</th>
                <td>${submission.belum_spt_non_lhkpn || 0}</td>
            </tr>
            <tr>
                <th>Total Belum Lapor LHKAN</th>
                <td class="font-weight-bold text-danger">${submission.total_belum_lhkan || 0}</td>
            </tr>
            <tr>
                <th>Link Google Drive</th>
                <td><a href="${submission.link_rekap_gdrive || '#'}" target="_blank">${submission.link_rekap_gdrive || '-'}</a></td>
            </tr>
            <tr>
                <th>PIC</th>
                <td>
                    ${submission.pics && submission.pics.length > 0 ? submission.pics.map(pic => `<div>${pic.nama} (${pic.nomor_hp})</div>`).join('') : '-'}
                </td>
            </tr>
            <tr>
                <th>Tanggal Submit</th>
                <td>${submission.submitted_at ? new Date(submission.submitted_at).toLocaleString('id-ID') : '-'}</td>
            </tr>
        </table>
    `;

    document.getElementById('detailModalBody').innerHTML = html;
    $('#detailModal').modal('show');
}

function showChangeRequestModal(submissionId) {
    document.getElementById('cr_submission_id').value = submissionId;
    document.getElementById('cr_field_name').value = '';
    document.getElementById('cr_old_value').value = '';
    document.getElementById('cr_new_value').value = '';
    document.getElementById('cr_reason').value = '';
    $('#changeRequestModal').modal('show');
}

function showCurrentValue() {
    const submissionId = parseInt(document.getElementById('cr_submission_id').value);
    const fieldName = document.getElementById('cr_field_name').value;
    const submission = submissions.find(s => s.id === submissionId);
    
    if (!submission || !fieldName) {
        document.getElementById('cr_old_value').value = '';
        return;
    }

    let oldValue = '';
    if (fieldName === 'pics') {
        if (submission.pics && submission.pics.length > 0) {
            oldValue = submission.pics.map(pic => pic.nama).join(', ');
        }
    } else {
        oldValue = submission[fieldName] || 0;
    }

    document.getElementById('cr_old_value').value = oldValue;
}
</script>
@endsection