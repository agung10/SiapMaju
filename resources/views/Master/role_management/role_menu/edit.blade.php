@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('master.role-menu.update', $role->role_id) }}"  method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<div class="form-group">
					<label>Nama <span class="text-danger">*</span></label>
					<input type="text" name="role_name" class="form-control"  placeholder="Masukan Nama Role" value="{{$role->role_name}}" />
					<div style="font-size:10px;margin:5px;color:red" class="err-role_name"></div>
				</div>
				<div class="form-group mb-1">
					<table class="table table-bordered">
						<thead class="thead-light">
							<th class="font-weight-bold"><b>Menu Name</b></th>
						</thead>                            
					</table>
					<div style="height: 310px;overflow-x: hidden;overflow-y: auto;">
						<table class="table table-bordered table-hover">
							<tbody>
								@foreach($roleMenu as $parent)
								<tr>
									<td class="font-weight-bold">
										<label>
											<input name="menu[]" type="checkbox" value="{{ $parent['menu_id'] }}" {{ $parent['active'] ? 'checked' : '' }}>
											<i class="fa fa-folder-open"></i>
											{{ $parent['name'] }}
										</label>
									</td>
								</tr>
									@foreach($parent['menu'] as $child)
									<tr>
										<td>
											<label class="role-menu-child" style="margin-left: 25px;">
												<input name="menu[]" type="checkbox" data-parent="{{ $parent['menu_id'] }}" value="{{ $child['menu_id'] }}" {{ $child['active'] ? 'checked' : '' }}> {{ $child['name'] }}
											</label>
										</td>
									</tr>
										@foreach($child['menu'] as $grandchild)
										<tr>
											<td>
												<label class="role-menu-grandchild" style="margin-left: 50px;">
													<input name="menu[]" type="checkbox" data-parent="{{ $child['menu_id'] }}" value="{{ $grandchild['menu_id'] }}" {{ $grandchild['active'] ? 'checked' : '' }}> {{ $grandchild['name']}}
												</label>
											</td>
										</tr>
										@endforeach
									@endforeach 
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
	<script>

$('input[type=checkbox]').on('click', function() {
  let childs  = document.querySelectorAll('[data-parent="'+ this.value +'"]');
  let parents = document.querySelectorAll('[value="'+ this.getAttribute('data-parent') +'"]');

  // auto checked node child
  setCheckbox(childs, this.checked)

  if(this.checked) {
    // auto checked node parent if this checked
    for (let parent of parents) {
      parent.checked = this.checked
    }
  } else {
    // if this unchecked then check all sibling
    let siblings       = document.querySelectorAll('[data-parent="'+ this.getAttribute('data-parent') +'"]')
    let siblingChecked = false
    
    for (let sibling of siblings) {
      if(sibling.checked) siblingChecked = sibling.checked;
    }

    // if none checked then uncheck node parent
    if(!siblingChecked) {
      setCheckbox(parents, false)
    }

  }
    
})

// set checkbox until grandchild and grandprent if exists
function setCheckbox(elements, value) {
  for (let element of elements) {
    let childElements  = document.querySelectorAll('[data-parent="'+ element.getAttribute('value') +'"]')
    let parentElements = document.querySelectorAll('[value="'+ element.getAttribute('value') +'"]')

    for (let childElement of childElements) {
      childElement.checked = value
    }

    for (let parentElement of parentElements) {
      parentElement.checked = value
    }

    element.checked = value
  }
}

</script>
	@endsection