@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.breadcumb')

    <div class="card card-custom gutter-b">
			<form id="headerForm" action="{{route('master.permission.store')}}" method="POST">
				@csrf
				<div class="card-body">
					<div class="form-group">
						<label>Nama Permission <span class="text-danger">*</span></label>
						<input type="text" name="permission_name" class="form-control"  placeholder="Masukan Nama Permission"/>
						<div style="font-size:10px;margin:5px;color:red" class="err-permission_name"></div>
					</div>
					<div class="form-group">
						<label>Aksi Permission <span class="text-danger">*</span></label>
						<input type="text" name="permission_action" class="form-control"  placeholder="Masukan Aksi Permission"/>
						<div style="font-size:10px;margin:5px;color:red" class="err-permission_action"></div>
					</div>
					<div class="form-group mb-1">
						<label for="exampleTextarea">Deskripsi <span class="text-danger">*</span></label>
						<textarea name="description" class="form-control" id="exampleTextarea" rows="3"></textarea>
						<div style="font-size:10px;margin:5px;color:red" class="err-description"></div>
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

		const res = await fetch("{{route('master.permission.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}"
						},
						method:'post',
						body:formData
					});

		const {status,errors} = await res.json();
		
		if(status === 'success'){

			localStorage.setItem('success','Permission Berhasil Ditambahkan');

			return location.replace('{{route("master.permission.index")}}');
		}else{

			const {permission_name, permission_action, description} = errors;

			document.querySelector('.err-permission_name').innerHTML = permission_name ? permission_name : '';
			document.querySelector('.err-permission_action').innerHTML = permission_action ? permission_action : '';
			document.querySelector('.err-description').innerHTML = description ? description : '';
		}
	}

	document.querySelector('.submit').addEventListener('click', (e) => {
		e.preventDefault();
		storeData();
	})

</script>
@endsection