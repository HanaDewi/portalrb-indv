@extends('lhkan.layout.lhkan_layout')

@section('title', 'Manajemen Periode Pelaporan')

@section('content')
    <div class="block block-rounded block-bordered mt-8">
        <div class="block-content">
            <!-- Add Button -->
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPeriodeModal">
                    <i class="fa fa-plus"></i> Tambah Periode Baru
                </button>
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

        <!-- Add Periode Modal -->
        <div class="modal fade" id="addPeriodeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Periode Baru</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="addPeriodeForm">
                            @csrf
                            <div class="form-group">
                                <label for="add_tahun">Tahun *</label>
                                <input type="number" id="add_tahun" name="tahun" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="add_nama">Nama Periode *</label>
                                <input type="text" id="add_nama" name="nama" class="form-control" required
                                    placeholder="Contoh: Pelaporan 2024">
                            </div>
                            <div class="form-group">
                                <label for="add_status">Status *</label>
                                <select id="add_status" name="status" class="form-control" required>
                                    <option value="open">Open</option>
                                    <option value="locked">Locked</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="add_deskripsi">Deskripsi</label>
                                <textarea id="add_deskripsi" name="deskripsi" class="form-control" rows="3"
                                    placeholder="Deskripsi periode pelaporan..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" form="addPeriodeForm">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Periode Modal -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Periode</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editPeriodeForm">
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
                        <button type="submit" class="btn btn-primary" form="editPeriodeForm">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                // // Add Periode Modal
                // $('#btnShowAddPeriode').on('click', function() {
                //     $('#addPeriodeModal').modal('show');
                // });

                $('#addPeriodeForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('lhkan.periode.store') }}",
                        method: 'POST',
                        data: $('#addPeriodeForm').serialize(),
                        success: function(response) {
                            $('#addPeriodeModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: errorMessage
                            });
                        }
                    });
                });

                // Edit Periode Modal
                window.editPeriode = function(id, tahun, nama, status, deskripsi) {
                    $('#edit_tahun').val(tahun);
                    $('#edit_nama').val(nama);
                    $('#edit_status').val(status);
                    $('#edit_deskripsi').val(deskripsi);
                    $('#editModal').data('periode-id', id);
                    $('#editModal').modal('show');
                }

                $('#editPeriodeForm').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#editModal').data('periode-id');
                    if (!id) return;

                    $.ajax({
                        url: "{{ route('lhkan.periode.update', ':id') }}".replace(':id', id),
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'PUT',
                            tahun: $('#edit_tahun').val(),
                            nama: $('#edit_nama').val(),
                            status: $('#edit_status').val(),
                            deskripsi: $('#edit_deskripsi').val()
                        },
                        success: function(response) {
                            $('#editModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat memperbarui data.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: errorMessage
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection