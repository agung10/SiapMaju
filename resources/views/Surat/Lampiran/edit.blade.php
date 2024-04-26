@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Lampiran
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Surat.Lampiran.update', $data->lampiran_id) }}" method="POST">
			@csrf
			{{ method_field('PATCH') }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Surat Permohonan<span class="text-danger">*</span></label>
                            <select class="form-control" name="jenis_surat_id">
                                {!! $resultSurat !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label>Nama Lampiran<span class="text-danger">*</span></label>
                        <input type="text" name="nama_lampiran" class="form-control" value="{{ $data->nama_lampiran }}">
                    </div>
                    <div class="col-md-12">
                        <label>Keterangan<span class="text-danger">*</span></label>
                        <textarea name="keterangan" cols="30" rows="2" class="form-control">{{ $data->keterangan }}</textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori<span class="text-danger">*</span></label>
                            <select class="form-control" name="kategori">
                                <option selected disabled></option>
                                <option value="1" {{$data->kategori == '1' ? 'selected' : ''}}>Wajib</option>
                                <option value="0" {{$data->kategori == '0' ? 'selected' : ''}}>Tidak Wajib</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select class="form-control" name="status">
                                <option selected disabled></option>
                                <option value="1" {{$data->status == '1' ? 'selected' : ''}}>Aktif</option>
                                <option value="0" {{$data->status == '0' ? 'selected' : ''}}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Surat\LampiranRequest','#headerForm') !!}
<script>
	const editData = async () => {	
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Surat.Lampiran.update', '')}}/{{\Request::segment(3)}}`,{
				headers: { "X-CSRF-TOKEN":"{{ csrf_token() }}" },
				method:'post',
				body:formData
			})
			.then(response => response.json())
			.catch(() => {
				KTApp.unblockPage()
				swal.fire('Maaf Terjadi Kesalahan','','error')
				.then(result => {
					if(result.isConfirmed) window.location.reload()
				})
			})
		} 

		const {status} = await res()
		
		if(status === 'success'){
			localStorage.setItem('success','Lampiran Berhasil Diupdate');
			return location.replace('{{route("Surat.Lampiran.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		editData();
	})

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
		});
	}

    // Class Select2 definition
    var KTSelect2 = function() {
        // Functions
        return {
            init: function() {
                $('select[name=jenis_surat_id]').select2({ placeholder: '-- Pilih Jenis Surat --' });
                $('select[name=kategori]').select2({ width: '100%', placeholder: '-- Pilih Kategori Lampiran --'});
                $('select[name=status]').select2({ width: '100%', placeholder: '-- Pilih Status Lampiran --'});
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@endsection