{{-- SHOW BUTTON --}}
@if ( isset($show) && in_array("show", $availablePermission) )
	<a title="Show details" href="{{ $show }}" class="btn btn-light-primary font-weight-bold mr-2">
		Detail
	</a>
@endif
{{-- EDIT BUTTON --}}
@if ( isset($edit) && in_array("edit", $availablePermission) )
    <a title="Edit details" href="{{ $edit }}" class="btn btn-light-warning font-weight-bold mr-2">
     	Edit
    </a>
@endif
{{-- DESTROY BUTTON --}}
@if ( isset($destroy) && in_array("destroy", $availablePermission) )
    <a title="Delete" href="{{ $destroy }}" class="btn btn-light-danger font-weight-bold">
    	Delete
    </a>
@endif

{{-- DESTROY BUTTON --}}
@if ( isset($ajax_destroy) && in_array("destroy", $availablePermission) )
<a href="javascript:void(null)" onClick="deleteFunc({{$ajax_destroy}})" class="btn btn-light-danger font-weight-bold mr-2">Delete</a>
@endif

{{-- BUTTON LIAR --}}
@if(isset($customButton))
<a target="_blank" title="{{ $customButton['name'] }}" href="{{ $customButton['route'] }}" class="btn {{ array_key_exists('class', $customButton) ? $customButton['class'] : 'btn-light-dark' }} font-weight-bold btn-custom">
	{{ $customButton['name'] }}
</a>
@endif

@if(isset($show2))
<a title="{{ $show2['name'] }}" href="{{ $show2['route'] }}" class="btn btn-light-primary font-weight-bold mr-2 mt-2">
	{{ $show2['name'] }}
</a>
@endif
@if(isset($edit2))
<a title="{{ $edit2['name'] }}" href="{{ $edit2['route'] }}" class="btn btn-light-warning font-weight-bold mr-2 mt-2">
	{{ $edit2['name'] }}
</a>
@endif
@if(isset($ajaxDestroy2))
<a href="javascript:void(null)" onClick="deleteFunc({{$ajaxDestroy2['id']}})" class="btn btn-light-danger font-weight-bold mr-2">{{ $ajaxDestroy2['name'] }}</a>
@endif