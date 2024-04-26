@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="javascript::void(0)"  method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Nama Permission <span class="text-danger">*</span></label>
					<input type="text" name="permission_name" class="form-control"  placeholder="Masukan Nama Permission" value="{{$data->permission_name}}" disabled />
					<div style="font-size:10px;margin:5px;color:red" class="err-permission_name"></div>
				</div>
				<div class="form-group">
					<label>Aksi Permission <span class="text-danger">*</span></label>
					<input type="text" name="permission_action" class="form-control"  placeholder="Masukan Aksi Permission" value="{{$data->permission_action}}" disabled/>
					<div style="font-size:10px;margin:5px;color:red" class="err-permission_action"></div>
				</div>
				<div class="form-group mb-1">
					<label for="exampleTextarea">Deskripsi <span class="text-danger">*</span></label>
					<textarea name="description" class="form-control" id="exampleTextarea" rows="3" disabled>{{$data->description}}</textarea>
					<div style="font-size:10px;margin:5px;color:red" class="err-description"></div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
	@endsection