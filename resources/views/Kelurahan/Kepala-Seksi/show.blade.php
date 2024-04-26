@extends('layouts.master')
@section('content')
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">Detail Surat Permohonan</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="form-surat-container">
                    <form id="kepala-seksi" action="{{ route('Kelurahan.Kepala-Seksi.update', \Crypt::encryptString($data->surat_permohonan_id)) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <div class="row">
                            <h4>Form Surat Permohonan</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Warga</label>
                                    <input type="text" class="form-control" value="{{ (($data->nama_lengkap) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Surat</label>
                                    <input type="text" class="form-control" value="{{ (($data->letterType->jenis_permohonan) ?? ('')) }}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" class="form-control" value="{{ (($data->tempat_lahir) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="text" class="form-control" value="{{ (($data->tgl_lahir) ?? ('')) }}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bangsa</label>
                                    <input type="text" class="form-control" value="{{ (($data->bangsa) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Pernikahan</label>
                                    <input type="text" class="form-control" value="{{ (($data->marriageStatus->nama_status_pernikahan) ?? ('')) }}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Agama</label>
                                    <input type="text" class="form-control" value="{{ (($data->religion->nama_agama) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <input type="text" class="form-control" value="{{ (($data->pekerjaan) ?? ('')) }}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No KK</label>
                                    <input type="text" class="form-control" value="{{ (($data->no_kk) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No KTP</label>
                                    <input type="text" class="form-control" value="{{ (($data->no_ktp) ?? ('')) }}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Permohonan</label>
                                    <input type="text" class="form-control" value="{{ (($data->tgl_permohonan) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keperluan Pembuatan</label>
                                    <textarea class="form-control" disabled>{{ $data->keperluan ? $data->keperluan : '-' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @if ($data->lampiran1 || $data->lampiran2 || $data->lampiran3) 
                            <div class="row">
                                <h4>Lampiran</h4>
                            </div>
                            <div class="row lampiran-container">
                                @if ($data->lampiran1)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lampiran 1</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$data->lampiran1) }}" download>
                                                <input class="lampiran form-control" type="text" name="lampiran1" value="{{ $data->lampiran1 }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if ($data->lampiran2)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lampiran 2</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$data->lampiran2) }}" download>
                                                <input class="lampiran form-control" type="text" name="lampiran2" value="{{ $data->lampiran2 }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if ($data->lampiran3)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lampiran 3</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$data->lampiran3) }}" download>
                                                <input class="lampiran form-control" type="text" name="lampiran3"
                                                    value="{{ $data->lampiran3 }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif (count($lampiranSurat) > 0) 
                            <div class="row">
                                <h4>Lampiran</h4>
                            </div>
                            <div class="row lampiran-container">
                                @foreach ($lampiranSurat as $res)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $res->nama_lampiran }}</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$res->upload_lampiran) }}" download>
                                                <input class="lampiran form-control" type="text" value="{{ $res->upload_lampiran }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>  
                        @endif
                        <div class="row">
                            <h4>Data Detail Alamat</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <input type="text" class="form-control" value="{{ (($province) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kota / Kabupaten</label>
                                    <input type="text" class="form-control" value="{{ (($city) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <input type="text" class="form-control" value="{{ (($subdistrict) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Kelurahan</label>
                                    <input type="text" class="form-control" value="{{ (($data->wards->nama) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nama RW</label>
                                    <input type="text" class="form-control" value="{{ (($data->rw->rw) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nama RT</label>
                                    <input type="text" class="form-control" value="{{ (($data->rt->rt) ?? ('')) }}" disabled />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Alamat Lengkap</label>
                                    <textarea type="text" name="alamat" class="form-control" disabled>{{ (($data->alamat) ?? ('')) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" style="padding:2rem 0;">
                            <button type="submit" class="btn btn-warning btn-submit">Approval Kepala Seksi</button>
                            <a href="{{ route('Kelurahan.Surat.previewSuratKelurahan', \Crypt::encryptString($data->surat_permohonan_id)) }}" class="btn btn-warning" target="_blank">Preview Surat Kelurahan</a>
                            <a href="{{ route('Kelurahan.Surat.previewSurat', \Crypt::encryptString($data->surat_permohonan_id)) }}" class="btn btn-warning" target="_blank">Preview Surat Permohonan</a>
                            <button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('body').on('submit', 'form#kepala-seksi', function(e) {
                e.preventDefault();
                const _thisForm = $(this);

                Swal.fire({
                    title: 'Apakah data sudah benar?',
                    text: 'Surat yang disetujui tidak dapat dibatalkan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, menyetujui!',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Batalkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        _thisForm[0].submit();
                    }
                });
            });
        });
    </script>
@endsection