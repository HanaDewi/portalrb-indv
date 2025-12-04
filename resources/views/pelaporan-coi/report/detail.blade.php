@extends('layout.rubick')
@section('title', 'Detail Modul COI')

@php
    $answerLabel = $answer ? 'Ya' : 'Tidak';
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
                    <div class="text-slate-500 text-sm">{{ $questionLabel }} | Jawaban: {{ $answerLabel }}</div>
                    @if ($groupFilter)
                        <div class="text-xs text-slate-500 mt-1">Filter group: {{ ucfirst($groupFilter) }}</div>
                    @endif
                </div>
                <a href="{{ url('evaluasi/modul-coi') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
            <div class="p-5">
                <div class="table-responsive">
                    @if (!$child)
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="w-12 text-center">No.</th>
                                    <th>Instansi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filtered as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->instansi->name ?? $item->instansi->nama_instansi ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-slate-500">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif ($questionKey === 'q6_pencatatan_register' && $answer == 1)
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="w-12 text-center">No.</th>
                                    <th>Instansi</th>
                                    <th>Total ASN Wajib Melaporkan</th>
                                    <th>Total ASN Telah Melaporkan</th>
                                    <th>% Sudah Melapor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filtered as $item)
                                    @php
                                        $wajib = (int) ($item->q61_total_wajib ?? 0);
                                        $lapor = (int) ($item->q62_total_lapor ?? 0);
                                        $percent = $wajib > 0 ? round(($lapor / $wajib) * 100, 2) : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->instansi->name ?? $item->instansi->nama_instansi ?? '-' }}</td>
                                        <td>{{ $item->q61_total_wajib ?? '-' }}</td>
                                        <td>{{ $item->q62_total_lapor ?? '-' }}</td>
                                        <td>{{ $percent }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="w-12 text-center">No.</th>
                                    <th>Instansi</th>
                                    <th>{{ $child['label'] }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filtered as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->instansi->name ?? $item->instansi->nama_instansi ?? '-' }}</td>
                                        <td>
                                            @php
                                                $val = $item->{$child['field']};
                                            @endphp
                                            @if ($child['field'] === 'q51_url_sistem' && $val)
                                                <a href="{{ $val }}" target="_blank" class="text-primary underline">{{ $val }}</a>
                                            @elseif (is_bool($val))
                                                {!! $val ? '<span class=\"text-success\">&#10003;</span>' : '<span class=\"text-danger\">&#10007;</span>' !!}
                                            @else
                                                {{ $val ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-slate-500">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
