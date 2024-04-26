@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('master.menu-permission.update', $role->role_id) }}"  method="POST">
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
							<th class="font-weight-bold" style="width: 29%"><b>Menu Name</b></th>
							@foreach($permissions as $permission)
							<th class="permission-name" style="width: 10%;text-align: center;"> 
								<b>{{ $permission->permission_name }}</b>
								<br>
								<input type="checkbox" data-type="parent" data-route="{{ $permission->permission_action }}">
							</th>
							@endforeach
						</thead>                            
					</table>
					<div style="height: 310px;overflow-x: hidden;overflow-y: auto;">
						<table class="table table-bordered table-hover">
							<tbody>
								@if(count($menuPermission) == 0)
                                      <tr>
                                        <td class="text-center" colspan="{{ count($permissions) + 1 }}"> 
                                          <b>Role ini tidak mendapatkan akses menu</b>
                                        </td>
                                      </tr>
                                    @else
                                      {{-- list menu --}}
                                      @foreach($menuPermission as $parent)
                                        <tr>
                                          <td class="font-weight-bold" style="width: 30%"> 
                                            <i class="fa fa-folder-open"></i> 
                                            {{ $parent['name'] }}
                                          </td>
                                          
                                          {{-- if have any route then show permission available --}}
                                          
                                          @for ($i = 0; $i < count($permissions); $i++)
                                            @if($parent['route'])
                                              <td class="permission-checkbox" style="text-align: center;">
                                                <!-- checked checkbox permission -->
                                                 <label>
                                                   <input 
                                                    name="permissions"
                                                    data-menu="{{ $parent['menu_id'] }}"
                                                    data-permission="{{ $permissionIds[$i] }}"
                                                    type="checkbox"
                                                    data-route="{{ $permissions[$i]->permission_action }}"
                                                    {{ 
                                                      in_array( $permissionIds[$i], $parent['permissions']) 
                                                        ? 'checked="checked"'
                                                        : ''  
                                                    }}
                                                  >
                                                 </label>
                                               </td>
                                            @else
                                              <td>
                                               </td>
                                            @endif
                                          @endfor
                                          

                                        </tr>

                                        @foreach($parent['menu'] as $child)
                                          <tr>
                                            <td> 
                                              <span class="role-menu-child" style="margin-left: 25px;">{{ $child['name'] }}</span>
                                            </td>

                                            {{-- if have any route then show permission available --}}
                                            @for ($i = 0; $i < count($permissions); $i++)  
                                              @if($child['route'])
                                                <td class="permission-checkbox" style="text-align: center;">
                                                  <!-- checked checkbox permission -->
                                                   <label>
                                                     <input 
                                                      name="permissions"
                                                      data-menu="{{ $child['menu_id'] }}"
                                                      data-permission="{{ $permissionIds[$i] }}"
                                                      data-route="{{ $permissions[$i]->permission_action }}"
                                                      type="checkbox"
                                                      {{ 
                                                        in_array( $permissionIds[$i], $child['permissions']) 
                                                          ? 'checked="checked"'
                                                          : ''  
                                                      }}
                                                     >
                                                   </label>
                                                 </td>
                                              @else
                                                <td>
                                                </td>
                                              @endif
                                            @endfor
                                            

                                          </tr>

                                          @foreach($child['menu'] as $grandchild)
                                            <tr>
                                              <td>
                                                <span class="role-menu-grandchild" style="margin-left: 50px;">
                                                  {{ $grandchild['name'] }}
                                                </span>
                                              </td>

                                              {{-- if have any route then show permission available --}}
                                                @for ($i = 0; $i < count($permissions); $i++)  
                                                  @if($grandchild['route'])
                                                    <td class="permission-checkbox" style="text-align: center;">
                                                      <!-- checked checkbox permission -->
                                                       <label>
                                                         <input 
                                                          name="permissions"
                                                          data-menu="{{ $grandchild['menu_id'] }}"
                                                          data-permission="{{ $permissionIds[$i] }}"
                                                          data-route="{{ $permissions[$i]->permission_action }}"
                                                          type="checkbox"
                                                          {{ 
                                                            in_array( $permissionIds[$i], $grandchild['permissions']) 
                                                              ? 'checked="checked"'
                                                              : ''  
                                                          }}
                                                         >
                                                       </label>
                                                     </td>
                                                  @else
                                                    <td>
                                                    </td>
                                                  @endif
                                                @endfor
                                              

                                            </tr>
                                          @endforeach
                                        
                                        @endforeach

                                      @endforeach
                                    @endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card-footer">
        <input type="hidden" name="menu_permission" />
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
	<script>

let menuPermissions   = @json($menuPermission); // menu permission from controller
let updatedPermission = []; // assign empty array for updatedpermission

constructUpdatedPermission() // construct updated permission first

// auto check chekcbox below this permission checkbox
$('input[type=checkbox][data-type=parent]').on('click', function() {
  let thisDataRoute      = $(this).attr('data-route')
  let checkboxBelow  = document.querySelectorAll('[data-route="'+ thisDataRoute +'"]');
  
  for (let checkbox of checkboxBelow) {
    checkbox.checked = this.checked
    updatePermissionMenu(checkbox)
  }
})

// on click checkbox permission
$('input[name=permissions]').on('click', function() {
  updatePermissionMenu(this)
})

function constructUpdatedPermission() {
  for(let parent of menuPermissions) {
    pushPermission(parent)

    for(let child of parent.menu) {
      pushPermission(child)

      for(let grandchild of child.menu) {
        pushPermission(grandchild)
      }
    }
  }  

  // assign to form input for update when submit later
  $('input[name=menu_permission]').val(JSON.stringify(updatedPermission))
}

// update variable updatedPermission when element checked/unchecked
function updatePermissionMenu(inputElement) {
  let checked  = inputElement.checked
  let thisMenu = inputElement.getAttribute('data-menu')
  let sameMenus = document.querySelectorAll('[data-menu="'+ thisMenu +'"]');

  // get all permission checked on same menu
  let permissions = []
  for(sameMenu of sameMenus) {
    if(sameMenu.checked) permissions.push(sameMenu.getAttribute('data-permission'))
  }
  
  // update variable updatedPermission
  for(let update of updatedPermission) {
    if(update.menu_id == thisMenu) {
      update.permissions = permissions
    }
  }

  $('input[name=menu_permission]').val(JSON.stringify(updatedPermission))
}

// push permission into variable updatedpermission
function pushPermission(obj) {
  // only menu with exist route which can be updated
  if(obj.route) {
    let menuPermission = {
      'menu_id': obj.menu_id,
      'permissions': obj.permissions
    }

    updatedPermission.push(menuPermission) 
  }
}


</script>
	@endsection