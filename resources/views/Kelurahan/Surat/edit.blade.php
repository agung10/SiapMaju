<form id="upload-surat-kelurahan" method="POST" action="{{ route('Kelurahan.Surat.update', \Crypt::encryptString($data->surat_permohonan_id)) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="modal-header">
        <h5 class="modal-title" id="uploadDocumentLabel">Upload Surat Kelurahan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>No Surat</label>
                <input type="text" name="no_surat_kel" class="form-control no-surat" value="{{ (($data->no_surat_kel) ?? ('')) }}" placeholder="Masukkan No Surat" />
                <span class="font-weight-bold btn btn-secondary btn-sm text-justify w-100 err-no-surat" style="padding:5px; margin-top:3px; font-size:.9rem; display:none;">
                </span>
            </div>
            <div class="form-group">
                <label>Isi Surat</label>
                <textarea name="isi_surat" id="summernote" class="form-control isi-surat">{{ (($data->isi_surat) ?? ('')) }}</textarea>
                <span class="font-weight-bold btn btn-secondary btn-sm text-justify w-100 err-isi-surat" style="padding:5px; margin-top:3px; font-size:.9rem; display:none;">
                </span>
            </div>
            <div class="form-group">
                <label>Upload Surat Kelurahan</label>
                <input type="file" name="upload_surat_kelurahan" class="form-control upload-surat" />
                <span class="font-weight-bold btn btn-secondary btn-sm text-justify w-100 err-upload" style="padding:5px; margin-top:3px; font-size:.9rem; display:none;">
                </span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary btn-submit-modal">Simpan</button>
    </div>
</form>