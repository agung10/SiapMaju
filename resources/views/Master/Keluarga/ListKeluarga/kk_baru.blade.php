<div class="card-body" style="padding: 0;">
    <div class="form-keluarga-container">
        <form class="form-keluarga" action="{{route('Master.ListKeluarga.store')}}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <h4>Form Kepala Keluarga</h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Provinsi <span class="text-danger">*</span></label>
                        <select class="form-control provinceNewData" name="province_id" id="provinceNewData">
                            {!! $resultProvince !!}
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kota/Kabupaten <span class="text-danger">*</span></label>
                        <select class="form-control cityNewData" name="city_id" id="cityNewData">
                            @if ($isRt) 
                                {!! $resultCity !!}
                            @else
                                <option></option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kecamatan <span class="text-danger">*</span></label>
                        <select class="form-control subdistrictNewData" name="subdistrict_id" id="subdistrictNewData">
                            @if ($isRt) 
                                {!! $resultSubdistrict !!}
                            @else
                                <option></option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kelurahan <span class="text-danger">*</span></label>
                        <select class="form-control kelurahanNewData" name="kelurahan_id" id="kelurahanNewData">
                            @if ($isRt) 
                                {!! $resultKelurahan !!}
                            @else
                                <option></option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>RW <span class="text-danger">*</span></label>
                        <select class="form-control rwNewData" name="rw_id" id="rwNewData">
                            @if ($isRt) 
                                {!! $resultRW !!}
                            @else
                                <option></option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>RT <span class="text-danger">*</span></label>
                        <select class="form-control rtNewData rt" name="rt_id" id="rtNewData">
                            @if ($isRt) 
                                {!! $resultRT !!}
                            @else
                                <option></option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Blok Rumah <span class="text-danger">*</span></label>
                        <select class="form-control blokNewData" name="blok_id" id="blokNewData">
                            @if ($isRt) 
                                {!! $resultBlok !!}
                            @else
                                <option></option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Alamat Domisili <span class="text-danger">*</span></label>
                        <textarea type="text" name="alamat" class="form-control"></textarea>
                        <div style="font-size:10px;margin:5px;color:red" class="err-alamat"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Domisili <span class="text-danger">*</span></label>
                        <select class="form-control" name="status_domisili">
                            <option></option>
                            <option value="1">Alamat KK Sesuai Domisili</option>
                            <option value="2">Warga Asli Tinggal Diluar</option>
                            <option value="3">Warga Pendatang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Alamat Lain <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control alamat-ktp" name="alamat_ktp" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" />
                        <div style="font-size:10px;margin:5px;color:red" class="err-email"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>No Telp <span class="text-danger">*</span></label>
                        <input type="text" name="no_telp" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <label>File Upload</label>
                    <div class="form-group">
                        <div class="custom-file">
                            <input name="file" type="file" class="custom-file-input" id="customFile"/>
                            <label class="custom-file-label" for="customFile" id="file-name">Choose file </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row btn-keluarga-container">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary submit float-right createButton">Simpan Keluarga</button>
                </div>
            </div>
        </form>
    </div>
    <div class="form-anggota-container" style="padding: 0;">
        <form id="anggota-form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <h4>Form Anggota Keluarga</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Anggota Keluarga <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Hubungan Keluarga <span class="text-danger">*</span></label>
                        <select class="form-control rw" name="hub_keluarga_id">
                            <option value="{{$kepalaKeluarga->hub_keluarga_id}}">
                                {{$kepalaKeluarga->hubungan_kel}}
                            </option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="keluarga-id" name="keluarga_id">
                <input type="hidden" class="anggota-alamat" name="alamat">
                <input type="hidden" class="anggota-email" name="email">
                <input type="hidden" class="anggota-rt_id" name="rt_id">
                <input type="hidden" class="anggota-rw_id" name="rw_id">
                <input type="hidden" class="anggota-kelurahan_id" name="kelurahan_id">
                <input type="hidden" class="anggota-subdistrict_id" name="subdistrict_id">
                <input type="hidden" class="anggota-city_id" name="city_id">
                <input type="hidden" class="anggota-province_id" name="province_id">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control anggota-mobile" name="mobile" readonly/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_lahir" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-control" name="jenis_kelamin">
                            <option></option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 offset-md-6">
                <button type="submit" class="btn btn-primary submit float-right">Simpan Anggota Keluarga</button>
            </div>
        </form>
    </div>
</div>
<button type="reset" onClick='goBack()' class="btn btn-secondary mt-5">Back</button>