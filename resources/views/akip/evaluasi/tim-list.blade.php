@extends('layout.midone', ['akip' => true])
@section('title', 'Daftar Tim Evaluasi')

@section('content')
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            @if($tim)
                <h3 class="font-medium text-base mr-auto">Daftar Anggota Tim {{ $tim->nama }} ({{ $tim->keterangan }})</h3>
            @else
                <h3 class="font-medium text-base mr-auto">Daftar Anggota Tim</h3>
            @endif
        </div>

        @if(isset($message))
            <div class="text-center py-8">
                <div class="flex flex-col items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">{{ $message }}</p>
                    <p class="text-gray-500 text-sm mt-1">Silakan hubungi administrator untuk mendaftarkan Anda sebagai anggota tim evaluasi.</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table id="tabel-tim" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Email</th>
                            <th class="text-center">Jumlah Instansi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        @endif
    </div>

           <!-- Modal Detail Instansi menggunakan BladewindUI -->
           <x-bladewind::modal
               name="detail-instansi"
               title="Daftar Instansi yang Dievaluasi"
               size="medium"
               show_close_icon="true"
               backdrop_can_close="true"
               blur_size="medium"
               ok_button_text=""
               cancel_button_text=""
               show_ok_button="false"
               show_cancel_button="false">
               <div id="modal-content" class="max-h-64 overflow-y-auto">
                   <!-- Content will be loaded here -->
               </div>
           </x-bladewind::modal>
@endsection

@push('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #1f2937;
            color: white;
            font-weight: 600;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 1rem;
        }

        /* Align length and filter controls */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
            margin: 0 0.5rem;
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

        /* Fix modal z-index untuk muncul di depan DataTable */
        .bw-modal-detail-instansi {
            z-index: 99999 !important;
            position: fixed !important;
        }

        .bw-modal-detail-instansi .bw-modal-backdrop {
            z-index: 99998 !important;
            position: fixed !important;
        }

        .bw-modal-detail-instansi .bw-modal-container {
            z-index: 100000 !important;
            position: relative !important;
        }

        /* Ensure modal appears above DataTable */
        [data-modal="detail-instansi"] {
            z-index: 99999 !important;
            position: fixed !important;
        }

        [data-modal="detail-instansi"] .bw-modal-backdrop {
            z-index: 99998 !important;
            position: fixed !important;
        }

        [data-modal="detail-instansi"] .bw-modal-container {
            z-index: 100000 !important;
            position: relative !important;
        }

        /* Override DataTable z-index */
        .dataTables_wrapper {
            z-index: 1 !important;
            position: relative !important;
        }

        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            z-index: 1 !important;
            position: relative !important;
        }

        /* Force modal to be on top of everything */
        .bw-modal {
            z-index: 99999 !important;
            position: fixed !important;
        }

        .bw-modal .bw-modal-backdrop {
            z-index: 99998 !important;
            position: fixed !important;
        }

        .bw-modal .bw-modal-container {
            z-index: 100000 !important;
            position: relative !important;
        }

        /* Additional aggressive z-index fixes */
        .bw-modal[data-modal="detail-instansi"] {
            z-index: 99999 !important;
            position: fixed !important;
        }

        .bw-modal[data-modal="detail-instansi"] .bw-modal-backdrop {
            z-index: 99998 !important;
            position: fixed !important;
        }

        .bw-modal[data-modal="detail-instansi"] .bw-modal-container {
            z-index: 100000 !important;
            position: relative !important;
        }

        /* Override any DataTable or other high z-index elements */
        .dataTables_wrapper,
        .dataTables_wrapper *,
        .dataTables_filter,
        .dataTables_length,
        .dataTables_info,
        .dataTables_paginate,
        .dataTables_processing {
            z-index: 1 !important;
            position: relative !important;
        }

        /* Nuclear option - force modal to be absolutely on top */
        body:has(.bw-modal[data-modal="detail-instansi"]) .bw-modal[data-modal="detail-instansi"] {
            z-index: 2147483647 !important;
            position: fixed !important;
        }

        body:has(.bw-modal[data-modal="detail-instansi"]) .bw-modal[data-modal="detail-instansi"] .bw-modal-backdrop {
            z-index: 2147483646 !important;
            position: fixed !important;
        }

        body:has(.bw-modal[data-modal="detail-instansi"]) .bw-modal[data-modal="detail-instansi"] .bw-modal-container {
            z-index: 2147483647 !important;
            position: relative !important;
        }

        /* Alternative approach using attribute selector */
        [data-modal="detail-instansi"][style*="display: block"],
        [data-modal="detail-instansi"][style*="display:block"] {
            z-index: 2147483647 !important;
            position: fixed !important;
        }

        /* Modal content styling untuk scroll yang lebih baik */
        #modal-content {
            max-height: 16rem; /* 256px - lebih compact dari max-h-64 */
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        #modal-content::-webkit-scrollbar {
            width: 6px;
        }

        #modal-content::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 3px;
        }

        #modal-content::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        #modal-content::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Ensure modal content items have proper spacing */
        #modal-content .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        /* Modal size adjustment */
        .bw-modal[data-modal="detail-instansi"] .bw-modal-container {
            max-height: 80vh;
            max-width: 600px;
        }

    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#tabel-tim').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ url('akip/evaluasi/tim/getDatas') }}",
                    type: 'GET',
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', error);
                        console.error('Response:', xhr.responseText);

                        // Show error message to user
                        $('#tabel-tim tbody').html(`
                            <tr>
                                <td colspan="4" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <p class="text-red-600 font-medium">Terjadi kesalahan saat memuat data</p>
                                        <p class="text-gray-500 text-sm">Silakan refresh halaman atau hubungi administrator</p>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                },
                columns: [
                    {
                        data: 'nama',
                        name: 'nama',
                        className: 'font-medium'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'text-gray-600'
                    },
                    {
                        data: 'jumlah_instansi',
                        name: 'jumlah_instansi',
                        className: 'text-center font-semibold',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                // Format jumlah instansi dengan badge
                                var badgeClass = data > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
                                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + badgeClass + '">' + data + '</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<button class="btn-detail inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" data-user-id="${row.user_id}" data-nama="${row.nama}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </button>`;
                        }
                    }
                ],
                order: [[1, 'asc']], // Sort by nama anggota
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    processing: "Memproses data...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data anggota tim",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                dom: '<"flex flex-row items-center justify-between mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-row items-center justify-between mt-4"<"flex items-center"i><"flex items-center"p>>',
                initComplete: function() {
                    // Add custom styling after initialization
                    $('.dataTables_wrapper').addClass('mt-4');

                    // Style the search input
                    $('.dataTables_filter input').addClass('form-control');

                    // Style the length select
                    $('.dataTables_length select').addClass('form-control');

                    // Ensure proper alignment
                    $('.dataTables_length').css({
                        'display': 'flex',
                        'align-items': 'center'
                    });

                    $('.dataTables_filter').css({
                        'display': 'flex',
                        'align-items': 'center'
                    });
                }
            });

            // Add loading state
            table.on('processing.dt', function(e, settings, processing) {
                if (processing) {
                    $('#tabel-tim tbody').html(`
                        <tr>
                            <td colspan="4" class="text-center py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                                    <p class="text-gray-600">Memuat data...</p>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            });

            // Handle empty data
            table.on('draw.dt', function() {
                if (table.data().count() === 0) {
                    $('#tabel-tim tbody').html(`
                        <tr>
                        <td colspan="4" class="text-center py-8">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-600">Tidak ada data anggota tim</p>
                            </div>
                        </td>
                        </tr>
                    `);
                }
            });

            // Handle detail button click
            $(document).on('click', '.btn-detail', function() {
                const userId = $(this).data('user-id');
                const nama = $(this).data('nama');

                // Update modal title dengan nama anggota
                $('.bw-modal-detail-instansi .bw-modal-title').text('Daftar Instansi yang Dievaluasi - ' + nama);
                $('#modal-content').html('<div class="text-center py-4">Memuat data...</div>');

                // Ensure modal content has proper height and scroll
                $('#modal-content').css({
                    'max-height': '16rem',
                    'overflow-y': 'auto'
                });

                // Show BladewindUI modal
                showModal('detail-instansi');

                // Force modal to appear on top with maximum z-index
                setTimeout(function() {
                    // Set z-index for modal elements with maximum value
                    $('[data-modal="detail-instansi"]').css({
                        'z-index': '2147483647',
                        'position': 'fixed'
                    });
                    $('[data-modal="detail-instansi"] .bw-modal-backdrop').css({
                        'z-index': '2147483646',
                        'position': 'fixed'
                    });
                    $('[data-modal="detail-instansi"] .bw-modal-container').css({
                        'z-index': '2147483647',
                        'position': 'relative'
                    });

                    // Also target by class name
                    $('.bw-modal-detail-instansi').css({
                        'z-index': '2147483647',
                        'position': 'fixed'
                    });
                    $('.bw-modal-detail-instansi .bw-modal-backdrop').css({
                        'z-index': '2147483646',
                        'position': 'fixed'
                    });
                    $('.bw-modal-detail-instansi .bw-modal-container').css({
                        'z-index': '2147483647',
                        'position': 'relative'
                    });

                    // Force all modals to be on top
                    $('.bw-modal').css('z-index', '2147483647');
                    $('.bw-modal .bw-modal-backdrop').css('z-index', '2147483646');
                    $('.bw-modal .bw-modal-container').css('z-index', '2147483647');
                }, 50);

                // Additional attempt after modal is fully rendered
                setTimeout(function() {
                    $('[data-modal="detail-instansi"]').css('z-index', '2147483647');
                    $('.bw-modal').css('z-index', '2147483647');
                }, 200);

                // Monitor and force modal to stay on top
                const modalInterval = setInterval(function() {
                    if ($('[data-modal="detail-instansi"]').is(':visible')) {
                        $('[data-modal="detail-instansi"]').css('z-index', '2147483647');
                        $('.bw-modal').css('z-index', '2147483647');
                        $('.bw-modal .bw-modal-backdrop').css('z-index', '2147483646');
                        $('.bw-modal .bw-modal-container').css('z-index', '2147483647');
                    } else {
                        clearInterval(modalInterval);
                    }
                }, 100);

                // Fetch instansi data
                $.ajax({
                    url: "{{ url('akip/evaluasi/tim/instansi') }}/" + userId,
                    type: 'GET',
                        success: function(response) {
                            if (response.success && response.data.length > 0) {
                                let html = '<div class="space-y-2">';
                                response.data.forEach(function(item, index) {
                                    html += `
                                        <a href="${item.url}" class="block p-3 border rounded hover:bg-blue-50 hover:border-blue-500 transition">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900">${item.nama_instansi}</h4>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">${item.group}</span>
                                                        <span class="ml-2">${item.tahun} - ${item.periode}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Terakhir update: ${item.last_updated}
                                                    </p>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </a>
                                    `;
                                });
                                html += '</div>';
                                $('#modal-content').html(html);

                                // Ensure scroll is enabled after content is loaded
                                $('#modal-content').css({
                                    'max-height': '16rem',
                                    'overflow-y': 'auto'
                                });
                            } else {
                                $('#modal-content').html('<div class="text-center py-4 text-gray-500">Tidak ada instansi yang dievaluasi</div>');
                            }
                        },
                    error: function() {
                        $('#modal-content').html('<div class="text-center py-4 text-red-500">Terjadi kesalahan saat memuat data</div>');
                    }
                });
            });
        });
    </script>
@endpush
