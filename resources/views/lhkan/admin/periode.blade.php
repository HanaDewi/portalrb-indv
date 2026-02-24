@extends('layout.rubick')

@section('title', 'Manajemen Periode LHKAN')

@section('content')
<div class="content">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Manajemen Periode Pelaporan</h3>
        </div>
        <div class="block-content">
            <!-- Add New Periode Form -->
            <div class="row">
                <div class="col-md-12">
                    <div class="block block-rounded block-themed block-mode-loading bg-primary-light">
                        <div class="block-header">
                            <h3 class="block-title">Tambah Periode Baru</h3>
                        </div>
                        <div class="block-content">
                            <form method="POST" action="{{ route('lhkan.periode.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tahun">Tahun *</label>
                                            <input type="number" id="tahun" name="tahun" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nama">Nama Periode *</label>
                                            <input type="text" id="nama" name="nama" class="form-control" required
                                                placeholder="Contoh: Pelaporan 2024">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <select id="status" name="status" class="form-control" required>
                                                <option value="open">Open</option>
                                                <option value="locked">Locked</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="deskripsi">Deskripsi</label>
                                            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="2"
                                                placeholder="Deskripsi periode pelaporan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Periodes Table -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th width="100">Tahun</th>
                            <th>Nama Periode</th>
                            <th>Deskripsi</th>
                            <th width="100">Status</th>
                            <th width="150">Jumlah Submit</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($periodes->count() > 0)
                            @foreach($periodes as $index => $periode)
                                <tr>
                                    <td class="text-center">
                                        {{ ($periodes->currentPage() - 1) * $periodes->perPage() + $index + 1 }}</td>
                                    <td>{{ $periode->tahun }}</td>
                                    <td>{{ $periode->nama }}</td>
                                    <td>{{ $periode->deskripsi ?? '-' }}</td>
                                    <td>
                                        @if($periode->status === 'open')
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Locked</span>
                                        @endif
                                    </td>
                                    <td>{{ $periode->submissions()->where('status', '!=', 'draft')->count() }}</td>
                                    <td class="text-center">
                                        <!-- Toggle Lock/Unlock -->
                                        @if($periode->status === 'open')
                                            <form method="POST" action="{{ route('lhkan.periode.toggle-lock', $periode->id) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin mengunci periode ini? Instansi tidak akan dapat mengedit data.')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-warning" title="Kunci Periode">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('lhkan.periode.toggle-lock', $periode->id) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membuka periode ini? Instansi akan dapat mengedit data.')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-success" title="Buka Periode">
                                                    <i class="fa fa-unlock"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Edit -->
                                        <button type="button" class="btn btn-sm btn-info"
                                            onclick="editPeriode({{ $periode->id }}, '{{ $periode->tahun }}', '{{ $periode->nama }}', '{{ $periode->status }}', '{{ $periode->deskripsi ?? '' }}')"
                                            title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <!-- Delete -->
                                        @if(!$periode->submissions()->exists())
                                            <form method="POST" action="{{ route('lhkan.periode.delete', $periode->id) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger" disabled
                                                title="Tidak dapat dihapus (ada data pelaporan)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada periode ditemukan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $periodes->firstItem() }} sampai {{ $periodes->lastItem() }} dari
                    {{ $periodes->total() }} data
                </div>
                {{ $periodes->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Periode</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('lhkan.periode.update', ':id') }}" id="editForm">
                    @csrf
                    <div class="form-group">
                        <label for="edit_tahun">Tahun *</label>
                        <input type="number" id="edit_tahun" name="tahun" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama">Nama Periode *</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status *</label>
                        <select id="edit_status" name="status" class="form-control" required>
                            <option value="open">Open</option>
                            <option value="locked">Locked</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_deskripsi">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('editForm').submit()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        function editPeriode(id, tahun, nama, status, deskripsi) {
            var form = document.getElementById('editForm');
            form.action = form.action.replace(':id', id);
            document.getElementById('edit_tahun').value = tahun;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_deskripsi').value = deskripsi;
            $('#editModal').modal('show');
        }
    </script>
@endsection