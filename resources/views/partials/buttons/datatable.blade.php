<?php
	use App\Helpers\helper;
?>

@if(isset($show))
	<a href="{{$show}}" class="btn btn-light-primary font-weight-bold mr-2">Detail</a>
@endif

@if(isset($edit))
	<a href="{{$edit}}" class="btn btn-light-warning font-weight-bold mr-2">Edit</a>
@endif

@if(isset($delete))
	<a href="{{$delete}}" class="btn btn-light-danger font-weight-bold mr-2">Delete</a>
@endif

@if(isset($ajaxDelete))
	<a href="javascript:void(null)" onClick="deleteFunc({{$ajaxDelete}})" class="btn btn-light-danger font-weight-bold mr-2">Delete</a>
@endif

@if(isset($create))
	<a href="{{$create['route']}}" class="btn btn-primary font-weight-bolder"> <span class="svg-icon svg-icon-md"><i class="fas fa-plus-square"></i></span> Tambah {{$create['name']}}
</a>
@endif

@if(isset($activePill))
	@if($activePill === true)
		<span class="badge badge-pill badge-primary ml-3">Active</span>
	@else
		<span class="badge badge-pill badge-danger ml-3">Inactive</span>
	@endif
@endif

@if(isset($materi1))
	@if($materi1)
		<?php 	
			$extension1 = explode('.',$materi1)[1];
		?>
		<a href="javascript:void(null)" onClick="materiHandler('{{$extension1}}' , '{{$materi1}}')" class="btn btn-success btn-sm mr-3"><i class="flaticon2-file"></i> Materi </a>
	@endif
@endif

@if(isset($materi2))
	@if($materi2)
		<?php 	
			$extension1 = explode('.',$materi2)[1];
		?>
		<a href="javascript:void(null)" onClick="materiHandler('{{$extension1}}' , '{{$materi2}}')" class="btn btn-success btn-sm mr-3"><i class="flaticon2-file"></i> Materi </a>
	@endif
@endif

@if(isset($materi3))
	@if($materi3)
		<?php 	
			$extension1 = explode('.',$materi3)[1];
		?>
		<a href="javascript:void(null)" onClick="materiHandler('{{$extension1}}' , '{{$materi3}}')" class="btn btn-success btn-sm mr-3"><i class="flaticon2-file"></i> Materi </a>
	@endif
@endif

@if(isset($materi4))
	@if($materi4)
		<?php 	
			$extension1 = explode('.',$materi4)[1];
		?>
		<a href="javascript:void(null)" onClick="materiHandler('{{$extension1}}' , '{{$materi4}}')" class="btn btn-success btn-sm mr-3"><i class="flaticon2-file"></i> Materi </a>
	@endif
@endif

@if(isset($materi5))
	@if($materi5)
		<?php 	
			$extension1 = explode('.',$materi5)[1];
		?>
		<a href="javascript:void(null)" onClick="materiHandler('{{$extension1}}' , '{{$materi5}}')" class="btn btn-success btn-sm mr-3"><i class="flaticon2-file"></i> Materi </a>
	@endif
@endif

{{-- DESTROY BUTTON --}}
@isset($destroy)
<a href="{{ $destroy }}" class="btn btn-light-danger font-weight-bold btn-delete-datatable">
	Delete
</a>
@endif

{{-- EXTRA BUTTON --}}
@isset($extra)
{!! $extra !!}
@endisset