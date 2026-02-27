@extends('lhkan.layout.lhkan_layout')

@section('title', 'Manajemen Periode Pelaporan')

@push('css')
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <style>
        .bw-add-periode-modal,
        .bw-edit-periode-modal,
        [data-modal="add-periode-modal"],
        .bw-modal[name="add-periode-modal"],
        [data-modal="edit-periode-modal"],
        .bw-modal[name="edit-periode-modal"] {
            z-index: 2147483647 !important;
            position: fixed !important;
        }

        .bw-add-periode-modal .bw-modal-backdrop,
        .bw-edit-periode-modal .bw-modal-backdrop,
        [data-modal="add-periode-modal"] .bw-modal-backdrop,
        .bw-modal[name="add-periode-modal"] .bw-modal-backdrop,
        [data-modal="edit-periode-modal"] .bw-modal-backdrop,
        .bw-modal[name="edit-periode-modal"] .bw-modal-backdrop,
        .bw-modal-backdrop {
            z-index: 2147483646 !important;
            position: fixed !important;
        }

        .bw-add-periode-modal .bw-modal-container,
        .bw-edit-periode-modal .bw-modal-container,
        [data-modal="add-periode-modal"] .bw-modal-container,
        .bw-modal[name="add-periode-modal"] .bw-modal-container,
        [data-modal="edit-periode-modal"] .bw-modal-container,
        .bw-modal[name="edit-periode-modal"] .bw-modal-container {
            z-index: 2147483647 !important;
            position: relative !important;
        }

        body.overflow-hidden .top-bar,
        body.overflow-hidden .side-nav,
        body.overflow-hidden .mobile-menu {
            pointer-events: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="block block-rounded block-bordered mt-8">
        <div class="block-content">
            <!-- Add Button -->
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <x-bladewind::button color="green" has_icon="true" icon="plus" onclick="showModal('add-periode-modal')">
                    Tambah Periode Baru
                </x-bladewind::button>
            </div>

            <!-- Periodes Table -->
                    <div class="table-responsive mt-4">
                        <x-bladewind::table compact="true" divider="thin" celled="true">
                            <x-slot name="header">
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Nama Periode</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Jumlah Submit</th>
                                <th>Aksi</th>
                            </x-slot>
                            @foreach($periodes as $index => $periode)
                                <tr>
                                    <td>{{ ($periodes->currentPage() - 1) * $periodes->perPage() + $index + 1 }}</td>
                                    <td>{{ $periode->tahun }}</td>
                                    <td>{{ $periode->nama }}</td>
                                    <td>{{ $periode->deskripsi ?? '-' }}</td>
                                    <td>
                                        @if($periode->status === 'open')
                                            <x-bladewind::tag label="Open" color="green" />
                                        @else
                                            <x-bladewind::tag label="Locked" color="red" />
                                        @endif
                                    </td>
                                    <td>{{ $periode->submissions()->where('status', '!=', 'draft')->count() }}</td>
                                    <td>
                                        <x-bladewind::button color="yellow" has_icon="true" icon="lock" onclick="toggleLock({{ $periode->id }})">
                                            {{ $periode->status === 'open' ? 'Kunci Periode' : 'Buka Periode' }}
                                        </x-bladewind::button>
                                        <x-bladewind::button color="green" has_icon="true" icon="edit" onclick="editPeriode({{ $periode->id }}, '{{ $periode->tahun }}', '{{ $periode->nama }}', '{{ $periode->status }}', '{{ $periode->deskripsi ?? '' }}')">
                                            Edit
                                        </x-bladewind::button>
                                        <x-bladewind::button color="red" has_icon="true" icon="trash" onclick="deletePeriode({{ $periode->id }})">
                                            Delete
                                        </x-bladewind::button>
                                    </td>
                                </tr>
                            @endforeach
                        </x-bladewind::table>
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
        <x-bladewind::modal
            name="add-periode-modal"
            title="Tambah Periode Baru"
            size="medium"
            show_close_icon="true"
            backdrop_can_close="true"
            show_action_buttons="false">
            <form id="addPeriodeForm" class="space-y-4">
                @csrf
                <div>
                    <div>Tahun *</div>
                    <x-bladewind::input type="number" id="add_tahun" name="tahun" required="true" />
                </div>
                <div>
                    <div>Nama Periode *</div>
                    <x-bladewind::input type="text" id="add_nama" name="nama" required="true"
                        placeholder="Contoh: Pelaporan 2024" />
                </div>
                <div>
                    <div>Status *</div>
                    <select id="add_status" name="status" class="form-control" required>
                        <option value="open">Open</option>
                        <option value="locked">Locked</option>
                    </select>
                </div>
                <div>
                    <div>Deskripsi</div>
                    <textarea id="add_deskripsi" name="deskripsi" class="form-control" rows="3"
                        placeholder="Deskripsi periode pelaporan..."></textarea>
                </div>
                <div class="text-right pt-2">
                    <button type="button" class="btn btn-outline-secondary mr-2" onclick="hideModal('add-periode-modal')">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </x-bladewind::modal>

        <!-- Edit Periode Modal -->
        <x-bladewind::modal
            name="edit-periode-modal"
            title="Edit Periode"
            size="medium"
            show_close_icon="true"
            backdrop_can_close="true"
            show_action_buttons="false">
            <form id="editPeriodeForm" class="space-y-4">
                @csrf
                <div>
                    <div class="form-label">Tahun *</div>
                    <x-bladewind::input type="number" id="edit_tahun" name="tahun" required="true" />
                </div>
                <div>
                    <div class="form-label">Nama Periode *</div>
                    <x-bladewind::input type="text" id="edit_nama" name="nama" required="true" />
                </div>
                <div>
                    <div class="form-label">Status *</div>
                    <select id="edit_status" name="status" class="form-control" required>
                        <option value="open">Open</option>
                        <option value="locked">Locked</option>
                    </select>
                </div>
                <div>
                    <div class="form-label">Deskripsi</div>
                    <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <div class="text-right pt-2">
                    <button type="button" class="btn btn-outline-secondary mr-2" onclick="hideModal('edit-periode-modal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </x-bladewind::modal>
    </div>

    @push('js')
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
        <script>
            $(document).ready(function() {
                let editPeriodeId = null;

                $('#addPeriodeForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('lhkan.periode.store') }}",
                        method: 'POST',
                        data: $('#addPeriodeForm').serialize(),
                        success: function(response) {
                            hideModal('add-periode-modal');
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
                    editPeriodeId = id;
                    showModal('edit-periode-modal');
                }

                $('#editPeriodeForm').on('submit', function(e) {
                    e.preventDefault();
                    var id = editPeriodeId;
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
                            hideModal('edit-periode-modal');
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
    @endpush
@endsection