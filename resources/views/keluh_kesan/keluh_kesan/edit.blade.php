@extends('layouts.master') 
@section('content')
<style>
 .container-balasan {
 	overflow: auto;
    height: 600px;
 }
 .wrapper-balasan {
 	padding: 10px;
    background-color: blanchedalmond;
    border-radius: 5px;
    margin-bottom: 10px;
 }
 .username-balasan {
 	font-weight: bold;
 }
 .waktu-balasan {
 	margin-top: -10px;
    font-size: 9px;
    color: #575757;
    font-style: italic;
    float: right;
    right: 40px;
    font-weight: 100;
 }
 .isi-balasan {
 	margin-left: 10px;
 }
 .img-balasan {
 	width: 150px;
    display: block;
    margin-bottom: 5px;
    border-radius: 5px;
 }
 .action-balasan {
 	background-color: darkcyan;
    width: 30px;
    height: 30px;
    padding: 7px;
    border-radius: 50%;
    float: right;
    margin-left: 10px;
 }
 .action-balasan i {
    color: #000;
    /*font-size:12px*/
 }
 .action-balasan:hover {
    cursor: pointer;
 } 
 .btn-hapus-balasan {
 	background-color: #fa9898;
 }
</style>
<div class="container">
    <div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit keluh kesan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
    	<form id="headerForm" action="{{route('keluhKesan.keluhKesan.update', $keluhKesan->keluh_kesan_id)}}" enctype="multipart/form-data" method="POST">
		@csrf
		@method('PUT')
        <div class="card-body">
        	<div class="row">
	            <div class="col-md-6">
	            	<h3>Keluh Kesan</h3>
	                <div class="form-group">
	                    <label>User <span class="text-danger">*</span></label>
	                    <select name="user_id" class="form-control" required>
	                        @foreach($users as $user)
	                        	<option value="{{ $user->user_id }}" {{ $user->user_id == $keluhKesan->user_id ? 'selected' : '' }}>
	                        		{{ $user->username }}
	                        	</option>
	                        @endforeach
	                    </select>
	                    <div style="font-size: 10px; margin: 5px; color: red;" class="err-user_id"></div>
	                </div>
	                <div class="form-group mb-1">
	                    <label>Keluh Kesan</label>
	                    <textarea name="keluh_kesan" class="form-control" rows="5">{!! $keluhKesan->keluh_kesan !!}</textarea>
	                </div>
	                <br />
	                <div class="form-group">
	                    <label>Image<span class="text-danger">*</span></label>
	                    <div></div>
	                    <div class="custom-file">
	                        <input name="file_image" type="file" class="custom-file-input" id="customFile" />
	                        <label class="custom-file-label" for="customFile">Choose file </label>
	                        <div style="font-size: 10px; margin: 5px; color: red;" class="err-file_image"></div>
	                    </div>
	                    <div class="imageContainerEdit">
	                        <img class="imagePicture" src="{{ helper::imageLoad('keluh_kesan', $keluhKesan->file_image) }}" />
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-6">
	            	<h3>Balasan</h3>
	            	<div class="container-balasan">
	            		@forelse($keluhKesan->balasan as $balasan)
	            			<div class="wrapper-balasan" data-id="{{ $balasan->balas_keluh_kesan_id }}">
	            				<p class="username-balasan">
	            					{{ $balasan->user->username }}
	            				
	            				<span title="Edit balasan" class="action-balasan btn-edit-balasan"><i class="fa fa-pen"></i></span>
	            				<span title="Hapus balasan" class="action-balasan btn-hapus-balasan"><i class="fa fa-trash"></i></span>
	            				</p>
	            				<p class="isi-balasan">
	            					@if($balasan->file_image)
	            						<img class="img-balasan" src={{ helper::imageLoad('balas_keluh_kesan', $balasan->file_image) }} />
	            					@endif
	            					{{ $balasan->balas_keluh_kesan }}
	            				</p>
	            				<span class="waktu-balasan">
            						{{ $balasan->created_at->diffForHumans() }}
            					</span>
	            			</div>
	            		@empty
		            		<p>Belum ada balasan</p>
						@endforelse
						<button type="button" class="btn btn-success btn-buat-balasan">Buat Balasan</button>
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

<!-- Modal -->
<div class="modal fade" id="modal-buat-balasan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buat Balasan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form id="form-buat-balasan">
	        <div class="form-group">
	            <label>User <span class="text-danger">*</span></label>
	            <select name="user_id" class="form-control" required>
	                @foreach($users as $user)
	                    <option value="{{ $user->user_id }}">
	                        {{ $user->username }}
	                    </option>
	                @endforeach
	            </select>
	            <div style="font-size: 10px; margin: 5px; color: red;" class="err-user_id"></div>
	        </div>
	        <br />
	        <div class="form-group">
	            <label>Image<span class="text-danger">*</span></label>
	            <div></div>
	            <div class="custom-file">
	                <input name="file_image" type="file" class="custom-file-input" id="customFile" />
	                <label class="custom-file-label" for="customFile">Choose file </label>
	                <div style="font-size: 10px; margin: 5px; color: red;" class="err-file_image"></div>
	            </div>
	            <div class="imageContainerEdit">
	                <img class="imagePicture" src="{{asset('images/NoPic.png')}}" />
	            </div>
	        </div>
	        <div class="form-group mb-1">
	            <label>Balasan <span class="text-danger">*</span></label>
	            <textarea name="balas_keluh_kesan" class="form-control" rows="5"></textarea>
	        </div>
	    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button id="btn-action-balasan" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
	$(() => {

        $('body').on('change', 'input[name="file_image"]', function(e){
            const preview = $(this).parent().next().find('.imagePicture')
            preview.attr('src',  URL.createObjectURL(event.target.files[0]));
        })

        $('body').on('click', '.btn-buat-balasan', async function(e){
            e.preventDefault()

            $('#form-buat-balasan').trigger("reset");
            $('#form-buat-balasan input[name="file_image"]').val(null);
            $('#form-buat-balasan input[name="file_image"]').next().html('')
            $('#form-buat-balasan .imagePicture').attr('src', @json(asset('images/NoPic.png')) );
            $('#modal-buat-balasan .modal-title').html('Buat Balasan')
            $('#btn-action-balasan').removeClass('btn-update-balasan').addClass('btn-simpan-balasan')
            $('#modal-buat-balasan').modal('toggle')
        })

        $('body').on('click', '.btn-simpan-balasan', async function(e){
        	const url = @json(route('keluhKesan.keluhKesan.storeBalasan'));
        	const form = $('#form-buat-balasan')[0];
        	const formData = new FormData(form);
        	formData.append('keluh_kesan_id',@json($keluhKesan->keluh_kesan_id))

        	return await fetch(url, {
		      headers: {
		        "X-Requested-With": "XMLHttpRequest",
		        "X-CSRF-TOKEN": @json(csrf_token()),
		      },
		      method: "post",
		      body: formData
		    })
		      .then((response) => response.json())
		      .then((data) => {
		      	if(data.status) {
		      		$('#modal-buat-balasan').modal('toggle')
	      			swal.fire('Balasan berhasil disimpan','','success')
	      				.then((next) => next && window.location.reload())
		      	}
		      })
		      .catch(function () {
		        swal.fire('Gagal menyimpan','Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...','warning')
		        return;
		      });
        })

        $('body').on('click', '.btn-update-balasan', async function(e){
        	const url = @json(route('keluhKesan.keluhKesan.updateBalasan'));
        	const idBalasan = $('#modal-buat-balasan').attr('data-id');
        	const form = $('#form-buat-balasan')[0];
        	const formData = new FormData(form);
        	formData.append('balasan_id', idBalasan)

        	return await fetch(url, {
		      headers: {
		        "X-Requested-With": "XMLHttpRequest",
		        "X-CSRF-TOKEN": @json(csrf_token()),
		      },
		      method: "post",
		      body: formData
		    })
		      .then((response) => response.json())
		      .then((data) => {
		      	if(data.status) {
		      		$('#modal-buat-balasan').modal('toggle')
		      		swal.fire('Balasan berhasil diupdate','','success')
		      			.then((next) => next && window.location.reload())
		      	}
		      })
		      .catch(function () {
		        swal.fire('Gagal menyimpan','Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...','warning')
		        return;
		      });
        })

        $('body').on('click', '.btn-edit-balasan', async function(e){
        	const idBalasan = $(this).parent().parent().attr('data-id')
        	let url = @json(route('keluhKesan.keluhKesan.getBalasan', 'url_placeholder'));
        		  url = url.replace('url_placeholder', idBalasan);
        	const balasan = await fetch(url)
        						.then(res => res.json())

        	$('#form-buat-balasan select[name=user_id]').val(balasan.user_id);
            $('#form-buat-balasan .imagePicture').attr('src', balasan.file_image_src );
            $('#form-buat-balasan textarea[name=balas_keluh_kesan]').val(balasan.balas_keluh_kesan);
            $('#modal-buat-balasan .modal-title').html('Edit Balasan')
            $('#btn-action-balasan').removeClass('btn-simpan-balasan').addClass('btn-update-balasan')
            $('#modal-buat-balasan').attr('data-id', idBalasan)
            $('#modal-buat-balasan').modal('toggle')
        })

        $('body').on('click', '.btn-hapus-balasan', async function(e){
        	swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data balasan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Ya, saya yakin!"
            })
            .then(async ({ value }) => {
            	if(value) {
            		const url = @json(route('keluhKesan.keluhKesan.destroyBalasan'));
		        	const idBalasan = $(this).parent().parent().attr('data-id')

		        	return await fetch(url, {
				      headers: {
				      	"Content-Type": "application/json",
				        "X-Requested-With": "XMLHttpRequest",
				        "X-CSRF-TOKEN": @json(csrf_token()),
				      },
				      method: "post",
				      body: JSON.stringify({ balasan_id: idBalasan }),
				    })
				      .then((response) => response.json())
				      .then((data) => {
				      	if(data.status) {
				      		swal.fire('Balasan berhasil dihapus','','success')
				      			.then((next) => next && window.location.reload())
				      	}
				      })
				      .catch(function () {
				        swal.fire('Gagal menghapus','Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...','warning')
				        return;
				      });
            	}
		    })
        })
	})
</script>
@endsection
