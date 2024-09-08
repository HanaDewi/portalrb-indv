@extends('layout.rubick')
@section('title', 'Capaian Output - RB Tematik')
@section('content')
    <div class="intro-y box col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-bold text-base mr-auto">Capaian Output - RB Tematik</h2>
        </div>
        <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
            <table id="capaian-output" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
                <thead class="table-dark font-bold">
                    <tr>
                        <th rowspan="2" class="w-5">No.</th>
                        <th rowspan="2">Instansi Pemerintah</th>
                        <th rowspan="2">Group Instansi</th>
                        <th colspan="4">Prosenstase Realisasi</th>
                        <th colspan="4">Rata-rata Prosentase Capaian Output</th>
                    </tr>
                    <tr>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th>
                        <th>TW1</th><th>TW2</th><th>TW3</th><th>TW4</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($instansis as $num=>$mm): ?>
                    <tr>
                        <td><?= $num+1 ?></td>
                        <td><?= $mm->name ?></td>
                        <td align="center"><?php 
                        switch ($mm->group) {
                            case 'kl': echo "Kementrian"; break;
                            case 'pemda': echo "PEMDA"; break;
                            case 'kab': echo "Kabupaten"; break;
                            case 'lain': echo "Lainnya"; break;
                            case 'prov': echo "Provinsi"; break;
                        }
                        ?></td>
                        <td></td><td></td><td></td><td></td>
                        <td></td><td></td><td></td><td></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    </script>
@endpush
