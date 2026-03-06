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
                <table id="table-lhkan-pic" class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Instansi</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Nama PIC</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Nomor HP</th>
                            <th style="width: 100px; min-width: 100px; padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                            <th style="width: 140px; min-width: 140px; padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Ditambahkan Oleh</th>
                            <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Tanggal Dibuat</th>
                            <th class="text-center" style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pics->count() > 0)
                            @foreach($pics as $index => $pic)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $pic->instansi->name ?? '-' }}</td>
                                    <td>{{ $pic->nama }}</td>
                                    <td>{{ $pic->nomor_hp }}</td>
                                    <td style="padding: 0.75rem 1rem; vertical-align: middle; white-space: nowrap;">
                                        @switch($pic->status)
                                            @case('pending')
                                                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #ffc107; color: #000; border-radius: 0.25rem;">Pending</span>
                                                @break
                                            @case('approved')
                                                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #198754; color: #fff; border-radius: 0.25rem;">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #dc3545; color: #fff; border-radius: 0.25rem;">Rejected</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td style="padding: 0.75rem 1rem; vertical-align: middle; white-space: nowrap;">
                                        @if($pic->added_by === 'admin')
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #0dcaf0; color: #000; border-radius: 0.25rem;">Admin</span>
                                        @else
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #6c757d; color: #fff; border-radius: 0.25rem;">Instansi</span>
                                        @endif
                                    </td>
                                    <td>{{ $pic->created_at ? $pic->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <div class="flex justify-content-center flex-wrap">
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
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
function editPic(id, nama, nomor_hp) {
    var form = document.getElementById('editForm');
    var actionTemplate = form.dataset.actionTemplate || form.action;
    form.action = actionTemplate.replace(':id', id);
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_nomor_hp').value = nomor_hp;
    $('#editModal').modal('show');
}
$(document).ready(function() {
    if ($.fn.DataTable && $('#table-lhkan-pic').length) {
        $('#table-lhkan-pic').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json' },
            pageLength: 20,
            order: [[6, 'desc']],
            columnDefs: [{ orderable: false, targets: -1 }]
        });
    }
});
</script>
@endpush