@extends('lhkan.layout.lhkan_layout')

@section('title', 'Dashboard')

@section('content')
<div class="block block-rounded block-bordered mt-8">
    @if(in_array(auth()->user()->level, ['admin', 'tpn']))
        <form method="GET" action="{{ route('lhkan.export-csv') }}" class="inline-flex">
            <input type="hidden" name="periode_id" value="{{ $periodeId }}">
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <i class="fa fa-file-excel mr-2"></i> Export CSV
            </button>
        </form>
    @endif
    
    <div class="block-content">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-4 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h3 class="font-semibold text-blue-600">Total Instansi</h3>
                </div>
                <div class="px-6 py-4">
                    <h2 class="text-3xl font-bold text-blue-600">{{ $totalInstansi }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Total instansi terdaftar</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-cyan-50">
                    <h3 class="font-semibold text-cyan-600">Sudah Submit</h3>
                </div>
                <div class="px-6 py-4">
                    <h2 class="text-3xl font-bold text-cyan-600">{{ $submittedCount }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Instansi sudah melaporkan</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <h3 class="font-semibold text-green-600">Sudah Approve</h3>
                </div>
                <div class="px-6 py-4">
                    <h2 class="text-3xl font-bold text-green-600">{{ $approvedCount }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Data disetujui</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-amber-50">
                    <h3 class="font-semibold text-amber-600">Tingkat Penyelesaian</h3>
                </div>
                <div class="px-6 py-4">
                    <h2 class="text-3xl font-bold text-amber-600">{{ $completionRate }}%</h2>
                    <p class="text-sm text-gray-500 mt-1">Tingkat kepatuhan</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('lhkan.dashboard') }}" class="grid grid-cols-3 md:grid-cols-3 gap-4 mt-6 contents">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select name="periode_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}" {{ $periodeId == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama }} ({{ $periode->tahun }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            @if(auth()->user()->level === 'admin')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instansi</label>
                    <select name="instansi_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                        <option value="">Semua Instansi</option>
                        {{-- Add instansi options --}}
                    </select>
                </div>
            @endif
        </form>

        <!-- Data Table -->
        <div class="overflow-x-auto mt-6 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Aparatur</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Wajib LHKPN</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi LHKPN</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Belum LHKAN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Submit</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($submissions->count() > 0)
                        @foreach($submissions as $index => $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ ($submissions->currentPage() - 1) * $submissions->perPage() + $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->instansi->nama_instansi ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->period->nama ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @switch($submission->status)
                                        @case('draft')
                                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Draft</span>
                                            @break
                                        @case('submitted')
                                            <span class="px-2 py-1 text-xs font-medium bg-cyan-100 text-cyan-800 rounded-full">Submitted</span>
                                            @break
                                        @case('approved')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rejected</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $submission->jml_aparatur ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $submission->jml_wajib_lhkpn ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $submission->realisasi_lhkpn ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-red-600">{{ $submission->total_belum_lhkan ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @foreach($submission->pics as $pic)
                                        <div>{{ $pic->nama }} ({{ $pic->nomor_hp }})</div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if(auth()->user()->level === 'admin')
                                        <a href="{{ route('lhkan.periode.index') }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors" title="Kelola Periode">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                        <a href="{{ route('lhkan.pic.index') }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-cyan-600 rounded hover:bg-cyan-700 transition-colors" title="Kelola PIC">
                                            <i class="fa fa-users"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $submissions->firstItem() }} sampai {{ $submissions->lastItem() }} dari {{ $submissions->total() }} data
            </div>
            {{ $submissions->links() }}
        </div>
    </div>
</div>
@endsection