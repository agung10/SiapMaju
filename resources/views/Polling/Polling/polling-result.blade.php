@if (count($pollingResult) > 0)
    <tbody>
        @php $no = 0; @endphp
        @foreach ($pollingResult as $key => $value)
            @php $no++; @endphp
            <tr>
                <td width="20px" class="text-center">{{ $no }}</td>
                <td width="200px">{{ $value->nama }}</td>
                <td>{{ $value->isi_pilih_jawaban }}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning btn-edit-poll" data-url="{{ route('Polling.Polling.editPolling', \Crypt::encrypt($value->id_hasil_polling)) }}">Ubah</button>
                </td>
            </tr>
        @endforeach
    </tbody>
@endif