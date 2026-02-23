@extends('layout.main')

@section('title', 'Dashboard LHKAN')

@section('content')
<div class="content">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Dashboard Laporan Harta Kekayaan Aparatur Negara</h3>
            <div class="block-options">
                @if(in_array(auth()->user()->level, ['admin', 'tpn']))
                    <form method="GET" action="{{ route('lhkan.export-csv') }}" class="d-inline">
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-file-excel"></i> Export CSV
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="block-content">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="block block-rounded block-themed block-mode-loading bg-primary-light">
                        <div class="block-header">
                            <h3 class="block-title">Total Instansi</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <h2 class="h1 font-w700 text-primary">{{ $totalInstansi }}</h2>
                            <p class="text-muted">Total instansi terdaftar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="block block-rounded block-themed block-mode-loading bg-info-light">
                        <div class="block-header">
                            <h3 class="block-title">Sudah Submit</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <h2 class="h1 font-w700 text-info">{{ $submittedCount }}</h2>
                            <p class="text-muted">Instansi sudah melaporkan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="block block-rounded block-themed block-mode-loading bg-success-light">
                        <div class="block-header">
                            <h3 class="block-title">Sudah Approve</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <h2 class="h1 font-w700 text-success">{{ $approvedCount }}</h2>
                            <p class="text-muted">Data disetujui</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="block block-rounded block-themed block-mode-loading bg-warning-light">
                        <div class="block-header">
                            <h3 class="block-title">Tingkat Penyelesaian</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <h2 class="h1 font-w700 text-warning">{{ $completionRate }}%</h2>
                            <p class="text-muted">Tingkat kepatuhan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mt-4">
                <div class="col-12">
                    <form method="GET" action="{{ route('lhkan.dashboard') }}" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Periode</label>
                                    <select name="periode_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">Semua Periode</option>
                                        @foreach($periodes as $periode)
                                            <option value="{{ $periode->id }}" {{ $periodeId == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->nama }} ({{ $periode->tahun }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            @if(auth()->user()->level === 'admin')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Instansi</label>
                                        <select name="instansi_id" class="form-control" onchange="this.form.submit()">
                                            <option value="">Semua Instansi</option>
                                            {{-- Add instansi options --}}
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Instansi</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-center">Total Aparatur</th>
                            <th class="text-center">Wajib LHKPN</th>
                            <th class="text-center">Realisasi LHKPN</th>
                            <th class="text-center">Belum LHKAN</th>
                            <th>PIC</th>
                            <th>Tanggal Submit</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($submissions->count() > 0)
                            @foreach($submissions as $index => $submission)
                                <tr>
                                    <td class="text-center">{{ ($submissions->currentPage() - 1) * $submissions->perPage() + $index + 1 }}</td>
                                    <td>{{ $submission->instansi->nama_instansi ?? '-' }}</td>
                                    <td>{{ $submission->period->nama ?? '-' }}</td>
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
                                    <td>
                                        @foreach($submission->pics as $pic)
                                            <div>{{ $pic->nama }} ({{ $pic->nomor_hp }})</div>
                                        @endforeach
                                    </td>
                                    <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        @if(auth()->user()->level === 'admin')
                                            <a href="{{ route('lhkan.periode.index') }}" class="btn btn-sm btn-primary" title="Kelola Periode">
                                                <i class="fa fa-cog"></i>
                                            </a>
                                            <a href="{{ route('lhkan.pic.index') }}" class="btn btn-sm btn-info" title="Kelola PIC">
                                                <i class="fa fa-users"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data ditemukan</td>
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
@endsection