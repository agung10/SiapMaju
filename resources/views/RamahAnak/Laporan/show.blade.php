@extends('layouts.master')
@section('content')
    <style type="text/css">
        textarea { overflow-y:auto; resize:none; }
        table.table { width:1700px !important; }
    </style>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Detail Vaksinasi
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <h4>Data Anak</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Anak</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->anggotaKeluarga->nama) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? (($data->anggotaKeluarga->jenis_kelamin == 'L') ? ('Laki-laki') : ('Perempuan')) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? (date('d M Y', strtotime($data->anggotaKeluarga->tgl_lahir))) : (null)) }}" readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" rows="5" readonly>{{ ((!empty($data)) ? ($data->anggotaKeluarga->alamat) : (null)) }}</textarea>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line" data-remember-tab="tab_id" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#berat_badan"><strong>Berat Badan</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tinggi_badan"><strong>Tinggi Badan</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#vaksinasi"><strong>Vaksinasi</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#detail"><strong>Detail</strong></a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane active" id="berat_badan">
                        <div style="overflow-x:auto;">
                            <div class="chart" style="padding:10px; background:#EFEFEF; border-radius:10px; width:1500px; height:350px;">
                                <canvas id="weightChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tinggi_badan">
                        <div style="overflow-x:auto;">
                            <div class="chart" style="padding:10px; background:#EFEFEF; border-radius:10px; width:1500px; height:350px;">
                                <canvas id="heightChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="vaksinasi">
                        <table class="table table-bordered table-checkable">
                            <tr>
                                <th width="50px" class="text-center">No</th>
                                <th width="200px">Nama Vaksin</th>
                                <th width="150px" class="text-center">Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                            @if (count($data->childHealthcare) > 0)
                                @php $no = 0; @endphp
                                @foreach ($data->childHealthcare as $key => $value)
                                    @if ($value->id_vaksin)
                                        @php $no++; @endphp
                                        <tr>
                                            <td class="text-center">{{ $no }}</td>
                                            <td>{{ $value->vaccine->nama_vaksin }}</td>
                                            <td class="text-center">{{ date('d M Y', strtotime($value->tgl_input)) }}</td>
                                            <td>{{ $value->ket_vaksinasi }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">Tidak ada data!</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="tab-pane" id="detail">
                        <div style="overflow-x:auto;">
                            <table class="table table-bordered table-checkable">
                                <tr>
                                    <th width="50px" class="text-center">No</th>
                                    <th width="150px">Vaksin</th>
                                    <th width="150px">Tanggal</th>
                                    <th width="150px">Berat</th>
                                    <th width="150px">Tinggi</th>
                                    <th width="200px">Lingkar Kepala</th>
                                    <th width="200px">Nilai Stunting</th>
                                    <th width="300px">Keluhan</th>
                                    <th width="300px">Keterangan</th>
                                </tr>
                                @if (count($data->childHealthcare) > 0)
                                    @php $no = 0; @endphp
                                    @foreach ($data->childHealthcare as $key => $value)
                                        @php $no++; @endphp
                                        <tr>
                                            <td class="text-center">{{ $no }}</td>
                                            <td>{{ (($value->vaccine) ? ($value->vaccine->nama_vaksin) : ('-')) }}</td>
                                            <td>{{ date('d M Y', strtotime($value->tgl_input)) }}</td>
                                            <td>{{ (($value->berat) ? ($value->berat . ' kg') : ('-')) }}</td>
                                            <td>{{ (($value->tinggi) ? ($value->tinggi . ' cm') : ('-')) }}</td>
                                            <td>{{ (($value->lingkar_kepala) ? ($value->lingkar_kepala . ' cm') : ('-')) }}</td>
                                            <td>{{ (($value->nilai_stunting) ? ($value->nilai_stunting) : ('-')) }}</td>
                                            <td>{{ $value->keluhan }}</td>
                                            <td>{{ $value->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="9">Tidak ada data!</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('partials.buttons.submit')
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            const graphData = JSON.parse('{!! $graphData !!}');

            new Chart(document.getElementById('weightChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: graphData.map(({ month }) => month),
                    datasets: [{
                        label: 'Berat Badan',
                        data: graphData.map(({ weight }) => weight),
                        fill: false,
                        spanGaps: true,
                        pointRadius: 5,
                        tension: 0.2,
                        borderColor: '{{ (($data) ? (($data->anggotaKeluarga->jenis_kelamin == 'L') ? ('rgb(79, 126, 196)') : ('rgb(255, 105, 180)')) : ('')) }}'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: (context) => 'Bulan ke-' + context[0].parsed.x,
                                label: (context) => context.dataset.label + ': ' + context.parsed.y + 'kg'
                            }
                        }
                    },
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            new Chart(document.getElementById('heightChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: graphData.map(({ month }) => month),
                    datasets: [{
                        label: 'Tinggi Badan',
                        data: graphData.map(({ height }) => height),
                        fill: false,
                        spanGaps: true,
                        pointRadius: 5,
                        tension: 0.2,
                        borderColor: '{{ (($data) ? (($data->anggotaKeluarga->jenis_kelamin == 'L') ? ('rgb(79, 126, 196)') : ('rgb(255, 105, 180)')) : ('')) }}'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: (context) => 'Bulan ke-' + context[0].parsed.x,
                                label: (context) => context.dataset.label + ': ' + context.parsed.y + 'cm'
                            }
                        }
                    },
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
@endsection