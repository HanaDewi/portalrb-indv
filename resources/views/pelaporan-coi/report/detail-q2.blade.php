@extends('layout.rubick')
@section('title', 'Detail Modul COI - Q2')

@php
    $groupFilter = request('group');
    $filtered = $groupFilter
        ? $records->filter(fn($item) => $item->instansi?->group === $groupFilter)
        : $records;
@endphp

@section('content')
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <div class="mr-auto">
                    <div class="text-base font-bold">Detail Modul COI</div>
                    <div class="text-slate-500 text-sm">{{ $questionLabel }} | Jawaban: Tidak</div>
                    @if ($groupFilter)
                        <div class="text-xs text-slate-500 mt-1">Filter group: {{ ucfirst($groupFilter) }}</div>
                    @endif
                </div>
                <a href="{{ url('evaluasi/modul-coi') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="w-12 text-center">No.</th>
                                <th>Instansi</th>
                                <th>2.1. Menyusun revisi?</th>
                                <th class="text-center w-16">Ya</th>
                                <th class="text-center w-16">Tidak</th>
                                <th>2.1.1. Rencana penyesuaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($filtered as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->instansi->name ?? $item->instansi->nama_instansi ?? '-' }}</td>
                                    <td>{{ $item->q21_susun_revisi === null ? '-' : ($item->q21_susun_revisi ? 'Ya' : 'Tidak') }}</td>
                                    <td class="text-center">{!! $item->q21_susun_revisi ? '&#10003;' : '' !!}</td>
                                    <td class="text-center">{!! $item->q21_susun_revisi === 0 ? '&#10003;' : '' !!}</td>
                                    <td>{{ $item->q21_susun_revisi === 0 ? ($item->q211_rencana_penyesuaian ?? '-') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-slate-500">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
