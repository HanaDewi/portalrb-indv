@extends('layout.midone', ['akip' => true])
@section('title', 'Hasil Evaluasi SAKIP')

@section('content')
    <!-- END: Top Bar -->
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Hasil Evaluasi SAKIP {{ $tim ? $tim->nama . ' (' . $tim->keterangan . ')' : '' }}
        </h2>
    </div>

    <!-- Filter Tahun -->
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Filter Data</h3>
        </div>
        <form method="GET" action="{{ url('akip/evaluasi/sakip') }}" id="filterForm" class="flex items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                @php
                    $tahunOptions = [];
                    for ($year = date('Y'); $year >= 2020; $year--) {
                        $tahunOptions[] = ['label' => $year, 'value' => $year];
                    }
                @endphp

                <x-bladewind::select
                    name="tahun"
                    id="tahun"
                    label="Tahun"
                    placeholder="Pilih Tahun"
                    :data="$tahunOptions"
                    selected_value="{{ $tahun }}"
                    searchable="true"
                    size="medium"
                    class="shadow-sm"
                />
            </div>
            {{-- <div class="flex items-end">
                <button
                    type="submit"
                    id="filterBtn"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    Filter
                </button>
            </div> --}}
        </form>
    </div>

    <!-- Tabel Kementerian/Lembaga -->
    @if($anggota_kl->count() > 0)
        <div class="intro-y box p-5 mt-5">
            <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
                <h3 class="font-medium text-base mr-auto">
                    Kementerian/Lembaga ({{ $anggota_kl->count() }} instansi) - Tahun {{ $tahun }}
                </h3>
            </div>

            <!-- Search Box untuk K/L -->
            <div class="mb-4">
                <input
                    type="text"
                    id="searchKl"
                    placeholder="Cari nama instansi K/L..."
                    class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <x-bladewind::table
                striped="true"
                has_border="true"
                has_shadow="true"
                compact="true"
                divider="thin"
                has_hover="true"
                id="tableKl"
            >
                <x-slot name="header">
                    <th>No</th>
                    <th>Nama Instansi</th>
                    <th>Group</th>
                    <th>Status Evaluasi</th>
                    <th>Aksi</th>
                </x-slot>

                <tbody id="tbodyKl">
                    @foreach($klPaginated as $index => $anggota)
                        <tr class="kl-row" data-nama="{{ strtolower(strip_tags($anggota->instansi->nama_instansi)) }}">
                            <td>{{ ($currentPageKl - 1) * 10 + $index + 1 }}</td>
                            <td>{!! $anggota->instansi->nama_instansi !!}</td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($anggota->instansi->group) }}
                                </span>
                            </td>
                            <td>
                                @if(isset($anggota->status_evaluasi))
                                    @if($anggota->status_evaluasi == 'sudah')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sudah Dinilai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('akip/evaluasi/sakip/' . $anggota->instansi_id) }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-feather="eye" class="w-4 h-4 mr-1"></i>
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-bladewind::table>

            <!-- Pagination untuk K/L -->
            @if($klTotalPages > 1)
                <div class="flex items-center justify-between mt-4">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span id="klShowingStart">{{ ($currentPageKl - 1) * 10 + 1 }}</span> sampai <span id="klShowingEnd">{{ min($currentPageKl * 10, $anggota_kl->count()) }}</span> dari <span id="klTotal">{{ $anggota_kl->count() }}</span> hasil
                    </div>
                    <div class="flex space-x-2">
                        @if($currentPageKl > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page_kl' => $currentPageKl - 1, 'tahun' => $tahun]) }}"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @endif

                        @for($i = max(1, $currentPageKl - 2); $i <= min($klTotalPages, $currentPageKl + 2); $i++)
                            <a href="{{ request()->fullUrlWithQuery(['page_kl' => $i, 'tahun' => $tahun]) }}"
                                class="px-3 py-2 text-sm font-medium {{ $i == $currentPageKl ? 'text-white bg-blue-600 border-blue-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' }} border rounded-md">
                                {{ $i }}
                            </a>
                        @endfor

                        @if($currentPageKl < $klTotalPages)
                            <a href="{{ request()->fullUrlWithQuery(['page_kl' => $currentPageKl + 1, 'tahun' => $tahun]) }}"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Tabel Pemerintah Daerah -->
    @if($anggota_pemda->count() > 0)
        <div class="intro-y box p-5 mt-5">
            <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
                <h3 class="font-medium text-base mr-auto">
                    Pemerintah Daerah ({{ $anggota_pemda->count() }} instansi) - Tahun {{ $tahun }}
                </h3>
            </div>

            <!-- Search Box untuk Pemda -->
            <div class="mb-4">
                <input
                    type="text"
                    id="searchPemda"
                    placeholder="Cari nama instansi Pemda..."
                    class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <x-bladewind::table
                striped="true"
                has_border="true"
                has_shadow="true"
                compact="true"
                divider="thin"
                has_hover="true"
                id="tablePemda"
            >
                <x-slot name="header">
                    <th>No</th>
                    <th>Nama Instansi</th>
                    <th>Group</th>
                    <th>TW 1</th>
                    <th>TW 2</th>
                    <th>TW 3</th>
                    <th>TW 4</th>
                    <th>Aksi</th>
                </x-slot>

                <tbody id="tbodyPemda">
                    @foreach($pemdaPaginated as $index => $anggota)
                        <tr class="pemda-row" data-nama="{{ strtolower(strip_tags($anggota->instansi->nama_instansi)) }}">
                            <td>{{ ($currentPagePemda - 1) * 10 + $index + 1 }}</td>
                            <td>{!! $anggota->instansi->nama_instansi !!}</td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($anggota->instansi->group) }}
                                </span>
                            </td>
                            <td>
                                @if(isset($anggota->status_tw1))
                                    @if($anggota->status_tw1 == 'sudah')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Belum
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(isset($anggota->status_tw2))
                                    @if($anggota->status_tw2 == 'sudah')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Belum
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(isset($anggota->status_tw3))
                                    @if($anggota->status_tw3 == 'sudah')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Belum
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(isset($anggota->status_tw4))
                                    @if($anggota->status_tw4 == 'sudah')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Belum
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('akip/evaluasi/sakip/' . $anggota->instansi_id) }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-feather="eye" class="w-4 h-4 mr-1"></i>
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-bladewind::table>

            <!-- Pagination untuk Pemda -->
            @if($pemdaTotalPages > 1)
                <div class="flex items-center justify-between mt-4">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span id="pemdaShowingStart">{{ ($currentPagePemda - 1) * 10 + 1 }}</span> sampai <span id="pemdaShowingEnd">{{ min($currentPagePemda * 10, $anggota_pemda->count()) }}</span> dari <span id="pemdaTotal">{{ $anggota_pemda->count() }}</span> hasil
                    </div>
                    <div class="flex space-x-2">
                        @if($currentPagePemda > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page_pemda' => $currentPagePemda - 1, 'tahun' => $tahun]) }}"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @endif

                        @for($i = max(1, $currentPagePemda - 2); $i <= min($pemdaTotalPages, $currentPagePemda + 2); $i++)
                            <a href="{{ request()->fullUrlWithQuery(['page_pemda' => $i, 'tahun' => $tahun]) }}"
                                class="px-3 py-2 text-sm font-medium {{ $i == $currentPagePemda ? 'text-white bg-blue-600 border-blue-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' }} border rounded-md">
                                {{ $i }}
                            </a>
                        @endfor

                        @if($currentPagePemda < $pemdaTotalPages)
                            <a href="{{ request()->fullUrlWithQuery(['page_pemda' => $currentPagePemda + 1, 'tahun' => $tahun]) }}"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Pesan jika tidak ada data -->
    @if($anggota_kl->count() == 0 && $anggota_pemda->count() == 0)
        <div class="intro-y box p-5 mt-5">
            <div class="text-center">
                <p class="text-gray-600">Tidak ada data instansi untuk tahun {{ $tahun }}.</p>
            </div>
        </div>
    @endif
@endsection

@push('css')
    <style>
        .badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 0.25rem;
        }

        .badge-success {
            background-color: #10b981;
            color: white;
        }

        .badge-warning {
            background-color: #f59e0b;
            color: white;
        }

        .badge-info {
            background-color: #3b82f6;
            color: white;
        }

        .badge-secondary {
            background-color: #6b7280;
            color: white;
        }

        .search-highlight {
            background-color: #fef3c7;
            padding: 1px 2px;
            border-radius: 2px;
        }
    </style>
@endpush

@push('js')
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Re-initialize feather icons
            feather.replace();

            // Function to get tahun value safely
            function getTahunValue() {
                // Try multiple selectors to find the tahun input
                const selectors = [
                    'input[name="tahun"]',
                    '#tahun',
                    'select[name="tahun"]',
                    '.bw-select input[name="tahun"]'
                ];

                for (let selector of selectors) {
                    const element = document.querySelector(selector);
                    if (element && element.value) {
                        return element.value;
                    }
                }

                return null;
            }

            // Handle form submission
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const tahun = getTahunValue();
                    if (!tahun) {
                        alert('Silakan pilih tahun terlebih dahulu.');
                        return;
                    }

                    // Submit form
                    this.submit();
                });
            }

            // Handle button click
            const filterBtn = document.getElementById('filterBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const tahun = getTahunValue();
                    if (!tahun) {
                        alert('Silakan pilih tahun terlebih dahulu.');
                        return;
                    }

                    // Submit form
                    const form = document.getElementById('filterForm');
                    if (form) {
                        form.submit();
                    }
                });
            }

            // Auto-submit when tahun changes (with delay to ensure BladewindUI is ready)
            setTimeout(function() {
                const tahunInputs = document.querySelectorAll('input[name="tahun"], select[name="tahun"]');
                tahunInputs.forEach(function(input) {
                    input.addEventListener('change', function() {
                        const form = document.getElementById('filterForm');
                        if (form && this.value) {
                            form.submit();
                        }
                    });
                });
            }, 1000); // Wait 1 second for BladewindUI to initialize

            // Search functionality for K/L table
            const searchKl = document.getElementById('searchKl');
            if (searchKl) {
                searchKl.addEventListener('input', function() {
                    filterTable('kl', this.value);
                });
            }

            // Search functionality for Pemda table
            const searchPemda = document.getElementById('searchPemda');
            if (searchPemda) {
                searchPemda.addEventListener('input', function() {
                    filterTable('pemda', this.value);
                });
            }

            // Function to filter table rows
            function filterTable(tableType, searchTerm) {
                const rows = document.querySelectorAll(`.${tableType}-row`);
                const tbody = document.getElementById(`tbody${tableType.charAt(0).toUpperCase() + tableType.slice(1)}`);
                let visibleCount = 0;

                rows.forEach(function(row, index) {
                    const namaInstansi = row.getAttribute('data-nama');
                    const searchLower = searchTerm.toLowerCase();

                    if (namaInstansi.includes(searchLower)) {
                        row.style.display = '';
                        visibleCount++;

                        // Update row number
                        const noCell = row.querySelector('td:first-child');
                        if (noCell) {
                            noCell.textContent = visibleCount;
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update counter
                updateCounter(tableType, visibleCount, rows.length);
            }

            // Function to update counter
            function updateCounter(tableType, visibleCount, totalCount) {
                const showingStart = document.getElementById(`${tableType}ShowingStart`);
                const showingEnd = document.getElementById(`${tableType}ShowingEnd`);
                const total = document.getElementById(`${tableType}Total`);

                if (showingStart && showingEnd && total) {
                    if (visibleCount === 0) {
                        showingStart.textContent = '0';
                        showingEnd.textContent = '0';
                    } else {
                        showingStart.textContent = '1';
                        showingEnd.textContent = visibleCount;
                    }
                    total.textContent = totalCount;
                }
            }

            // Initialize counters
            updateCounter('kl', document.querySelectorAll('.kl-row').length, document.querySelectorAll('.kl-row').length);
            updateCounter('pemda', document.querySelectorAll('.pemda-row').length, document.querySelectorAll('.pemda-row').length);
        });
    </script>
@endpush
