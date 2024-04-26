@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('master.menu.update', $data->menu_id) }}"  method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Menu<span class="text-danger">*</span></label>
							<input type="text" name="name" class="form-control"  placeholder="Masukan Nama Menu" value="{{$data->name}}" />
							<div style="font-size:10px;margin:5px;color:red" class="err-name"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Route </label>
							<input type="text" name="route" class="form-control"  placeholder="Masukan Nama Route" value="{{$data->route}}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Menu Induk <span class="text-danger">*</span></label>
							<select name="id_parent" class="form-control">
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
							<input type="text" name="icon" class="form-control"  placeholder="Masukan Nama Role" value="{{$data->icon}}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Status <span class="text-danger">*</span></label>
							<select name="is_active" class="form-control">
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
							<input type="text" name="order" class="form-control"  placeholder="Masukan urutan Menu" value="{{$data->order}}" />
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
	<script>
		const storeData = async () => {

			const formData = new FormData(document.getElementById('headerForm'));

			const res = await fetch(`{{route('master.menu.update', '')}}/{{\Request::segment(3)}}`,{
				headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"},
				method:'post',
				body:formData
			});

			const {status,errors} = await res.json();

			if(status === 'success'){

				localStorage.setItem('success','Menu Berhasil Diupdate');

				return location.replace('{{route("master.menu.index")}}');
			}else{
				const {name, id_parent, is_active, order} = errors;

				document.querySelector('.err-name').innerHTML = name ? name : '';
				document.querySelector('.err-id_parent').innerHTML = id_parent ? id_parent : '';
				document.querySelector('.err-is_active').innerHTML = is_active ? is_active : '';
				document.querySelector('.err-order').innerHTML = order ? order : '';
			}
		}

		document.querySelector('.submit').addEventListener('click', (e) => {
			e.preventDefault();
			storeData();
		})
	</script>
	@endsection