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

        /* Dark theme untuk tabel periode */
        #table-lhkan-periode {
            background-color: #e9ecef;
            color: #212529;
            border-color: #495057;
        }
        #table-lhkan-periode thead {
            background-color: #212529;
            color: #fff;
            border-color: #495057;
        }
        #table-lhkan-periode thead th {
            padding: 0.75rem 1rem;
            border-color: #495057;
            font-weight: 500;
        }
        #table-lhkan-periode tbody td {
            background-color: #e9ecef;
            color: #212529;
            border-color: #495057;
            padding: 0.75rem 1rem;
        }
        #table-lhkan-periode tbody tr:hover td {
            background-color: #e9ecef;
        }
    </style>
@endpush

@section('content')
    <div class="block block-rounded block-bordered mt-8">
        <div class="block-content">
            @if(session('success'))
                <div class="mb-4">
                    <x-bladewind::alert type="success">{{ session('success') }}</x-bladewind::alert>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4">
                    <x-bladewind::alert type="error">{{ session('error') }}</x-bladewind::alert>
                </div>
            @endif
            <!-- Add Button -->
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <x-bladewind::button color="green" has_icon="true" icon="plus" onclick="showModal('add-periode-modal')">
                    Tambah Periode Baru
                </x-bladewind::button>
            </div>

            <!-- Periodes Table -->
                    <div class="table-responsive mt-4">
                        <table id="table-lhkan-periode" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="color: #fff;">No</th>
                                    <th style="color: #fff;">Tahun</th>
                                    <th style="color: #fff;">Nama Periode</th>
                                    <th style="color: #fff;">Deskripsi</th>
                                    <th style="color: #fff;">Status</th>
                                    <th style="color: #fff;">Jumlah Submit</th>
                                    <th style="color: #fff;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periodes as $index => $periode)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $periode->tahun }}</td>
                                        <td>{{ $periode->nama }}</td>
                                        <td>{{ $periode->deskripsi ?? '-' }}</td>
                                        <td>
                                            @if($periode->status === 'open')
                                                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #198754; color: #fff; border-radius: 0.25rem;">Open</span>
                                            @else
                                                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 500; background-color: #dc3545; color: #fff; border-radius: 0.25rem;">Locked</span>
                                            @endif
                                        </td>
                                        <td>{{ $periode->submissions()->where('status', '!=', 'draft')->count() }}</td>
                                        <td class="flex gap-2 items-center">
                                            @if($periode->status === 'open')
                                                <button type="button" class="flex gap-2 items-center btn btn-warning btn-sm" onclick="toggleLock({{ $periode->id }})">
                                                    <i class="fa fa-lock"></i> Kunci Periode
                                                </button>
                                            @else
                                                <button type="button" class="flex gap-2 items-center btn btn-info btn-sm" onclick="toggleLock({{ $periode->id }})">
                                                    <i class="fa fa-unlock"></i> Buka Periode
                                                </button>
                                            @endif
                                            <button type="button" class="flex gap-2 items-center btn btn-success btn-sm" onclick="editPeriode({{ $periode->id }}, '{{ addslashes($periode->tahun) }}', '{{ addslashes($periode->nama) }}', '{{ $periode->status }}', {{ json_encode($periode->deskripsi ?? '') }})">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                let editPeriodeId = null;

                if ($.fn.DataTable && $('#table-lhkan-periode').length) {
                    $('#table-lhkan-periode').DataTable({
                        language: { url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json' },
                        pageLength: 10,
                        order: [[1, 'desc']],
                        columnDefs: [{ orderable: false, targets: -1 }]
                    });
                }

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

                // Toggle lock periode (Kunci/Buka periode)
                window.toggleLock = function(id) {
                    $.post("{{ route('lhkan.periode.toggle-lock', ':id') }}".replace(':id', id), {
                        _token: "{{ csrf_token() }}"
                    })
                    .done(function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 2000
                            });
                            setTimeout(function() { location.reload(); }, 2000);
                        }
                    })
                    .fail(function(xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                    });
                };
            });
        </script>
    @endpush
@endsection