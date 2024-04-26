@if(in_array('create', $availablePermission))
    
    <a href="{{ isset($route) ? $route : \Request::url() . '/create' }}" class="btn btn-primary font-weight-bolder">
		<span class="svg-icon svg-icon-md"><i class="fas fa-plus-square"></i></span>
		{{ isset($text) ? $text : 'Tambah Data' }} 
	</a>
    <div class="table-toolbar"></div>
@endif