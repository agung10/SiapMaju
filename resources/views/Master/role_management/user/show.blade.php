@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="javascript:void(0)" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Username</label>
							<input type="text" name="username" class="form-control"  placeholder="Masukan Username" value="{{$data->username}}" disabled />
							<div style="font-size:10px;margin:5px;color:red" class="err-username"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Email</label>
							<input type="text" name="email" class="form-control"  placeholder="Masukan email" value="{{$data->email}}" disabled />
							<div style="font-size:10px;margin:5px;color:red" class="err-email"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Role</label>
							<select name="role_id" class="form-control" disabled>
								<option value="">Pilih Role</option>
								@foreach($roles as $r)
								<option value="{{$r->role_id}}" {{$data->role->role_id == $r->role_id ? 'selected' : ''}}>{{$r->role_name}}</option>
								@endforeach
							</select>
							<div style="font-size:10px;margin:5px;color:red" class="err-role_id"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Photo</label>
							<input type="file" name="picture" class="form-control"  placeholder="Masukan Photo" disabled />
							<div style="font-size:10px;margin:5px;color:red" class="err-picture"></div>
						</div>
					</div>
				</div>

			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
	@endsection