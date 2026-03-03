@extends('lhkan.layout.lhkan_layout')

@section('title', 'Manajemen PIC LHKAN')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0"> 
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('lhkan.pic.index') }}" class="form-horizontal">
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

            <!-- PIC Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Instansi</th>
                            <th>Nama PIC</th>
                            <th>Nomor HP</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 150px;">Ditambahkan Oleh</th>
                            <th style="width: 180px;">Tanggal Dibuat</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pics->count() > 0)
                            @foreach($pics as $index => $pic)
                                <tr>
                                    <td class="text-center">{{ ($pics->currentPage() - 1) * $pics->perPage() + $index + 1 }}</td>
                                    <td>{{ $pic->instansi->nama ?? '-' }}</td>
                                    <td>{{ $pic->nama }}</td>
                                    <td>{{ $pic->nomor_hp }}</td>
                                    <td>
                                        @switch($pic->status)
                                            @case('pending')
                                                <span class="badge badge-warning bg-warning text-dark">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge badge-success bg-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger bg-danger">Rejected</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($pic->added_by === 'admin')
                                            <span class="badge badge-info bg-info text-dark">Admin</span>
                                        @else
                                            <span class="badge badge-secondary bg-secondary">Instansi</span>
                                        @endif
                                    </td>
                                    <td>{{ $pic->created_at ? $pic->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center flex-wrap">
                                            @if($pic->status === 'pending')
                                                <!-- Approve -->
                                                <form method="POST" action="{{ route('lhkan.pic.approve', $pic->id) }}" class="d-inline mr-1 mb-1" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui PIC ini?')">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                <!-- Reject -->
                                                <form method="POST" action="{{ route('lhkan.pic.reject', $pic->id) }}" class="d-inline mr-1 mb-1" onsubmit="return confirm('Apakah Anda yakin ingin menolak PIC ini?')">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Edit -->
                                            <button type="button" class="btn btn-sm btn-outline-info mr-1 mb-1" onclick='editPic({{ $pic->id }}, @js($pic->nama), @js($pic->nomor_hp))' title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('lhkan.pic.delete', $pic->id) }}" class="d-inline mb-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PIC ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada PIC ditemukan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                <div class="text-muted">
                    @if($pics->count() > 0)
                        Menampilkan {{ $pics->firstItem() }} sampai {{ $pics->lastItem() }} dari {{ $pics->total() }} data
                    @else
                        Menampilkan 0 dari {{ $pics->total() }} data
                    @endif
                </div>
                {{ $pics->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit PIC</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('lhkan.pic.update', ':id') }}" id="editForm" data-action-template="{{ route('lhkan.pic.update', ':id') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama PIC *</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nomor_hp" class="form-label">Nomor HP *</label>
                        <input type="text" id="edit_nomor_hp" name="nomor_hp" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('editForm').submit()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
function editPic(id, nama, nomor_hp) {
    var form = document.getElementById('editForm');
    var actionTemplate = form.dataset.actionTemplate || form.action;
    form.action = actionTemplate.replace(':id', id);
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_nomor_hp').value = nomor_hp;
    $('#editModal').modal('show');
}
</script>
@endpush