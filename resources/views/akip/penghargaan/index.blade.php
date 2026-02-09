@extends('layout.midone', ['akip' => true])
@section('title', 'Penghargaan AKIP')

@section('content')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Penghargaan AKIP
        </h2>
    </div>

    <!-- Filter & Actions -->
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center justify-between gap-4">
            <!-- Filter Tahun -->
            <form method="GET" action="{{ route('akip.penghargaan.index') }}" id="filterForm" class="w-full basis-1/2">
                @php
                    $tahunOptions = [['label' => '2025', 'value' => '2025']];
                @endphp

                <x-bladewind::select name="tahun" id="tahun" label="Tahun" placeholder="Pilih Tahun" :data="$tahunOptions"
                    selected_value="{{ $tahun }}" searchable="true" size="medium" class="shadow-sm w-full" />

                <!-- Hidden input to preserve search -->
                <input type="hidden" name="search" value="{{ $search ?? '' }}">
            </form>

            <!-- Tambah Button (TPN only) -->
            @if(in_array(auth()->user()->level, ['tpn', 'admin']))
                <button onclick="openModal('create')"
                    class="flex items-center px-4 py-2 basis-1/3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    Tambah Penghargaan
                </button>
            @endif
        </div>
    </div>

    <!-- Tabel Penghargaan -->
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">
                Daftar Penghargaan - Tahun {{ $tahun }}
            </h3>
        </div>

        <!-- DataTables -->
        <div class="table-container overflow-x-auto">
            <table id="table-penghargaan" class="table table-bordered table-striped table-hover" cellspacing="0"
                width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Instansi</th>
                        <th>Group</th>
                        <th>Tahun</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables will handle rendering -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit Penghargaan -->
    <div class="modal fade" id="penghargaanModal" tabindex="-1" role="dialog" aria-labelledby="penghargaanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penghargaanModalLabel">Tambah Penghargaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="penghargaanForm" enctype="multipart/form-data">
                        <input type="hidden" id="penghargaanId" name="penghargaan_id">
                        <input type="hidden" id="isEdit" name="is_edit" value="0">

                        <div class="space-y-4">
                            <!-- Instansi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Instansi <span
                                        class="text-red-500">*</span></label>
                                <select id="instansi_id" name="instansi_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">Pilih Instansi</option>
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun <span
                                        class="text-red-500">*</span></label>
                                <select id="modal_tahun" name="tahun"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">Pilih Tahun</option>
                                    @for($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- File Sertifikat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">File Sertifikat <span
                                        class="text-red-500">*</span></label>
                                <input type="file" id="file_sertifikat" name="file_sertifikat"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG (Maksimal 5MB)</p>
                                <p id="existingFile" class="mt-1 text-sm text-blue-600 hidden"></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        data-dismiss="modal">Batal</button>
                    <button type="button" onclick="submitForm()"
                        class="px-4 py-2 border border-transparent rounded-md text-white bg-blue-600 hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Penghargaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i data-feather="alert-triangle" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus data penghargaan ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <input type="hidden" id="deletePenghargaanId">
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        data-dismiss="modal">Batal</button>
                    <button type="button" onclick="confirmDelete()"
                        class="px-4 py-2 border border-transparent rounded-md text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Bootstrap Modal z-index - Above ALL elements */
        #penghargaanModal,
        #deleteModal {
            z-index: 99999 !important;
        }

        .modal-backdrop {
            z-index: 99998 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        #penghargaanModal .modal-content,
        #deleteModal .modal-content {
            z-index: 100000 !important;
            max-width: 600px !important;
            width: 100% !important;
            margin: 0 auto !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.5rem !important;
        }

        #penghargaanModal .modal-dialog,
        #deleteModal .modal-dialog {
            max-width: 600px !important;
            margin: 1.75rem auto !important;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 1.25rem;
            background-color: #f8f9fa;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .modal-header h5 {
            font-weight: 600;
            color: #1f2937;
        }

        .modal-body {
            padding: 1.5rem;
            background-color: #fff;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 1.25rem;
            background-color: #f8f9fa;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        /* Select2 styling for instansi dropdown */
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        .select2-dropdown {
            z-index: 1000001 !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
            text-align: center;
        }

        .table th {
            background-color: #1f2937;
            color: white;
            font-weight: 600;
            border: 1px solid #374151;
        }

        .table td {
            border: 1px solid #e5e7eb;
        }

        .table {
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            background: white;
            color: #374151;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            background: #f9fafb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .overflow-x-auto {
            overflow-y: visible !important;
        }

        .intro-y.box {
            overflow: visible !important;
        }

        .table-container {
            overflow: visible !important;
            height: auto !important;
        }
    </style>
@endpush

@push('js')
    <!-- Select2 Library -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.isTpn = {{ in_array(auth()->user()->level, ['tpn', 'admin']) ? 'true' : 'false' }};

        $(document).ready(function () {
            // Initialize Select2 for instansi dropdown
            $('#instansi_id').select2({
                placeholder: 'Pilih Instansi',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#penghargaanModal'),
                theme: 'default'
            });

            // Load instansi data for select
            loadInstansi();

            // Initialize DataTable
            var table = $('#table-penghargaan').DataTable({
                data: @json(isset($penghargaan) ? $penghargaan->toArray() : []),
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'instansi.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'instansi.group',
                        defaultContent: 'N/A',
                        render: function (data, type, row) {
                            if (!data) return 'N/A';
                            var group = data.toLowerCase();
                            var colorClass = 'bg-gray-100 text-gray-800';
                            if (group === 'kl') colorClass = 'bg-blue-100 text-blue-800';
                            else if (group === 'provinsi') colorClass = 'bg-purple-100 text-purple-800';
                            else if (group === 'kabupaten') colorClass = 'bg-green-100 text-green-800';

                            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + colorClass + '">' +
                                data.toUpperCase() + '</span>';
                        }
                    },
                    { data: 'tahun' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (data, type, row) {
                            var actions = '';

                            // PERBAIKAN UTAMA: Replace dilakukan di luar kurung kurawal Blade
                            var downloadUrl = "{{ route('akip.penghargaan.download', ':id') }}".replace(':id', data);

                            actions += '<a href="' + downloadUrl + '" class="flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-green-600 bg-green-100 hover:bg-green-200 mr-1" title="Download">' +
                                '<i data-feather="download" class="w-4 h-4"></i></a>';

                            @if(in_array(auth()->user()->level, ['tpn', 'admin']))
                                actions += '<button onclick="openModal(\'edit\', ' + data + ')" class="flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-600 bg-blue-100 hover:bg-blue-200 mr-1" title="Edit">' +
                                    '<i data-feather="edit-2" class="w-4 h-4"></i></button>' +
                                    '<button onclick="openDeleteModal(' + data + ')" class="flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-600 bg-red-100 hover:bg-red-200" title="Hapus">' +
                                    '<i data-feather="trash-2" class="w-4 h-4"></i></button>';
                            @endif

                                                                                                                                                                                                                                                            return actions;
                        }
                    }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                drawCallback: function () {
                    feather.replace();
                }
            });

            // Handle year filter form submission
            $(document).on('change', 'select[name="tahun"]', function () {
                $('#filterForm').submit();
            });

            // Form submission handler for Bootstrap modal
            function submitForm() {
                var id = $('#penghargaanId').val();
                var isEdit = $('#isEdit').val() === '1';

                // Perbaikan URL dinamis
                var url = isEdit
                    ? "{{ route('akip.penghargaan.update', ':id') }}".replace(':id', id)
                    : "{{ route('akip.penghargaan.store') }}";

                // Gunakan FormData karena ada upload file sertifikat
                var formData = new FormData(document.getElementById('penghargaanForm'));
                if (isEdit) formData.append('_method', 'PUT'); // Laravel butuh ini untuk route PUT

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('#penghargaanModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function (xhr) {
                        var error = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
                        alert(error);
                    }
                });
            }
        });

        // Load instansi data
        function loadInstansi() {
            $.get('/api/instansi', function (data) {
                var select = $('#instansi_id');
                select.empty();
                select.append('<option value="">Pilih Instansi</option>');

                // Handle both array response and object with data property
                var instansiList = Array.isArray(data) ? data : (data.data || []);

                $.each(instansiList, function (key, value) {
                    select.append('<option value="' + value.id + '">' + value.name + '</option>');
                });

                // Trigger Select2 update after loading data
                select.trigger('change');
            }).fail(function () {
                alert('Gagal memuat data instansi');
            });
        }

        // Open modal (create or edit)
        function openModal(mode, id = null) {
            var form = document.getElementById('penghargaanForm');
            var fileInput = document.getElementById('file_sertifikat');
            var existingFile = document.getElementById('existingFile');

            if (mode === 'edit' && id) {
                // Update modal title for edit mode
                $('#penghargaanModalLabel').text('Edit Penghargaan');
                $('#isEdit').val('1');
                $('#penghargaanId').val(id);
                fileInput.removeAttribute('required');
                existingFile.classList.remove('hidden');

                // Load existing data
                $.get("/akip/penghargaan/" + id, function (data) {
                    $('#instansi_id').val(data.instansi_id).trigger('change');
                    $('#modal_tahun').val(data.tahun);
                }).fail(function () {
                    alert('Gagal memuat data penghargaan');
                });
            } else {
                // Update modal title for create mode
                $('#penghargaanModalLabel').text('Tambah Penghargaan');
                $('#isEdit').val('0');
                $('#penghargaanId').val('');
                form.reset();
                fileInput.setAttribute('required', 'required');
                existingFile.classList.add('hidden');
            }

            // Show Bootstrap modal
            $('#penghargaanModal').modal('show');
            feather.replace();
        }

        // Close modal - wrapper for Bootstrap's modal
        function closeModal() {
            $('#penghargaanModal').modal('hide');
        }

        // Check for duplicate instansi-year combination
        function checkTahun() {
            var instansiId = $('#instansi_id').val();
            var tahun = $('#modal_tahun').val();
            var isEdit = $('#isEdit').val() === '1';

            if (instansiId && tahun) {
                $.post("{{ route('akip.penghargaan.check') }}", {
                    instansi_id: instansiId,
                    tahun: tahun,
                    _token: '{{ csrf_token() }}'
                }, function (response) {
                    if (response.exists && !isEdit) {
                        alert('Instansi ini sudah memiliki sertifikat untuk tahun ' + tahun);
                        $('#modal_tahun').val('');
                    }
                });
            }
        }

        // Open delete modal
        function openDeleteModal(id) {
            $('#deletePenghargaanId').val(id);
            $('#deleteModal').modal('show');
            feather.replace();
        }

        // Close delete modal - wrapper for Bootstrap's modal
        function closeDeleteModal() {
            $('#deleteModal').modal('hide');
        }

        // Confirm delete
        function confirmDelete() {
            var id = $('#deletePenghargaanId').val();
            var url = "{{ route('akip.penghargaan.destroy', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    var error = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
                    alert(error);
                }
            });
        }
    </script>
@endpush