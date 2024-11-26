<table class="table">
    <thead>
        <tr>
            <td>instansi_id</td>
            <td>lke_bobot_id</td>
            <td>no</td>
            <td>group_instansi</td>
            <td>nama_instansi</td>
            <td>bobot</td>
            <td>min_value</td>
            <td>max_value</td>
            <td>target_baik</td>
            <td>score</td>
            <td>catatan</td>
            <td>rekomendasi</td>
        </tr>
        <tr>
            <td>{{ $parameter->id }}</td>
            <td></td>
            <td colspan="10" style="font-weight: bold; font-size: 18px;">LKE - {{ $parameter->nama }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th></th>
            <th></th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;" height="20">No.</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Kelompok Instansi</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Nama Instansi</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Bobot</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Minimal</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Maksimal</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Target Baik</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Skor</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Catatan</th>
            <th align="center" valign="middle" style="border: solid; font-weight: bold;">Rekomendasi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($datas as $data)
        <tr>
            <td style="border: solid;">{{ $data->instansi_id }}</td>
            <td style="border: solid;">{{ $data->lke_bobot_id }}</td>
            <td style="border: solid;">{{ $no }}</td>
            <td style="border: solid;">{{ $data->group_instansi }}</td>
            <td style="border: solid;">{{ $data->nama_instansi }}</td>
            <td style="border: solid;">{{ $data->bobot }}</td>
            <td style="border: solid;">{{ $data->min_value }}</td>
            <td style="border: solid;">{{ $data->max_value }}</td>
            <td style="border: solid;">{{ $data->target_baik }}</td>
            <td style="border: solid;">{{ $data->score }}</td>
            <td style="border: solid;">{{ $data->catatan }}</td>
            <td style="border: solid;">{{ $data->rekomendasi }}</td>
        </tr>
        @php
            $no++;
        @endphp
        @endforeach
    </tbody>
</table>