@extends('layout.rubick')
@section('title', 'Capaian Output - RB General')

@section('content')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-5">
        <h2 class="text-lg font-medium mr-auto">
            Capaian Output - <span style="color: #2563eb; font-weight: bold;">RB General</span>
        </h2>
    </div>

    <div class="intro-y box p-5 shadow-sm border border-slate-200 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('webdashboard.rb-general.capaian-output') }}" class="flex items-center gap-3">
                <label for="filter-tahun" class="text-sm font-medium text-gray-700 whitespace-nowrap">Pilih Tahun <span class="text-red-500">*</span></label>
                <div class="w-32">
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
                <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center" style="background-color: #dbeafe; color: #2563eb;">RB General</a>
            </div>
        </div>

        <div class="flex gap-4 mt-5 pt-5 border-t border-slate-200/60">
            <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" class="pb-3 text-sm font-medium text-slate-500 hover:text-primary">
                Rencana Aksi
            </a>
            <a href="{{ route('webdashboard.rb-general.capaian-output') }}" class="pb-3 text-sm font-bold border-b-2" style="border-color: #2563eb; color: #2563eb;">
                Capaian Output
            </a>
        </div>
    </div>

    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Tabel Capaian Output Per Instansi</h3>
        </div>

        {{-- Toolbar Export --}}
        <div class="flex flex-wrap items-center gap-3 mb-4 justify-end">
            <button onclick="exportTableToExcel()" 
                style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#059669; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Export Excel
            </button>
            
            <button onclick="exportTableToPDF()" 
                style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Export PDF
            </button>
        </div>

        {{-- ELEMEN FILTER GRUP --}}
        <div class="hidden" id="filter-wrapper-template">
            <div id="custom-group-filter" class="flex items-center gap-2 ml-6 pl-4 border-l border-gray-300">
                <label class="text-sm font-medium text-gray-600 whitespace-nowrap mb-0">Grup Instansi:</label>
                <select id="filterGroup" onchange="applyGroupFilter()"
                    style="min-width: 190px;" 
                    class="border border-gray-300 rounded-md text-sm px-3 py-1.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Semua Grup</option>
                    <option value="kementerian">Kementerian / Lembaga</option>
                    <option value="provinsi">Provinsi</option>
                    <option value="kabupaten">Kabupaten / Kota</option>
                </select>
            </div>
        </div>

        <div class="table-container overflow-x-auto">
            <table id="capaian-output-table" class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center align-middle w-10">No</th>
                        <th rowspan="2" class="align-middle">Instansi Pemerintah</th>
                        <th rowspan="2" class="text-center align-middle">Group Instansi</th>
                        <th colspan="4" class="text-center">Tingkat Pengisian Output</th>
                        <th colspan="5" class="text-center">Rata-rata Persentasi Capaian Output</th>
                    </tr>
                    <tr>
                        <th class="text-center">TW1</th>
                        <th class="text-center">TW2</th>
                        <th class="text-center">TW3</th>
                        <th class="text-center">TW4</th>
                        <th class="text-center">TW1</th>
                        <th class="text-center">TW2</th>
                        <th class="text-center">TW3</th>
                        <th class="text-center">TW4</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($capaians as $index => $capaian)
                    <tr>
                        <td class="text-center text-gray-600">{{ $index + 1 }}</td>
                        <td class="font-medium text-gray-800">{{ $capaian->name }}</td>
                        <td class="text-center text-gray-600">{{ group_instansi($capaian->group) }}</td>
                        
                        {{-- Mengubah nilai kosong menjadi N/A --}}
                        <td class="text-center">{{ trim($capaian->realisasi_tw1) !== '' ? $capaian->realisasi_tw1 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->realisasi_tw2) !== '' ? $capaian->realisasi_tw2 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->realisasi_tw3) !== '' ? $capaian->realisasi_tw3 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->realisasi_tw4) !== '' ? $capaian->realisasi_tw4 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->output_tw1) !== '' ? $capaian->output_tw1 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->output_tw2) !== '' ? $capaian->output_tw2 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->output_tw3) !== '' ? $capaian->output_tw3 : 'N/A' }}</td>
                        <td class="text-center">{{ trim($capaian->output_tw4) !== '' ? $capaian->output_tw4 : 'N/A' }}</td>
                        <td class="text-center font-bold text-blue-600">{{ trim($capaian->output_total) !== '' ? $capaian->output_total : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('css')
<style>
    .table th { background-color: #1f2937; color: white; font-weight: 600; border: 1px solid #374151; vertical-align: middle !important; text-align: center !important; }
    .table td { border: 1px solid #e5e7eb; }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { margin-bottom: 1.5rem; display: flex; align-items: center; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_length { float: left; }
    
    /* CSS Khusus Untuk Lebar Kotak Tampilkan Data agar tidak squish */
    .dataTables_wrapper .dataTables_length select { 
        border: 1px solid #d1d5db; 
        border-radius: 0.375rem; 
        padding: 0.375rem 2rem 0.375rem 0.75rem; 
        min-width: 85px; 
        margin: 0 0.5rem;
    }

    .dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; }
    .dataTables_wrapper .dataTables_paginate { margin-top: 1.5rem; text-align: center; clear: both; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; border-radius: 0.375rem !important;
        padding: 0.4rem 0.75rem !important; margin: 0 0.125rem !important;
        background: white !important; cursor: pointer !important; text-decoration: none !important; color: #374151 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important; color: white !important; border-color: #3b82f6 !important; font-weight: bold;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #3b82f6 !important; }
</style>
@endpush

@push('js')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <script>
        var dtTable = null; 

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#capaian-output-table')) {
                $('#capaian-output-table').DataTable().destroy();
            }

            dtTable = $('#capaian-output-table').DataTable({
                "scrollX": true,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "order": [[0, "asc"]],
                "language": {
                    "search": "Cari Instansi:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "paginate": { 
                        "previous": "Sebelumnya", 
                        "next": "Selanjutnya" 
                    }
                }
            });

            $('#custom-group-filter').appendTo('.dataTables_length');
        });

        // FUNGSI FILTER GRUP
        function applyGroupFilter() {
            var valGroup = document.getElementById('filterGroup').value.toLowerCase();
            
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'capaian-output-table') return true;
                
                var rowGroup = data[2].toLowerCase(); 
                
                if (valGroup === 'kementerian' && !rowGroup.includes('kementerian') && !rowGroup.includes('lembaga') && rowGroup !== 'kl') return false;
                if (valGroup === 'provinsi' && !rowGroup.includes('provinsi')) return false;
                if (valGroup === 'kabupaten' && !rowGroup.includes('kabupaten') && !rowGroup.includes('kota')) return false;

                return true;
            });

            dtTable.draw();
        }

        // =============================================
        // EXPORT EXCEL TABEL UTAMA (Sesuai Filter)
        // =============================================
        function exportTableToExcel() {
            var rows = dtTable.rows({ page: 'current', search: 'applied' }).data().toArray();
            var csvRows = [];
            
            // Header Baris 1
            csvRows.push(['No', 'Instansi Pemerintah', 'Group Instansi', 'Tingkat Pengisian Output TW1', 'TW2', 'TW3', 'TW4', 'Rata-rata Capaian Output TW1', 'TW2', 'TW3', 'TW4', 'Total'].join(','));
            
            var selGroup = document.getElementById('filterGroup');
            var labelGroup = selGroup && selGroup.selectedIndex >= 0 ? selGroup.options[selGroup.selectedIndex].text : 'Semua Grup';
            var namaGrupUnduh = labelGroup === 'Semua Grup' ? 'Semua_Instansi' : labelGroup.replace(/[^a-zA-Z0-9]/g, '_');

            rows.forEach(function(row, i) {
                var cols = [];
                for (var c = 0; c < 12; c++) {
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
            a.download = 'Capaian_Output_' + namaGrupUnduh + '_{{ $selectedYear }}.csv';
            
            document.body.appendChild(a);
            a.click();
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 0);
        }

        // =============================================
        // EXPORT PDF TABEL UTAMA (Sesuai Filter & Format Kolom)
        // =============================================
        function exportTableToPDF() {
            var rows = dtTable.rows({ page: 'current', search: 'applied' }).data().toArray();
            
            var selGroup = document.getElementById('filterGroup');
            var labelGroup = selGroup && selGroup.selectedIndex >= 0 ? selGroup.options[selGroup.selectedIndex].text : 'Semua Grup';
            var judulGrup = labelGroup === 'Semua Grup' ? 'Semua Instansi Pemerintah' : labelGroup;
            var namaGrupUnduh = labelGroup === 'Semua Grup' ? 'Semua_Instansi' : labelGroup.replace(/[^a-zA-Z0-9]/g, '_');

            // Struktur Tabel Kompleks dengan rowSpan & colSpan
            var tableBody = [
                [
                    { text: 'No', rowSpan: 2, bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937', margin: [0, 8, 0, 0] },
                    { text: 'Instansi Pemerintah', rowSpan: 2, bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937', margin: [0, 8, 0, 0] },
                    { text: 'Group Instansi', rowSpan: 2, bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937', margin: [0, 8, 0, 0] },
                    { text: 'Tingkat Pengisian Output', colSpan: 4, bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    {}, {}, {},
                    { text: 'Rata-rata Capaian Output', colSpan: 5, bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    {}, {}, {}, {}
                ],
                [
                    {}, {}, {},
                    { text: 'TW1', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW2', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW3', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW4', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW1', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW2', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW3', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'TW4', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Total', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' }
                ]
            ];

            rows.forEach(function(row, i) {
                var cols = [];
                var bg = i % 2 === 0 ? '#f9fafb' : null;

                for (var c = 0; c < 12; c++) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim();
                    
                    cols.push({
                        text: c === 0 ? String(i+1) : text,
                        alignment: c === 1 ? 'left' : 'center',
                        color: (c === 11 && text !== 'N/A') ? '#2563eb' : '#374151',
                        fillColor: bg
                    });
                }
                tableBody.push(cols);
            });

            pdfMake.createPdf({
                pageOrientation: 'landscape',
                pageMargins: [20, 40, 20, 30],
                content: [
                    { text: 'Capaian Output RB General - ' + judulGrup + ' (Tahun {{ $selectedYear }})', style: 'header' },
                    { text: 'Menampilkan ' + rows.length + ' instansi pada halaman ini', style: 'subheader' },
                    {
                        table: {
                            headerRows: 2,
                            // Pengaturan lebar 12 kolom agar muat di landscape
                            widths: [15, '*', 55, 28, 28, 28, 28, 28, 28, 28, 28, 30],
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
            }).download('Capaian_Output_' + namaGrupUnduh + '_{{ $selectedYear }}.pdf');
        }
    </script>
@endpush