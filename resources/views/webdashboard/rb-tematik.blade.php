@extends('layout.rubick')
@section('title', 'Dashboard RB Tematik')

@section('content')
    @php
        // LOGIKA PERHITUNGAN DATA RB TEMATIK
        $temaDefinitions = [];
        foreach ($temas as $index => $tema) {
            $alias = 'tema' . ($index + 1);
            $temaDefinitions[] = [
                'alias' => $alias,
                'label' => 'Tema ' . ($index + 1),
                'name' => $tema->nama,
            ];
        }

        $temaLabels = array_column($temaDefinitions, 'label');
        $temaLegendLabels = array_map(fn($d) => $d['label'] . ': ' . $d['name'], $temaDefinitions);
        $palette = ['#f1c40f', '#b32d29', '#b3611f', '#b39450', '#a2a2a2', '#30d15b', '#2980b9', '#8e44ad', '#16a085', '#e67e22', '#2c3e50', '#95a5a6'];
        $temaColors = array_map(fn($i) => $palette[$i % count($palette)], array_keys($temaDefinitions));

        $temaCountsByGroup = [
            'kl' => array_fill(0, count($temaDefinitions), 0), 
            'provinsi' => array_fill(0, count($temaDefinitions), 0), 
            'kabupaten' => array_fill(0, count($temaDefinitions), 0)
        ];
        
        $groupTotals = [
            'kl' => ['yes' => 0, 'no' => 0],
            'provinsi' => ['yes' => 0, 'no' => 0],
            'kabupaten' => ['yes' => 0, 'no' => 0]
        ];

        $processedInstansis = [];

        foreach ($instansis as $instansi) {
            $temaData = $tematiks[$instansi->id] ?? null;
            
            // Pengelompokan Data
            $rawGroup = strtolower(trim($instansi->group));
            $groupKey = 'lain';
            if (in_array($rawGroup, ['kl', 'kementerian', 'lembaga'])) $groupKey = 'kl';
            elseif (in_array($rawGroup, ['kabupaten', 'kota'])) $groupKey = 'kabupaten';
            elseif ($rawGroup === 'provinsi') $groupKey = 'provinsi';

            $themesStatus = [];
            if ($temaData && $groupKey !== 'lain') {
                foreach ($temaDefinitions as $position => $definition) {
                    $alias = $definition['alias'];
                    $hasTheme = !empty($temaData->{$alias});
                    $themesStatus[$position] = $hasTheme;
                    if ($hasTheme) {
                        $temaCountsByGroup[$groupKey][$position]++;
                    }
                }
            } else {
                foreach ($temaDefinitions as $position => $definition) {
                    $themesStatus[$position] = false;
                }
            }

            // HITUNG KELENGKAPAN (FIX: Dikembalikan ke logika asli, tanpa mewajibkan capaian_output)
            $tematik = $temaData->tematik ?? '---';
            $permasalahan = $temaData->permasalahan ?? '---';
            $rencana_aksi = $temaData->rencana_aksi ?? '---';
            $capaian_output = $temaData->capaian_output ?? '---'; 
            
            // Logika asli kelengkapan
            $semua = $tematik === 'yes' && $permasalahan === 'yes' && $rencana_aksi === 'yes';
            $tema_id_count = $temaData->tema_id_count ?? '0';

            if ($groupKey !== 'lain') {
                if ($semua) $groupTotals[$groupKey]['yes']++;
                else $groupTotals[$groupKey]['no']++;
            }

            // Simpan Array Rapi Untuk DataTables & JS Modal
            $processedInstansis[] = [
                'id' => $instansi->id,
                'name' => $instansi->name,
                'group' => group_instansi($instansi->group),
                'group_key' => $groupKey,
                'tema_id_count' => $tema_id_count,
                'tematik' => $tematik === 'yes',
                'permasalahan' => $permasalahan === 'yes',
                'rencana_aksi' => $rencana_aksi === 'yes',
                'capaian_output' => $capaian_output === 'yes',
                'semua' => $semua,
                'themes' => $themesStatus,
                'detail_url' => url('/rencana_aksi/rb-tematik/rekap_data?instansi_id=' . $instansi->id)
            ];
        }

        $yes_kl = $groupTotals['kl']['yes']; $no_kl = $groupTotals['kl']['no'];
        $yes_prov = $groupTotals['provinsi']['yes']; $no_prov = $groupTotals['provinsi']['no'];
        $yes_kab = $groupTotals['kabupaten']['yes']; $no_kab = $groupTotals['kabupaten']['no'];
    @endphp

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-5">
        <h2 class="text-lg font-medium mr-auto">
            Dashboard <span style="color: #2563eb; font-weight: bold;">RB Tematik</span>
        </h2>
    </div>

    {{-- FILTER TAHUN & NAVIGASI --}}
    <div class="intro-y box p-5 shadow-sm border border-slate-200 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">
                <label for="tahun" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun Kegiatan <span class="text-red-500">*</span></label>
                <div class="w-40">
                    <select name="tahun" id="tahun" class="form-select border-gray-300 rounded-md shadow-sm w-full" onchange="this.form.submit()">
                        @foreach (tahun_tematik() as $k => $v)
                            <option value="{{ $k }}" {{ $k == $tahun ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="flex flex-wrap gap-2 mt-2 md:mt-0 justify-start md:justify-end">
                <a href="{{ url('webdashboard') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600 hover:bg-gray-200">Hasil Evaluasi</a>
                <a href="{{ route('webdashboard.rb-general.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center bg-gray-100 text-gray-600 hover:bg-gray-200">RB General</a>
                <a href="{{ route('webdashboard.rb-tematik.rencana-aksi') }}" class="px-4 py-2 rounded-md font-medium text-sm text-center" style="background-color: #dbeafe; color: #2563eb;">RB Tematik</a>
            </div>
        </div>

        <div class="flex gap-4 mt-5 pt-5 border-t border-slate-200/60">
            <a href="{{ route('webdashboard.rb-tematik.rencana-aksi') }}" 
               class="pb-3 text-sm font-bold border-b-2" 
               style="border-color: #2563eb; color: #2563eb;">
                Rencana Aksi
            </a>
            <a href="{{ url('/rencana_aksi/rb-tematik/capaian-output') }}" 
               class="pb-3 text-sm font-medium text-slate-500 hover:text-primary">
                Capaian Output
            </a>
        </div>
    </div>

    {{-- PIE CHARTS (STYLE RB GENERAL) --}}
    <div class="grid grid-cols-12 gap-6 mt-5">
        @php
            $pieData = [
                ['id' => 'pie-chart-prov',  'judul' => 'Provinsi',            'yes' => $yes_prov, 'no' => $no_prov, 'group' => 'provinsi'],
                ['id' => 'pie-chart-kl',    'judul' => 'Kementerian Lembaga', 'yes' => $yes_kl,   'no' => $no_kl,   'group' => 'kl'],
                ['id' => 'pie-chart-kab',   'judul' => 'Pemerintah Kab/Kota', 'yes' => $yes_kab,  'no' => $no_kab,  'group' => 'kabupaten'],
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
                        <span class="text-xs text-gray-600 font-medium">{{ $pie['yes'] }} Lengkap</span>
                    </div>
                    <div class="flex items-center gap-2 cursor-pointer"
                         onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'"
                         onclick="showPieModal('{{ $pie['group'] }}', '{{ $pie['judul'] }}', 'belum')">
                        <div style="width:12px; height:12px; border-radius:50%; background:#ef4444;"></div>
                        <span class="text-xs text-gray-600 font-medium">{{ $pie['no'] }} Belum</span>
                    </div>
                </div>
                
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

    {{-- BAR CHARTS (MURNI HTML STYLE HASIL EVALUASI - DISUSUN KE BAWAH) --}}
    <div class="intro-y mt-10 mb-3">
        <h2 class="text-lg font-medium">Dashboard RB Tematik Berdasarkan Tematik Permasalahan</h2>
    </div>

    <div class="flex flex-col gap-6 mt-5">
        @php
            $barGroups = [
                ['key' => 'provinsi',  'judul' => 'Provinsi'],
                ['key' => 'kl',        'judul' => 'Kementerian Lembaga'],
                ['key' => 'kabupaten', 'judul' => 'Pemerintah Kabupaten/Kota'],
            ];
        @endphp

        @foreach($barGroups as $bg)
            @php
                $counts = $temaCountsByGroup[$bg['key']] ?? [];
                $maxCount = max($counts ?: [0]);
                $maxCount = $maxCount > 0 ? $maxCount : 1;
                $totalGroup = array_sum($counts);
            @endphp
            <div class="intro-y box border border-gray-200 rounded-lg p-6 bg-white shadow-sm w-full">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <span class="font-bold text-gray-800 text-base">{{ $bg['judul'] }}</span>
                        <span class="text-xs text-gray-500 ml-2 font-medium">{{ $totalGroup }} Output</span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 180px; gap: 8px; padding: 10px 4px 0 4px; position: relative; border-bottom: 1px solid #e5e7eb;">
                    @foreach($temaDefinitions as $index => $tema)
                        @php
                            $jmlInstansi = $counts[$index] ?? 0;
                            // Menghitung persentase tinggi balok HTML
                            $tinggiPx = $jmlInstansi > 0 ? round(($jmlInstansi / $maxCount) * 150) : 0;
                            $hexAsli = $temaColors[$index] ?? '#cbd5e1';
                            $hexBackground = $jmlInstansi > 0 ? $hexAsli : 'transparent';
                            $minHeight = $jmlInstansi > 0 ? '4px' : '0px';
                        @endphp
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; flex: 1; position: relative;">
                            <div style="font-size: 12px; font-weight: bold; color: #4b5563; text-align: center; margin-bottom: 6px;">
                                {{ $jmlInstansi }}
                            </div>
                            <div class="chart-bar"
                                 style="width: 80%; max-width: 100px; background-color: {{ $hexBackground }}; height: {{ $tinggiPx }}px; border-radius: 4px 4px 0 0; min-height: {{ $minHeight }}; cursor: {{ $jmlInstansi > 0 ? 'pointer' : 'default' }}; transition: opacity 0.15s;"
                                 onmouseover="showTooltip(event, 'T{{ $index+1 }}', '{{ $jmlInstansi }}', '{{ $bg['judul'] }}')"
                                 onmouseout="hideTooltip()"
                                 onclick="{{ $jmlInstansi > 0 ? "openTemaModal('{$bg['key']}', '{$bg['judul']}', $index, '{$tema['name']}')" : "" }}">
                            </div>
                            <span style="position: absolute; bottom: -24px; font-size: 12px; font-weight: bold; color: #6b7280; white-space: nowrap;">T{{ $index+1 }}</span>
                        </div>
                    @endforeach
                </div>
                <div style="height: 30px;"></div>
            </div>
        @endforeach
    </div>

    {{-- Keterangan Tema (Legend) --}}
    <div class="intro-y box p-5 mt-5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <tbody>
                    <tr class="flex flex-wrap gap-x-6 gap-y-3">
                        @forelse ($temaLegendLabels as $legendIndex => $legend)
                            <td class="border-none p-0">
                                <div class="flex items-center text-xs text-slate-600 font-medium">
                                    <div class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $temaColors[$legendIndex] }}"></div>
                                    {{ $legend }}
                                </div>
                            </td>
                        @empty
                            <td class="text-center w-full text-slate-400 italic">Belum ada tema pada tahun terpilih.</td>
                        @endforelse
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABEL DATA UTAMA --}}
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h3 class="font-medium text-base mr-auto">Hasil Semua Instansi Pemerintah - RB Tematik</h3>
        </div>
        
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

        <div class="table-container overflow-x-auto">
            <table id="perencanaan" class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="w-10 text-center">No.</th>
                        <th>Instansi Pemerintah</th>
                        <th class="text-center">Grup Instansi</th>
                        <th class="text-center">Jumlah Tema</th>
                        <th class="text-center">Tema & Sasaran</th>
                        <th class="text-center">Permasalahan</th>
                        <th class="text-center">Rencana Aksi</th>
                        <th class="text-center">Capaian Output</th>
                        <th class="text-center">Semua</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($processedInstansis as $key => $inst)
                        <tr>
                            <td class="text-center text-gray-600">{{ $key + 1 }}</td>
                            <td class="font-medium text-gray-800">
                                <a href="{{ $inst['detail_url'] }}" style="color:#2563eb; font-weight:600; text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    {{ $inst['name'] }}
                                </a>
                            </td>
                            <td class="text-center text-gray-600">{{ $inst['group'] }}</td>
                            <td class="text-center font-bold text-gray-800">{{ $inst['tema_id_count'] }}</td>
                            <td class="text-center">@if($inst['tematik']) <span style="color:#10b981; font-size:20px; font-weight:bold;">✔</span> @else <span style="color:#9ca3af;">---</span> @endif</td>
                            <td class="text-center">@if($inst['permasalahan']) <span style="color:#10b981; font-size:20px; font-weight:bold;">✔</span> @else <span style="color:#9ca3af;">---</span> @endif</td>
                            <td class="text-center">@if($inst['rencana_aksi']) <span style="color:#10b981; font-size:20px; font-weight:bold;">✔</span> @else <span style="color:#9ca3af;">---</span> @endif</td>
                            <td class="text-center">@if($inst['capaian_output']) <span style="color:#10b981; font-size:20px; font-weight:bold;">✔</span> @else <span style="color:#9ca3af;">---</span> @endif</td>
                            <td class="text-center">@if($inst['semua']) <span style="color:#10b981; font-size:20px; font-weight:bold;">✔</span> @else <span style="color:#9ca3af;">---</span> @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tooltip & Modals Component (MURNI RB GENERAL) --}}
    <div id="chart-tooltip" style="display:none; position:fixed; z-index:9999; background:#1f2937; color:white; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; pointer-events:none; white-space:nowrap; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>

    <div id="barModal" style="display:none; position:fixed; inset:0; z-index:99998; background:rgba(0,0,0,0.5);" onclick="closeBarModal(event)">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:white; border-radius:12px; width:90%; max-width:900px; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
            
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
            
            <div id="barModalContent" style="padding:0 20px 16px 20px; overflow-y:auto; flex:1;"></div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .chart-bar:hover { opacity: 0.75 !important; }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; display: flex; align-items: center; }
    .dataTables_wrapper .dataTables_length { float: left; }
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; text-align: center; clear: both; padding-top: 10px; }
    .table th { background-color: #1f2937; color: white; font-weight: 600; border: 1px solid #374151; }
    .table td { border: 1px solid #e5e7eb; vertical-align: middle; }
    .table { border-collapse: collapse; border: 1px solid #e5e7eb; width: 100%; }
    
    .dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.375rem 0.75rem; }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.375rem 2rem 0.375rem 0.75rem; min-width: 85px; margin: 0 0.5rem; }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; border-radius: 0.375rem !important; padding: 0.5rem 0.75rem !important; margin: 0 0.125rem !important; background: white !important; color: #374151 !important; cursor: pointer !important; display: inline-block !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #3b82f6 !important; border-color: #9ca3af !important; color: #374151 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #3b82f6 !important; border-color: #3b82f6 !important; color: white !important; font-weight: 600 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { background: #f9fafb !important; color: #9ca3af !important; cursor: not-allowed !important; border-color: #e5e7eb !important; }
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
        var allRows = @json($processedInstansis);
        var currentModalDataSudah = [];
        var currentModalDataBelum = [];
        var currentModalFilter = '';
        var currentModalFilename = '';

        // FUNGSI TOOLTIP
        function showTooltip(e, predikat, jumlah, judul) {
            if (jumlah == 0) return;
            var tip = document.getElementById('chart-tooltip');
            tip.innerHTML = '<span style="opacity:0.7;">'+judul+'</span> &nbsp;|&nbsp; <strong>'+predikat+'</strong> &nbsp;: &nbsp;<strong>'+jumlah+' Output</strong>';
            tip.style.display = 'block';
            tip.style.left = (e.clientX + 12) + 'px';
            tip.style.top = (e.clientY - 36) + 'px';
        }
        function hideTooltip() { document.getElementById('chart-tooltip').style.display = 'none'; }
        document.addEventListener('mousemove', function(e) {
            var tip = document.getElementById('chart-tooltip');
            if (tip.style.display === 'block') { tip.style.left = (e.clientX + 12) + 'px'; tip.style.top = (e.clientY - 36) + 'px'; }
        });

        // INIT PIE CHART.JS (Style RB General)
        function buatPieChart(canvasId, yes, no, groupKey, judul) {
            var ctx = document.getElementById(canvasId);
            if (!ctx) return;
            new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: [yes + ' Lengkap', no + ' Belum'],
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

        @foreach($pieData as $pie)
            buatPieChart('{{ $pie['id'] }}', {{ $pie['yes'] }}, {{ $pie['no'] }}, '{{ $pie['group'] }}', '{{ $pie['judul'] }}');
        @endforeach

        // FUNGSI HELPER: Cetak HTML Tabel Modal Dinamis
        function generateTableHTML(title, data, color) {
            if (data.length === 0) return '';
            
            var html = '<h4 style="margin: 16px 0 8px 0; font-size: 14px; font-weight: bold; color: '+color+'; border-bottom: 2px solid '+color+'; display:inline-block; padding-bottom:4px;">' + title + ' (' + data.length + ' Instansi)</h4>';
            html += '<table class="table table-bordered table-striped table-hover w-full" cellspacing="0" width="100%" style="margin-bottom: 24px; font-size: 13px;">';
            html += '<thead><tr>';
            var thStyle = 'position: sticky; top: 0; background: #f8fafc; padding: 12px; font-weight: 600; color: #475569; z-index: 20; box-shadow: 0 1px 0 #cbd5e1;';
            html += '<th class="w-10 text-center" style="'+thStyle+'">No</th>';
            html += '<th style="'+thStyle+'">Nama Instansi</th>';
            html += '<th class="text-center" style="'+thStyle+'">Grup</th>';
            html += '<th class="text-center" style="'+thStyle+'">Jml Tema</th>';
            html += '<th class="text-center" style="'+thStyle+'">Tema & Sasaran</th>';
            html += '<th class="text-center" style="'+thStyle+'">Permasalahan</th>';
            html += '<th class="text-center" style="'+thStyle+'">Rencana Aksi</th>';
            html += '<th class="text-center" style="'+thStyle+'">Capaian Output</th>';
            html += '<th class="text-center" style="'+thStyle+'">Semua</th>';
            html += '</tr></thead><tbody>';
            
            var check = '<span style="color:#10b981; font-size:18px; font-weight:700;">✔</span>';
            var cross = '<span style="color:#9ca3af;">---</span>';

            data.forEach(function(r, i) {
                html += '<tr>';
                html += '<td class="text-center text-gray-600">' + (i+1) + '</td>';
                html += '<td class="font-medium text-gray-800"><a href="' + (r.detail_url) + '" style="color:#2563eb; font-weight:600; text-decoration:none;" onmouseover="this.style.textDecoration=\'underline\'" onmouseout="this.style.textDecoration=\'none\'">' + r.name + '</a></td>';
                html += '<td class="text-center text-gray-600">' + (r.group||'-') + '</td>';
                html += '<td class="text-center font-bold text-gray-800">' + r.tema_id_count + '</td>';
                html += '<td class="text-center">' + (r.tematik ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.permasalahan ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.rencana_aksi ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.capaian_output ? check : cross) + '</td>';
                html += '<td class="text-center">' + (r.semua ? check : cross) + '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            return html;
        }

        // FUNGSI BUKA MODAL DARI PIE CHART
        function showPieModal(group, judul, filter) {
            var fSudah = [];
            var fBelum = [];

            allRows.forEach(function(r) {
                if (group !== r.group_key) return; 
                if (r.semua) fSudah.push(r);
                else fBelum.push(r);
            });

            currentModalDataSudah = fSudah;
            currentModalDataBelum = fBelum;
            currentModalFilter    = filter; 

            var color = filter === 'sudah' ? '#10b981' : filter === 'belum' ? '#ef4444' : '#2563eb';
            var filterLabel = filter === 'sudah' ? 'Sudah Lengkap' : filter === 'belum' ? 'Belum Lengkap' : 'Semua Status';
            var countTotal = filter === 'sudah' ? fSudah.length : filter === 'belum' ? fBelum.length : (fSudah.length + fBelum.length);

            currentModalFilename = 'Data_RB_Tematik_' + filterLabel.replace(/\s+/g,'_') + '_' + judul.replace(/\s+/g,'_');

            document.getElementById('barModalHeader').style.backgroundColor = color + '18';
            document.getElementById('barModalTitle').innerHTML = '<span style="padding:2px 10px; border-radius:20px; font-size:13px; background:'+color+'22; color:'+color+';">'+filterLabel+'</span>' + ' &nbsp;&mdash;&nbsp; ' + judul;
            document.getElementById('barModalSubtitle').textContent = countTotal + ' instansi ditemukan';

            var html = '';
            if (filter === 'semua' || filter === 'sudah') html += generateTableHTML(' Sudah Lengkap', fSudah, '#10b981');
            if (filter === 'semua' || filter === 'belum') html += generateTableHTML(' Belum Lengkap', fBelum, '#ef4444');
            if (html === '') html = '<div style="text-align:center; padding:40px; color:#9ca3af;">Data tidak ditemukan</div>';

            document.getElementById('barModalContent').innerHTML = html;
            document.getElementById('barModal').style.display = 'block';
        }

        // FUNGSI BUKA MODAL DARI BAR CHART TEMA
        function openTemaModal(group, judul, indexTema, namaTema) {
            var fTerkait = [];
            allRows.forEach(function(r) {
                if (group !== r.group_key) return;
                // Cek apakah instansi ini punya tema indeks ke-X
                if (r.themes[indexTema] === true) {
                    fTerkait.push(r);
                }
            });

            currentModalDataSudah = fTerkait; 
            currentModalFilter    = 'sudah'; 
            currentModalFilename  = 'Data_Tema_' + (indexTema+1) + '_' + judul.replace(/\s+/g,'_');

            var color = '#eab308'; // warna kuning/tema
            document.getElementById('barModalHeader').style.backgroundColor = color + '18';
            document.getElementById('barModalTitle').innerHTML = '<span style="padding:2px 10px; border-radius:20px; font-size:13px; background:'+color+'22; color:#a16207;">Tema '+(indexTema+1)+'</span>' + ' &nbsp;&mdash;&nbsp; ' + judul;
            document.getElementById('barModalSubtitle').textContent = namaTema;

            var html = generateTableHTML(' Data Output Terkait', fTerkait, '#a16207');
            if (html === '') html = '<div style="text-align:center; padding:40px; color:#9ca3af;">Data tidak ditemukan</div>';

            document.getElementById('barModalContent').innerHTML = html;
            document.getElementById('barModal').style.display = 'block';
        }

        function closeBarModal() {
            document.getElementById('barModal').style.display = 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.getElementById('barModal').style.display = 'none';
        });

        // INIT DATATABLES
        $(document).ready(function() {
            $('#perencanaan').DataTable({
                "scrollX": true,
                dom: 'lfrtip',
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                ordering: true,
                language: {
                    search: "Cari Instansi:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: { first: "Pertama", last: "Terakhir", previous: "Sebelumnya", next: "Selanjutnya" }
                }
            });
        });

        // EXPORT EXCEL MODAL
        function exportModalToExcel() {
            var csvRows = [];
            var headers = ['No', 'Nama Instansi', 'Grup', 'Jumlah Tema', 'Tema & Sasaran', 'Permasalahan', 'Rencana Aksi', 'Capaian Output', 'Semua'];
            
            function pushDataToExcel(title, data) {
                if (data.length === 0) return;
                csvRows.push([title.toUpperCase()]);
                csvRows.push(headers.join(','));
                data.forEach(function(r, i) {
                    var name = (r.name || '').replace(/"/g, '""');
                    var group = (r.group || '-').replace(/"/g, '""');
                    csvRows.push([
                        (i+1), '"'+name+'"', '"'+group+'"', r.tema_id_count, 
                        (r.tematik ? '✔' : '-'), (r.permasalahan ? '✔' : '-'), 
                        (r.rencana_aksi ? '✔' : '-'), (r.capaian_output ? '✔' : '-'), (r.semua ? '✔' : '-')
                    ].join(','));
                });
                csvRows.push([]); 
            }

            if (currentModalFilter === 'semua' || currentModalFilter === 'sudah') pushDataToExcel('DATA INSTANSI', currentModalDataSudah);
            if (currentModalFilter === 'semua' || currentModalFilter === 'belum') pushDataToExcel('BELUM LENGKAP', currentModalDataBelum);

            if (csvRows.length === 0) return;
            var blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a'); a.href = url; a.download = currentModalFilename + '.csv';
            document.body.appendChild(a); a.click();
            setTimeout(function() { document.body.removeChild(a); window.URL.revokeObjectURL(url); }, 0);
        }

        // EXPORT PDF MODAL
        function exportModalToPDF() {
            var contentPDF = [{ text: 'Rincian Data: ' + currentModalFilename.replace(/_/g, ' '), style: 'header' }];

            function getCheckIcon(bg) {
                return { alignment: 'center', fillColor: bg, margin: [0, 4, 0, 0], canvas: [{ type: 'polyline', lineWidth: 2, lineColor: '#10b981', lineCap: 'round', lineJoin: 'round', points: [{x: 0, y: 5}, {x: 4, y: 9}, {x: 10, y: 1}] }] };
            }
            function getCrossText(bg) { return { text: '-', alignment: 'center', color: '#9ca3af', fillColor: bg }; }

            function pushDataToPDF(title, data, color) {
                if (data.length === 0) return;
                contentPDF.push({ text: title + ' (' + data.length + ' Instansi)', style: 'tableTitle', color: color });
                var tableBody = [[
                    { text: 'No', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Nama Instansi', bold: true, color: 'white', fillColor: '#1f2937' },
                    { text: 'Grup', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Jml Tema', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Tema & Sasaran', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Permasalahan', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Rencana Aksi', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Output', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' },
                    { text: 'Semua', bold: true, alignment: 'center', color: 'white', fillColor: '#1f2937' }
                ]];

                data.forEach(function(r, i) {
                    var bg = i % 2 === 0 ? '#f9fafb' : null;
                    tableBody.push([
                        { text: String(i + 1), alignment: 'center', fillColor: bg },
                        { text: r.name || '-', alignment: 'left', fillColor: bg },
                        { text: r.group || '-', alignment: 'center', fillColor: bg },
                        { text: String(r.tema_id_count), alignment: 'center', fillColor: bg, bold: true },
                        r.tematik ? getCheckIcon(bg) : getCrossText(bg),
                        r.permasalahan ? getCheckIcon(bg) : getCrossText(bg),
                        r.rencana_aksi ? getCheckIcon(bg) : getCrossText(bg),
                        r.capaian_output ? getCheckIcon(bg) : getCrossText(bg),
                        r.semua ? getCheckIcon(bg) : getCrossText(bg)
                    ]);
                });

                contentPDF.push({
                    table: { headerRows: 1, widths: [15, '*', 50, 30, 45, 45, 45, 40, 35], body: tableBody },
                    layout: { hLineWidth: ()=>0.5, vLineWidth: ()=>0.5, hLineColor: ()=>'#e5e7eb', vLineColor: ()=>'#e5e7eb' }, margin: [0, 0, 0, 20] 
                });
            }

            if (currentModalFilter === 'semua' || currentModalFilter === 'sudah') pushDataToPDF('DATA INSTANSI', currentModalDataSudah, '#059669');
            if (currentModalFilter === 'semua' || currentModalFilter === 'belum') pushDataToPDF('BELUM LENGKAP', currentModalDataBelum, '#dc2626');

            if (contentPDF.length === 1) return; 
            pdfMake.createPdf({
                pageOrientation: 'landscape', pageMargins: [20, 40, 20, 30], content: contentPDF,
                styles: { header: { fontSize: 14, bold: true, color: '#1f2937', marginBottom: 15 }, tableTitle:{ fontSize: 11, bold: true, marginBottom: 6 } }, defaultStyle: { fontSize: 8, color: '#374151' }
            }).download(currentModalFilename + '.pdf');
        }

        // EXPORT EXCEL TABEL UTAMA
        function exportTableToExcel() {
            var rows = $('#perencanaan').DataTable().rows({ search: 'applied' }).data().toArray();
            var csvRows = [['No','Instansi Pemerintah','Grup Instansi','Jumlah Tema','Tema & Sasaran','Permasalahan','Rencana Aksi','Capaian Output','Semua'].join(',')];
            rows.forEach((row, i) => {
                var cols = [];
                for (var c = 0; c < 9; c++) {
                    var tmp = document.createElement('div'); tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim();
                    if(text === '✔') text = 'YA';
                    if(text === 'LENGKAP') text = 'YA';
                    if(text === 'BELUM') text = '-';
                    cols.push(c === 0 ? (i+1) : '"' + text.replace(/"/g, '""') + '"');
                }
                csvRows.push(cols.join(','));
            });
            var blob = new Blob(['\uFEFF' + csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a'); a.href = url; a.download = 'RB_Tematik_Data_{{ $tahun }}.csv'; a.click();
        }

        // EXPORT PDF TABEL UTAMA
        function exportTableToPDF() {
            var rows = $('#perencanaan').DataTable().rows({ search: 'applied' }).data().toArray();
            var tableBody = [[
                {text:'No',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Instansi',bold:true,fillColor:'#1f2937',color:'white'},
                {text:'Grup',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Jml Tema',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Tema/Sasaran',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Masalah',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Renaksi',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Output',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'},
                {text:'Status',bold:true,fillColor:'#1f2937',color:'white',alignment:'center'}
            ]];
            
            rows.forEach((row, i) => {
                var bg = i % 2 === 0 ? '#f9fafb' : null;
                var cols = [];
                for(var c=0; c<9; c++){
                    var tmp = document.createElement('div'); tmp.innerHTML = row[c];
                    var text = (tmp.textContent || tmp.innerText || '').trim();
                    if(text === '✔' || text === 'LENGKAP') {
                        cols.push({ alignment:'center', fillColor:bg, margin:[0,2,0,0], canvas:[{type:'polyline', lineWidth:2, lineColor:'#10b981', lineCap:'round', lineJoin:'round', points:[{x:0,y:4}, {x:3,y:8}, {x:8,y:1}]}]});
                    } else if (text === '---' || text === 'BELUM') {
                        cols.push({ text:'-', alignment:'center', color:'#9ca3af', fillColor:bg });
                    } else {
                        cols.push({ text: c===0 ? String(i+1) : text, alignment: (c===1)?'left':'center', fillColor:bg, color:'#374151' });
                    }
                }
                tableBody.push(cols);
            });

            pdfMake.createPdf({
                pageOrientation: 'landscape', pageMargins: [20,40,20,30],
                content: [
                    { text: 'Data Dashboard RB Tematik ({{ $tahun }})', style: 'header' },
                    { table: { headerRows: 1, widths: [15,'*',55,30,45,45,40,40,35], body: tableBody }, layout: { hLineWidth: ()=>0.5, vLineWidth: ()=>0.5, hLineColor: ()=>'#e5e7eb', vLineColor: ()=>'#e5e7eb' } }
                ],
                styles: { header: { fontSize: 14, bold: true, color: '#1f2937', marginBottom: 10 } }, defaultStyle: { fontSize: 7.5 }
            }).download('RB_Tematik_Data_{{ $tahun }}.pdf');
        }
    </script>
@endpush