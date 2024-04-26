						<!--begin::Aside Menu-->
						<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
							<!--begin::Menu Container-->
							<div
								id="kt_aside_menu"
								class="aside-menu my-4 "
								data-menu-vertical="1"
								 data-menu-scroll="1" data-menu-dropdown-timeout="500" 			>
								<!--begin::Menu Nav-->
								<ul class="menu-nav ">
									@foreach($menus as $menu)
									@if(empty($menu['menu']))
									<li class="menu-item" aria-haspopup="true" >
										<a  href="javascript::void(0)" class="menu-link ">
											<span class="svg-icon menu-icon">
												<i class="{{$menu['icon']}}"></i>
											</span>
											<span class="menu-text">{{$menu['name']}}</span>
										</a>
									</li>
									@else
									<li class="menu-item  menu-item-submenu {{$menu['active'] ? 'menu-item-active menu-item-open' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-icon">
												<i class="{{$menu['icon']}}"></i>
											</span>
											<span class="menu-text">{{$menu['name']}}</span>
											<i class="menu-arrow"></i>
										</a>

										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												@foreach($menu['menu'] as $child)
												@if(empty($child['submenu']))
												<li class="menu-item {{$child['active'] ? 'menu-item-active current-active' : ''}}" aria-haspopup="true" >
													@if($child['route'] != "Gallery.Content")
													<a 
														href="{{ 
															Route::has($child['route'].'.index') 
																? route($child['route'].'.index' )
																: 'javascript:;' 
														}}" 
														class="menu-link"
													>
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">{{$child['name']}}</span>
													</a>
													@endif
												</li>
												@else
												<li class="menu-item menu-item-submenu {{$child['active'] ? 'menu-item-active menu-item-open' : ''}}">
													<a  href="javascript:;" class="menu-link menu-toggle">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">{{$child['name']}}</span>
														<i class="menu-arrow"></i>
													</a>
														<div class="menu-submenu ">
															<ul class="menu-subnav">
																@foreach($child['submenu'] as $subchild)
																<li class="menu-item {{$subchild['active'] ? 'menu-item-active current-active' : ''}}" aria-haspopup="true" >
																	<a 
																		href="{{ 
																			Route::has($subchild['route'].'.index') 
																				? route($subchild['route'].'.index' )
																				: 'javascript:;' 
																		}}"
																		class="menu-link"
																	>
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">{{$subchild['name']}}</span>
																	</a>
																</li>
																@endforeach
															</ul>
														</div>
													</li>
												</li>
												@endif
												@endforeach
											</ul>
										</div>
									</li>
									@endif
									@endforeach
								</ul>
								<!--end::Menu Nav-->
							</div>
							<!--end::Menu Container-->
						</div>
						<!--end::Aside Menu-->
					</div>
					<!--end::Aside-->