@extends('lhkan.layout.lhkan_layout')

@section('title', 'Dashboard Laporan Harta Kekayaan Aparatur Negara')

@section('content')
<div class="block block-rounded block-bordered mt-8">
    @if(in_array(auth()->user()->level, ['admin', 'tpn']))
        <form method="GET" action="{{ route('lhkan.export-csv') }}" style="display: inline-flex;">
            <input type="hidden" name="periode_id" value="{{ $periodeId }}">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-file-excel" style="margin-right: 0.5rem;"></i> Export CSV
            </button>
        </form>
    @endif
    
    <div class="block-content">
        <!-- Statistics Cards -->
        <style>
            @media (min-width: 640px) {
                .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
            }
            @media (min-width: 1024px) {
                .stats-grid { grid-template-columns: repeat(4, 1fr) !important; }
            }
        </style>
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 1rem; margin-top: 1.5rem; width: 100%;">
            <div class="box rounded-lg" style="border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; background-color: #eff6ff;">
                    <h3 style="font-weight: 600; color: #2563eb;">Total Instansi</h3>
                </div>
                <div style="padding: 1rem 1.5rem;">
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #2563eb;">{{ $totalInstansi }}</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Total instansi terdaftar</p>
                </div>
            </div>
            <div class="box rounded-lg" style="border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; background-color: #ecfeff;">
                    <h3 style="font-weight: 600; color: #0891b2;">Sudah Submit</h3>
                </div>
                <div style="padding: 1rem 1.5rem;">
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #0891b2;">{{ $submittedCount }}</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Instansi sudah melaporkan</p>
                </div>
            </div>
            <div class="box rounded-lg" style="border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; background-color: #f0fdf4;">
                    <h3 style="font-weight: 600; color: #16a34a;">Sudah Approve</h3>
                </div>
                <div style="padding: 1rem 1.5rem;">
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #16a34a;">{{ $approvedCount }}</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Data disetujui</p>
                </div>
            </div>
            <div class="box rounded-lg" style="border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; background-color: #fffbeb;">
                    <h3 style="font-weight: 600; color: #d97706;">Tingkat Penyelesaian</h3>
                </div>
                <div style="padding: 1rem 1.5rem;">
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #d97706;">{{ $completionRate }}%</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Tingkat kepatuhan</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <style>
            @media (max-width: 1024px) {
                .filter-grid { grid-template-columns: repeat(1, 1fr) !important; }
            }
        </style>
        <form method="GET" action="{{ route('lhkan.dashboard') }}" class="filter-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.5rem;">
            <div>
                <label class="form-label" style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; display: inline-block;">Periode</label>
                <select name="periode_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}" {{ $periodeId == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama }} ({{ $periode->tahun }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; display: inline-block;">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            @if(auth()->user()->level === 'admin')
                <div>
                    <label class="form-label" style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; display: inline-block;">Instansi</label>
                    <select name="instansi_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Instansi</option>
                        {{-- Add instansi options --}}
                    </select>
                </div>
            @endif
        </form>

        <!-- Data Table -->
        <div style="overflow-x: auto; margin-top: 1.5rem; background-color: white;">
            <table class="table">
                <thead style="background-color: #f9fafb;">
                    <tr>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">No</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Instansi</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Periode</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Total Aparatur</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Wajib LHKPN</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Realisasi LHKPN</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Belum LHKAN</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">PIC</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Tanggal Submit</th>
                        <th style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="background-color: white; border-bottom: 1px solid #e5e7eb;">
                    @if($submissions->count() > 0)
                        @foreach($submissions as $index => $submission)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem; color: #111827;">{{ ($submissions->currentPage() - 1) * $submissions->perPage() + $index + 1 }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #111827;">{{ $submission->instansi->nama_instansi ?? '-' }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #111827;">{{ $submission->period->nama ?? '-' }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem;">
                                    @switch($submission->status)
                                        @case('draft')
                                            <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #f3f4f6; color: #1f2937; border-radius: 9999px;">Draft</span>
                                            @break
                                        @case('submitted')
                                            <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #cffafe; color: #155e75; border-radius: 9999px;">Submitted</span>
                                            @break
                                        @case('approved')
                                            <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #dcfce7; color: #166534; border-radius: 9999px;">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #fee2e2; color: #991b1b; border-radius: 9999px;">Rejected</span>
                                            @break
                                    @endswitch
                                </td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem; color: #111827;">{{ $submission->jml_aparatur ?? 0 }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem; color: #111827;">{{ $submission->jml_wajib_lhkpn ?? 0 }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem; color: #111827;">{{ $submission->realisasi_lhkpn ?? 0 }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem; font-weight: 700; color: #dc2626;">{{ $submission->total_belum_lhkan ?? 0 }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #111827;">
                                    @foreach($submission->pics as $pic)
                                        <div>{{ $pic->nama }} ({{ $pic->nomor_hp }})</div>
                                    @endforeach
                                </td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #111827;">{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem;">
                                    @if(auth()->user()->level === 'admin')
                                        <a href="{{ route('lhkan.periode.index') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; padding: 0.375rem 0.5rem; font-size: 0.75rem;" title="Kelola Periode">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                        <a href="{{ route('lhkan.pic.index') }}" class="btn btn-info btn-sm" style="display: inline-flex; align-items: center; padding: 0.375rem 0.5rem; font-size: 0.75rem;" title="Kelola PIC">
                                            <i class="fa fa-users"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11" style="padding: 1rem 1.5rem; text-align: center; font-size: 0.875rem; color: #6b7280;">Tidak ada data ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            <div style="font-size: 0.875rem; color: #4b5563;">
                Menampilkan {{ $submissions->firstItem() }} sampai {{ $submissions->lastItem() }} dari {{ $submissions->total() }} data
            </div>
            {{ $submissions->links() }}
        </div>
    </div>
</div>
@endsection