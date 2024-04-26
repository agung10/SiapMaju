@extends('layouts.master')
@section('content')
    <style type="text/css">
        input:focus { outline:none; }
    </style>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Detail Laporan Polling
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div style="padding:10px; background:#EFEFEF; border-radius:10px; height:320px;">
                            <h3>Pertanyaan:</h3>
                            <h3 style="padding-bottom:21px;">{{ $data->isi_pertanyaan }}</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart" style="padding:10px; background:#EFEFEF; border-radius:10px; height:320px;">
                            <canvas id="pollingChart"></canvas>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-checkable table-responsive" id="datatable">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nama Warga</th>
                            @if (count($data->answer) > 0)
                                @foreach ($data->answer as $key => $value)
                                    <th class="text-center">{{ $value->isi_pilih_jawaban }}</th>
                                @endforeach
                            @endif
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($hasilPolling) > 0)
                            @php $no = 0; @endphp
                            @foreach ($hasilPolling as $key => $value)
                                @php $no++; @endphp
                                <tr>
                                    <td class="text-center">{{ $no }}</td>
                                    <td><input type="{{ ((\Auth::user()->is_admin) ? ('text') : ('password')) }}" value="{{ $value->citizen->nama }}" style="border:none;" readonly></td>
                                    @if (count($data->answer) > 0)
                                        @foreach ($data->answer as $keyAnswer => $valueAnswer)
                                            @if ($value->id_pilih_jawaban == $valueAnswer->id_pilih_jawaban)
                                                <td class="text-center"><i class="fa fa-check-circle" style="color:#16bb16;"></i></td>
                                            @else
                                                <td class="text-center">-</td>
                                            @endif
                                        @endforeach
                                    @endif
                                    <td>{{ $value->keterangan }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            @if (count($data->answer) > 0)
                                @foreach ($data->answer as $keyAnswer => $value)
                                    @if ($data->rt === 'DKM')
                                        <td class="text-center" style="font-weight:bold;">{{ $value->pollingDKM->count() }}</td>
                                    @else
                                        <td class="text-center" style="font-weight:bold;">{{ $value->pollingResult->count() }}</td>
                                    @endif
                                @endforeach
                            @endif
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-lg-12 text-right headerSaveButtonContainer">
                <button onClick="goBack()" class="btn btn-secondary">Back</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable();
            const chart = JSON.parse('{!! $pollData !!}');
            const myChart = new Chart(document.getElementById('pollingChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: chart.map(({ jawaban }) => jawaban),
                    datasets: [{
                        label: 'Data Keluarga',
                        data: chart.map(({ jumlah }) => jumlah),
                        backgroundColor: chart.map((data) => `#${Math.floor(Math.random() * 16777215).toString(16)}`),
                        borderWidth: 1
                    }]
                },
                options: { maintainAspectRatio: false }
            });
        });
    </script>
@endsection