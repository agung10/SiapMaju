<form class="form-keluarga-lama" action="{{route('Master.ListKeluarga.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body" style="padding: 0;">
        <div class="row col-md-12">
            <h4>Form Tambah Kepala Keluarga (Dari Anggota Keluarga)</h4>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nama Warga <span class="text-danger">*</span></label>
                    <select class="form-control" name="warga_id">
                        {!! $resultWarga !!}
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control emailOldData" readonly/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Hubungan Keluarga <span class="text-danger">*</span></label>
                    <select class="form-control" name="hub_keluarga_id">
                        <option value="{{$kepalaKeluarga->hub_keluarga_id}}">
                            {{$kepalaKeluarga->hubungan_kel}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>No Telp <span class="text-danger">*</span></label>
                    <input type="text" class="form-control telpOldData" name="no_telp" readonly/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_lahir" class="form-control" readonly/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                    <input type="text" name="jenis_kelamin" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Provinsi <span class="text-danger">*</span></label>
                    <select class="form-control provinceOldData" name="province_id" id="provinceOldData" disabled>
                        {!! $resultProvince !!}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kota/Kabupaten <span class="text-danger">*</span></label>
                    <select class="form-control cityOldData" name="city_id" id="cityOldData" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kecamatan <span class="text-danger">*</span></label>
                    <select class="form-control subdistrictOldData" name="subdistrict_id" id="subdistrictOldData" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kelurahan <span class="text-danger">*</span></label>
                    <select class="form-control kelurahanOldData" name="kelurahan_id" id="kelurahanOldData" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>RW <span class="text-danger">*</span></label>
                    <select class="form-control rwOldData" name="rw_id" id="rwOldData" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>RT <span class="text-danger">*</span></label>
                    <select class="form-control rtOldData" name="rt_id" id="rtOldData" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Blok Rumah <span class="text-danger">*</span></label>
                    <select class="form-control blokOldData" name="blok_id" id="blokOldData" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Alamat Domisili <span class="text-danger">*</span></label>
                    <textarea type="text" name="alamat" class="form-control alamatOldData" readonly></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status Domisili <span class="text-danger">*</span></label>
                    <select class="form-control sdOldData" name="status_domisili">
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
                    <textarea type="text" name="alamat_ktp" class="form-control alamat-KTP" readonly></textarea>
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
        <div class="card-footer" style="padding: 2rem 0 2rem 2.25rem;">
            <div class="row btn-keluarga-container float-right">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary submit">Simpan Keluarga</button>
                    <button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
                </div>
            </div>
        </div>
        <input type="hidden" class="anggota-keluarga-id" name="anggota_keluarga_id">
        <input type="hidden" class="anggota-email" name="email">
    </div>
</form>