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
</style>
<div class="container">
    <div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail keluh kesan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-body">
        	<div class="row">
	            <div class="col-md-6">
	            	<h3>Keluh Kesan</h3>
	                <div class="form-group">
	                    <label>User</label>
	                    <input type="text" name="user" class="form-control" value="{{ $keluhKesan->user->username }}" disabled />
	                </div>
	                <div class="form-group mb-1">
	                    <label>Keluh Kesan</label>
	                    <textarea name="keluh_kesan" class="form-control" rows="5" disabled>{!! $keluhKesan->keluh_kesan !!}</textarea>
	                </div>
	                <br />
	                <div class="form-group">
	                    <label>Image</label>
	                    <div class="imageContainerEdit">
	                        <img class="imagePicture" src="{{ helper::imageLoad('keluh_kesan', $keluhKesan->file_image) }}" />
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-6">
	            	<h3>Balasan</h3>
	            	<div class="container-balasan">
	            		@forelse($keluhKesan->balasan as $balasan)
	            			<div class="wrapper-balasan">
	            				<p class="username-balasan">
	            					{{ $balasan->user->username }}
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
	            	</div>
	            </div>
	        </div>
        </div>
        <div class="card-footer">
            @include('partials.buttons.submit')
        </div>
    </div>
</div>
@endsection
