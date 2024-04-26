@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.breadcumb')

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="#" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Nama <span class="text-danger">*</span></label>
					<input type="text" name="role_name" class="form-control"  placeholder="Masukan Nama Role" value="{{$data->role_name}}" disabled />
					<div style="font-size:10px;margin:5px;color:red" class="err-role_name"></div>
				</div>
				<div class="form-group mb-1">
					<label for="exampleTextarea">Deskripsi</label>
					<textarea name="description" class="form-control" id="exampleTextarea" rows="3" disabled>{{$data->description}}</textarea>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
<script>
@endsection