@extends('layout.rubick')
@section('content')
<div style="padding: 24px; font-family: 'Inter', sans-serif; background-color: #f8fafc; min-height: 100vh;">
    
    {{-- Header & Button Ekspor --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #1e293b;">Data Respons</h2>
            <p style="color: #64748b; font-size: 14px;">{{ $totalResponses }} respons masuk — Survei Kepatuhan COI</p>
        </div>
        <button style="background-color: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Ekspor CSV
        </button>
    </div>
    
    {{-- TAB NAVIGASI ADMIN (Dibalik: Respons Aktif) --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div style="display: flex; gap: 8px; background: #e2e8f0; padding: 4px; border-radius: 10px; width: fit-content;">
                {{-- Tab Editor (Sekarang Muted) --}}
                <a href="{{ route('coi.admin.index') }}" style="padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; background: transparent; color: #64748b; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editor Formulir
                </a>
                {{-- Tab Respons (Sekarang Aktif/Putih) --}}
                <a href="{{ route('coi.admin.responses') }}" style="padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; background: white; color: #1e293b; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Data Respons
                </a>
            </div>

            {{-- Quick Stats Card (Kanan) --}}
            <div style="background: white; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <div style="background: #eff6ff; padding: 6px; border-radius: 8px; color: #2563eb; display: flex;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p style="margin: 0; font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Respons Masuk</p>
                    <h5 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;">{{ $totalResponses }} <span style="font-size: 12px; color: #94a3b8; font-weight: 400; margin-left: 2px;">Instansi</span></h5>
                </div>
            </div>
        </div>

    {{-- Statistik Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
            <div style="background: #eff6ff; padding: 12px; border-radius: 12px; color: #2563eb;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg></div>
            <div><p style="color: #64748b; font-size: 13px; margin: 0;">Total Respons</p><h4 style="font-size: 20px; font-weight: 700; margin: 0;">{{ $totalResponses }}</h4></div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
            <div style="background: #ecfdf5; padding: 12px; border-radius: 12px; color: #10b981;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            <div><p style="color: #64748b; font-size: 13px; margin: 0;">Hari Ini</p><h4 style="font-size: 20px; font-weight: 700; margin: 0;">{{ $todayResponses }}</h4></div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px;">
            <div style="background: #fdf2f8; padding: 12px; border-radius: 12px; color: #db2777;"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p style="color: #64748b; font-size: 13px; margin: 0;">Tingkat Selesai</p><h4 style="font-size: 20px; font-weight: 700; margin: 0;">{{ number_format($completionRate, 1) }}%</h4></div>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display: flex; gap: 8px; background: #e2e8f0; padding: 4px; border-radius: 10px; width: fit-content; margin-bottom: 24px;">
        <button onclick="switchTab('tabel')" id="btn-tabel" style="padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; background: white; color: #1e293b;">Tabel Respons</button>
        <button onclick="switchTab('ringkasan')" id="btn-ringkasan" style="padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; background: transparent; color: #64748b;">Ringkasan</button>
    </div>

    {{-- Content: TABEL --}}
    <div id="tab-tabel" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 16px 24px; font-size: 13px; color: #64748b; font-weight: 600;">RESPONDEN</th>
                    <th style="padding: 16px 24px; font-size: 13px; color: #64748b; font-weight: 600;">WAKTU</th>
                    <th style="padding: 16px 24px; font-size: 13px; color: #64748b; font-weight: 600; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tableData as $row)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px 24px;">
                        <div style="font-weight: 600; color: #1e293b;">{{ $row->instansi->nama ?? 'Instansi Anonim' }}</div>
                        <div style="font-size: 12px; color: #94a3b8;">{{ $row->instansi->email ?? '' }}</div>
                    </td>
                    <td style="padding: 16px 24px; color: #64748b; font-size: 14px;">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                    <td style="padding: 16px 24px; text-align: right;">
                        <button style="background: none; border: none; color: #94a3b8; cursor: pointer;"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Content: RINGKASAN (Grafik) --}}
    <div id="tab-ringkasan" style="display: none; gap: 24px; flex-direction: column;">
        @foreach($summary as $s)
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; position: relative;">
            
            {{-- TOMBOL AKSI --}}
            <div style="position: absolute; top: 20px; right: 20px; display: flex; gap: 8px;">
                <a href="{{ route('coi.admin.index') }}" title="Edit di Editor" style="padding: 6px; border-radius: 8px; color: #64748b; border: 1px solid #e2e8f0; background: white; display: flex; align-items: center; transition: all 0.2s;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form action="{{ route('coi.admin.question.destroy', $s['id']) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini? Semua jawaban yang sudah masuk untuk soal ini juga akan terhapus!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 6px; border-radius: 8px; color: #ef4444; background: #fef2f2; border: 1px solid #fee2e2; cursor: pointer; display: flex; align-items: center; transition: all 0.2s;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
            <h5 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #1e293b; padding-right: 80px;">{{ $s['question'] }}</h5>
            <p style="font-size: 12px; color: #94a3b8; margin-bottom: 20px;">{{ $s['total'] }} jawaban — {{ ucwords(str_replace('_', ' ', $s['type'])) }}</p>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($s['counts'] as $label => $count)
                @php $perc = $s['total'] > 0 ? ($count / $s['total']) * 100 : 0; @endphp
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 14px;">
                        <span style="color: #475569;">{{ $label }}</span>
                        <span style="font-weight: 600;">{{ $count }} ({{ number_format($perc, 0) }}%)</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden;">
                        <div style="width: {{ $perc }}%; height: 100%; background: #2563eb; border-radius: 99px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function switchTab(tab) {
        const btnTabel = document.getElementById('btn-tabel');
        const btnRingkasan = document.getElementById('btn-ringkasan');
        const tabTabel = document.getElementById('tab-tabel');
        const tabRingkasan = document.getElementById('tab-ringkasan');

        if (tab === 'tabel') {
            tabTabel.style.display = 'block';
            tabRingkasan.style.display = 'none';
            btnTabel.style.background = 'white'; btnTabel.style.color = '#1e293b';
            btnRingkasan.style.background = 'transparent'; btnRingkasan.style.color = '#64748b';
        } else {
            tabTabel.style.display = 'none';
            tabRingkasan.style.display = 'flex';
            btnRingkasan.style.background = 'white'; btnRingkasan.style.color = '#1e293b';
            btnTabel.style.background = 'transparent'; btnTabel.style.color = '#64748b';
        }
    }
</script>
@endsection