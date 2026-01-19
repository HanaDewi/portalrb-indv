@extends('layout.rubick')
@section('title', 'Evaluasi Renaksi RB General - ' . auth()->user()->nama)

@section('content')
    <div class="intro-y col-span-12 lg:col-span-12">
        @include('common.status')
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                <h2 class="font-bold text-base mr-auto"> Evaluasi Renaksi RB General
                    @if (isset($instansi))
                        - {{ $instansi->name }}
                    @endif
                </h2>
            </div>
            <div class="lg:col-span-12 p-5 border-b border-slate-200/60">
                <div class="row">
                    <div class="form-group mb-3">
                        <label for="kegiatan_id" class="form-label mt-2">Kegiatan <span class="text-danger">*</span></label>
                        {!! Form::select('kegiatan_id', kegiatan(), $kegiatan->id, ['class' => 'w-full', 'id' => 'kegiatan_id', 'data-placeholder' => 'Pilih Kegiatan', 'onchange' => 'getData();']) !!}
                    </div>
                </div>
                <div class="row" hidden>
                    <a class="btn btn-danger" href="/evaluasi/data-lke-renaksi">Data LKE Renaksi</a> &nbsp;
                    <a class="btn btn-danger" href="/evaluasi/renaksi-rb-general">Data Konversi Jawaban</a>
                </div>
                <div class="separator mt-5"></div>
                @if ($kembali == false)
                    <table id="table-instansi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead class="table-dark font-bold">
                            <tr>
                                <th class="w-5">No.</th>
                                <th>Instansi</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $cc => $jw)
                                <tr>
                                    <td>{{ $cc + 1 }}</td>
                                    <td><a href="?kegiatan_id={{ $kegiatan->id }}&instansi={{ $jw->instansi_id }}" style="color:blue">{{ $jw->instansi->name }}</a>
                                    </td>
                                    <td class="text-center">{{ $jw->instansi->jawaban_renaksi->where('tahun', $kegiatan->tahun)->where('lke_renaksi_id', $skor_id)->first()->jawaban ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @php
                        $specialKriteria = [
                            'Penilaian Kegiatan Utama Road Map Reformasi Birokrasi',
                            'Kriteria Penilaian Penetapan Rencana Aksi',
                            'Strategi Pelaksanaan RB General',
                        ];
                        $renaksiIds = collect($renaksi)->pluck('id')->all();
                        $showAksi = $check;
                    @endphp
                    <table id="table-instansi" class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead class="table-dark font-bold">
                            <tr>
                                <th>LKE Renaksi</th>
                                <th>Jawaban</th>
                                <th>Skor</th>
                                <th>Catatan</th>
                                <th>Rekomendasi</th>
                                @if ($showAksi)
                                    <th style="width:100px;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lkerenaksi as $cc => $jw)
                                @php
                                    $jawaban = $fjawaban[$jw->id] ?? null;
                                    $isSpecial = in_array($jw->kriteria, $specialKriteria, true);
                                    $hasJawaban = $jawaban && $jawaban->jawaban !== '';
                                    $canAnswer = in_array($jw->id, $renaksiIds, true);
                                    $canEdit = $check && $jawaban && $jawaban->id !== '';
                                @endphp
                                <tr>
                                    <td><strong>{{ $jw->kriteria }}</strong></td>
                                    @if ($hasJawaban)
                                        @if ($isSpecial)
                                            <td class="text-center" colspan="2" style="font-weight:bold; @if ($jw->kriteria == 'Strategi Pelaksanaan RB General') color:#b42b2d; font-size:1.25em; @endif">
                                                {{ $jawaban->jawaban }}
                                            </td>
                                        @else
                                            <td class="text-center">{{ $jawaban->jawaban }}</td>
                                            <td class="text-center">{{ $jawaban->skor }}</td>
                                        @endif
                                        <td class="text-center">{{ $jawaban->catatan }}</td>
                                        <td class="text-center">{{ $jawaban->rekomendasi }}</td>
                                    @else
                                        @if ($isSpecial)
                                            <td colspan="2"></td>
                                        @else
                                            <td></td>
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                    @endif
                                    @if ($showAksi)
                                        <td class="text-center">
                                            @if (!$isSpecial)
                                                @if ($canEdit)
                                                    <a class="btn btn-warning btn-xs" data-raw="{{ json_encode($jawaban) }}" data-id="{{ $jawaban->id }}" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-jawaban-renaksi"><i class="nav-icon fas fa-edit"></i></a>
                                                    &nbsp;
                                                    <a class="btn btn-danger btn-xs" data-id="{{ $jawaban->id }}" onclick="dodelete(this)"><i class="nav-icon fas fa-remove"></i></a> &nbsp;
                                                @elseif ($canAnswer)
                                                    <a class="btn btn-warning btn-xs" data-raw="{{ json_encode($jawaban) }}" data-id="{{ $jawaban->id }}" onclick="showform(this)" data-bs-toggle="modal" data-bs-target="#modal-form-jawaban-renaksi"> Jawab &nbsp; <i class="nav-icon fas fa-edit"></i></a> &nbsp;
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <div class="row mt-4">
                    @if ($kembali == true)
                        <a href="/evaluasi/renaksi-rb-general?kegiatan_id={{ $kegiatan->id }}" class="btn btn-warning">&lt; Kembali</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($check == true)
        <div id="modal-form-jawaban-renaksi" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-medium fs-base me-auto" id="title">Data Jawaban Renaksi</h2>
                    </div>
                    <form action="{{ url('evaluasi/renaksi-rb-general/save') }}" id="form-konversi-jawaban" method="post">
                        @csrf
                        <input type="hidden" name="instansi_id" id="instansi_id" value="{{ $instansi->id }}">
                        <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                        <input type="hidden" name="jawaban_renaksi_id" id="jawaban_renaksi_id">
                        <div class="modal-body grid columns-12 gap-4 gap-y-3">
                            <div class="g-col-12">
                                <div class="form-group">
                                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                    <input type="text" name="tahun" value="{{ $tahun }}" class="form-control" readonly placeholder="Tahun" />
                                </div>
                            </div>
                            <div class="g-col-12">
                                <div class="form-group">
                                    <label for="lkerenaksi" class="form-label">LKE Renaksi <span class="text-danger">*</span></label>
                                    <select id="lkerenaksi" name="lke_renaksi_id" class="form-control" required onchange="showInfo(this)">
                                        <option></option>
                                        @foreach ($renaksi as $ren)
                                            <option value="{{ $ren->id }}" data-info="{{ addslashes($ren->info) }}">{{ $ren->kriteria }}</option>
                                        @endforeach
                                    </select>
                                    <textarea class="form-control" disabled id="renaksiinfo" rows="10" style="font-size:9pt !important;"></textarea>
                                </div>
                            </div>
                            <div class="g-col-12">
                                <div class="form-group">
                                    <label for="jawaban" class="form-label">Jawaban <span class="text-danger">*</span></label>
                                    <select id="jawbaan" name="jawaban" class="form-control" required placeholder="Pilih jawaban">
                                        <option></option>
                                        @foreach ($list_jawaban as $lj)
                                            <option value="{{ $lj->jawaban }}">{{ $lj->jawaban }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="g-col-12">
                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan </label>
                                    <textarea id="catatan" name="catatan" class="form-control" placeholder="Catatan"></textarea>
                                </div>
                            </div>
                            <div class="g-col-12">
                                <div class="form-group">
                                    <label for="rekomendasi" class="form-label">Rekomendasi </label>
                                    <textarea id="rekomendasi" name="rekomendasi" class="form-control" placeholder="Rekomendasi"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-end">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 me-1">Batal</button> &nbsp;
                            <button type="submit" class="btn btn-success w-20 saveButton">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('css')
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            window.rowdata = new DataTable('#table-instansi', {
                "pageLength": 100,
                "dom": 'Blfrtip',
                "buttons": [{
                        extend: 'pdf',
                        text: '<button class="btn btn-danger w-32 mr-2 mb-2"><svg fill="#ffffff" height="24px" width="24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 482.14 482.14" xml:space="preserve" stroke="#ffffff"> <g id="SVGRepo_bgCarrier" stroke-width="0"/> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/> <g id="SVGRepo_iconCarrier"> <g> <path d="M142.024,310.194c0-8.007-5.556-12.782-15.359-12.782c-4.003,0-6.714,0.395-8.132,0.773v25.69 c1.679,0.378,3.743,0.504,6.588,0.504C135.57,324.379,142.024,319.1,142.024,310.194z"/> <path d="M202.709,297.681c-4.39,0-7.227,0.379-8.905,0.772v56.896c1.679,0.394,4.39,0.394,6.841,0.394 c17.809,0.126,29.424-9.677,29.424-30.449C230.195,307.231,219.611,297.681,202.709,297.681z"/> <path d="M315.458,0H121.811c-28.29,0-51.315,23.041-51.315,51.315v189.754h-5.012c-11.418,0-20.678,9.251-20.678,20.679v125.404 c0,11.427,9.259,20.677,20.678,20.677h5.012v22.995c0,28.305,23.025,51.315,51.315,51.315h264.223 c28.272,0,51.3-23.011,51.3-51.315V121.449L315.458,0z M99.053,284.379c6.06-1.024,14.578-1.796,26.579-1.796 c12.128,0,20.772,2.315,26.58,6.965c5.548,4.382,9.292,11.615,9.292,20.127c0,8.51-2.837,15.745-7.999,20.646 c-6.714,6.32-16.643,9.157-28.258,9.157c-2.585,0-4.902-0.128-6.714-0.379v31.096H99.053V284.379z M386.034,450.713H121.811 c-10.954,0-19.874-8.92-19.874-19.889v-22.995h246.31c11.42,0,20.679-9.25,20.679-20.677V261.748 c0-11.428-9.259-20.679-20.679-20.679h-246.31V51.315c0-10.938,8.921-19.858,19.874-19.858l181.89-0.19v67.233 c0,19.638,15.934,35.587,35.587,35.587l65.862-0.189l0.741,296.925C405.891,441.793,396.987,450.713,386.034,450.713z M174.065,369.801v-85.422c7.225-1.15,16.642-1.796,26.58-1.796c16.516,0,27.226,2.963,35.618,9.282 c9.031,6.714,14.704,17.416,14.704,32.781c0,16.643-6.06,28.133-14.453,35.224c-9.157,7.612-23.096,11.222-40.125,11.222 C186.191,371.092,178.966,370.446,174.065,369.801z M314.892,319.226v15.996h-31.23v34.973h-19.74v-86.966h53.16v16.122h-33.42 v19.875H314.892z"/> </g> </g> </svg> &nbsp;PDF </button>',
                        titleAttr: 'Download PDF',
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'excel',
                        text: '<button class="btn btn-warning w-32 mr-2 mb-2"> <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="24px" height="24px"><path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"/></svg>  &nbsp;Excel </button>',
                        titleAttr: 'Download Excel'
                    }
                ]
            });
        });
        window.getData = function() {
            const kegiatanId = $('#kegiatan_id').val();
            const instansiId = @json(request('instansi'));
            const params = new URLSearchParams();
            if (kegiatanId) {
                params.set('kegiatan_id', kegiatanId);
            }
            if (instansiId) {
                params.set('instansi', instansiId);
            }
            window.location = `${window.location.pathname}?${params.toString()}`;
        }
        @if ($check == true)
            window.modal_jawaban_renaksi = tailwind.Modal.getInstance(document.querySelector("#modal-form-jawaban-renaksi"));
            window.showform = function(th) {
                const id = $(th).data('id');
                let tahun = '{{ date('Y') }}';
                let lkerenaksi_id = '';
                let jawaban = '';
                let catatan = '';
                let rekomendasi = '';
                if (id != undefined) {
                    const raw = $(th).data('raw');
                    tahun = raw.tahun;
                    lkerenaksi_id = raw.lke_renaksi_id;
                    jawaban = raw.jawaban;
                    catatan = raw.catatan;
                    rekomendasi = raw.rekomendasi;
                }
                $('#modal-form-jawaban-renaksi').find('input[name=jawaban_renaksi_id]').val(id);
                $('#modal-form-jawaban-renaksi').find('input[name=tahun]').val(tahun);
                $('#modal-form-jawaban-renaksi').find('select[name=lke_renaksi_id]').val(lkerenaksi_id).change();
                $('#modal-form-jawaban-renaksi').find('select[name=jawaban]').val(jawaban);
                $('#modal-form-jawaban-renaksi').find('textarea[name=catatan]').val(catatan);
                $('#modal-form-jawaban-renaksi').find('textarea[name=rekomendasi]').val(rekomendasi);
                window.modal_jawaban_renaksi.show();
            }
            window.showInfo = function(th) {
                const info = $(th).find('option:selected').data('info');
                $('#renaksiinfo').val(info);
            }
            window.dodelete = function(th) {
                const id = $(th).data('id');
                if (!id) {
                    return false;
                }
                Swal.fire({
                    title: "Yakin?",
                    text: "Hapus data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Hapus aja!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('evaluasi/renaksi-rb-general/delete') }}",
                            type: "delete",
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            dataType: "json",
                            success: function(res) {
                                if (res.result) {
                                    Swal.fire('Selamat!', 'Data data berhasil dihapus!', 'success');
                                    location.reload();
                                } else {
                                    Swal.fire('Aduh!', 'Data data gagal dihapus! Coba lagi nanti ya..', 'error');
                                }
                            },
                            error: function(err) {
                                Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
                            }
                        });
                    }
                });
            }
        @endif
    </script>
@endpush
