@extends('layout.rubick')
@section('title', 'Capaian Output - RB Tematik')

@section('content')
    @include('common.status')

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-5">
        <h2 class="text-lg font-medium mr-auto">
            Dashboard <span style="color: #2563eb; font-weight: bold;">RB Tematik</span>
        </h2>
    </div>

    {{-- FILTER TAHUN & NAVIGASI TAB UTAMA --}}
    <div class="intro-y box p-5 shadow-sm border border-slate-200 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('webdashboard.rb-tematik.capaian-output') }}" class="flex items-center gap-3">
                <label for="filter-tahun" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun Kegiatan <span class="text-red-500">*</span></label>
                <div class="w-40">
                    <select id="filter-tahun" name="tahun" class="form-select border-gray-300 rounded-md shadow-sm w-full" onchange="this.form.submit()">
                        @forelse ($years as $year)
                            <option value="{{ $year }}" {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                        @empty
                            <option value="">-</option>
                        @endforelse
                    </select>
                </div>
            </form>
            <div class="flex flex-wrap gap-2 mt-2 md:mt-0 justify-start md:justify-end">
                <a href="{{ url('webdashboard') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600 hover:bg-gray-200">Hasil Evaluasi</a>
                <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600 hover:bg-gray-200">RB General</a>
                <a href="{{ route('webdashboard.rb-tematik.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center" style="background-color: #dbeafe; color: #2563eb;">RB Tematik</a>
            </div>
        </div>

        {{-- TAB SUB-MENU (Capaian Output Aktif) --}}
        <div class="flex gap-4 mt-5 pt-5 border-t border-slate-200/60">
            <a href="{{ route('webdashboard.rb-tematik.rencana-aksi') }}" 
               class="pb-3 text-sm font-medium text-slate-500 hover:text-primary">
                Rencana Aksi
            </a>
            <a href="{{ url('/webdashboard/rb-tematik/capaian-output') }}" 
               class="pb-3 text-sm font-bold border-b-2" 
               style="border-color: #2563eb; color: #2563eb;">
                Capaian Output
            </a>
        </div>
    </div>

    {{-- KOTAK INFORMASI UPDATE DATA --}}
    <div class="intro-y box p-5 mb-5 shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-slate-600 text-sm leading-relaxed">
                Data Capaian Output ini bukan data <i>realtime</i>. Data berikut dikalkulasi terakhir pada tanggal <span class="font-bold text-gray-800">{{ optional($data_pertama)->updated_at ?? '-' }}</span>.<br>
                Untuk kalkulasi dengan data terbaru harap menghubungi admin.
            </div>
            @if(Auth::User()->level == 'admin')
                <a href="{{ route('cogenerate', ['pilihan' => 'all', 'tahun' => $selectedYear]) }}" 
                   class="btn btn-primary shadow-md font-medium px-4 py-2 whitespace-nowrap">
                   <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Generate Semua Instansi
                </a>
            @endif
        </div>
    </div>

    {{-- TABEL CAPAIAN OUTPUT --}}
    <div class="intro-y box p-5 shadow-sm border border-slate-200">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Detail Capaian Output @if($selectedYear) Tahun {{ $selectedYear }} @endif</h3>
        </div>

        {{-- TOMBOL UNDUH CUSTOM (Gaya Rencana Aksi) --}}
        <div class="flex flex-wrap items-center justify-end gap-3 mb-4">
            <button onclick="$('.buttons-excel').click()"
                style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#059669; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Unduh Excel
            </button>
            <button onclick="$('.buttons-pdf').click()"
                style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2v14a2 2 0 002 2z"/></svg>
                Unduh PDF
            </button>
        </div>

        <div class="table-container">
            <table id="capaian-output" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead class="table-dark font-bold text-center">
                    <tr>
                        <th rowspan="2" class="w-5 align-middle">No.</th>
                        <th rowspan="2" class="align-middle">Instansi Pemerintah</th>
                        <th rowspan="2" class="align-middle">Group Instansi</th>
                        <th colspan="5">Capaian Output Pengentasan Kemiskinan</th>
                        <th colspan="5">Capaian Output Realisasi Investasi</th>
                        <th colspan="5">Capaian Output Digitalisasi Pemerintahan</th>
                        <th colspan="5">Capaian Output Penggunaan Produk Dalam Negeri</th>
                        <th colspan="5">Capaian Output Pengendalian Inflasi</th>
                        <th colspan="4">Tingkat Pengisian Output</th>
                        <th colspan="5">Rata-rata Persentasi Capaian Output</th>
                    </tr>
                    <tr>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th><th>Total</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th><th>Total</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th><th>Total</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th><th>Total</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th><th>Total</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th><th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instansis as $instansi)
                    <tr>
                        <td class="text-center text-gray-600">{{ $loop->iteration }}</td>
                        <td class="font-medium text-gray-800 whitespace-nowrap">{{ $instansi['nama'] }}</td>
                        <td class="text-center text-gray-600">{{ $instansi['group'] }}</td>
                        
                        <!-- Tema 1 -->
                        <td class="text-center">{{ round($instansi[1]["capaian_output_tw1"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[1]["capaian_output_tw2"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[1]["capaian_output_tw3"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[1]["capaian_output_tw4"],2) }}%</td>
                        <td class="text-center font-bold">{{ round($instansi[1]["capaian_output_total"],2) }}%</td>
                        
                        <!-- Tema 2 -->
                        <td class="text-center">{{ round($instansi[2]["capaian_output_tw1"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[2]["capaian_output_tw2"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[2]["capaian_output_tw3"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[2]["capaian_output_tw4"],2) }}%</td>
                        <td class="text-center font-bold">{{ round($instansi[2]["capaian_output_total"],2) }}%</td>
                        
                        <!-- Tema 3 -->
                        <td class="text-center">{{ round($instansi[3]["capaian_output_tw1"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[3]["capaian_output_tw2"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[3]["capaian_output_tw3"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[3]["capaian_output_tw4"],2) }}%</td>
                        <td class="text-center font-bold">{{ round($instansi[3]["capaian_output_total"],2) }}%</td>
                        
                        <!-- Tema 4 -->
                        <td class="text-center">{{ round($instansi[4]["capaian_output_tw1"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[4]["capaian_output_tw2"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[4]["capaian_output_tw3"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[4]["capaian_output_tw4"],2) }}%</td>
                        <td class="text-center font-bold">{{ round($instansi[4]["capaian_output_total"],2) }}%</td>
                        
                        <!-- Tema 5 -->
                        <td class="text-center">{{ round($instansi[5]["capaian_output_tw1"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[5]["capaian_output_tw2"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[5]["capaian_output_tw3"],2) }}%</td>
                        <td class="text-center">{{ round($instansi[5]["capaian_output_tw4"],2) }}%</td>
                        <td class="text-center font-bold">{{ round($instansi[5]["capaian_output_total"],2) }}%</td>
                        
                        <!-- Pengisian -->
                        <td class="text-center bg-slate-50">{{ round($instansi[5]["pengisian_tw1"],2) }}%</td>
                        <td class="text-center bg-slate-50">{{ round($instansi[5]["pengisian_tw2"],2) }}%</td>
                        <td class="text-center bg-slate-50">{{ round($instansi[5]["pengisian_tw3"],2) }}%</td>
                        <td class="text-center bg-slate-50">{{ round($instansi[5]["pengisian_tw4"],2) }}%</td>
                        
                        <!-- Rata-Rata Total -->
                        <td class="text-center text-blue-600 font-medium bg-blue-50/50">
                            @php
                            $total_tw1 = ($instansi[1]["capaian_output_tw1"] + $instansi[2]["capaian_output_tw1"] + $instansi[3]["capaian_output_tw1"] + $instansi[4]["capaian_output_tw1"] + $instansi[5]["capaian_output_tw1"])/5 ;
                            echo round($total_tw1,2)."%";
                            @endphp
                        </td>
                        <td class="text-center text-blue-600 font-medium bg-blue-50/50">
                            @php
                            $total_tw2 = ($instansi[1]["capaian_output_tw2"] + $instansi[2]["capaian_output_tw2"] + $instansi[3]["capaian_output_tw2"] + $instansi[4]["capaian_output_tw2"] + $instansi[5]["capaian_output_tw2"])/5 ;
                            echo round($total_tw2,2)."%";
                            @endphp
                        </td>
                        <td class="text-center text-blue-600 font-medium bg-blue-50/50">
                            @php
                            $total_tw3 = ($instansi[1]["capaian_output_tw3"] + $instansi[2]["capaian_output_tw3"] + $instansi[3]["capaian_output_tw3"] + $instansi[4]["capaian_output_tw3"] + $instansi[5]["capaian_output_tw3"])/5 ;
                            echo round($total_tw3,2)."%";
                            @endphp
                        </td>
                        <td class="text-center text-blue-600 font-medium bg-blue-50/50">
                            @php
                            $total_tw4 = ($instansi[1]["capaian_output_tw4"] + $instansi[2]["capaian_output_tw4"] + $instansi[3]["capaian_output_tw4"] + $instansi[4]["capaian_output_tw4"] + $instansi[5]["capaian_output_tw4"])/5 ;
                            echo round($total_tw4,2)."%";
                            @endphp
                        </td>
                        <td class="text-center text-blue-700 font-bold bg-blue-100/50">
                            @php
                            $total_total = ($instansi[1]["capaian_output_total"] + $instansi[2]["capaian_output_total"] + $instansi[3]["capaian_output_total"] + $instansi[4]["capaian_output_total"] + $instansi[5]["capaian_output_total"])/5 ;
                            echo round($total_total,2)."%";
                            @endphp
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('css')
<style>
    /* Sembunyikan container asli dari tombol export DataTables */
    .hidden-buttons { display: none !important; }

    .table th { background-color: #1f2937 !important; color: white !important; font-weight: 600; border: 1px solid #374151; vertical-align: middle; }
    .table td { border: 1px solid #e5e7eb; vertical-align: middle; }
    .table { border-collapse: collapse; border: 1px solid #e5e7eb; width: 100%; }
    
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; display: flex; align-items: center; }
    .dataTables_wrapper .dataTables_length { float: left; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.375rem 0.75rem; }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.375rem 2rem 0.375rem 0.75rem; min-width: 85px; margin: 0 0.5rem; }
    
    .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; text-align: center; clear: both; padding-top: 10px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid #d1d5db !important; border-radius: 0.375rem !important; padding: 0.5rem 0.75rem !important; margin: 0 0.125rem !important; background: white !important; color: #374151 !important; cursor: pointer !important; display: inline-block !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #3b82f6 !important; border-color: #9ca3af !important; color: #374151 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #3b82f6 !important; border-color: #3b82f6 !important; color: white !important; font-weight: 600 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { background: #f9fafb !important; color: #9ca3af !important; cursor: not-allowed !important; border-color: #e5e7eb !important; }
    
    /* Memastikan header DataTable rapi saat menggunakan scrollY */
    .dataTables_scrollHeadInner table { margin-bottom: 0 !important; }
    .dataTables_scrollBody { border-bottom: 1px solid #e5e7eb; }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        var empDataTable = $('#capaian-output').DataTable({
            scrollX: true,
            scrollY: "60vh", // Mengaktifkan scroll vertikal
            scrollCollapse: true, // Memastikan tabel tidak terlalu tinggi jika datanya sedikit
            dom: '<"hidden-buttons"B>lfrtip',
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            buttons: [
                {
                    extend: 'excel',
                    className: 'buttons-excel'
                },
                {
                    extend: 'pdf',
                    exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11] },
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'buttons-pdf'
                }
            ],
            bInfo: true,
            ordering: false,
            columnDefs: [
                { targets: 0, orderable: false, searchable: false }
            ],
            language: {
                search: "Cari Instansi:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                paginate: { first: "Pertama", last: "Terakhir", previous: "Sebelumnya", next: "Selanjutnya" }
            }
        });

        // Hapus style bawaan DataTables pada button agar custom CSS kita berjalan
        $('.dt-action-buttons .dt-button').removeClass('dt-button');

        empDataTable.on('draw.dt', function () {
            var PageInfo = $('#capaian-output').DataTable().page.info();
            empDataTable.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        }).draw();
    });
</script>
@endpush