@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Peraturan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Kajian.Konten.update', $data->kajian_id) }}" enctype="multipart/form-data" method="POST">
			@csrf
			@method('PUT')
			<div class="card-body">
				<h4>Data Peraturan</h4>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="imageContainerEdit">
								<img class="imagePicture" src="{{asset('images/NoPic.png')}}" />
							</div><br>
							<label>Image<span class="text-danger">*</span></label>
							<div class="custom-file">
								<input name="image" type="file" class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">{{$data->image}}</label>
								{{-- <div style="font-size:10px;margin:5px;color:red" class="err-image"></div> --}}
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleSelect1">Kategori Peraturan <span class="text-danger">*</span></label>
							<select name="kat_kajian_id" class="form-control" id="exampleSelect1">
								{!! $kat_kajian !!}
							</select>
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-kat_kajian_id"></div> --}}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Judul </label>
							<input type="text" value="{{!empty($data->judul) ? $data->judul : '' }}" name="judul"
								class="form-control" placeholder="Masukan Judul" />
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-judul"></div> --}}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Penulis </label>
							<input type="text" value="{{!empty($data->author) ? $data->author : '' }}" name="author"
								class="form-control" placeholder="Masukan Judul" />
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-author"></div> --}}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleTextarea">Peraturan <span class="text-danger">*</span></label>
							<textarea name="kajian" class="form-control" id="exampleTextarea"
								rows="3">{{!empty($data->kajian) ? $data->kajian : '' }}</textarea>
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-kajian"></div> --}}
						</div>
					</div>
					@if ($data->upload_materi_1)
					<div class="col-md-12">
						<div class="form-group materi1">
							<label>Materi 1 </label>
							<div></div>
							<div class="inputContainer">
								<div class="custom-file" style="width: 650px;">
									<input onChange="putFileName(event)" name="upload_materi_1" type="file"
										class="custom-file-input" id="customFile" />
									<label class="custom-file-label" for="customFile">{{$data->upload_materi_1}}</label>
								</div>
								<a style="width:45px;" data-materi="{{$data->upload_materi_1}}"
									onClick="clickHandler(event)" class="btn btn-warning mt-3"><i
										class="flaticon2-search"></i></a>
								<a style="width:45px;display:none" class="btn btn-primary mt-3 showButton"><i
										class="flaticon2-plus"></i></a>
								<a style="width:45px;display:none" class="btn btn-danger mt-3 hideButton"><i
										class="flaticon2-cross"></i></a>
								{{-- <a style="width:45px;" class="btn btn-primary mt-3 resetButton"><i
										class="flaticon2-refresh"></i></a> --}}
							</div>
						</div>
					</div>
					@endif
					@if ($data->upload_materi_2)
					<div class="col-md-12">
						<div class="form-group materi1">
							<label>Materi 2 </label>
							<div></div>
							<div class="inputContainer">
								<div class="custom-file" style="width: 650px;">
									<input onChange="putFileName(event)" name="upload_materi_2" type="file"
										class="custom-file-input" id="customFile" />
									<label class="custom-file-label" for="customFile">{{$data->upload_materi_2}}</label>
								</div>
								<a style="width:45px;" data-materi="{{$data->upload_materi_2}}"
									onClick="clickHandler(event)" class="btn btn-warning mt-3"><i
										class="flaticon2-search"></i></a>
								<a style="width:45px;display:none" class="btn btn-primary mt-3 showButton"><i
										class="flaticon2-plus"></i></a>
								<a style="width:45px;display:none" class="btn btn-danger mt-3 hideButton"><i
										class="flaticon2-cross"></i></a>
								{{-- <a style="width:45px;" class="btn btn-primary mt-3 resetButton"><i
										class="flaticon2-refresh"></i></a> --}}
							</div>
						</div>
					</div>
					@endif
					@if ($data->upload_materi_3)
					<div class="col-md-12">
						<div class="form-group materi1">
							<label>Materi 3 </label>
							<div></div>
							<div class="inputContainer">
								<div class="custom-file" style="width: 650px;">
									<input onChange="putFileName(event)" name="upload_materi_3" type="file"
										class="custom-file-input" id="customFile" />
									<label class="custom-file-label" for="customFile">{{$data->upload_materi_3}}</label>
								</div>
								<a style="width:45px;" data-materi="{{$data->upload_materi_3}}"
									onClick="clickHandler(event)" class="btn btn-warning mt-3"><i
										class="flaticon2-search"></i></a>
								<a style="width:45px;display:none" class="btn btn-primary mt-3 showButton"><i
										class="flaticon2-plus"></i></a>
								<a style="width:45px;display:none" class="btn btn-danger mt-3 hideButton"><i
										class="flaticon2-cross"></i></a>
								{{-- <a style="width:45px;" class="btn btn-primary mt-3 resetButton"><i
										class="flaticon2-refresh"></i></a> --}}
							</div>
						</div>
					</div>
					@endif
					@if ($data->upload_materi_4)
					<div class="col-md-12">
						<div class="form-group materi1">
							<label>Materi 4 </label>
							<div></div>
							<div class="inputContainer">
								<div class="custom-file" style="width: 650px;">
									<input onChange="putFileName(event)" name="upload_materi_4" type="file"
										class="custom-file-input" id="customFile" />
									<label class="custom-file-label" for="customFile">{{$data->upload_materi_4}}</label>
								</div>
								<a style="width:45px;" data-materi="{{$data->upload_materi_4}}"
									onClick="clickHandler(event)" class="btn btn-warning mt-3"><i
										class="flaticon2-search"></i></a>
								<a style="width:45px;display:none" class="btn btn-primary mt-3 showButton"><i
										class="flaticon2-plus"></i></a>
								<a style="width:45px;display:none" class="btn btn-danger mt-3 hideButton"><i
										class="flaticon2-cross"></i></a>
								{{-- <a style="width:45px;" class="btn btn-primary mt-3 resetButton"><i
										class="flaticon2-refresh"></i></a> --}}
							</div>
						</div>
					</div>
					@endif
					@if ($data->upload_materi_5)
					<div class="col-md-12">
						<div class="form-group materi1">
							<label>Materi 5 </label>
							<div></div>
							<div class="inputContainer">
								<div class="custom-file" style="width: 650px;">
									<input onChange="putFileName(event)" name="upload_materi_5" type="file"
										class="custom-file-input" id="customFile" />
									<label class="custom-file-label" for="customFile">{{$data->upload_materi_5}}</label>
								</div>
								<a style="width:45px;" data-materi="{{$data->upload_materi_5}}"
									onClick="clickHandler(event)" class="btn btn-warning mt-3"><i
										class="flaticon2-search"></i></a>
								<a style="width:45px;display:none" class="btn btn-primary mt-3 showButton"><i
										class="flaticon2-plus"></i></a>
								<a style="width:45px;display:none" class="btn btn-danger mt-3 hideButton"><i
										class="flaticon2-cross"></i></a>
								{{-- <a style="width:45px;" class="btn btn-primary mt-3 resetButton"><i
										class="flaticon2-refresh"></i></a> --}}
							</div>
						</div>
					</div>
					@endif
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan<span class="text-danger">*</span></label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								{{!! $resultKelurahan !!}}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW<span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw">
								{{!! $resultRW !!}}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT<span class="text-danger">*</span></label>
							<select class="form-control" name="rt_id" id="rt">
								{{!! $resultRT !!}}
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

	<div id="videoModal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<video width="450" height="240" autoplay class="videoContent" src="" controls></video>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Kajian\KontenRequest','#headerForm') !!}
<script>
	document.addEventListener('DOMContentLoaded',()=>{
		createMateriInput()
		showPicture()
	})

	const showPicture = () =>{
		const picture = '{{$data->image}}'

		if(picture){
			document.querySelector('.imagePicture').src = '{{asset("upload/kajian/image/$data->image")}}'
		}
	}

	const createMateriInput = async () => {

		const res = await fetch(`{{route('Kajian.Kontent.checkMateri','')}}/{{(\Request::segment(3))}}`,{
											headers:{'X-CSRF-TOKEN':'{{csrf_token()}}'},
											method:'post'
										})
		const {materi1,materi2,materi3,materi4,materi5}  = await res.json()
		
		const materi = {
			materi1,
			materi2,
			materi3,
			materi4,
			materi5,
		}

		let count = 0

		Object.values(materi).map(i => {
									if(i !== null){
										count++
									}
							   	})

		localStorage.setItem('indexMateri',count)

		const createMateri = (num,parentIndex,materi) => {

			const content = materi ? materi : 'Tidak ada materi';
			const showButton = `<a style="width:45px;" data-materi="${content}"  onClick="clickHandler(event)" class="btn btn-warning mt-3"><i class="flaticon2-search"></i></a>`
			const deleteButton = `<a style="width:45px;" data-materiNo="${num}" onClick="deleteHandler(event)" class="btn btn-danger mt-3"><i class="flaticon2-trash"></i></a>`

			const el = `<div class="form-group materi${num}">
						<label>Materi ${num} </label>
						<div></div>
						<div class="inputContainer">
							<div class="custom-file" style="width: 650px;">
								<input onChange="putFileName(event)" name="upload_materi_${num}" type="file" class="custom-file-input" id="customFile"/>
								<label class="custom-file-label" for="customFile">${content}</label>
								<div style="font-size:10px;margin:5px;color:red" class="err-upload_materi_${num}"></div>
							</div>
							${materi ? showButton : ''}
							${materi ? deleteButton : ''}
						</div>
					</div>`
			
			const elMateri = document.querySelector(`.materi${parentIndex}`)

				if(elMateri){
					return elMateri.insertAdjacentHTML('afterend',el)
				}					   
		}

		if(count > 1){
			document.querySelector('.hideButton').style.display = ''
		}

			createMateri(2,1,materi2)
			createMateri(3,2,materi3)
			createMateri(4,3,materi4)
			createMateri(5,4,materi5)
	}

	const clickHandler = (e) => {

		const materi = () => {
			if(e.target.tagName === 'I'){
				return e.target.parentElement.getAttribute('data-materi')
			}else if(e.target.tagName === 'A'){
				return e.target.getAttribute('data-materi')
			}else{
				return;
			}
		}
	
		const extension = materi().split('.')[1].toLowerCase()
		
		if(extension === 'jpg' || extension === 'jpeg' ||extension === 'png' ){

			const el = document.querySelector('.videoContent')

            el.style.display = 'none'

            const imgEl = `<img src="{{asset('upload/kajian/${materi()}')}}"  class="imgMateri" />`

            el.insertAdjacentHTML('afterend',imgEl)
		}

		if(extension === 'mp4'){

            document.querySelector('.videoContent').setAttribute('src',`{{asset('upload/kajian')}}/${materi()}`)
        }

		if(extension === 'pdf' || extension === 'docx' || extension === 'doc'){
            window.open(`{{asset('upload/kajian')}}/${materi()}`)

			return;
        }

		$('#videoModal').modal('show')
	}

	const deleteHandler = (e) => {

			if(e.target.tagName === 'I'){
				const i = e.target.parentElement.getAttribute('data-materiNo')

				return deleteMateri(i)
			}else if(e.target.tagName === 'A'){
				const i = e.target.getAttribute('data-materiNo')

				return deleteMateri(i)
			}else{
				return;
			}
	}

	const deleteMateri = async (noMateri) => {

		const res = await fetch(`{{route('Kajian.Konten.deleteMateri','')}}/{{\Request::segment(3)}}`,{
									headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}',
											 "Content-Type": "application/json",},
									method:'post',
									body:JSON.stringify({noMateri})
								})

		const {status} = await res.json()

		if(status === 'success'){
			document.querySelector(`input[name="upload_materi_${noMateri}"]`)
					.nextSibling
					.nextSibling
					.innerHTML = ''
		}
	}

	document.querySelector('.showButton').addEventListener('click',()=>{

		const i = localStorage.getItem('indexMateri');
		
		const el = `<div class="form-group materi${parseInt(i)+1}">
						<label>Materi ${parseInt(i)+1} </label>
						<div></div>
						<div class="inputContainer">
							<div class="custom-file" style="width: 790px;">
								<input onChange="putFileName(event)" name="upload_materi_${parseInt(i)+1}" type="file" class="custom-file-input" id="customFile"/>
								<label class="custom-file-label" for="customFile">Choose file </label>
								<div style="font-size:10px;margin:5px;color:red" class="err-upload_materi_${parseInt(i)+1}"></div>
							</div>
						</div>
					</div>`

		if(parseInt(i)+1 < 6){

			const formParent = document.querySelector(`.materi${i}`)

			formParent.insertAdjacentHTML('beforeend',el)

			localStorage.setItem('indexMateri',parseInt(i)+1)
		}

		if(i >= 1){
			const element = document.querySelector('.hideButton')
									.style.display = ''
		}
	})

	document.querySelector('.hideButton').addEventListener('click',()=>{

		const storage = JSON.stringify(parseInt(localStorage.getItem('indexMateri')))

		if(storage > 1){
			
			const el = document.querySelector(`.materi${storage}`)
			
			if(el){
				el.remove()
			}

			const i = storage >= 1 ? storage-1 : storage-0;

			localStorage.setItem('indexMateri',i)

			if(i === 1){
				document.querySelector('.hideButton').style.display = 'none'
			}
		}
	})

	// document.querySelector('.resetButton').addEventListener('click',()=>{

	// 	const materi1 = document.querySelector('.materi1')
	// 	const materi2 = document.querySelector('.materi2')
	// 	const materi3 = document.querySelector('.materi3')
	// 	const materi4 = document.querySelector('.materi4')
	// 	const materi5 = document.querySelector('.materi5')

	// 	if(materi2){
	// 		materi2.remove()
	// 	}

	// 	if(materi3){
	// 		materi3.remove()
	// 	}

	// 	if(materi4){
	// 		materi4.remove()
	// 	}

	// 	if(materi5){
	// 		materi5.remove()
	// 	}

	// 	createMateriInput()

	// 	const i = localStorage.getItem('indexMateri');

	// 	if(i == 1){
	// 		document.querySelector('.hideButton').style.display = 'none'
	// 	}
	// })

	const editData = async () => {
		loading()
		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Kajian.Konten.update','')}}/{{\Request::segment(3)}}`,{
									headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
									method:'post',
									body:formData
							   })
							   .then(response => response.json())
								.catch(() => {
									KTApp.unblockPage()
									swal.fire('Maaf Terjadi Kesalahan','','error')
										.then(result => {
											if(result.isConfirmed) window.location.reload()
										})
								})
							}
		const {status} = await res();

		if(status === 'success'){

			localStorage.setItem('success','Peraturan Berhasil Diupdate');
			return location.replace('{{route("Kajian.Konten.index")}}');
		}		
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		editData();
	})

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}
	
	$(document).ready(() => {
        $('#videoModal').on('hide.bs.modal',() => {
            document.querySelector('.videoContent').pause();

            document.querySelector('.videoContent').style.display = ''

            const imgEl = document.querySelector('.imgMateri')

            if(imgEl){
                imgEl.remove();
            }
        })
    })

	const putFileName = (e) => {

		const i = e.target
				    .getAttribute('name')
					.split('_')[2]

		const deleteButton = `<a style="width:45px;margin-left:10px;" onClick="clearInput('${i}')" class="btn btn-danger mt-3 clearBtn${i}"><i class="flaticon2-trash"></i></a>`

		const deleteEl = document.querySelector(`.clearBtn${i}`)

		const fileName = e.target.files[0].name;

		e.target.nextSibling.nextSibling.innerHTML = fileName

		if(i > 1){

			if(deleteEl){
				return;
			}

			e.target.parentElement
					.insertAdjacentHTML('afterend',deleteButton)
		}
	}

	const clearInput = (indexElement) => {
		const el = document.querySelector(`input[name="upload_materi_${indexElement}"]`)

		el.nextSibling.nextElementSibling.innerHTML = ''
	}

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=kat_kajian_id]').select2({ width: '100%', placeholder: '-- Pilih Kategori Peraturan --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
			
			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

            $('body').on('change', 'select[name=kelurahan_id]', async function(){
				let kelurahan = $(this).val()
				if (kelurahan) {
					let subOption = '<option></option>';
					let url = @json(route('DetailAlamat.getRW'));
						url += `?kelurahanID=${ encodeURIComponent(kelurahan) }`
	
					$(this).prop("disabled", true);
					selectRW.parent().append(spinner);
					selectRW.html('');
					selectRW.prop("disabled", true);
	
					selectRT.html('');
					selectRT.prop("disabled", true);
	
					const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
						selectRW.prop("disabled", false);
						spinner.remove()
						Swal.fire({
							title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
							icon: 'warning'
						})
					});
	
					for(const data of fetchRW) {
						subOption += `<option value="${data.rw_id}">${data.rw}</option>`;
					}
	
					selectRW.html(subOption);
					selectRW.select2({ 
						placeholder: '-- Pilih RW --', 
						width: '100%'
					});
					$(this).prop("disabled", false);
					selectRW.prop("disabled", false);
					spinner.remove();
				}
			})

            $('body').on('change', 'select[name=rw_id]', async function(){
				let rw = $(this).val()
				if (rw) {
					let subOption = '<option></option>';
					let url = @json(route('DetailAlamat.getRT'));
						url += `?rwID=${ encodeURIComponent(rw) }`
	
					$(this).prop("disabled", true);
					selectRT.parent().append(spinner);
					selectRT.html('');
					selectRT.prop("disabled", true);
	
					
					const fetchRT = await fetch(url).then(res => res.json()).catch((err) => {
						selectRT.prop("disabled", false);
						spinner.remove()
						Swal.fire({
							title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
							icon: 'warning'
						})
					});
	
					for(const data of fetchRT) {
						subOption += `<option value="${data.rt_id}">${data.rt}</option>`;
					}
	
					selectRT.html(subOption);
					selectRT.select2({ 
						placeholder: '-- Pilih RT --', 
						width: '100%'
					});
					$(this).prop("disabled", false);
					selectRT.prop("disabled", false);
					spinner.remove();
				}
			})
        }

        // Functions
        return {
            init: function() {
                demos();
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@endsection