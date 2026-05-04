@extends('layout.rubick')
@section('title', 'Dashboard RB General')

@section('content')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-5">
        <h2 class="text-lg font-medium mr-auto">
            Dashboard <span style="color: #2563eb; font-weight: bold;">RB General</span>
        </h2>
    </div>

    {{-- Filter Tahun --}}
    <div class="intro-y box p-5 shadow-sm border border-slate-200 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">
                <label for="tahun" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun <span class="text-red-500">*</span></label>
                <div class="w-40">
                    <select name="tahun" id="tahun" class="form-select border-gray-300 rounded-md shadow-sm w-full" onchange="this.form.submit()">
                        @foreach ([2024, 2025] as $th)
                            <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="flex flex-wrap gap-2 mt-2 md:mt-0 justify-start md:justify-end">
                <a href="{{ url('webdashboard') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600">Hasil Evaluasi</a>
                <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center" style="background-color: #dbeafe; color: #2563eb;">RB General</a>
            </div>
        </div>

        <div class="flex gap-4 mt-5 pt-5 border-t border-slate-200/60">
            <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" 
               class="pb-3 text-sm font-bold border-b-2" 
               style="border-color: #2563eb; color: #2563eb;">
                Rencana Aksi
            </a>
            <a href="{{ route('webdashboard.rb-general.capaian-output') }}" 
               class="pb-3 text-sm font-medium text-slate-500 hover:text-primary">
                Capaian Output
            </a>
        </div>
    </div>

    {{-- Pie Charts (Sekarang ada 5) --}}
    <div class="grid grid-cols-12 gap-6 mt-5">
        @php
            $pieData = [
                ['id' => 'pie-chart-kemen', 'judul' => 'Kementerian', 'yes' => $yes_kementerian, 'no' => $no_kementerian, 'group' => 'kementerian'],
                ['id' => 'pie-chart-lemb',  'judul' => 'Lembaga',     'yes' => $yes_lembaga,     'no' => $no_lembaga,     'group' => 'lembaga'],
                ['id' => 'pie-chart-prov',  'judul' => 'Provinsi',    'yes' => $yes_prov,        'no' => $no_prov,        'group' => 'provinsi'],
                ['id' => 'pie-chart-kab',   'judul' => 'Kabupaten',   'yes' => $yes_kab,         'no' => $no_kab,         'group' => 'kabupaten'],
                ['id' => 'pie-chart-kota',  'judul' => 'Kota',        'yes' => $yes_kota,        'no' => $no_kota,        'group' => 'kota'],
            ];
        @endphp

        @foreach($pieData as $pie)
        <div class="col-span-12 md:col-span-6 lg:col-span-4 intro-y">
            <div class="box p-5 shadow-sm border border-slate-200 h-full">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                    <h3 class="font-semibold text-sm text-gray-700">{{ $pie['judul'] }}</h3>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                        {{ $pie['yes'] + $pie['no'] }} Instansi
                    </span>
                </div>
                
                {{-- Tooltip untuk chart --}}
                <div style="position: relative; height: 180px; cursor: pointer;"
                     title="Klik diagram untuk melihat semua data status"
                     onmouseover="this.style.opacity='0.85'"
                     onmouseout="this.style.opacity='1'">
                    <canvas id="{{ $pie['id'] }}"></canvas>
                </div>
                
                <div class="flex justify-center gap-6 mt-3">
                    <div class="flex items-center gap-2 cursor-pointer"
                         onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'"
                         onclick="showPieModal('{{ $pie['group'] }}', '{{ $pie['judul'] }}', 'sudah')">
                        <div style="width:12px; height:12px; border-radius:50%; background:#2563eb;"></div>
                        <span class="text-xs text-gray-600 font-medium">{{ $pie['yes'] }} Sudah</span>
                    </div>
                    <div class="flex items-center gap-2 cursor-pointer"
                         onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'"
                         onclick="showPieModal('{{ $pie['group'] }}', '{{ $pie['judul'] }}', 'belum')">
                        <div style="width:12px; height:12px; border-radius:50%; background:#ef4444;"></div>
                        <span class="text-xs text-gray-600 font-medium">{{ $pie['no'] }} Belum</span>
                    </div>
                </div>
                
                {{-- Tombol Lihat Semua --}}
                <div class="flex justify-center mt-3 pt-3 border-t border-slate-100">
                    <button onclick="showPieModal('{{ $pie['group'] }}', '{{ $pie['judul'] }}', 'semua')" 
                            class="text-xs font-semibold" style="color: #2563eb; background: none; border: none; cursor: pointer;"
                            onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        Lihat Semua Instansi ({{ $pie['yes'] + $pie['no'] }})
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabel Utama --}}
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Hasil Semua Instansi Pemerintah - RB General</h3>
        </div>

        {{-- Toolbar Export --}}
        <div class="flex flex-wrap items-center justify-end gap-3 mb-4">
            <button onclick="exportTableToExcel()"
                style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#059669; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Unduh Excel
            </button>
            <button onclick="exportTableToPDF()"
                style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2v14a2 2 0 002 2z"/></svg>
                Unduh PDF
            </button>
        </div>

        {{-- Filter Grup (Sekarang ada 5 opsi) --}}
        <div class="hidden" id="filter-wrapper-template">
            <div id="custom-group-filter" class="flex items-center gap-2 ml-6 pl-4 border-l border-gray-300">
                <label class="text-sm font-medium text-gray-600 whitespace-nowrap mb-0">Grup Instansi:</label>
                <select id="filterGroup" onchange="applyGroupFilter()"
                    style="min-width: 150px;" 
                    class="border border-gray-300 rounded-md text-sm px-3 py-1.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Semua Grup</option>
                    <option value="kementerian">Kementerian</option>
                    <option value="lembaga">Lembaga</option>
                    <option value="provinsi">Provinsi</option>
                    <option value="kabupaten">Kabupaten</option>
                    <option value="kota">Kota</option>
                </select>
            </div>
        </div>

        <div class="table-container overflow-x-auto">
            <table id="perencanaan" class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="w-10 text-center">No</th>
                        <th>Instansi Pemerintah</th>
                        <th class="text-center">Grup Instansi</th>
                        <th class="text-center">Baseline</th>
                        <th class="text-center">Tahun Target</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Rencana Aksi</th>
                        <th class="text-center">Semua</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $index => $data)
                    <tr>
                        <td class="text-center text-gray-600">{{ $index + 1 }}</td>
                        <td class="font-medium text-gray-800">
                            <a href="{{ url('/rencana_aksi/rb-general/rekap_data?instansi_id%5B%5D=' . $data['instansi']->id) }}" style="color:#2563eb; font-weight:600; text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                {{ $data['instansi']->name }}
                            </a>
                        </td>
                        <td class="text-center text-gray-600">{{ $data['group'] }}</td>
                        <td class="text-center">
                            @if($data['baseline'])
                                <span style="color:#10b981; font-size:20px; font-weight:700;">✔</span>
                            @else
                                <span style="color:#9ca3af;">---</span>
                            @endif
                        </td>
                        <td class="text-center text-gray-600">{{ $data['tahun_target'] }}</td>
                        <td class="text-center">
                            @if($data['target'])
                                <span style="color:#10b981; font-size:20px; font-weight:700;">✔</span>
                            @else
                                <span style="color:#9ca3af;">---</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['rencana_aksi'])
                                <span style="color:#10b981; font-size:20px; font-weight:700;">✔</span>
                            @else
                                <span style="color:#9ca3af;">---</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['semua'])
                                <span style="color:#10b981; font-size:20px; font-weight:700;">✔</span>
                            @else
                                <span style="color:#9ca3af;">---</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tooltip --}}
    <div id="chart-tooltip" style="display:none; position:fixed; z-index:9999; background:#1f2937; color:white; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; pointer-events:none; white-space:nowrap; box-shadow:0 4px 12px rgba(0,0,0,0.3);"></div>

    {{-- Modal Popup --}}
    <div id="barModal" style="display:none; position:fixed; inset:0; z-index:99998; background:rgba(0,0,0,0.5);" onclick="closeBarModal(event)">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:white; border-radius:12px; width:90%; max-width:800px; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
            
            <div id="barModalHeader" style="padding:16px 20px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; border-radius:12px 12px 0 0;">
                <div style="flex: 1;">
                    <div id="barModalTitle" style="font-size:15px; font-weight:700; color:#1f2937;"></div>
                    <div id="barModalSubtitle" style="font-size:12px; color:#6b7280; margin-top:2px;"></div>
                </div>
                
                <div class="flex items-center gap-2">
                    <button onclick="exportModalToExcel()"
                        style="display:flex; align-items:center; gap:6px; padding:6px 12px; background:#059669; color:white; border:none; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer;"
                        onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        Unduh Excel
                    </button>
                    <button onclick="exportModalToPDF()"
                        style="display:flex; align-items:center; gap:6px; padding:6px 12px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer;"
                        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Unduh PDF
                    </button>
                    <button onclick="document.getElementById('barModal').style.display='none'" style="background:#f3f4f6; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer; font-size:16px; color:#6b7280; display:flex; align-items:center; justify-content:center; margin-left:4px;">&times;</button>
                </div>
            </div>
            
            <div id="barModalContent" style="padding:0 20px 16px 20px; overflow-y:auto; flex:1;">
                <!-- Konten JS -->
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; display: flex; align-items: center; }
    .dataTables_wrapper .dataTables_length { float: left; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; text-align: center; clear: both; padding-top: 10px; }
    .table th { background-color: #1f2937; color: white; font-weight: 600; border: 1px solid #374151; }
    .table td { border: 1px solid #e5e7eb; vertical-align: middle; }
    .table { border-collapse: collapse; border: 1px solid #e5e7eb; width: 100%; }
    
    .dataTables_wrapper .dataTables_filter input { 
        border: 1px solid #d1d5db; 
        border-radius: 0.375rem; 
        padding: 0.375rem 0.75rem; 
    }
    .dataTables_wrapper .dataTables_length select { 
        border: 1px solid #d1d5db; 
        border-radius: 0.375rem; 
        padding: 0.375rem 2rem 0.375rem 0.75rem; 
        min-width: 85px; 
        margin: 0 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; border-radius: 0.375rem !important;
        padding: 0.5rem 0.75rem !important; margin: 0 0.125rem !important;
        background: white !important; color: #374151 !important;
        cursor: pointer !important; display: inline-block !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #3b82f6 !important; border-color: #9ca3af !important; color: #374151 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #3b82f6 !important; border-color: #3b82f6 !important;
        color: white !important; font-weight: 600 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f9fafb !important; color: #9ca3af !important;
        cursor: not-allowed !important; border-color: #e5e7eb !important;
    }
    .overflow-x-auto { overflow-y: visible !important; }
</style>
@endpush

@push('js')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var rekapData = @json($rekapJson);
        var dtTable = null;

        var currentModalDataSudah = [];
        var currentModalDataBelum = [];
        var currentModalFilter = '';
        var currentModalFilename = '';

        // PIE CHART
        function buatPieChart(canvasId, yes, no, groupKey, judul) {
            var ctx = document.getElementById(canvasId);
            if (!ctx) return;
            new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: [yes + ' Sudah', no + ' Belum'],
                    datasets: [{
                        data: [yes, no],
                        backgroundColor: ['#2563eb', '#ef4444'],
                        hoverBackgroundColor: ['#1d4ed8', '#dc2626'],
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    onClick: function(event, elements) {
                        if (elements && elements.length > 0) {
                            var index = elements[0].index;
                            var filterType = index === 0 ? 'sudah' : 'belum';
                            showPieModal(groupKey, judul, filterType);
                        } else {
                            showPieModal(groupKey, judul, 'semua'); 
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return ' ' + ctx.label + ' instansi'; }
                            }
                        }
                    }
                }
            });
        }
        
        // Panggil untuk 5 grafik secara dinamis dari PHP
        @foreach($pieData as $pie)
            buatPieChart('{{ $pie['id'] }}', {{ $pie['yes'] }}, {{ $pie['no'] }}, '{{ $pie['group'] }}', '{{ $pie['judul'] }}');
        @endforeach

        // =============================================
        // FUNGSI HELPER: Cetak HTML Tabel Modal Dinamis
        // =============================================
        function generateTableHTML(title, data, color) {
            if (data.length === 0) return '';
            
            var html = '<h4 style="margin: 16px 0 8px 0; font-size: 14px; font-weight: bold; color: '+color+'; border-bottom: 2px solid '+color+'; display:inline-block; padding-bottom:4px;">' + title + ' (' + data.length + ' Instansi)</h4>';
            
            html += '<table class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%" style="margin-bottom: 24px; font-size: 13px;">';
            
            html += '<thead><tr>';
            html += '<th class="w-10 text-center" style="position:sticky; top:0; z-index:20;">No</th>';
            html += '<th style="position:sticky; top:0; z-index:20;">Nama Instansi</th>';
            html += '<th class="text-center" style="position:sticky; top:0; z-index:20;">Grup</th>';
            html += '<th class="text-center" style="position:sticky; top:0; z-index:20;">Baseline</th>';
            html += '<th class="text-center" style="position:sticky; top:0; z-index:20;">Target</th>';
            html += '<th class="text-center" style="position:sticky; top:0; z-index:20;">Rencana Aksi</th>';
            html += '<th class="text-center" style="position:sticky; top:0; z-index:20;">Semua</th>';
            html += '</tr></thead><tbody>';
            
            var check = '<span style="color:#10b981; font-size:20px; font-weight:700;">✔</span>';
            var cross = '<span style="color:#9ca3af;">---</span>';

            data.forEach(function(r, i) {
                html += '<tr>';
                html += '<td class="text-center text-gray-600">' + (i+1) + '</td>';
                html += '<td class="font-medium text-gray-800"><a href="' + (r.detail_url || '#') + '" style="color:#2563eb; font-weight:600; text-decoration:none;" onmouseover="this.style.textDecoration=\'underline\'" onmouseout="this.style.textDecoration=\'none\'">' + r.name + '</a></td>';
                html += '<td class="text-center text-gray-600">' + (r.group||'-') + '</td>';
                html += '<td class="text-center">' + (r.baseline ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.target ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.rencana_aksi ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.semua ? check : cross) + '</td>';
                html += '</tr>';
            });
            
            html += '</tbody></table>';
            return html;
        }
      
        // MODAL PIE CHART KLIK
        function showPieModal(group, judul, filter) {
            var fSudah = [];
            var fBelum = [];

            rekapData.forEach(function(r) {
                // Sekarang r.group_key sudah diset ke kementerian, lembaga, provinsi, kabupaten, atau kota
                var rGroup = (r.group_key || '').toString().toLowerCase(); 
                
                // Cukup cek kesamaan secara langsung
                if (group !== rGroup) return; 
                
                var isLengkap = (r.semua == true || r.semua == 1 || r.semua === 'true');
                if (isLengkap) fSudah.push(r);
                else fBelum.push(r);
            });

            currentModalDataSudah = fSudah;
            currentModalDataBelum = fBelum;
            currentModalFilter    = filter; 

            var color    = filter === 'sudah' ? '#10b981' : filter === 'belum' ? '#ef4444' : '#2563eb';
            var filterLabel = filter === 'sudah' ? 'Sudah Lengkap' : filter === 'belum' ? 'Belum Lengkap' : 'Semua Status';
            var countTotal = filter === 'sudah' ? fSudah.length : filter === 'belum' ? fBelum.length : (fSudah.length + fBelum.length);

            currentModalFilename = 'Data_RB_General_' + filterLabel.replace(/\s+/g,'_') + '_' + judul.replace(/\s+/g,'_');

            document.getElementById('barModalHeader').style.backgroundColor = color + '18';
            document.getElementById('barModalTitle').innerHTML =
                '<span style="padding:2px 10px; border-radius:20px; font-size:13px; background:'+color+'22; color:'+color+';">'+filterLabel+'</span>' +
                ' &nbsp;&mdash;&nbsp; ' + judul;
            document.getElementById('barModalSubtitle').textContent = countTotal + ' instansi ditemukan';

            var html = '';
            if (filter === 'semua' || filter === 'sudah') html += generateTableHTML(' Sudah Lengkap', fSudah, '#10b981');
            if (filter === 'semua' || filter === 'belum') html += generateTableHTML(' Belum Lengkap', fBelum, '#ef4444');

            if (html === '') html = '<div style="text-align:center; padding:40px; color:#9ca3af;">Data tidak ditemukan</div>';

            document.getElementById('barModalContent').innerHTML = html;
            document.getElementById('barModal').style.display = 'block';
        }

        function closeBarModal(e) {
            document.getElementById('barModal').style.display = 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.getElementById('barModal').style.display = 'none';
        });

        // FILTER GROUP DI TABEL
        function applyGroupFilter() {
            var valGroup = document.getElementById('filterGroup').value.toLowerCase();
            
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'perencanaan') return true;
                
                var rowGroup = data[2].toLowerCase(); 
                
                // Karena HTML tabel juga sudah memunculkan Kementerian, Lembaga, dsb.
                if (valGroup !== '' && rowGroup !== valGroup) return false;

                return true;
            });

            dtTable.draw();
        }

        // =============================================
        // EXPORT EXCEL (MODAL POP-UP TERPISAH)
        // =============================================
        function exportModalToExcel() {
            var csvRows = [];
            var headers = ['No', 'Nama Instansi', 'Grup', 'Baseline', 'Target', 'Rencana Aksi', 'Semua'];
            
            function pushDataToExcel(title, data) {
                if (data.length === 0) return;
                csvRows.push([title.toUpperCase()]);
                csvRows.push(headers.join(','));
                data.forEach(function(r, i) {
                    var name = (r.name || '').replace(/"/g, '""');
                    var group = (r.group || '-').replace(/"/g, '""');
                    var baseline = r.baseline ? '✔' : '-';
                    var target = r.target ? '✔' : '-';
                    var rencanaAksi = r.rencana_aksi ? '✔' : '-';
                    var semua = r.semua ? '✔' : '-';
                    csvRows.push([(i+1), '"'+name+'"', '"'+group+'"', '"'+baseline+'"', '"'+target+'"', '"'+rencanaAksi+'"', '"'+semua+'"'].join(','));
                });
                csvRows.push([]); 
            }

            if (currentModalFilter === 'semua' || currentModalFilter === 'sudah') pushDataToExcel('SUDAH LENGKAP', currentModalDataSudah);
            if (currentModalFilter === 'semua' || currentModalFilter === 'belum') pushDataToExcel('BELUM LENGKAP', currentModalDataBelum);

            if (csvRows.length === 0) return;

            var blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href   = url;
            a.download = currentModalFilename + '.csv';
            
            document.body.appendChild(a);
            a.click();
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 0);
        }

        // =============================================
        // EXPORT PDF (MODAL POP-UP TERPISAH)
        // =============================================
        function exportModalToPDF() {
            var contentPDF = [
                { text: 'Rincian Data: ' + currentModalFilename.replace(/_/g, ' '), style: 'header' }
            ];

            function getCheckIcon(bg) {
                return {
                    alignment: 'center',
                    fillColor: bg,
                    margin: [0, 4, 0, 0],
                    canvas: [{
                        type: 'polyline',
                        lineWidth: 2,
                        lineColor: '#10b981',
                        lineCap: 'round',
                        lineJoin: 'round',
                        points: [{x: 0, y: 5}, {x: 4, y: 9}, {x: 10, y: 1}]
                    }]
                };
            }

            function getCrossText(bg) {
                return { text: '-', alignment: 'center', color: '#9ca3af', fillColor: bg };
            }

            function pushDataToPDF(title, data, color) {
                if (data.length === 0) return;
                
                contentPDF.push({ text: title + ' (' + data.length + ' Instansi)', style: 'tableTitle', color: color });

                var tableBody = [[
                    { text: 'No', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Nama Instansi', bold: true, color: 'white', fillColor: '#1f2937' },
                    { text: 'Grup', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Baseline', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Target', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Rencana Aksi', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Semua', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' }
                ]];

                data.forEach(function(r, i) {
                    var bg = i % 2 === 0 ? '#f9fafb' : null;
                    tableBody.push([
                        { text: String(i + 1), alignment: 'center', fillColor: bg },
                        { text: r.name || '-', alignment: 'left', fillColor: bg },
                        { text: r.group || '-', alignment: 'center', fillColor: bg },
                        r.baseline ? getCheckIcon(bg) : getCrossText(bg),
                        r.target ? getCheckIcon(bg) : getCrossText(bg),
                        r.rencana_aksi ? getCheckIcon(bg) : getCrossText(bg),
                        r.semua ? getCheckIcon(bg) : getCrossText(bg)
                    ]);
                });

                contentPDF.push({
                    table: {
                        headerRows: 1,
                        widths: [20, '*', 60, 45, 45, 55, 45],
                        body: tableBody
                    },
                    layout: {
                        hLineWidth: function() { return 0.5; },
                        vLineWidth: function() { return 0.5; },
                        hLineColor: function() { return '#e5e7eb'; },
                        vLineColor: function() { return '#e5e7eb'; },
                    },
                    margin: [0, 0, 0, 20] 
                });
            }

            if (currentModalFilter === 'semua' || currentModalFilter === 'sudah') pushDataToPDF('Sudah Lengkap', currentModalDataSudah, '#059669');
            if (currentModalFilter === 'semua' || currentModalFilter === 'belum') pushDataToPDF('Belum Lengkap', currentModalDataBelum, '#dc2626');

            if (contentPDF.length === 1) return; 

            pdfMake.createPdf({
                pageMargins: [20, 40, 20, 30],
                content: contentPDF,
                styles: {
                    header:    { fontSize: 14, bold: true, color: '#1f2937', marginBottom: 15 },
                    tableTitle:{ fontSize: 11, bold: true, marginBottom: 6 },
                },
                defaultStyle: { fontSize: 8, color: '#374151' }
            }).download(currentModalFilename + '.pdf');
        }

        // =============================================
        // EXPORT EXCEL TABEL UTAMA (Dengan Nilai Filter)
        // =============================================
        function exportTableToExcel() {
            var rows    = dtTable.rows({ page: 'current', search: 'applied' }).data().toArray();
            var headers = ['No', 'Instansi Pemerintah', 'Grup Instansi', 'Baseline', 'Tahun Target', 'Target', 'Rencana Aksi', 'Semua'];
            var csvRows = [headers.join(',')];

            var selGroup = document.getElementById('filterGroup');
            var labelGroup = selGroup && selGroup.selectedIndex >= 0 ? selGroup.options[selGroup.selectedIndex].text : 'Semua Grup';
            var namaGrupUnduh = labelGroup === 'Semua Grup' ? 'Semua_Instansi' : labelGroup.replace(/[^a-zA-Z0-9]/g, '_');

            rows.forEach(function(row, i) {
                var cols = [];
                for (var c = 0; c < 8; c++) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim().replace(/"/g,'""');
                    cols.push(c === 0 ? (i+1) : '"' + text + '"');
                }
                csvRows.push(cols.join(','));
            });

            var blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href   = url;
            a.download = 'RB_General_' + namaGrupUnduh + '_{{ $tahun }}.csv';
            
            document.body.appendChild(a);
            a.click();
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 0);
        }

        // =============================================
        // EXPORT PDF TABEL UTAMA (Dengan Nilai Filter di Judul)
        // =============================================
        function exportTableToPDF() {
            var rows      = dtTable.rows({ page: 'current', search: 'applied' }).data().toArray();
            var tableBody = [[
                { text: 'No',           bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Instansi',     bold: true, color: 'white', fillColor: '#1f2937' },
                { text: 'Grup',        bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Baseline',     bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Thn Target',   bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Target',       bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Rencana Aksi', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Semua',        bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
            ]];

            var selGroup = document.getElementById('filterGroup');
            var labelGroup = selGroup && selGroup.selectedIndex >= 0 ? selGroup.options[selGroup.selectedIndex].text : 'Semua Grup';
            var judulGrup = labelGroup === 'Semua Grup' ? 'Semua Instansi Pemerintah' : labelGroup;
            var namaGrupUnduh = labelGroup === 'Semua Grup' ? 'Semua_Instansi' : labelGroup.replace(/[^a-zA-Z0-9]/g, '_');

            function getCheckIcon(bg) {
                return {
                    alignment: 'center',
                    fillColor: bg,
                    margin: [0, 4, 0, 0],
                    canvas: [{
                        type: 'polyline',
                        lineWidth: 2,
                        lineColor: '#10b981',
                        lineCap: 'round',
                        lineJoin: 'round',
                        points: [{x: 0, y: 5}, {x: 4, y: 9}, {x: 10, y: 1}]
                    }]
                };
            }

            rows.forEach(function(row, i) {
                var cols = [];
                var bg = i % 2 === 0 ? '#f9fafb' : null;

                for (var c = 0; c < 8; c++) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim();
                    
                    if (c >= 3 && c !== 4) {
                        if (text === '✔') {
                            cols.push(getCheckIcon(bg));
                        } else {
                            cols.push({ text: '-', alignment: 'center', color: '#9ca3af', fillColor: bg });
                        }
                    } else {
                        cols.push({
                            text: c === 0 ? String(i+1) : text,
                            alignment: (c === 0 || c >= 2) ? 'center' : 'left',
                            color: '#374151',
                            fillColor: bg
                        });
                    }
                }
                tableBody.push(cols);
            });

            pdfMake.createPdf({
                pageOrientation: 'landscape',
                pageMargins: [20, 40, 20, 30],
                content: [
                    { text: 'Dashboard RB General - ' + judulGrup + ' (Tahun {{ $tahun }})', style: 'header' },
                    { text: 'Menampilkan ' + rows.length + ' instansi pada halaman ini', style: 'subheader' },
                    {
                        table: {
                            headerRows: 1,
                            widths: [18, '*', 65, 45, 50, 40, 60, 40],
                            body: tableBody
                        },
                        layout: {
                            hLineWidth: function() { return 0.5; },
                            vLineWidth: function() { return 0.5; },
                            hLineColor: function() { return '#e5e7eb'; },
                            vLineColor: function() { return '#e5e7eb'; },
                        }
                    }
                ],
                styles: {
                    header:    { fontSize: 14, bold: true, color: '#1f2937', marginBottom: 4 },
                    subheader: { fontSize: 9,  color: '#6b7280', marginBottom: 10 },
                },
                defaultStyle: { fontSize: 7, color: '#374151' }
            }).download('RB_General_' + namaGrupUnduh + '_{{ $tahun }}.pdf');
        }

        // DATATABLE
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#perencanaan')) {
                $('#perencanaan').DataTable().destroy();
            }
            dtTable = $('#perencanaan').DataTable({
                "scrollX": true,
                "dom": 'lfrtip',
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "ordering": true,
                "language": {
                    "search": "Cari Instansi:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "zeroRecords": "Data tidak ditemukan",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya"
                    }
                }
            });

            $('#custom-group-filter').appendTo('.dataTables_length');
        });
    </script>
@endpush