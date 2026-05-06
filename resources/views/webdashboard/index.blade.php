@extends('layout.rubick')
@section('title', 'Dashboard Evaluasi RB')

@section('content')
    @php
        $collection = collect($rows);
        $totalInstansi = $collection->count();
        
        $dinilai = $collection->filter(function($item) { return is_numeric(str_replace(',', '.', $item['index_rb'])); });
        $rataRata = $dinilai->count() > 0 ? round($dinilai->avg(function($item) { return (float)str_replace(',', '.', $item['index_rb']); }), 2) : 0;
        
        $predikatAA = $collection->where('predikat', 'AA')->count();
        $belumEvaluasi = $collection->where('index_rb', '---')->count();
    @endphp

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-5">
        <h2 class="text-lg font-medium mr-auto">
            Dashboard <span style="color: #2563eb; font-weight: bold;">Evaluasi</span> RB
        </h2>
    </div>

    <div class="intro-y box p-5 shadow-sm border border-slate-200 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ url('webdashboard') }}" class="flex items-center gap-3">
                <label for="kegiatan_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun Kegiatan <span class="text-red-500">*</span></label>
                <div class="w-64"> 
                    {!! Form::select('kegiatan_id', kegiatan(), $selectedKegiatanId, ['class' => 'form-select border-gray-300 rounded-md shadow-sm w-full', 'id' => 'kegiatan_id', 'onchange' => 'this.form.submit()']) !!}
                </div>
            </form>
            <div class="flex flex-wrap gap-2 mt-2 md:mt-0 justify-start md:justify-end">
                <a href="{{ url('webdashboard') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center" style="background-color: #dbeafe; color: #2563eb;">Hasil Evaluasi</a>
                <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600 hover:bg-gray-200">RB General</a>
                <a href="{{ route('webdashboard.rb-tematik.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600 hover:bg-gray-200">RB Tematik</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        {{-- Card Total Instansi --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 shadow-sm border border-slate-200 flex items-center cursor-pointer hover:shadow-md transition-shadow" onclick="showSummaryModal('all')">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-none" style="background-color: #dbeafe;">
                    <i data-lucide="layers" style="color: #2563eb; width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Total Instansi</div>
                    <div class="text-2xl font-bold">{{ $totalInstansi }}</div>
                </div>
            </div>
        </div>

        {{-- Card Rata-rata Nilai --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 shadow-sm border border-slate-200 flex items-center cursor-pointer hover:shadow-md transition-shadow" onclick="showSummaryModal('rated')">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-none" style="background-color: #dcfce7;">
                    <i data-lucide="trending-up" style="color: #16a34a; width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Rata-rata Nilai</div>
                    <div class="text-2xl font-bold" style="color: #16a34a;">{{ $rataRata }}</div>
                </div>
            </div>
        </div>

        {{-- Card Predikat AA --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 shadow-sm border border-slate-200 flex items-center cursor-pointer hover:shadow-md transition-shadow" onclick="showSummaryModal('AA')">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-none" style="background-color: #f3e8ff;">
                    <i data-lucide="award" style="color: #9333ea; width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Predikat AA</div>
                    <div class="text-2xl font-bold" style="color: #9333ea;">{{ $predikatAA }}</div>
                </div>
            </div>
        </div>

        {{-- Card Belum Evaluasi --}}
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 shadow-sm border border-slate-200 flex items-center cursor-pointer hover:shadow-md transition-shadow" onclick="showSummaryModal('belum')">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-none" style="background-color: #fee2e2;">
                    <i data-lucide="alert-circle" style="color: #dc2626; width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Belum Evaluasi</div>
                    <div class="text-2xl font-bold" style="color: #dc2626;">{{ $belumEvaluasi }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Statistik Per Grup Instansi</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $barColors = ['AA' => '#10b981', 'A' => '#3b82f6', 'A-' => '#38bdf8', 'BB' => '#facc15', 'B' => '#fb923c', 'CC' => '#94a3b8', 'C' => '#f472b6', 'D' => '#ef4444'];
                $chartGroups = [
                    'Kementerian' => 'kementerian',
                    'Lembaga'     => 'lembaga',
                    'Provinsi'    => 'provinsi',
                    'Kabupaten'   => 'kabupaten',
                    'Kota'        => 'kota',
                ];                
                $labels = $predikatCountsData['labels'] ?? ['AA', 'A', 'A-', 'BB', 'B', 'CC', 'C', 'D'];
            @endphp
            @foreach($chartGroups as $judul => $dbKey)
                @php
                    $counts = $predikatCountsData[$dbKey] ?? [0,0,0,0,0,0,0,0];
                    $maxCount = max($counts) > 0 ? max($counts) : 1;
                    $totalGroup = array_sum($counts);
                @endphp
                <div class="border border-gray-200 rounded-lg p-4 px-6 pt-5">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-sm">{{ $judul }}</h4>
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800">{{ $totalGroup }} Instansi</span>
                    </div>
                    <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 96px; gap: 4px; padding: 10px 4px 0 4px; margin-top: 48px; position: relative;">
                        @foreach($labels as $index => $lbl)
                            @php
                                $jmlInstansi = $counts[$index] ?? 0;
                                $tinggiPx = $jmlInstansi > 0 ? round(($jmlInstansi / $maxCount) * 88) + 4 : 0;
                                $hexAsli = $barColors[$lbl] ?? '#cbd5e1';
                                $hexBackground = $jmlInstansi > 0 ? $hexAsli : 'transparent';
                                $minHeight = $jmlInstansi > 0 ? '3px' : '0px';
                            @endphp
                            <div style="display: flex; flex-direction: column; align-items: center; flex: 1; gap: 4px; position: relative;">
                                <div style="font-size: 9px; font-weight: 700; color: #475569; text-align: center; min-height: 14px;">
                                    {{ $jmlInstansi }}
                                </div>
                                <div class="chart-bar"
                                     data-predikat="{{ $lbl }}"
                                     data-group="{{ $dbKey }}"
                                     data-judul="{{ $judul }}"
                                     data-jumlah="{{ $jmlInstansi }}"
                                     data-color="{{ $hexAsli }}"
                                     style="width: 100%; background-color: {{ $hexBackground }}; height: {{ $tinggiPx }}px; border-radius: 2px 2px 0 0; min-height: {{ $minHeight }}; cursor: {{ $jmlInstansi > 0 ? 'pointer' : 'default' }}; transition: opacity 0.15s;"
                                     onmouseover="showTooltip(event, '{{ $lbl }}', '{{ $jmlInstansi }}', '{{ $judul }}')"
                                     onmouseout="hideTooltip()"
                                     onclick="{{ $jmlInstansi > 0 ? 'showBarModal(this)' : '' }}">
                                </div>
                                <span style="font-size: 9px; font-weight: 700; color: #6b7280; white-space: nowrap;">{{ $lbl }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Daftar Hasil Evaluasi Semua Instansi</h3>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Peringkat:</label>
                    <select id="filterPeringkat" onchange="applyAllFilters()"
                        class="border border-gray-300 rounded-md text-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 w-32">
                        <option value="">Semua</option>
                        <option value="top5">5 Teratas</option>
                        <option value="top10">10 Teratas</option>
                        <option value="bottom5">5 Terbawah</option>
                        <option value="bottom10">10 Terbawah</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Grup:</label>
                    <select id="filterGroup" onchange="applyAllFilters()" class="border border-gray-300 rounded-md text-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 w-36">
                        <option value="">Semua</option>
                        <option value="Kementerian">Kementerian</option>
                        <option value="Lembaga">Lembaga</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Kabupaten">Kabupaten</option>
                        <option value="Kota">Kota</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Predikat:</label>
                    <select id="filterPredikat" onchange="applyAllFilters()"
                        class="border border-gray-300 rounded-md text-sm px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 w-28">
                        <option value="">Semua</option>
                        <option value="AA">AA</option>
                        <option value="A">A</option>
                        <option value="A-">A-</option>
                        <option value="BB">BB</option>
                        <option value="B">B</option>
                        <option value="CC">CC</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 lg:ml-auto shrink-0">
                <button onclick="exportTableToExcel()"
                    style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#059669; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Unduh Excel
                </button>

                <button onclick="exportTableToPDF()"
                    style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Unduh PDF
                </button>

                <button onclick="resetRankFilter()"
                    style="display:flex; align-items:center; gap:8px; padding:8px 16px; background:#4b5563; color:white; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#374151'" onmouseout="this.style.background='#4b5563'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Atur Ulang
                </button>
            </div>
        </div>

        <div class="table-container overflow-x-auto">
            <table id="perencanaan" class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="w-10 text-center">No</th>
                        <th>Instansi Pemerintah</th>
                        <th class="text-center">Grup Instansi</th>
                        <th class="text-center">RB General</th>
                        <th class="text-center">RB Tematik</th>
                        <th class="text-center">Indeks RB</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key => $row)
                    <tr>
                        <td class="text-center text-gray-600">{{ $key + 1 }}</td>
                        <td class="font-medium text-gray-800">
                            <a href="{{ route('webdashboard.detail', ['id' => $row['id'], 'tahun' => $selectedKegiatanId]) }}" 
                        style="color:#2563eb; font-weight:600; text-decoration:none;" 
                        onmouseover="this.style.textDecoration='underline'" 
                        onmouseout="this.style.textDecoration='none'">
                            {{ $row['name'] }}
                        </a>
                        </td>
                        <td class="text-center text-gray-600">{{ $row['group_label'] ?? '-' }}</td>
                        <td class="text-center text-gray-600">{{ $row['rb_general_penyesuaian'] }}</td>
                        <td class="text-center text-gray-600">{{ $row['rb_tematik'] }}</td>
                        <td class="text-center font-bold text-blue-600">{{ $row['index_rb'] }}</td>
                        <td class="text-center">
                            @php
                                $p = $row['predikat'];
                                $color = 'background-color: #f3f4f6; color: #1f2937;';
                                if($p == 'AA' || $p == 'A') $color = 'background-color: #dbeafe; color: #1e40af;';
                                elseif($p == 'A-') $color = 'background-color: #dcfce7; color: #166534;';
                                elseif($p == 'BB' || $p == 'B') $color = 'background-color: #fef9c3; color: #9a3412;';
                                elseif($p == 'CC' || $p == 'C' || $p == 'D') $color = 'background-color: #fee2e2; color: #991b1b;';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold" style="{{ $color }}">{{ $p }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tooltip hover --}}
    <div id="chart-tooltip" style="display:none; position:fixed; z-index:9999; background:#1f2937; color:white; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; pointer-events:none; white-space:nowrap; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>

    {{-- Modal popup klik bar & card --}}
    <div id="barModal" style="display:none; position:fixed; inset:0; z-index:99998; background:rgba(0,0,0,0.5);" onclick="closeBarModal(event)">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:white; border-radius:12px; width:90%; max-width:850px; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
            
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
                        Excel
                    </button>
                    <button onclick="exportModalToPDF()"
                        style="display:flex; align-items:center; gap:6px; padding:6px 12px; background:#dc2626; color:white; border:none; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer;"
                        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        PDF
                    </button>
                    <button onclick="document.getElementById('barModal').style.display='none'" style="background:#f3f4f6; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer; font-size:16px; color:#6b7280; display:flex; align-items:center; justify-content:center; margin-left:4px;">&times;</button>
                </div>
            </div>

            <!-- Wadah Konten Dinamis -->
            <div id="barModalContent" style="padding:0 20px 16px 20px; overflow-y:auto; flex:1;"></div>
        </div>
    </div>

@endsection

@push('css')
<style>
    .chart-bar:hover { opacity: 0.75 !important; }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; display: flex; align-items: center; }
    .dataTables_wrapper .dataTables_length { float: left; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; text-align: center; clear: both; padding-top: 10px; }
    .table th { background-color: #1f2937; color: white; font-weight: 600; border: 1px solid #374151; }
    .table td { border: 1px solid #e5e7eb; }
    .table { border-collapse: collapse; border: 1px solid #e5e7eb; width: 100%; }
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; }
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
    #barModalBody tr:hover { background: #f9fafb; }
    #barModalBody tr td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; }
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

    <script>
        var allRows  = @json($rows);
        var dtTable  = null;
        var activeRankFilter = null; 
        
        var currentModalData = [];
        var currentModalFilename = '';
        var dataDinilaiGlobal = []; 
        
        // TANGKAP TAHUN DARI CONTROLLER BIAR DINAMIS
        var tahunSekarang = {{ $tahun ?? 2025 }}; 
        var tahunLalu = tahunSekarang - 1;

        function showTooltip(e, predikat, jumlah, judul) {
            if (jumlah == 0) return;
            var tip = document.getElementById('chart-tooltip');
            tip.innerHTML = '<span style="opacity:0.7;">'+judul+'</span> &nbsp;|&nbsp; Predikat <strong>'+predikat+'</strong> &nbsp;: &nbsp;<strong>'+jumlah+' instansi</strong>';
            tip.style.display = 'block';
            tip.style.left = (e.clientX + 12) + 'px';
            tip.style.top = (e.clientY - 36) + 'px';
        }

        function hideTooltip() {
            document.getElementById('chart-tooltip').style.display = 'none';
        }

        document.addEventListener('mousemove', function(e) {
            var tip = document.getElementById('chart-tooltip');
            if (tip.style.display === 'block') {
                tip.style.left = (e.clientX + 12) + 'px';
                tip.style.top = (e.clientY - 36) + 'px';
            }
        });

        // FUNGSI HITUNG RATA-RATA GLOBAL
        function hitungRata(dataArray, key) {
            var validData = dataArray.filter(r => r[key] !== '---' && r[key] !== null && !isNaN(parseFloat(r[key].toString().replace(',', '.'))));
            if(validData.length === 0) return 0;
            var sum = validData.reduce((acc, r) => acc + parseFloat(r[key].toString().replace(',', '.')), 0);
            return (sum / validData.length).toFixed(2);
        }

        // GENERATOR KARTU KOMPARASI BASELINE
        function renderCompareCard(title, dataArray) {
            var avgSekarang = hitungRata(dataArray, 'index_rb');
            var avgLalu = hitungRata(dataArray, 'index_rb_prev');

            var diff = (avgSekarang - avgLalu).toFixed(2);
            var color = diff > 0 ? '#16a34a' : (diff < 0 ? '#dc2626' : '#64748b');
            var icon = diff > 0 ? '▲ +' : (diff < 0 ? '▼ ' : '');
            var statusHtml = (avgLalu > 0 && avgSekarang > 0) 
                ? `<span style="color:${color}; font-size:12px; font-weight:bold; margin-left:6px; background:${color}15; padding:2px 6px; border-radius:4px;">${icon}${diff}</span>` 
                : `<span style="color:#94a3b8; font-size:11px; margin-left:6px;">(Baru)</span>`;

            return `
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; flex:1; min-width: 250px;">
                    <div style="font-size:12px; font-weight:700; color:#475569; text-transform:uppercase; margin-bottom:12px; border-bottom:1px solid #e2e8f0; padding-bottom:6px;">
                        ${title}
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="text-align:center; flex:1;">
                            <div style="font-size:10px; color:#94a3b8; margin-bottom:4px;">Baseline (${tahunLalu})</div>
                            <div style="font-size:18px; font-weight:bold; color:#64748b;">${avgLalu > 0 ? avgLalu : '-'}</div>
                        </div>
                        <div style="color:#cbd5e1; padding:0 8px; font-size:18px;">➔</div>
                        <div style="text-align:center; flex:1;">
                            <div style="font-size:10px; color:#1e40af; margin-bottom:4px;">Tahun Ini (${tahunSekarang})</div>
                            <div style="display:flex; align-items:center; justify-content:center;">
                                <span style="font-size:22px; font-weight:bold; color:#1d4ed8;">${avgSekarang > 0 ? avgSekarang : '-'}</span>
                                ${statusHtml}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // GENERATOR TABEL HTML DINAMIS UNTUK MODAL
        function generateTableHTML(title, data, color) {
            if (data.length === 0) return '<div style="text-align:center; padding:20px; color:#9ca3af;">Data tidak ditemukan</div>';
            
            var html = '';
            if (title) html += '<h4 style="margin: 16px 0 8px 0; font-size: 14px; font-weight: bold; color: '+color+';">' + title + '</h4>';
            
            html += '<div style="max-height: 50vh; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; position: relative; margin-bottom: 10px;">';
            html += '<table style="width:100%; border-collapse:separate; border-spacing:0; font-size:13px; margin:0;">';
            html += '<thead><tr>';
            
            var thStyle = 'position: sticky; top: 0; background: #f8fafc; padding: 12px; font-weight: 600; color: #475569; z-index: 20; box-shadow: 0 1px 0 #cbd5e1;';
            
            html += '<th style="'+thStyle+' text-align:center; width:50px;">No</th>';
            html += '<th style="'+thStyle+' text-align:left;">Nama Instansi</th>';
            html += '<th style="'+thStyle+' text-align:center; width:130px;">Grup</th>';
            html += '<th style="'+thStyle+' text-align:center; width:90px;">' + tahunLalu + '</th>';
            html += '<th style="'+thStyle+' text-align:center; width:90px;">' + tahunSekarang + '</th>';
            html += '<th style="'+thStyle+' text-align:center; width:80px;">Status</th>';
            html += '</tr></thead><tbody>';
            
            data.forEach(function(r, i) {
                var bg = i % 2 === 0 ? '#ffffff' : '#f9fafb'; 
                
                var prev = (r.index_rb_prev && r.index_rb_prev !== '---') ? parseFloat(r.index_rb_prev.toString().replace(',','.')) : 0;
                var now = (r.index_rb && r.index_rb !== '---') ? parseFloat(r.index_rb.toString().replace(',','.')) : 0;
                var diff = (now - prev).toFixed(2);
                
                var stColor = diff > 0 ? '#16a34a' : (diff < 0 ? '#dc2626' : '#64748b');
                var stIcon = diff > 0 ? '▲' : (diff < 0 ? '▼' : '-');
                var stText = (prev > 0 && now > 0) ? `<span style="color:${stColor}; font-weight:bold; font-size:12px;">${stIcon} ${Math.abs(diff)}</span>` : '-';

                html += '<tr style="background:'+bg+';">';
                html += '<td style="padding:8px 12px; border-bottom:1px solid #f1f5f9; color:#6b7280; text-align:center;">' + (i+1) + '</td>';
                html += '<td style="padding:8px 12px; border-bottom:1px solid #f1f5f9; font-weight:500;"><a href="'+(r.detail_url||'#')+'" style="color:#2563eb; text-decoration:none;">'+r.name+'</a></td>';
                html += '<td style="padding:8px 12px; border-bottom:1px solid #f1f5f9; text-align:center; color:#6b7280;">'+(r.group_label||r.group||'-')+'</td>';
                html += '<td style="padding:8px 12px; border-bottom:1px solid #f1f5f9; text-align:center; color:#64748b;">'+(r.index_rb_prev||'-')+'</td>';
                html += '<td style="padding:8px 12px; border-bottom:1px solid #f1f5f9; text-align:center; font-weight:700; color:#1d4ed8;">'+(r.index_rb||'-')+'</td>';
                html += '<td style="padding:8px 12px; border-bottom:1px solid #f1f5f9; text-align:center;">'+stText+'</td>';
                html += '</tr>';
            });
            html += '</tbody></table></div>'; 
            return html;
        }

        // LOGIKA DROPDOWN FILTER KOMPARASI (Nasional vs IP, dll)
        function changeCompareMode(mode) {
            var wrapProv = document.getElementById('wrapProv');
            var compareCards = document.getElementById('compareCards');
            var tabelContainer = document.getElementById('tabelContainer');

            var dataNasional = dataDinilaiGlobal;

            if (mode === 'nasional_vs_ip') {
                wrapProv.style.display = 'none';
                var dataIP = dataDinilaiGlobal.filter(r => r.group === 'kementerian' || r.group === 'lembaga');
                
                compareCards.innerHTML = renderCompareCard('Rata-rata Nasional', dataNasional) + renderCompareCard('Rata-rata Instansi Pusat', dataIP);
                tabelContainer.innerHTML = generateTableHTML('Data Seluruh Instansi & Pusat', dataNasional, '#16a34a');
                currentModalData = dataNasional;

            } else if (mode === 'nasional_vs_prov') {
                wrapProv.style.display = 'none';
                var dataProvinsi = dataDinilaiGlobal.filter(r => r.group === 'provinsi');
                
                compareCards.innerHTML = renderCompareCard('Rata-rata Nasional', dataNasional) + renderCompareCard('Rata-rata Seluruh Provinsi', dataProvinsi);
                tabelContainer.innerHTML = generateTableHTML('Data Seluruh Provinsi', dataProvinsi, '#16a34a');
                currentModalData = dataProvinsi;

            } else if (mode === 'spesifik_daerah') {
                wrapProv.style.display = 'block';
                compareCards.innerHTML = '';
                tabelContainer.innerHTML = '<div style="text-align:center; padding:20px; color:#64748b; font-style:italic;">Silakan pilih Provinsi di atas untuk melihat data turunan daerah.</div>';
                document.getElementById('modalSelectProv').value = '';
            }
        }

        // LOGIKA DROPDOWN TURUNAN DAERAH (SPESIFIK DAERAH)
        function filterTurunanDaerah(provId) {
            var compareCards = document.getElementById('compareCards');
            var tabelContainer = document.getElementById('tabelContainer');

            if(!provId) {
                compareCards.innerHTML = '';
                tabelContainer.innerHTML = '';
                return;
            }

            var pemprov = dataDinilaiGlobal.find(r => r.id == provId);
            var anakKabKota = dataDinilaiGlobal.filter(r => (r.group === 'kabupaten' || r.group === 'kota') && r.prov_id == provId);
            
            var cardPemprovHtml = pemprov ? renderCompareCard('Nilai ' + pemprov.name, [pemprov]) : renderCompareCard('Nilai Pemprov', []);
            var cardKabKotaHtml = renderCompareCard('Rata-rata Kab/Kota Terkait', anakKabKota);

            compareCards.innerHTML = cardPemprovHtml + cardKabKotaHtml;

            var dataTabel = [];
            if(pemprov) dataTabel.push(pemprov);
            dataTabel = dataTabel.concat(anakKabKota);
            
            currentModalData = dataTabel; 
            var namaArea = pemprov ? pemprov.name.replace('Pemerintah Provinsi ', '') : 'Area';
            tabelContainer.innerHTML = generateTableHTML('Rincian Data ' + namaArea, dataTabel, '#16a34a');
        }

        // MODAL KLIK BAR CHART
        function showBarModal(el) {
            var predikat = el.dataset.predikat;
            var group    = el.dataset.group;
            var judul    = el.dataset.judul;
            var jumlah   = el.dataset.jumlah;
            var color    = el.dataset.color;

            var filtered = allRows.filter(function(r) {
                var dataGroup = (r.group || '').toLowerCase();
                var selectedGroup = (group || '').toLowerCase();
                return r.predikat === predikat && dataGroup === selectedGroup;
            });

            currentModalData = filtered;
            currentModalFilename = 'Predikat_' + predikat + '_' + judul.replace(/\s+/g,'_');

            document.getElementById('barModalHeader').style.backgroundColor = color + '22';
            document.getElementById('barModalTitle').innerHTML =
                'Predikat <span style="padding:2px 10px; border-radius:20px; font-size:14px; background:'+color+'33; color:'+color+';">'+predikat+'</span> &nbsp;&mdash;&nbsp; '+judul;
            document.getElementById('barModalSubtitle').textContent = jumlah + ' instansi ditemukan';

            document.getElementById('barModalContent').innerHTML = generateTableHTML('', filtered, color);
            document.getElementById('barModal').style.display = 'block';
        }

        // MODAL KLIK CARD SUMMARY
        function showSummaryModal(type) {
            var filtered = [];
            var title    = '';
            var subtitle = '';
            var color    = '#2563eb';

            if (type === 'rated') {
                dataDinilaiGlobal = allRows.filter(function(r) {
                    return r.index_rb !== '---' && !isNaN(parseFloat(r.index_rb.toString().replace(',','.')));
                });

                color    = '#16a34a';
                title    = '<span style="padding:2px 10px; border-radius:20px; font-size:13px; background:'+color+'22; color:'+color+';">Komparasi Baseline Rata-rata Nilai</span>';
                subtitle = dataDinilaiGlobal.length + ' instansi sudah dinilai';
                currentModalFilename = 'Statistik_Komparasi_Nilai';

                document.getElementById('barModalHeader').style.backgroundColor = color + '18';
                document.getElementById('barModalTitle').innerHTML   = title;
                document.getElementById('barModalSubtitle').innerHTML = subtitle;

                var dataProvinsi = dataDinilaiGlobal.filter(r => r.group === 'provinsi');
                var opsiProvinsi = '<option value="">-- Pilih Provinsi --</option>';
                dataProvinsi.sort((a,b) => a.name.localeCompare(b.name)).forEach(p => {
                    opsiProvinsi += `<option value="${p.id}">${p.name}</option>`;
                });

                var htmlCustom = `
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:8px; margin-top:15px; margin-bottom: 20px; display:flex; gap:16px;">
                        <div style="flex:1;">
                            <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:8px;">Pilih Mode Komparasi:</label>
                            <select id="modalCompareMode" onchange="changeCompareMode(this.value)" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; outline:none; background:white;">
                                <option value="nasional_vs_ip" selected>Nasional vs Pusat (K/L)</option>
                                <option value="nasional_vs_prov">Nasional vs Seluruh Provinsi</option>
                                <option value="spesifik_daerah">Spesifik Daerah & Turunan Kab/Kota...</option>
                            </select>
                        </div>
                        <div id="wrapProv" style="flex:1; display:none;">
                            <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:8px;">Pilih Provinsi:</label>
                            <select id="modalSelectProv" onchange="filterTurunanDaerah(this.value)" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; outline:none; background:white;">
                                ${opsiProvinsi}
                            </select>
                        </div>
                    </div>

                    <div id="compareCards" style="display:flex; flex-wrap:wrap; gap:16px; margin-bottom:20px;"></div>

                    <div id="tabelContainer"></div>
                `;

                document.getElementById('barModalContent').innerHTML = htmlCustom;
                document.getElementById('barModal').style.display = 'block';

                changeCompareMode('nasional_vs_ip');
                return; 
            }

            if (type === 'all') {
                filtered = allRows;
                color    = '#2563eb';
                title    = '<span style="padding:2px 10px; border-radius:20px; font-size:13px; background:'+color+'22; color:'+color+';">Semua Instansi</span>';
                subtitle = filtered.length + ' instansi terdaftar';
                currentModalFilename = 'Semua_Instansi';

            } else if (type === 'AA') {
                filtered = allRows.filter(function(r) { return r.predikat === 'AA'; });
                color    = '#9333ea';
                title    = 'Predikat <span style="padding:2px 10px; border-radius:20px; font-size:14px; background:'+color+'22; color:'+color+';">AA</span>';
                subtitle = filtered.length + ' instansi dengan predikat AA';
                currentModalFilename = 'Instansi_Predikat_AA';

            } else if (type === 'belum') {
                filtered = allRows.filter(function(r) { return r.index_rb === '---'; });
                color    = '#dc2626';
                title    = '<span style="padding:2px 10px; border-radius:20px; font-size:13px; background:'+color+'22; color:'+color+';">Belum Dievaluasi</span>';
                subtitle = filtered.length + ' instansi belum dievaluasi';
                currentModalFilename = 'Instansi_Belum_Dievaluasi';
            }

            currentModalData = filtered;
            document.getElementById('barModalHeader').style.backgroundColor = color + '18';
            document.getElementById('barModalTitle').innerHTML   = title;
            document.getElementById('barModalSubtitle').innerHTML = subtitle;

            document.getElementById('barModalContent').innerHTML = generateTableHTML('', filtered, color);
            document.getElementById('barModal').style.display = 'block';
        }

        function closeBarModal(e) {
            document.getElementById('barModal').style.display = 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.getElementById('barModal').style.display = 'none';
        });

        // -------------------------------------------------------------------
        // EXPORT EXCEL & PDF, DATATABLE, DLL
        // -------------------------------------------------------------------
        
        function applyAllFilters() {
            var valPeringkat = document.getElementById('filterPeringkat').value;
            var valGroup = document.getElementById('filterGroup').value.toLowerCase();
            var valPredikat = document.getElementById('filterPredikat').value;

            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            activeRankFilter = null;

            if (valPeringkat) {
                var isTop = valPeringkat.startsWith('top');
                var n = parseInt(valPeringkat.replace('top', '').replace('bottom', ''));
                var allData = dtTable.rows().data().toArray();
                var rated = [];

                allData.forEach(function(row) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = row[5]; 
                    var val = parseFloat((tmp.textContent || tmp.innerText || '').trim().replace(',','.'));
                    if (!isNaN(val)) rated.push({ row: row, val: val });
                });

                rated.sort(function(a, b) { return b.val - a.val; });
                var selected = isTop ? rated.slice(0, n) : rated.slice(-n).reverse();
                
                activeRankFilter = selected.map(function(item) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = item.row[1];
                    return (tmp.textContent || tmp.innerText || '').trim();
                });
            }

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'perencanaan') return true;

                var tmpDiv = document.createElement('div');
                tmpDiv.innerHTML = data[1];
                var rowName = (tmpDiv.textContent || tmpDiv.innerText || '').trim();
                
                var rowGroup = data[2].toLowerCase();
                
                tmpDiv.innerHTML = data[6];
                var rowPredikat = (tmpDiv.textContent || tmpDiv.innerText || '').trim();

                if (activeRankFilter && activeRankFilter.indexOf(rowName) === -1) return false;
                if (valGroup && !rowGroup.includes(valGroup)) return false;
                if (valPredikat && rowPredikat !== valPredikat) return false;

                return true; 
            });

            if (valPeringkat) {
                var isTop = valPeringkat.startsWith('top');
                dtTable.search('').order([[5, isTop ? 'desc' : 'asc']]).draw();
            } else {
                dtTable.draw(); 
            }
        }

        function resetRankFilter() {
            document.getElementById('filterPeringkat').value = '';
            document.getElementById('filterGroup').value = '';
            document.getElementById('filterPredikat').value = '';
            
            activeRankFilter = null;
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }
            dtTable.search('').order([[0, 'asc']]).draw();
        }

        function exportModalToExcel() {
            if (!currentModalData || currentModalData.length === 0) return;
            var headers = ['No', 'Nama Instansi', 'Grup', 'Baseline (' + tahunLalu + ')', 'Indeks (' + tahunSekarang + ')'];
            var csvRows = [headers.join(',')];

            currentModalData.forEach(function(r, i) {
                var name = (r.name || '').replace(/"/g, '""');
                var group = (r.group_label || r.group || '').replace(/"/g, '""');
                var prev = (r.index_rb_prev || '-').toString().replace(/"/g, '""');
                var indexRb = (r.index_rb || '-').toString().replace(/"/g, '""');
                csvRows.push([(i+1), '"'+name+'"', '"'+group+'"', '"'+prev+'"', '"'+indexRb+'"'].join(','));
            });

            var blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href   = url;
            a.download = currentModalFilename + '.csv';
            a.click();
            URL.revokeObjectURL(url);
        }

        function exportModalToPDF() {
            if (!currentModalData || currentModalData.length === 0) return;
            
            var tableBody = [[
                { text: 'No', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Nama Instansi', bold: true, color: 'white', fillColor: '#1f2937' },
                { text: 'Grup', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Baseline (' + tahunLalu + ')', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Indeks (' + tahunSekarang + ')', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' }
            ]];

            currentModalData.forEach(function(r, i) {
                tableBody.push([
                    { text: String(i + 1), alignment: 'center', fillColor: i % 2 === 0 ? '#f9fafb' : null },
                    { text: r.name || '-', alignment: 'left', fillColor: i % 2 === 0 ? '#f9fafb' : null },
                    { text: r.group_label || r.group || '-', alignment: 'center', fillColor: i % 2 === 0 ? '#f9fafb' : null },
                    { text: r.index_rb_prev || '-', alignment: 'center', fillColor: i % 2 === 0 ? '#f9fafb' : null },
                    { text: r.index_rb || '-', alignment: 'center', fillColor: i % 2 === 0 ? '#f9fafb' : null }
                ]);
            });

            pdfMake.createPdf({
                pageMargins: [20, 40, 20, 30],
                content: [
                    { text: 'Rincian Data Komparasi: ' + currentModalFilename.replace(/_/g, ' '), style: 'header' },
                    { text: 'Total: ' + currentModalData.length + ' instansi', style: 'subheader' },
                    {
                        table: {
                            headerRows: 1,
                            widths: [20, '*', 90, 60, 60],
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
                    header:    { fontSize: 14, bold: true, color: '#1f2937', margin: [0,0,0,4] },
                    subheader: { fontSize: 9,  color: '#6b7280', margin: [0,0,0,10] },
                },
                defaultStyle: { fontSize: 9, color: '#374151' }
            }).download(currentModalFilename + '.pdf');
        }

        function exportTableToExcel() {
            var selPeringkat = document.getElementById('filterPeringkat');
            var selGroup = document.getElementById('filterGroup');
            var selPredikat = document.getElementById('filterPredikat');
            
            var labelPeringkat = selPeringkat.value ? selPeringkat.options[selPeringkat.selectedIndex].text : '';
            var labelGroup = selGroup.value ? selGroup.options[selGroup.selectedIndex].text : '';
            var labelPredikat = selPredikat.value ? selPredikat.options[selPredikat.selectedIndex].text : '';
            
            var labelArr = [labelPeringkat, labelGroup, labelPredikat].filter(Boolean);
            var label = labelArr.length > 0 ? labelArr.join('_') : 'Semua';

            var rows    = dtTable.rows({ search: 'applied' }).data().toArray();
            var headers = ['No','Instansi Pemerintah','Grup Instansi','RB General','RB Tematik','Indeks RB','Predikat'];
            var csvRows = [headers.join(',')];

            rows.forEach(function(row, i) {
                var cols = [];
                for (var c = 0; c < 7; c++) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim().replace(/"/g, '""');
                    cols.push(c === 0 ? (i + 1) : ('"' + text + '"'));
                }
                csvRows.push(cols.join(','));
            });

            var blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href   = url;
            a.download = 'Evaluasi_RB_' + label.replace(/\s+/g,'_') + '.csv';
            a.click();
            URL.revokeObjectURL(url);
        }

        function exportTableToPDF() {
            var selPeringkat = document.getElementById('filterPeringkat');
            var selGroup = document.getElementById('filterGroup');
            var selPredikat = document.getElementById('filterPredikat');
            
            var labelPeringkat = selPeringkat.value ? selPeringkat.options[selPeringkat.selectedIndex].text : '';
            var labelGroup = selGroup.value ? selGroup.options[selGroup.selectedIndex].text : '';
            var labelPredikat = selPredikat.value ? 'Predikat ' + selPredikat.options[selPredikat.selectedIndex].text : '';
            
            var labelArr = [labelPeringkat, labelGroup, labelPredikat].filter(Boolean);
            var label = labelArr.length > 0 ? labelArr.join(', ') : 'Semua Instansi';

            var rows      = dtTable.rows({ search: 'applied' }).data().toArray();
            var tableBody = [[
                { text: 'No',               bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Instansi',         bold: true, color: 'white', fillColor: '#1f2937' },
                { text: 'Grup',            bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'RB General',       bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'RB Tematik',       bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Indeks RB',        bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                { text: 'Predikat',         bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
            ]];

            rows.forEach(function(row, i) {
                var cols = [];
                for (var c = 0; c < 7; c++) {
                    var tmp = document.createElement('div');
                    tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim();
                    cols.push({
                        text: c === 0 ? String(i + 1) : text,
                        alignment: (c === 0 || c >= 2) ? 'center' : 'left',
                        fillColor: i % 2 === 0 ? '#f9fafb' : null
                    });
                }
                tableBody.push(cols);
            });

            pdfMake.createPdf({
                pageOrientation: 'landscape',
                pageMargins: [20, 40, 20, 30],
                content: [
                    { text: 'Dashboard Evaluasi RB', style: 'header' },
                    { text: 'Filter: ' + label + '  |  Total: ' + rows.length + ' instansi', style: 'subheader' },
                    {
                        table: {
                            headerRows: 1,
                            widths: [20, '*', 75, 60, 60, 55, 50],
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
                    header:    { fontSize: 15, bold: true, color: '#1f2937', margin: [0,0,0,4] },
                    subheader: { fontSize: 9,  color: '#6b7280', margin: [0,0,0,10] },
                },
                defaultStyle: { fontSize: 7.5, color: '#374151' }
            }).download('Evaluasi_RB_' + label.replace(/,\s/g,'_') + '.pdf');
        }

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
                "columnDefs": [
                    {
                        "targets": 5,
                        "type": "num",
                        "render": function(data, type) {
                            if (type === 'sort' || type === 'type') {
                                var tmp = document.createElement('div');
                                tmp.innerHTML = data;
                                var val = parseFloat((tmp.textContent || tmp.innerText || '').trim().replace(',', '.'));
                                return isNaN(val) ? -999 : val;
                            }
                            return data;
                        }
                    }
                ],
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
        });
    </script>
@endpush