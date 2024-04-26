@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit Transaksi Kegiatan DKM
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
	<form class="form">
		<div class="card-body">
			<div class="form-group row">
				<div class="col-lg-6">
					<label>Transaksi</label>
					<select name="transaksi_id" class="form-control headerInput transaksi">
						{!! $selectTransaksi !!}
					</select>
					<span class="errorHeader"></span>
				</div>
				<div class="col-lg-6">
					<label>Kat Kegiatan</label>
					<select name="kat_kegiatan_id" class="form-control headerInput katKegiatan">
						{!! $selectKatKegiatan !!}
					</select>
					<span class="errorHeader"></span>
				</div>
			</div>
			<div class="form-group row" style="display:none;">
				<div class="col-lg-12">
					<label>No. Bukti</label>
					<div class="form-group row">
						<div class="col-lg-2">
							<input disabled type="text" class="form-control buktiNo" placeholder="No"/>
						</div>
						<div class="col-lg-2">
							<input disabled type="text" value="{{!empty($data->kode_kat) ? $data->kode_kat : ''}}" class="form-control buktiKode" placeholder="Kode Kegiatan"/>
						</div>
						<div class="col-lg-2">
							<input disabled type="text" value="{{!empty($data->nama_transaksi) ? $data->nama_transaksi : ''}}" class="form-control buktiJenisTrans" placeholder="Transaksi"/>
						</div>
						<div class="col-lg-3">
							<input disabled type="month" value="{{date('Y-m',strtotime($data->tgl_pendaftaran))}}" class="form-control buktiDate" placeholder="Pilih Bulan"/>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-6">
					<label>Nama Kegiatan</label>
						<select disabled name="kegiatan_id" class="form-control headerInput kegiatan">
							{!! $selectKegiatan !!}
						</select>
						<span class="errorHeader"></span>
				</div>
				<div class="col-lg-6">
					<label>Nama (Kepala Keluarga)</label>
						<select class="form-control headerInput kepalaKeluarga">
							{!! $selectKepalaKeluarga !!}
						</select>
						<span class="errorHeader"></span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-9">
					<label>No. Pendaftaran</label>
					<div class="form-group row">
						<div class="col-lg-2">
							<input disabled type="text" value="{{$regPendaftaran}}" class="form-control regPendaftaran" placeholder="Reg"/>
						</div>
						<div class="col-lg-2">
							<select class="form-control headerInput KodeKatKegiatan2" style="pointer-events: none;background:#F3F6F9">
							{!! $selectKodeKatKegiatan !!}
							</select>
							<span class="errorHeader"></span>
						</div>
						<div class="col-lg-3">
							<input disabled type="text" class="form-control monthPendaftaran" value="{{date('Y-m',strtotime($data->tgl_pendaftaran))}}" />
							<span class="errorHeader"></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<label>Tanggal Pendaftaran</label>
					<input type="date" class="form-control headerInput tglPendaftaran" value="{{!empty($data->tgl_pendaftaran) ? $data->tgl_pendaftaran : ''}}" placeholder="Pilih Bulan"/>
					<span class="errorHeader"></span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-6">
					<label>Alamat</label>
					<input disabled type="text" value="{{!empty($data->alamat) ? $data->alamat : ''}}" class="form-control alamat" placeholder="Alamat"/>
				</div>
				<div class="col-lg-6">
					<label>Bukti Pembayaran</label>
					<input  type="file" class="form-control bukti-pembayaran" placeholder="Alamat"/>
					<span class="errorHeader error-bukti-pembayaran"></span>
				</div>
			</div>
		</div>
		<div class="col-lg-12 text-right headerSaveButtonContainer">
			<button type="submit" class="btn btn-primary mr-5 saveHeader">Save</button>
			<button type="submit" class="btn btn-warning mr-5 editHeader" style="display:none">Edit</button>
			<button type="submit" class="btn btn-success mr-5 saveEditHeader" style="display:none">Save</button>
	    </div>
	</form>
		<div class="detailTransaksiContainer" style="display:">
			<div class="col-lg-12">
				<h5 class="detailTransText">Detail Transaksi</h5 style=>
			</div>
			<div class="col-lg-12">
					<table class="table table-bordered table-checkable" id="kt_datatable">
						<thead>
							<tr>
								<th class="text-center">Nama</th>
								<th class="text-center">Jenis Transaksi</th>
								<th class="text-center">Nilai</th>
								<th class="text-center">Jumlah</th>
								<th class="text-center">Total</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="tbodyDetail">
							{!! $detailTable !!}
						</tbody>
					</table>
			</div>
			<div class="row sendButtonContainer">
				<div class="col-lg-6">
					<button type="reset" class="btn btn-primary mr-2 sendTransaksi">Send</button>
				</div>
			</div>	
		</div>

		<div class="card-footer">
			
				<div class="col-lg-6 text-right">
				<button type="reset" onClick="goBack()" class="btn btn-secondary">Back</button>
				</div>
			</div>
		</div>
	</div>

<div class="modal" id="addFamilyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Anggota Keluarga</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	 	 <input placeholder="Nama Anggota" name="addedFamily" type="text" class="form-control transRow" placeholder="total"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary saveAddedFamily">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

	// Select Script //
	const selectTransaksi = document.querySelector('.transaksi')
	const selectKatKegiatan  = document.querySelector('.katKegiatan')
	const selectKodeKatKegiatan2 = document.querySelector('.KodeKatKegiatan2')
	const selectKegiatan = document.querySelector('.kegiatan')
	const selectKepalaKeluarga = document.querySelector('.kepalaKeluarga')
	const inputAlamat  = document.querySelector('.alamat')
	const buttonSaveHeader = document.querySelector('.saveHeader')

	const selectPlaceholder = (selector,name) => {
		selector.childNodes[1].innerHTML = `Pilih ${name}`
		selector.childNodes[1].setAttribute('disabled','')
	}


	selectPlaceholder(selectTransaksi, 'Transaksi')
	selectPlaceholder(selectKatKegiatan, 'Kategori Kegiatan')
	selectPlaceholder(selectKodeKatKegiatan2, 'Kode')
	selectPlaceholder(selectKegiatan, 'Kegiatan')
	selectPlaceholder(selectKepalaKeluarga, 'Kepala Keluarga')
	// End Select Script //

	const addRowBtn= document.querySelector('.addRow')

	addRowBtn.addEventListener('click',async (e)=>{
		e.preventDefault()

		const parentTR = addRowBtn.parentElement.parentElement.parentElement
		const kepalaKeluargaId = selectKepalaKeluarga.value
		const lastEl = parentTR.childNodes[parentTR.childNodes.length - 2]
			   
		if(isNaN(kepalaKeluargaId)){
			swal.fire('Perhatian','Kepala Keluarga Belum Dipilih','info')
			return;
		}

		const createTr = `<tr>
							<form class="formDetailTransakasi">
								<td>
									<input onChange="inputNamaChangeHandler(event)" name="nama_detail_trx" type="text" class="form-control transRow" placeholder="Nama Anggota">
								</td>
								<td>
									<select name="jenis_transaksi_id" onClick="getJenisTransaksi(event)" onChange="detailTransChangeHandler(event)" class="form-control transRow detailTransaksi">
										<option disabled selected>Pilih Jenis Transaksi</option>
									</select>
								</td>
								<td>
									<input name="nilai" oninput="sumRow(event)" type="number" class="form-control transRow" placeholder="nilai"/>
								</td>
								<td>
									<input name="jumlah" oninput="sumRow(event)" type="number" class="form-control transRow" placeholder="jumlah"/>
								</td>
								<td>
									<input name="total" readonly type="number" class="transRow form-control" placeholder="total"/>
								</td>
								<td>
									<button class="btn btn-danger mt-3" onClick="deleteRow(event)"><i class="flaticon2-cross"></i></button>
								</td>
							</form>
						</tr>`

						lastEl.insertAdjacentHTML('afterend',createTr)
	})

	//Header Transaksi Script //
		const transaksiInput = document.querySelector('.transaksi')
		const katKegiatanInput = document.querySelector('.katKegiatan')
		const jenisTransaksi = document.querySelector('.buktiJenisTrans')
		const buktiNo = document.querySelector('.buktiNo')
		const buktiDate = document.querySelector('.buktiDate')
		const buktiKode = document.querySelector('.buktiKode')
		const monthPendaftaran = document.querySelector('.monthPendaftaran')
		const tglPendaftaran = document.querySelector('.tglPendaftaran')
		const regPendaftaran = document.querySelector('.regPendaftaran')
		const editHeaderButton = document.querySelector('.editHeader')
		const saveEditHeaderButton = document.querySelector('.saveEditHeader')
		const detailJenisTransaksi = document.querySelector('.detailTransaksi')

		const inputChange = (jenis,kode) => {	

			if(jenis){
				jenisTransaksi.value = jenis
			}

			if(kode){
				buktiKode.value = kode
			}	

			buktiNo.value = '//'

		}
		
		transaksiInput.addEventListener('change',(e)=>{

			if(transaksiInput.value == 1){
				inputChange('M')
			}else if(transaksiInput.value == 2){
				inputChange('K')
			}
		})

		katKegiatanInput.addEventListener('change', async () => {
			
			const res = await fetch(`{{route('Transaksi.Header.getKodeKegiatan')}}`,{
										headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}',
												 'Content-Type':'application/json'
												},
										method:'post',
										body:JSON.stringify({'kat_kegiatan_id':katKegiatanInput.value})
									})
			
			const {kode_kat,selectElKegiatan} =  await res.json()
			
			inputChange(false,kode_kat)
			
			document.querySelector('.KodeKatKegiatan2').value = kode_kat

			selectKegiatan.replaceChildren()

			selectKegiatan.insertAdjacentHTML('afterbegin',selectElKegiatan)
			
			selectKegiatan.removeAttribute('disabled')
		})

		selectKepalaKeluarga.addEventListener('change', async (e) => {

			const kepalaKeluargaId = selectKepalaKeluarga.value

			const res = await fetch(`{{route('Transaksi.Header.getKepalaKeluarga')}}`,{
										headers:{
											'X-CSRF-TOKEN':'{{csrf_token()}}',
											'Content-Type':'application/json'
										},
										method:'post',
										body:JSON.stringify({'kepala_keluarga_id':kepalaKeluargaId})
									})
									
			const {result,alamat} = await res.json();
									
			inputAlamat.value = alamat;

			document.querySelectorAll('.detailKeluarga').forEach(el => {
															const parentTR = el.parentElement
															
															el.remove()
															parentTR.insertAdjacentHTML('beforeend',result)
														})
		});

		const selectKeluargaHandler = (e) => {
			const selectValue = e.target.value

			if(selectValue){
				e.target.style.background = 'linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 1px no-repeat'
			}
			
			const parentTD = e.target.parentElement

			const inputNama  = `<input name="nama_detail_trx" 
									   value="${selectValue ? selectValue == 'get more' ? '' : selectValue : ''}" 
									   type="text"
									   onChange="inputNamaChangeHandler(event)"
									   class="form-control transRow" 
									   placeholder="Nama Anggota"/>`

			parentTD.replaceChildren()
			
			if(selectValue == 'get more'){
			
				parentTD.insertAdjacentHTML('beforeend',inputNama)
			}else{
				parentTD.insertAdjacentHTML('beforeend',inputNama)
			}
		}

		const fetchJenisTransaksi = async (kegiatan_id) => {

			const res = await fetch(`{{route('Transaksi.Header.getJenisTransaksi')}}`,{
										headers:{
											'X-CSRF-TOKEN':'{{csrf_token()}}',
											'Content-Type':'application/json'
										},
										method:'post',
										body:JSON.stringify({kegiatan_id})
								   })
			const  {result} = await res.json()
			
			return result
		}
		
		selectKegiatan.addEventListener('change',async e => {
			const kegiatan_id = e.target.value

			const newEl = await fetchJenisTransaksi(kegiatan_id)

			document.querySelectorAll('.detailTransaksi').forEach(el => {
				el.replaceChildren()
				el.insertAdjacentHTML('afterbegin',newEl)
			})
		})

		tglPendaftaran.addEventListener('change',(e) => {
			const tgl_pendaftaran = e.currentTarget.value.split('-')
			monthPendaftaran.value = `${tgl_pendaftaran[1]}/${tgl_pendaftaran[0]}`
		})

		const getJenisTransaksi = async (e) => {

			const el = e.target

			countChild = e.target.childElementCount

			const kegiatan_id = selectKegiatan.value

			const newEl = await fetchJenisTransaksi(kegiatan_id)

			if(countChild > 1){
				return;
			}

			el.replaceChildren()

			el.insertAdjacentHTML('afterbegin',newEl)
		}

	//End Header Transaksi Script//

	// Input Data Header Script //
	document.querySelector('.bukti-pembayaran').addEventListener('change',(e) => {
		const bukti_pembayaran = e.currentTarget.files[0]

		const fileValid = buktiPembayaranValidation(bukti_pembayaran)
	})

	buttonSaveHeader.addEventListener('click',async e => {
		e.preventDefault()

		const date = '{{date("YmdHis")}}'
		const year = tglPendaftaran.value.split('-')
		const header_id = '{{$data->header_trx_kegiatan_id}}'
		const bukti_pembayaran = document.querySelector('.bukti-pembayaran').files[0]

		const storeData = {
			transaksi_id:selectTransaksi.value,
			kat_kegiatan_id:selectKatKegiatan.value,
			kegiatan_id:selectKegiatan.value,
			anggota_keluarga_id:selectKepalaKeluarga.value,
			tgl_pendaftaran:tglPendaftaran.value,
		}

		const formData = new FormData() 
		formData.append('transaksi_id',selectTransaksi.value)
		formData.append('kat_kegiatan_id',selectKatKegiatan.value)
		formData.append('kegiatan_id',selectKegiatan.value)
		formData.append('anggota_keluarga_id',selectKepalaKeluarga.value)
		formData.append('tgl_pendaftaran',tglPendaftaran.value)
		formData.append('bukti_pembayaran',bukti_pembayaran ?? null)
		formData.append('_method','PUT')

		// file validation

		const fileValid = buktiPembayaranValidation(bukti_pembayaran)
			
		// end file validation

		document.querySelectorAll('.headerInput').forEach(el => {

			const inputValidation = (el) => {
				const label = el.previousElementSibling?.innerHTML
			
				const checkInput = el.value.split(' ')[0] == 'Pilih'
			
				if((checkInput) || el.value === ''){
					el.nextElementSibling.innerHTML = `${label ? label : ''} Tidak Boleh Kosong`
				}else if((!checkInput) || el.value){
					el.nextElementSibling.innerHTML = ''
				}
			}
			
			inputValidation(el)
			
			el.addEventListener('change',() => {
				inputValidation(el)
			})
		})

		const checkKode = selectKodeKatKegiatan2.value
												.split(' ')[0] !== 'Pilih'

		const checkMonth = monthPendaftaran.value !== ''

		if(!isNaN(storeData.transaksi_id) && !isNaN(storeData.kat_kegiatan_id) && !isNaN(storeData.kegiatan_id) && !isNaN(storeData.anggota_keluarga_id) && (checkKode) && (checkMonth) && (fileValid)){

			const res = await fetch(`{{route('Transaksi.DKM.update','')}}/${header_id}`,{
									headers:{
										'X-CSRF-TOKEN':'{{csrf_token()}}',
										'X-Requested-With':'XMLHttpRequest',
									},
									method:'post',
									body:formData
								})

			const {status} =  await res.json()

			if(status == 'success'){

					selectTransaksi.setAttribute('disabled','')
					selectKatKegiatan.setAttribute('disabled','')
					selectKegiatan.setAttribute('disabled','')
					selectKepalaKeluarga.setAttribute('disabled','')
					regPendaftaran.setAttribute('disabled','')
					monthPendaftaran.setAttribute('disabled','')
					tglPendaftaran.setAttribute('disabled','')
					selectKodeKatKegiatan2.setAttribute('disabled','')
					inputAlamat.setAttribute('disabled','')
					document.querySelector('.bukti-pembayaran').setAttribute('disabled','')
					buttonSaveHeader.style.display = 'none'
					editHeaderButton.style.display = ''
					document.querySelector('.detailTransaksiContainer').style.display = ''

					document.querySelectorAll('.errorHeader').forEach(el => {
						el.innerHTML = ''
					})
			}
		}
	})

	// End Input Data Header Script //

	// Edit Data Header Script //

	editHeaderButton.addEventListener('click',e => {
		e.preventDefault()

		selectTransaksi.removeAttribute('disabled')
		selectKatKegiatan.removeAttribute('disabled')
		selectKegiatan.removeAttribute('disabled')
		selectKepalaKeluarga.removeAttribute('disabled')
		selectKodeKatKegiatan2.removeAttribute('disabled')
		tglPendaftaran.removeAttribute('disabled')
		inputAlamat.removeAttribute('disabled')
		document.querySelector('.bukti-pembayaran').removeAttribute('disabled')
		editHeaderButton.style.display = 'none'
		saveEditHeaderButton.style.display = ''
		document.querySelector('.detailTransaksiContainer').style.display = 'none'
	})

	saveEditHeaderButton.addEventListener('click', async (e) => {
		e.preventDefault();

		const date = '{{date("YmdHis")}}'
		const year = tglPendaftaran.value.split('-')
		const header_id = '{{$data->header_trx_kegiatan_id}}'
		const bukti_pembayaran = document.querySelector('.bukti-pembayaran').files[0]

		const storeData = {
			transaksi_id:selectTransaksi.value,
			kat_kegiatan_id:selectKatKegiatan.value,
			kegiatan_id:selectKegiatan.value,
			anggota_keluarga_id:selectKepalaKeluarga.value,
			tgl_pendaftaran:tglPendaftaran.value,
		}

		const formData = new FormData() 
		formData.append('transaksi_id',selectTransaksi.value)
		formData.append('kat_kegiatan_id',selectKatKegiatan.value)
		formData.append('kegiatan_id',selectKegiatan.value)
		formData.append('anggota_keluarga_id',selectKepalaKeluarga.value)
		formData.append('tgl_pendaftaran',tglPendaftaran.value)
		formData.append('bukti_pembayaran',bukti_pembayaran ?? null)
		formData.append('_method','PUT')

		// file validation

		const fileValid = buktiPembayaranValidation(bukti_pembayaran)
			
		// end file validation

		document.querySelectorAll('.headerInput').forEach(el => {
	
			const inputValidation = (el) => {
				const label = el.previousElementSibling?.innerHTML
			
				const checkInput = el.value.split(' ')[0] == 'Pilih'
			
				if((checkInput) || el.value === ''){
					el.nextElementSibling.innerHTML = `${label ? label : ''} Tidak Boleh Kosong`
				}else if((!checkInput) || el.value){
					el.nextElementSibling.innerHTML = ''
				}
			}
			
			inputValidation(el)
			
			el.addEventListener('change',() => {
				inputValidation(el)
			})
		})

		const checkKode = selectKodeKatKegiatan2.value
												.split(' ')[0] !== 'Pilih'

		const checkMonth = monthPendaftaran.value !== ''

		if(!isNaN(storeData.transaksi_id) && !isNaN(storeData.kat_kegiatan_id) && !isNaN(storeData.kegiatan_id) && !isNaN(storeData.anggota_keluarga_id) && (checkKode) && (checkMonth) && (fileValid)){
			const res = await fetch(`{{route('Transaksi.DKM.update','')}}/${header_id}`,{
									headers:{
										'X-CSRF-TOKEN':'{{csrf_token()}}',
										'X-Requested-With':'XMLHttpRequest'
									},
									method:'post',
									body:formData
								})
		
			const {status} = await res.json()

			if(status == 'success'){
				selectTransaksi.setAttribute('disabled','')
				selectKatKegiatan.setAttribute('disabled','')
				selectKegiatan.setAttribute('disabled','')
				selectKepalaKeluarga.setAttribute('disabled','')
				regPendaftaran.setAttribute('disabled','')
				monthPendaftaran.setAttribute('disabled','')
				tglPendaftaran.setAttribute('disabled','')
				selectKodeKatKegiatan2.setAttribute('disabled','')
				inputAlamat.setAttribute('disabled','')
				document.querySelector('.bukti-pembayaran').setAttribute('disabled','')
				buttonSaveHeader.style.display = 'none'
				editHeaderButton.style.display = ''
				document.querySelector('.detailTransaksiContainer').style.display = ''
				saveEditHeaderButton.style.display = 'none'

				document.querySelectorAll('.errorHeader').forEach(el => {
						el.innerHTML = ''
				})
			}
		}
	})

	function buktiPembayaranValidation(bukti_pembayaran){
			
			const bukti_pembayaran_size = bukti_pembayaran?.size / 1024 / 1024
			const bukti_pembayaran_format = bukti_pembayaran?.type.split('/')[1]
			const fileSizeValid = bukti_pembayaran_size < 2
			const formatFileValid = bukti_pembayaran_format == 'pdf' || bukti_pembayaran_format == 'jpg' || bukti_pembayaran_format == 'jpeg'
			const fileValid = fileSizeValid && formatFileValid
	
			if(!fileSizeValid && bukti_pembayaran){
					document.querySelector('.error-bukti-pembayaran').innerText = 'File Tidak Boleh Lebih Dari 2 MB'
					return false
			}else if(!formatFileValid && bukti_pembayaran){
					document.querySelector('.error-bukti-pembayaran').innerText = 'File Harus Berformat PDF,JPG,JPEG'
	
					return false
	
			}else if(fileValid){
					document.querySelector('.error-bukti-pembayaran').innerText = ''
					return true
			}
			
			return true
		}

	// End Edit Data Header Script //

	// Detail Transaksi Script //
	
	const deleteRow = (e) => {
		e.preventDefault();
		
		const deleteEl = () => {
			if(e.target.tagName === 'I'){
				return e.target.parentElement.parentElement.parentElement
			}else if(e.target.tagName === 'BUTTON'){
				return e.target.parentElement.parentElement
			}else{
				return;
			}
		}

		const detail_trx_kegiatan_id = deleteEl().getAttribute('data-id')

		swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus baris ini?",
            icon: "warning",
            showCancelButton: true,
    
        })
          .then(async (result) => {
                if (result.isConfirmed) {
                    if(detail_trx_kegiatan_id){
						const res = await fetch(`{{route('Transaksi.DKM.deleteDetail','')}}/${detail_trx_kegiatan_id}`,{
													headers:{
														'X-CSRF-TOKEN':'{{csrf_token()}}',
														'Content-Type':'application/json',
														'X-Requested-With':'XMLHttpRequest'
													},
													method:'post'
											})
						}
						deleteEl().remove()
                } else if(result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire("Proses Hapus Dibatalkan");
                }
            });

		
	}

	const sumRow = (e) => {	
		
		if(e.target.value){
				e.target.style.background = 'linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 1px no-repeat'
			}

		if(e.target.name == 'jumlah'){
			const nilai = e.target.parentElement
								  .previousElementSibling
								  .children[0]
								  .value
			
			const jumlah = e.target.value

			const total = e.target.parentElement
								  .nextElementSibling
								  .children[0]
			
			calculation(total,nilai,jumlah)
		}

		if(e.target.name == 'nilai'){
			const nilai = e.target.value
			
			const jumlah = e.target.parentElement
								   .nextElementSibling
								   .children[0]
								   .value
			
			const total = e.target.parentElement
								  .nextElementSibling
								  .nextElementSibling
								  .children[0]
			
			calculation(total,nilai,jumlah)
		}
	}

	const calculation = (total,nilai,jumlah) => {

		total.value = nilai * jumlah
	}

	document.querySelector('.sendTransaksi').addEventListener('click',async ()=>{
		let checkField = true
		const inputData = [];
		const header_trx_kegiatan_id = '{{$data->header_trx_kegiatan_id}}'

		document.querySelector('#tbodyDetail')
				.querySelectorAll('tr').forEach(el => {
					const nilai = el.querySelector('input[name=nilai]').value
					const jumlah = el.querySelector('input[name=jumlah]').value
					const total = el.querySelector('input[name=total]').value
					const inputNamaDetail = el.querySelector('input[name=nama_detail_trx]')?.value 
					const selectNamaDetail = el.querySelector('select[name=nama_detail_trx]')?.value 
					const jenis_transaksi_id = el.querySelector('select[name=jenis_transaksi_id]').value
					const detail_trx_kegiatan_id = el.getAttribute('data-id')

					const nama_detail_trx = inputNamaDetail ? inputNamaDetail
															: selectNamaDetail
																? selectNamaDetail
																: ''

					const storeData = {nilai,
									   jumlah,
									   total,
									   nama_detail_trx,
									   jenis_transaksi_id,
									   header_trx_kegiatan_id,
									   detail_trx_kegiatan_id
									   }  	 
					
					el.querySelectorAll('input,select').forEach(inputEl => {
						if(inputEl.value === '' || isNaN(jenis_transaksi_id)){
							checkField = false
							if(inputEl.name !== 'total' && inputEl.value === ''){
								inputEl.style.background = 'linear-gradient(red, red) center bottom 5px /calc(100% - 10px) 1px no-repeat'
							}
							if(nama_detail_trx == null){
								el.querySelector('select[name=nama_detail_trx]')
								  .style.background = 'linear-gradient(red, red) center bottom 5px /calc(100% - 10px) 1px no-repeat'
							}
							if(isNaN(jenis_transaksi_id)){
								el.querySelector('select[name=jenis_transaksi_id]')
								  .style.background = 'linear-gradient(red, red) center bottom 5px /calc(100% - 10px) 1px no-repeat'
							}
						}
					})
					
					if(checkField){
						inputData.push(storeData)
					}				
				})
		
		if(checkField){
			const res = await fetch(`{{route('Transaksi.DKM.updateDetail','')}}/${header_trx_kegiatan_id}`,{
										headers:{ 'Content-Type':'application/json',
												  'X-CSRF-TOKEN':'{{csrf_token()}}',
												  'X-Requested-With': 'XMLHttpRequest'
												},
										method:'post',
										body:JSON.stringify(inputData)	
								   	})
											   
						const {status} = await res.json()

						if(status == 'success'){

							document.querySelector('.sendTransaksi').innerHTML = 'Mohon Tunggu...'

							document.querySelectorAll('.btn').forEach(el => {
								el.setAttribute('disabled','')
							})

							document.querySelectorAll('input','select').forEach(el => {
								el.setAttribute('disabled','')
							})

							window.localStorage.setItem('finishInput','Transakasi Berhasil !!')

							location.replace(`{{route('Transaksi.DKM.index')}}`)
						}
		}
	})

	const detailTransChangeHandler = (e) => {
		if(e.target.value){
				e.target.style.background = 'linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 1px no-repeat'
		}
	}

	const inputNamaChangeHandler = (e) => {
	
		if(e.target.value){
				e.target.style.background = 'linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 1px no-repeat'
		}
	}
	// End Detail Transaksi Script //	
</script>
@endsection