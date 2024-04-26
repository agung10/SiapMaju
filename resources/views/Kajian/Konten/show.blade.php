@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Peraturan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Peraturan</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="imageContainerEdit">
							<img class="imagePicture" src="{{asset('images/NoPic.png')}}" />
						</div>
						<label>Image</label>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="exampleSelect1">Kategori Peraturan </label>
						<select disabled name="kat_kajian_id" class="form-control" id="exampleSelect1">
							{!! $kat_kajian !!}
						</select>
						{{-- <div style="font-size:10px;margin:5px;color:red" class="err-kat_kajian_id"></div> --}}
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Judul </label>
						<input disabled type="text" value="{{!empty($data->judul) ? $data->judul : '' }}" name="judul"
							class="form-control" placeholder="Masukan Judul" />
						{{-- <div style="font-size:10px;margin:5px;color:red" class="err-judul"></div> --}}
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Penulis </label>
						<input disabled type="text" value="{{!empty($data->author) ? $data->author : '' }}"
							name="author" class="form-control" placeholder="Masukan Judul" />
						{{-- <div style="font-size:10px;margin:5px;color:red" class="err-author"></div> --}}
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="exampleTextarea">Peraturan </label>
						<textarea disabled name="kajian" class="form-control" id="exampleTextarea"
							rows="3">{{!empty($data->kajian) ? $data->kajian : '' }}</textarea>
						{{-- <div style="font-size:10px;margin:5px;color:red" class="err-kajian"></div> --}}
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group materi1">
						<label>Materi 1 </label>
						<div></div>
						<div class="inputContainer">
							<div class="custom-file" style="width: 650px;">
								<input disabled onChange="putFileName(event)" name="upload_materi_1" type="file"
									class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">{{$data->upload_materi_1}}</label>
								{{-- <div style="font-size:10px;margin:5px;color:red" class="err-upload_materi_1"></div>
								--}}
							</div>
							<a style="width:45px;" data-materi="{{$data->upload_materi_1}}"
								onClick="clickHandler(event)" class="btn btn-warning mt-3"><i
									class="flaticon2-search"></i></a>
						</div>
					</div>
				</div>
			</div>

			<h4 class="mt-3">Data Detail Alamat</h4>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Kelurahan</label>
						<input type="text" value="{{ $data->nama ? $data->nama : 'Belum mengisi nama Kelurahan' }}"
							class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RW</label>
						<input type="text" value="{{ $data->rw ? $data->rw : 'Belum mengisi nama RW' }}"
							class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RT</label>
						<input type="text" value="{{ $data->rt ? $data->rt : 'Belum mengisi nama RT' }}"
							class="form-control" readonly />
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			@include('partials.buttons.submit')
		</div>
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

	const createMateriInput = () => {

		const materi = {
			materi1:'{{$data->upload_materi_1}}',
			materi2:'{{$data->upload_materi_2}}',
			materi3:'{{$data->upload_materi_3}}',
			materi4:'{{$data->upload_materi_4}}',
			materi5:'{{$data->upload_materi_5}}'
		}
		
		const {materi1,materi2,materi3,materi4,materi5} = materi;

		let count = 0

		Object.values(materi).map(i => {
									if(i !== ''){
										count++
									}
							   	})
		
		localStorage.setItem('indexMateri',count)

		const createMateri = (num,parentIndex,content) => {
			const el = `<div class="form-group materi${num}">
						<label>Materi ${num} </label>
						<div></div>
						<div class="inputContainer">
							<div class="custom-file" style="width: 650px;">
								<input disabled onChange="putFileName(event)" name="upload_materi_${num}" type="file" class="custom-file-input" id="customFile"/>
								<label class="custom-file-label" for="customFile">${content}</label>
								<div style="font-size:10px;margin:5px;color:red" class="err-upload_materi_${num}"></div>
							</div>
							<a style="width:45px;" data-materi="${content}"  onClick="clickHandler(event)" class="btn btn-warning mt-3"><i class="flaticon2-search"></i></a>
						</div>
					</div>`
			
			return document.querySelector(`.materi${parentIndex}`)
						   .insertAdjacentHTML('afterend',el)
		}

		if(materi2){
			createMateri(2,1,materi2)
		}

		if(materi3){
			createMateri(3,2,materi3)
		}
		if(materi4){
			createMateri(4,3,materi4)
		}
		if(materi5){
			createMateri(5,4,materi5)
		}
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
		const fileName = e.target.files[0].name;

		e.target.nextSibling.nextSibling.innerHTML = fileName
	}
</script>
@endsection