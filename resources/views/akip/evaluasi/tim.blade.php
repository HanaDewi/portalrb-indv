@extends('layout.midone', ['akip' => true])
@section('title', 'Hasil Evaluasi SAKIP')

@section('content')
    <!-- END: Top Bar -->
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Hasil Evaluasi SAKIP {{ $tim ? $tim->nama.' ('.$tim->keterangan.')' : '' }}
        </h2>
    </div>
    <!-- BEGIN: Datatable -->
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <div class="flex items-center mb-5 pb-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Daftar Instansi
            </h2>
        </div>
        <table class="table table-report table-report--bordered display datatable w-full table-fixed" id="instansi_table">
            <thead>
                <tr>
                    <th class="border-b-2 w-2">No.</th>
                    <th class="border-b-2 cursor-pointer">Instansi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anggota_tims as $anggota)
                    <tr>
                        <td></td>
                        <td><a
                                href="{{ url('akip/evaluasi/sakip/' . $anggota->instansi_id) }}">{{ $anggota->instansi->nama_instansi }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $('#instansi_table').DataTable({
            columnDefs: [{
                targets: 0, // kolom pertama = No.
                orderable: false,
                searchable: false,
            }],
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });

        function getData() {
            instansi_table.ajax.url("{{ url('dokumen/getDatas') }}").load(null, false);
        }
    </script>
@endpush
