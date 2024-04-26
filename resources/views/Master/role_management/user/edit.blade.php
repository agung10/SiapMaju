@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('master.user.update', $data->user_id) }}"  method="POST" enctype="multipart/form-data">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Username<span class="text-danger">*</span></label>
							<input type="text" name="username" class="form-control"  placeholder="Masukan Username" value="{{$data->username}}" />
							<div style="font-size:10px;margin:5px;color:red" class="err-username"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Email <span class="text-danger">*</span></label>
							<input type="text" name="email" class="form-control"  placeholder="Masukan email" value="{{$data->email}}" />
							<div style="font-size:10px;margin:5px;color:red" class="err-email"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Password</label>
							<input type="password" name="password" class="form-control"  placeholder="Masukan Password" />
							<div style="font-size:10px;margin:5px;color:red" class="err-password"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Konfirmasi Password</label>
							<input type="password" name="password_confirmation" class="form-control"  placeholder="Konfirmasi Password"/>
							<div style="font-size:10px;margin:5px;color:red" class="err-password-confirmation"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="hidden" name="user_role_id" value="{{$user_role_id}}">
							<label>Role <span class="text-danger">*</span></label>
							<select name="role_id" class="form-control">
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
							<input type="file" name="picture" class="form-control"  placeholder="Masukan Photo" />
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

			const res = await fetch(`{{route('master.user.update', '')}}/{{\Request::segment(3)}}`,{
				headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"},
				method:'post',
				body:formData
			});

			const {status,errors} = await res.json();

			if(status === 'success'){

				localStorage.setItem('success','User Berhasil Diupdate');

				return location.replace('{{route("master.user.index")}}');
			}else{
				const {username, email, password, password_confirmation, role_id, picture} = errors;

				document.querySelector('.err-username').innerHTML = username ? username : '';
				document.querySelector('.err-email').innerHTML = email ? email : '';
				document.querySelector('.err-password').innerHTML = password ? password : '';
				document.querySelector('.err-password-confirmation').innerHTML = password_confirmation ? password_confirmation : '';
				document.querySelector('.err-role_id').innerHTML = role_id ? role_id : '';
				document.querySelector('.err-picture').innerHTML = picture ? picture : '';
			}
		}

		document.querySelector('.submit').addEventListener('click', (e) => {
			e.preventDefault();
			storeData();
		})
	</script>
	@endsection