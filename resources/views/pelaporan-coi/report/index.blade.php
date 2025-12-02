@extends('layout.rubick')
@section('title', 'Modul COI')

@section('content')
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <div>
                    <div class="text-base font-bold">Modul COI</div>
                    <div class="text-slate-500 text-sm">Rekap jawaban Pelaporan COI per level instansi</div>
                </div>
            </div>
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2" class="text-center w-12">No.</th>
                                <th rowspan="2" class="text-center">Pertanyaan</th>
                                <th colspan="2" class="text-center">KL</th>
                                <th colspan="2" class="text-center">Provinsi</th>
                                <th colspan="2" class="text-center">Kabupaten/Kota</th>
                            </tr>
                            <tr>
                                <th class="text-center w-24">Ya</th>
                                <th class="text-center w-24">Tidak</th>
                                <th class="text-center w-24">Ya</th>
                                <th class="text-center w-24">Tidak</th>
                                <th class="text-center w-28">Ya</th>
                                <th class="text-center w-28">Tidak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $key => $row)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $row['label'] }}</td>
                                    @php
                                        $links = [
                                            ['group' => 'kl', 'ans' => 1, 'label' => $row['counts']['kl']['yes']],
                                            ['group' => 'kl', 'ans' => 0, 'label' => $row['counts']['kl']['no']],
                                            ['group' => 'provinsi', 'ans' => 1, 'label' => $row['counts']['provinsi']['yes']],
                                            ['group' => 'provinsi', 'ans' => 0, 'label' => $row['counts']['provinsi']['no']],
                                            ['group' => 'kabupaten', 'ans' => 1, 'label' => $row['counts']['kabupaten']['yes']],
                                            ['group' => 'kabupaten', 'ans' => 0, 'label' => $row['counts']['kabupaten']['no']],
                                        ];
                                    @endphp
                                    @foreach ($links as $lnk)
                                        @php
                                            $url =
                                                $key === 'q2_selaras_permepan' && $lnk['ans'] == 0
                                                    ? url("modul-coi/{$key}/lanjutan") . '?group=' . $lnk['group']
                                                    : url("modul-coi/{$key}/{$lnk['ans']}") . '?group=' . $lnk['group'];
                                        @endphp
                                        <td class="text-center">
                                            <a href="{{ $url }}" class="text-primary font-semibold underline">
                                                {{ $lnk['label'] }}
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
