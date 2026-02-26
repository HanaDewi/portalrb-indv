@extends('lhkan.layout.lhkan_layout')

@section('title', 'Manajemen PIC LHKAN')

@section('content')
<div class="content">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Manajemen PIC Pelaporan</h3>
            @if($pendingCount > 0)
                <span class="badge badge-warning">{{ $pendingCount }} Pending</span>
            @endif
        </div>
        <div class="block-content">
            <!-- Filters -->
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('lhkan.pic.index') }}" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
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
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Instansi</th>
                            <th>Nama PIC</th>
                            <th>Nomor HP</th>
                            <th width="100">Status</th>
                            <th width="150">Ditambahkan Oleh</th>
                            <th width="180">Tanggal Dibuat</th>
                            <th class="text-center" width="150">Aksi</th>
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
                                    <td>
                                        @if($pic->added_by === 'admin')
                                            <span class="badge badge-info">Admin</span>
                                        @else
                                            <span class="badge badge-secondary">Instansi</span>
                                        @endif
                                    </td>
                                    <td>{{ $pic->created_at ? $pic->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        @if($pic->status === 'pending')
                                            <!-- Approve -->
                                            <form method="POST" action="{{ route('lhkan.pic.approve', $pic->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui PIC ini?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                            <!-- Reject -->
                                            <form method="POST" action="{{ route('lhkan.pic.reject', $pic->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak PIC ini?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Edit -->
                                        <button type="button" class="btn btn-sm btn-info" onclick="editPic({{ $pic->id }}, '{{ $pic->nama }}', '{{ $pic->nomor_hp }}')" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        
                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('lhkan.pic.delete', $pic->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PIC ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada PIC ditemukan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $pics->firstItem() }} sampai {{ $pics->lastItem() }} dari {{ $pics->total() }} data
                </div>
                {{ $pics->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit PIC</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('lhkan.pic.update', ':id') }}" id="editForm">
                    @csrf
                    <div class="form-group">
                        <label for="edit_nama">Nama PIC *</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nomor_hp">Nomor HP *</label>
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

@section('scripts')
<script>
function editPic(id, nama, nomor_hp) {
    var form = document.getElementById('editForm');
    form.action = form.action.replace(':id', id);
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_nomor_hp').value = nomor_hp;
    $('#editModal').modal('show');
}
</script>
@endsection