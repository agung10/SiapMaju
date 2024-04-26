@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="#" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Nama Role <span class="text-danger">*</span></label>
					<input type="text" name="role_name" class="form-control"  placeholder="Masukan Nama Role" value="{{$role->role_name}}" disabled />
					<div style="font-size:10px;margin:5px;color:red" class="err-role_name"></div>
				</div>
				<div class="form-group mb-1">
					<table class="table table-bordered">
						<thead class="thead-light">
							<th class="font-weight-bold" style="width: 29%"><b>Menu Name</b></th>
							@foreach($permissions as $permission)
							<th class="permission-name" style="width: 10%"> 
								<b>{{ $permission->permission_name }}</b>
							</th>
							@endforeach
						</thead>                            
					</table>
					<div style="height: 310px;overflow-x: hidden;overflow-y: auto;">
						<table class="table table-bordered table-hover">
							<tbody>
								@foreach($menuPermission as $parent)
                                      <tr>
                                        <td class="font-weight-bold" style="width: 30%;text-align: center;"> 
                                          <i class="fa fa-folder-open"></i> 
                                          {{ $parent['name'] }}
                                        </td>                                                                                
                                        @for ($i = 0; $i < count($permissions); $i++)
                                          @if($parent['route'])
                                            <td class="permission-checkbox">
                                              <!-- checked checkbox permission -->
                                               <label>
                                                 <input 
                                                  type="checkbox"
                                                  disabled="" 
                                                  {{ 
                                                    in_array( $permissionIds[$i], $parent['permissions']) 
                                                      ? 'checked="checked"'
                                                      : ''  
                                                  }}
                                                >
                                               </label>
                                             </td>
                                          @else
                                            <td class="permission-checkbox">
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
                                              <td class="permission-checkbox">
                                                <!-- checked checkbox permission -->
                                                 <label>
                                                   <input 
                                                    type="checkbox"
                                                    disabled="" 
                                                    {{ 
                                                      in_array( $permissionIds[$i], $child['permissions']) 
                                                        ? 'checked="checked"'
                                                        : ''  
                                                    }}
                                                   >
                                                 </label>
                                               </td>
                                            @else
                                              <td class="permission-checkbox">
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
                                                  <td class="permission-checkbox">
                                                    <!-- checked checkbox permission -->
                                                     <label>
                                                       <input 
                                                        type="checkbox"
                                                        disabled="" 
                                                        {{ 
                                                          in_array( $permissionIds[$i], $grandchild['permissions']) 
                                                            ? 'checked="checked"'
                                                            : ''  
                                                        }}
                                                       >
                                                     </label>
                                                   </td>
                                                @else
                                                  <td class="permission-checkbox">
                                                  </td>
                                                @endif
                                              @endfor
                                            

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
	@endsection