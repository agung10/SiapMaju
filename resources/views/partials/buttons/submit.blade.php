@php
	$canStore  = Route::getCurrentRoute()->getActionMethod() === 'create' && in_array("store", $availablePermission);
	$canUpdate = Route::getCurrentRoute()->getActionMethod() === 'edit' && in_array("update", $availablePermission);
@endphp
@if( !isset($noSubmit) )
	@if($canStore || $canUpdate)
		<button type="submit" class="btn btn-primary mr-2 submit">Submit</button>
	@endif
	&nbsp;
@endif
<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>