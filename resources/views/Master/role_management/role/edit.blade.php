@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('master.role.update', $data->role_id) }}"  method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<div class="form-group">
					<label>Nama <span class="text-danger">*</span></label>
					<input type="text" name="role_name" class="form-control"  placeholder="Masukan Nama Role" value="{{$data->role_name}}" />
					<div style="font-size:10px;margin:5px;color:red" class="err-role_name"></div>
				</div>
				<div class="form-group mb-1">
					<label for="exampleTextarea">Deskripsi</label>
					<textarea name="description" class="form-control" id="exampleTextarea" rows="3">{{$data->description}}</textarea>
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

			const res = await fetch(`{{route('master.role.update', '')}}/{{\Request::segment(3)}}`,{
				headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"},
				method:'post',
				body:formData
			});

			const {status,errors} = await res.json();

			if(status === 'success'){

				localStorage.setItem('success','Role Berhasil Diupdate');

				return location.replace('{{route("master.role.index")}}');
			}else{
				const {role_name} = errors;
				document.querySelector('.err-role_name').innerHTML = role_name ? role_name : '';
			}
		}

		document.querySelector('.submit').addEventListener('click', (e) => {
			e.preventDefault();
			storeData();
		})
	</script>
	@endsection