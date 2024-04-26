@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{route('master.user.store')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Username<span class="text-danger">*</span></label>
							<input type="text" name="username" class="form-control"  placeholder="Masukan Nama Username"/>
							<div style="font-size:10px;margin:5px;color:red" class="err-username"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Email <span class="text-danger">*</span></label>
							<input type="text" name="email" class="form-control"  placeholder="Masukan Nama email"/>
							<div style="font-size:10px;margin:5px;color:red" class="err-email"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Password <span class="text-danger">*</span></label>
							<input type="password" name="password" class="form-control"  placeholder="Masukan Nama Password"/>
							<div style="font-size:10px;margin:5px;color:red" class="err-password"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Konfirmasi Password <span class="text-danger">*</span></label>
							<input type="password" name="password_confirmation" class="form-control"  placeholder="Konfirmasi Password"/>
							<div style="font-size:10px;margin:5px;color:red" class="err-password-confirmation"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Role <span class="text-danger">*</span></label>
							<select name="role_id" class="form-control">
								<option value="">Pilih Role</option>
								@foreach($roles as $r)
								<option value="{{$r->role_id}}">{{$r->role_name}}</option>
								@endforeach
							</select>
							<div style="font-size:10px;margin:5px;color:red" class="err-role_id"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Photo</label>
							<input type="file" name="picture" class="form-control"  placeholder="Masukan Photo"/>
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
	<script>

		const storeData = async () => {

			const formData = new FormData(document.getElementById('headerForm'));

			const res = await fetch("{{route('master.user.store')}}",{
				headers:{
					"X-CSRF-TOKEN":"{{ csrf_token() }}"
				},
				method:'post',
				body:formData
			});

			const {status,errors} = await res.json();

			if(status === 'success'){
				localStorage.setItem('success','User Berhasil Ditambahkan');

				return location.replace('{{route("master.user.index")}}');
			}else{

				const {username, email, password, password_confirmation, role_id, picture} = errors;

				document.querySelector('.err-username').innerHTML = username ? username : '';
				document.querySelector('.err-email').innerHTML = email ? email : '';
				document.querySelector('.err-password').innerHTML = password ? password : '';
				document.querySelector('.err-password-confirmation').innerHTML = password_confirmation ? password_confirmation : '';
				document.querySelector('.err-role_id').innerHTML = role_id ? 'Role wajib diisi' : '';
				document.querySelector('.err-picture').innerHTML = picture ? picture : '';
			}
		}

		document.querySelector('.submit').addEventListener('click', (e) => {
			e.preventDefault();
			storeData();
		})

	</script>
	@endsection