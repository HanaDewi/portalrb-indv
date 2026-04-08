@extends('lhkan.layout.lhkan_layout')

@section('title', 'Detail LHKAN')

@push('css')
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="mt-8 space-y-5">
    <!-- Header Section -->
    <div class="flex justify-between md:flex-row md:items-center gap-3 mb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail LHKAN</h1>
            <div class="text-sm text-slate-500 mt-1">Informasi lengkap pelaporan LHKAN Instansi</div>
        </div>
        <div>
            <x-bladewind::button color="gray" has_icon="true" icon="arrow-left" onclick="window.location.href='{{ route('lhkan.dashboard') }}'">
                Kembali
            </x-bladewind::button>
        </div>
    </div>

    <!-- Overview & Status -->
    <x-bladewind::card>
        <div class="flex justify-between items-center border-b border-slate-200 pb-4 mb-4">
            <h5 class="text-base font-semibold text-slate-800 m-0">Informasi Instansi & Periode</h5>
            <div>
                @switch($submission->status)
                    @case('draft')
                        <x-bladewind::tag label="Draft" color="gray" />
                        @break
                    @case('submitted')
                        <x-bladewind::tag label="Submitted" color="blue" />
                        @break
                    @case('approved')
                        <x-bladewind::tag label="Approved" color="green" />
                        @break
                    @case('rejected')
                        <x-bladewind::tag label="Rejected" color="red" />
                        @break
                    @case('edit_requested')
                        <x-bladewind::tag label="Menunggu Persetujuan Edit" color="yellow" />
                        @break
                    @case('edit_approved')
                        <x-bladewind::tag label="Disetujui Edit" color="cyan" />
                        @break
                    @default
                        <x-bladewind::tag label="{{ strtoupper($submission->status) }}" color="gray" />
                @endswitch
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1 font-semibold">Nama Instansi</div>
                <div class="font-semibold text-slate-800 text-sm mt-1">{{ $submission->instansi->name ?? '-' }}</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1 font-semibold">Periode</div>
                <div class="font-semibold text-slate-800 text-sm mt-1">{{ $submission->period->nama ?? '-' }} ({{ $submission->period->tahun ?? '-' }})</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1 font-semibold">Tanggal Submit</div>
                <div class="font-semibold text-slate-800 text-sm mt-1">{{ $submission->submitted_at ? $submission->submitted_at->format('d F Y H:i:s') : '-' }}</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1 font-semibold">Terakhir Diperbarui</div>
                <div class="font-semibold text-slate-800 text-sm mt-1">{{ $submission->updated_at ? $submission->updated_at->format('d F Y H:i:s') : '-' }}</div>
            </div>
        </div>
    </x-bladewind::card>

    <div class="my-4"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- left column (Data & document) -->
        <div class="lg:col-span-2 space-y-5">
            <x-bladewind::card>
                <h5 class="text-base font-semibold text-slate-800 border-b border-slate-200 pb-4 mb-4 m-0">Data Rekapitulasi LHKAN</h5>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <tbody>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-600">Jumlah Total Aparatur</td>
                                <td class="py-3 px-4 text-right font-semibold text-slate-800">{{ number_format($submission->jml_aparatur, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-600">Terdaftar Wajib LHKPN</td>
                                <td class="py-3 px-4 text-right font-semibold text-slate-800">{{ number_format($submission->jml_wajib_lhkpn, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-600">Terdaftar Tidak Wajib LHKPN</td>
                                <td class="py-3 px-4 text-right font-semibold text-slate-800">{{ number_format($submission->jml_non_wajib_lhkpn, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-600">Realisasi Lapor LHKPN</td>
                                <td class="py-3 px-4 text-right font-semibold text-emerald-600">{{ number_format($submission->realisasi_lhkpn, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-600">Realisasi Lapor SPT Tahunan (Non-LHKPN)</td>
                                <td class="py-3 px-4 text-right font-semibold text-emerald-600">{{ number_format($submission->realisasi_spt_non_lhkpn, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-600">Belum Lapor SPT Tahunan (Non-LHKPN)</td>
                                <td class="py-3 px-4 text-right font-semibold text-amber-500">{{ number_format($submission->belum_spt_non_lhkpn, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-rose-50 border-y border-rose-100">
                                <td class="py-3 px-4 font-bold text-rose-700">Total Belum Lapor LHKAN</td>
                                <td class="py-3 px-4 text-right font-bold text-rose-700 text-lg">{{ number_format($submission->total_belum_lhkan, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($submission->link_rekap_gdrive)
                    <div class="mt-6 p-4 bg-sky-50 rounded-lg border border-sky-100 flex items-start gap-3">
                        <div class="bg-sky-100 text-sky-600 p-2 rounded pt-1 pb-1">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="font-semibold text-sky-800 text-sm mb-1 mt-1">Dokumen Rekapitulasi (Google Drive)</div>
                            <div>
                                <a href="{{ $submission->link_rekap_gdrive }}" target="_blank" class="text-sky-600 hover:text-sky-800 hover:underline text-sm break-all font-medium bg-gray-100 p-2 rounded-lg">
                                    <i class="fab fa-google-drive mr-1"></i> Buka Link
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                @if($submission->catatan)
                    <div class="mt-4 p-4 bg-amber-50 rounded-lg border border-amber-100 flex items-start gap-3">
                        <div class="bg-amber-100 text-amber-600 p-2 rounded pt-1 pb-1">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-amber-800 text-sm mb-1 mt-1">Catatan Tambahan</div>
                            <p class="text-amber-700 text-sm m-0">{{ $submission->catatan }}</p>
                        </div>
                    </div>
                @endif
            </x-bladewind::card>

            @if(in_array($submission->status, ['submitted', 'approved']))
                <div class="my-4"></div>
                <x-bladewind::card>
                    <h5 class="text-base font-semibold text-slate-800 border-b border-slate-200 pb-4 mb-4 m-0">Aksi Administratif</h5>
                    <div class="flex flex-wrap gap-2">
                        @if($submission->status == 'submitted')
                            <form action="{{ route('lhkan.submission.approve', $submission->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bw-button bg-green-500 hover:bg-green-600 text-white inline-flex items-center rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan LHKAN ini?')">
                                    <i class="fas fa-check mr-2"></i> Setujui Laporan
                                </button>
                            </form>
                        @elseif($submission->status == 'approved')
                            <a href="{{ route('lhkan.change-requests.approve', $submission->id) }}" class="bw-button bg-yellow-500 hover:bg-yellow-600 text-white inline-flex items-center rounded-lg" onclick="return confirm('Apakah Anda yakin ingin mengizinkan instansi untuk mengedit data?')">
                                <i class="fas fa-edit mr-2"></i> Izinkan Edit Data
                            </a>
                        @endif

                        <form action="{{ route('lhkan.submission.delete', $submission->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bw-button bg-red-500 hover:bg-red-600 text-white inline-flex items-center rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan LHKAN ini secara permanen?')">
                                <i class="fas fa-trash mr-2"></i> Hapus Laporan
                            </button>
                        </form>
                    </div>
                </x-bladewind::card>
            @endif
        </div>

        <!-- right column (PIC & Log) -->
        <div class="space-y-5">
            <x-bladewind::card>
                <div class="flex items-center gap-2 border-b border-slate-200 pb-4 mb-4">
                    <i class="fa fa-users text-slate-400"></i>
                    <h5 class="text-base font-semibold text-slate-800 m-0">PIC LHKAN</h5>
                </div>
                
                @if($submission->pics && $submission->pics->count() > 0)
                    <div class="space-y-3">
                        @foreach($submission->pics as $index => $pic)
                            <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg border border-slate-100 my-4">
                                <div class="bg-blue-100 text-blue-600 h-8 w-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div class="overflow-hidden">
                                    <div class="font-semibold text-slate-800 text-sm truncate" title="{{ $pic->nama }}">{{ $pic->nama }}</div>
                                    <div class="text-slate-500 text-xs flex items-center gap-2 mt-1">
                                        <i class="fa fa-phone"></i>
                                        <a href="https://wa.me/{{ preg_replace('/^08/', '628', $pic->nomor_hp) }}" target="_blank" class="hover:text-emerald-600 hover:underline">
                                            {{ $pic->nomor_hp }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-slate-500 border border-dashed border-slate-200 rounded-lg">
                        <i class="fa fa-user-times text-2xl mb-2 text-slate-300"></i>
                        <p class="text-sm m-0">Tidak ada PIC terdaftar</p>
                    </div>
                @endif
            </x-bladewind::card>

            <div class="my-4"></div>

            <x-bladewind::card>
                <div class="flex items-center gap-2 border-b border-slate-200 pb-4 mb-4">
                    <i class=" text-slate-400"></i>
                    <h5 class="text-base font-semibold text-slate-800 m-0">Log Aktivitas</h5>
                </div>

                @if($submission->logs && $submission->logs->count() > 0)
                    <div class="relative border-l border-slate-200 ml-3 space-y-6 pb-2 mt-2">
                        @foreach($submission->logs->sortByDesc('created_at') as $log)
                            <div class="relative pl-6">
                                <div class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full bg-slate-400 border-2 border-white ring-2 ring-slate-100"></div>
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-semibold text-xs text-slate-800 tracking-wide uppercase">{{ $log->action }}</div>
                                    <div class="text-[10px] text-slate-500" title="{{ $log->created_at->format('d M Y H:i:s') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 mb-1 leading-snug">{{ $log->description }}</p>
                                <div class="text-[10px] text-slate-400 flex flex-wrap gap-x-3 gap-y-1 mt-1.5">
                                    <span class="flex items-center gap-1"><i class="fa fa-user"></i> {{ $log->user->name ?? 'Sistem' }}</span>
                                    <span class="flex items-center gap-1"><i class="fa fa-globe"></i> {{ $log->ip_address ?? '::1' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center flex flex-col items-center justify-center py-6 text-slate-500 border border-dashed border-slate-200 rounded-lg">
                        <i data-lucide="rotate-ccw" class="text-3xl mb-2 text-slate-300"></i>   
                        <p class="text-sm m-0">Belum ada log aktivitas</p>
                    </div>
                @endif
            </x-bladewind::card>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
@endpush
@endsection