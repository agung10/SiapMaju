<h4>Riwayat Mutasi</h4>
<table class="table table-bordered">
    <tr>
        <th width="50px" class="text-center">No</th>
        <th width="150px">Tanggal</th>
        <th width="150px">Status</th>
        <th>Keterangan</th>
    </tr>
    @if (count($data) > 0)
        @php $no = 0; @endphp
        @foreach ($data as $key => $value)
            @php $no++; @endphp
            <tr>
                <td class="text-center">{{ $no }}</td>
                <td>{{ date('d M Y', strtotime($value->tanggal_mutasi)) }}</td>
                <td>{{ $value->movingStatus->nama_status }}</td>
                <td>{{ $value->keterangan }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" class="text-center">Tidak ada riwayat!</td>
        </tr>
    @endif
</table>