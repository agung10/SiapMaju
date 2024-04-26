@extends('layouts.master') 
@section('content')
<div class="container">
    <div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah keluh kesan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <form id="headerForm" action="{{route('keluhKesan.keluhKesan.store')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="card-body">
            	<div class="col-md-8">
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
	                <div class="form-group mb-1">
	                    <label>Keluh Kesan <span class="text-danger">*</span></label>
	                    <textarea name="keluh_kesan" class="form-control" rows="5" required></textarea>
	                    <div style="font-size: 10px; margin: 5px; color: red;" class="err-kajian"></div>
	                </div>
	                <div class="form-group">
	                    <label>Image<span class="text-danger">*</span></label>
	                    <div></div>
	                    <div class="custom-file">
	                        <input name="file_image" type="file" class="custom-file-input" id="customFile" required/>
	                        <label class="custom-file-label" for="customFile">Choose file </label>
	                        <div style="font-size: 10px; margin: 5px; color: red;" class="err-file_image"></div>
	                    </div>
	                    <div class="imageContainerEdit">
	                        <img class="imagePicture" src="{{asset('images/NoPic.png')}}" />
	                    </div>
	                </div>
	            </div>
            </div>
            <div class="card-footer">
                @include('partials.buttons.submit')
            </div>
        </form>
    </div>
    <script>
    	$(() => {
    		const imageField = document.querySelector('input[name="file_image"]')

	        if(imageField){

	            imageField.addEventListener('change',(event)=> {
	                const output = document.querySelector('.imagePicture');

	                output.src = URL.createObjectURL(event.target.files[0]);
	                output.onload = () => {
	                    URL.revokeObjectURL(output.src)
	                }
	            });
	        }
    	})
    </script>
@endsection