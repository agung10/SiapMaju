@extends('layouts.master')

@section('content')
@include('LaporanSikad.LaporanTransaksiKegiatan.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Print Laporan Transaksi Kegiatan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form class="search-form">
			<div class="date-range-container">
				<div class="rentang-tanggal-container">
					<div class="label-container">
						<p>Rentang Tanggal</p>
					</div>
					<div class="input-date-container">
						<p>Start  <span class="text-danger">*</span></p>
						<input type="date" name="start_date" class="form-control">
					</div>
					<div class="input-date-container">
						<p>End  <span class="text-danger">*</span></p>
						<input type="date" name="end_date" class="form-control">
					</div>
				</div>
				<div class="button-container">
					<button type="submit" class="btn btn-success font-weight-bolder">Cari</button>
				</div>
			</form>
		</div>
		<div class="result-container">
		</div>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Laporan\LaporanSuratRequest','.search-form') !!}
<script>
	window.addEventListener('DOMContentLoaded',() => {

		document.querySelector('.search-form').addEventListener('submit',(e) => {
			e.preventDefault()
			const form = document.querySelector('.search-form')
			const valid = $(form).valid()

			if(!valid) return;
			searchFormHandler(e)
		})
	})

	async function searchFormHandler(e){
		loading()

		const form =  e.currentTarget;

		const fetchLaporan = async () => {
			formData = new FormData(form)
			const url = `{{route('LaporanSikad.LaporanTransaksiKegiatan.searchLaporan')}}`

			return await fetch(url,{
								headers:{
									'X-CSRF-TOKEN':'{{csrf_token()}}',
									'X-Requested-With':'XMLHttpRequest'
								},
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

		const {result} = await fetchLaporan()

		document.querySelector('.result-container').innerHTML = result;

		$('.table-search-result').DataTable({
			dom: 'Bfrtip',
			"columnDefs": [
							{"className": "dt-center", "targets": "_all"}
						  ],
			buttons: [
				{
					extend: 'print',
					customize: function ( win ) {
						$(win.document.body)
							.css( 'font-size', '10pt' )
	
						$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css( 'font-size', 'inherit' );
					}
				}
       	 	]
		})

		KTApp.unblockPage()
		$('.result-container').fadeIn('slow')

	}

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}
</script>
@endsection

