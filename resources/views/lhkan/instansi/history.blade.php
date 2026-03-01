@extends('lhkan.layout.lhkan_layout')

@section('title', 'History Pelaporan LHKAN')

@push('css')
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="mt-8">
    <x-bladewind::card>
        <div class="flex justify-between md:flex-row md:items-center md:justify-between gap-3 border-b border-slate-200 pb-4 mb-5">
            <div>
                <div class="text-sm text-slate-500">Instansi</div>
                <div class="text-lg font-semibold text-slate-800">{{ auth()->user()->instansi->name ?? '-' }}</div>
            </div>
            <div>
                <x-bladewind::button color="blue" has_icon="true" icon="plus" onclick="window.location.href='{{ route('lhkan.form') }}'">
                    Input Baru
                </x-bladewind::button>
            </div>
        </div>

            <div class="table-responsive">
                <x-bladewind::table compact="true" divider="thin" celled="true">
                    <x-slot name="header">
                        <th class="text-center" width="50">No</th>
                        <th width="150">Periode</th>
                        <th width="100">Status</th>
                        <th class="text-center" width="120">Total Aparatur</th>
                        <th class="text-center" width="120">Wajib LHKPN</th>
                        <th class="text-center" width="120">Realisasi LHKPN</th>
                        <th class="text-center" width="120">Belum LHKAN</th>
                        <th width="200">Tanggal Submit</th>
                        <th class="text-center" width="170">Aksi</th>
                    </x-slot>

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
                                            <x-bladewind::tag label="Draft" color="gray" />
                                            @break
                                        @case('submitted')
                                            <x-bladewind::tag label="Submitted" color="blue" />
                                            @break
                                        @case('approved')
                                            <x-bladewind::tag label="Approved" color="green" />
                                            @break
                                        @case('rejected')
                                            <x-bladewind::tag label="Rejected" color="red" />
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-center">{{ $submission->jml_aparatur ?? 0 }}</td>
                                <td class="text-center">{{ $submission->jml_wajib_lhkpn ?? 0 }}</td>
                                <td class="text-center">{{ $submission->realisasi_lhkpn ?? 0 }}</td>
                                <td class="text-center font-weight-bold text-danger">{{ $submission->total_belum_lhkan ?? 0 }}</td>
                                <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-1">
                                        @if($submission->status === 'draft')
                                            <button type="button" class="bw-button bw-blue" title="Edit"
                                                onclick="window.location.href='{{ route('lhkan.form') }}?submission_id={{ $submission->id }}'">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @elseif(in_array($submission->status, ['submitted', 'approved']) && $submission->period && $submission->period->status === 'open')
                                            <button type="button" class="bw-button bw-yellow" onclick="showChangeRequestModal({{ $submission->id }})" title="Ajukan Perubahan">
                                                <i class="fa fa-exchange-alt"></i>
                                            </button>
                                        @endif

                                        <button type="button" class="bw-button bw-cyan" onclick="showDetailModal({{ $submission->id }})" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="py-8">
                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-3">Belum ada data pelaporan</p>
                                    <x-bladewind::button color="blue" has_icon="true" icon="plus"
                                        onclick="window.location.href='{{ route('lhkan.form') }}'">
                                        Mulai Pelaporan
                                    </x-bladewind::button>
                                </div>
                            </td>
                        </tr>
                    @endif
                </x-bladewind::table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $submissions->firstItem() }} sampai {{ $submissions->lastItem() }} dari {{ $submissions->total() }} data
                </div>
                {{ $submissions->links() }}
            </div>
    </x-bladewind::card>
</div>

<x-bladewind::modal
    name="detail-modal"
    title="Detail Pelaporan"
    size="large"
    show_close_icon="true"
    backdrop_can_close="true"
    show_action_buttons="false">
    <div id="detailModalBody">
        <!-- Content will be loaded via AJAX -->
    </div>
    <div class="text-right pt-4">
        <button type="button" class="bw-button bw-gray" onclick="hideModal('detail-modal')">Tutup</button>
    </div>
</x-bladewind::modal>

<x-bladewind::modal
    name="change-request-modal"
    title="Ajukan Perubahan Data"
    size="medium"
    show_close_icon="true"
    backdrop_can_close="true"
    show_action_buttons="false">
    <form method="POST" action="{{ route('lhkan.change-requests.store') }}" id="changeRequestForm">
        @csrf
        <input type="hidden" name="submission_id" id="cr_submission_id">

        <div class="rounded-md border border-sky-200 bg-sky-50 text-sky-700 px-4 py-3 mb-4">
            <i class="fa fa-info-circle mr-1"></i> Perubahan yang diajukan memerlukan persetujuan Admin sebelum diterapkan.
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

        <div class="text-right pt-2">
            <button type="button" class="bw-button bw-gray mr-1" onclick="hideModal('change-request-modal')">Batal</button>
            <button type="button" class="bw-button bw-blue" onclick="document.getElementById('changeRequestForm').submit()">Ajukan</button>
        </div>
    </form>
</x-bladewind::modal>

@push('js')
<script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
<script>
let submissions = @json($submissions->items());

function statusLabel(status) {
    switch (status) {
        case 'approved':
            return '<span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">APPROVED</span>';
        case 'submitted':
            return '<span class="inline-flex rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-700">SUBMITTED</span>';
        case 'rejected':
            return '<span class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">REJECTED</span>';
        default:
            return '<span class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">DRAFT</span>';
    }
}

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
                <td>${statusLabel(submission.status)}</td>
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
    showModal('detail-modal');
}

function showChangeRequestModal(submissionId) {
    document.getElementById('cr_submission_id').value = submissionId;
    document.getElementById('cr_field_name').value = '';
    document.getElementById('cr_old_value').value = '';
    document.getElementById('cr_new_value').value = '';
    document.getElementById('cr_reason').value = '';
    showModal('change-request-modal');
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
@endpush

@endsection