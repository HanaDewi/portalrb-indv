@extends('lhkan.layout.lhkan_layout')

@section('title', 'Manajemen Pengajuan Perubahan LHKAN')
@section('content')
<div class="block block-rounded block-bordered mt-8">
    <div class="block-content">
            <!-- Filters -->
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('lhkan.change-requests.index') }}" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Requests Table -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Instansi</th>
                            <th>Periode</th>
                            <th>Field</th>
                            <th>Nilai Lama</th>
                            <th>Nilai Baru</th>
                            <th>Alasan</th>
                            <th width="100">Status</th>
                            <th width="150">Diajukan Pada</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($changeRequests->count() > 0)
                            @foreach($changeRequests as $index => $request)
                                <tr>
                                    <td class="text-center">{{ ($changeRequests->currentPage() - 1) * $changeRequests->perPage() + $index + 1 }}</td>
                                    <td>{{ $request->submission->instansi->nama ?? '-' }}</td>
                                    <td>{{ $request->submission->period->nama ?? '-' }}</td>
                                    <td>
                                        <strong>{{ ucwords(str_replace('_', ' ', $request->field_name)) }}</strong>
                                    </td>
                                    <td>
                                        @if(in_array($request->field_name, ['realisasi_lhkpn', 'realisasi_spt_non_lhkpn', 'belum_spt_non_lhkpn']))
                                            {{ $request->old_value }}
                                        @elseif($request->field_name === 'pics')
                                            @php
                                                $oldPics = json_decode($request->old_value, true);
                                            @endphp
                                            @if($oldPics)
                                                @foreach($oldPics as $pic)
                                                    <div>{{ $pic['nama'] ?? '' }}</div>
                                                @endforeach
                                            @else
                                                {{ $request->old_value }}
                                            @endif
                                        @else
                                            {{ $request->old_value }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($request->field_name, ['realisasi_lhkpn', 'realisasi_spt_non_lhkpn', 'belum_spt_non_lhkpn']))
                                            <span class="text-primary font-weight-bold">{{ $request->new_value }}</span>
                                        @elseif($request->field_name === 'pics')
                                            @php
                                                $newPics = json_decode($request->new_value, true);
                                            @endphp
                                            @if($newPics)
                                                @foreach($newPics as $pic)
                                                    <div class="text-primary font-weight-bold">{{ $pic['nama'] ?? '' }}</div>
                                                @endforeach
                                            @else
                                                <span class="text-primary font-weight-bold">{{ $request->new_value }}</span>
                                            @endif
                                        @else
                                            <span class="text-primary font-weight-bold">{{ $request->new_value }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $request->reason }}</small>
                                    </td>
                                    <td>
                                        @switch($request->status)
                                            @case('pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge badge-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        @if($request->status === 'pending')
                                            <!-- Approve -->
                                            <form method="POST" action="{{ route('lhkan.change-requests.approve', $request->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui perubahan ini?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                            <!-- Reject -->
                                            <form method="POST" action="{{ route('lhkan.change-requests.reject', $request->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak perubahan ini?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada pengajuan perubahan ditemukan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $changeRequests->firstItem() }} sampai {{ $changeRequests->lastItem() }} dari {{ $changeRequests->total() }} data
                </div>
                {{ $changeRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection