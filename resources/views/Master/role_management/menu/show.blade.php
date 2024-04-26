@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" method="POST">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Menu<span class="text-danger">*</span></label>
							<input type="text" name="name" class="form-control"  placeholder="Masukan Nama Menu" value="{{$data->name}}" disabled />
							<div style="font-size:10px;margin:5px;color:red" class="err-name"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Route </label>
							<input type="text" name="route" class="form-control"  placeholder="Masukan Nama Route" value="{{$data->route}}" disabled/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Menu Induk <span class="text-danger">*</span></label>
							<select name="id_parent" class="form-control" disabled>
								@foreach($menuList as $key => $value)
								<option value="{{$key}}" {{ $key == $data->id_parent ? 'selected' : '' }} >{!! $value !!} </option>
								@endforeach
							</select>
							<div style="font-size:10px;margin:5px;color:red" class="err-id_parent"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Icon</label>
							<input type="text" name="icon" class="form-control"  placeholder="Masukan Nama Role" value="{{$data->icon}}" disabled/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Status <span class="text-danger">*</span></label>
							<select name="is_active" class="form-control" disabled>
								@foreach($statusList as $key => $value)
								<option value="{{$key}}" {{ $key == $data->is_active ? 'selected' : '' }}>{{$value}}</option>
								@endforeach
							</select>
							<div style="font-size:10px;margin:5px;color:red" class="err-is_active"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Urutan Menu <span class="text-danger">*</span></label>
							<input type="text" name="order" class="form-control"  placeholder="Masukan urutan Menu" value="{{$data->order}}" disabled/>
							<div style="font-size:10px;margin:5px;color:red" class="err-order"></div>
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