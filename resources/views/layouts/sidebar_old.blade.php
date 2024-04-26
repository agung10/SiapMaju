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
									<li class="menu-item" aria-haspopup="true" >
										<a  href="index.html" class="menu-link ">
											<span class="svg-icon menu-icon">
												<i class="fa fa-home"></i>
											</span>
											<span class="menu-text">Dashboard</span>
										</a>
									</li>
									<li class="menu-item menu-item-submenu {{\Request::segment(1) === 'Beranda' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="svg-icon menu-icon">
												<i class="fa fa-hourglass-half"></i>
											</span>
											<span class="menu-text">Beranda</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Beranda</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Header' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Beranda.Header.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
															<span></span>
														</i>
														<span class="menu-text">Header</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Kontak' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Beranda.Kontak.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
															<span></span>
														</i>
														<span class="menu-text">Kontak</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Tentang' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-icon">
												<i class="fa fa-users"></i>
											</span>
											<span class="menu-text">Tentang</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Tentang</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Profile' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Tentang.Profile.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Profile</span>
													</a>
												</li>
												<li class="menu-item menu-item-submenu {{\Request::segment(2) === 'Tentang' ? 'menu-item-active' : ''}}"">
													<a  href="javascript:;" class="menu-link menu-toggle">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Pengurus</span>
														<i class="menu-arrow"></i>
													</a>
														<div class="menu-submenu ">
															<ul class="menu-subnav">
																<li class="menu-item {{\Request::segment(3) === 'KategoriPengurus' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Tentang.KategoriPengurus.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Kategori Pengurus</span>
																	</a>
																</li>
																<li class="menu-item {{\Request::segment(3) === 'ListPengurus' ? 'menu-item-active' : ''}}"  aria-haspopup="true" >
																	<a  href="{{route('Tentang.ListPengurus.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Pengurus</span>
																	</a>
																</li>
															</ul>
														</div>
													</li>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Program' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="svg-icon menu-icon">
												<i class="fa fa-list-ol"></i>
											</span>
											<span class="menu-text">Program Kegiatan</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Program Kegiatan</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Kegiatan' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Program.Kegiatan.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
															<span></span>
														</i>
														<span class="menu-text">Program</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Agenda' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-icon">
												<i class="fa fa-clock"></i>
											</span>
											<span class="menu-text">Agenda</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Agenda</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'AgendaKegiatan' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Agenda.AgendaKegiatan.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Agenda Kegiatan</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Pengumuman' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="svg-icon menu-icon">
												<i class="fa fa-bullhorn"></i>
											</span>
											<span class="menu-text">Pengumuman</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Pengumuman</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'List' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Pengumuman.List.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
															<span></span>
														</i>
														<span class="menu-text">Pengumuman</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Kajian' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="svg-icon menu-icon">
												<i class="fa fa-book"></i>
											</span>
											<span class="menu-text">Kajian</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Kajian</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Kategori' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Kajian.Kategori.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
															<span></span>
														</i>
														<span class="menu-text">Kategori</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Konten' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Kajian.Konten.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
															<span></span>
														</i>
														<span class="menu-text">Kajian</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Gallery' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-icon">
												<i class="fa fa-file"></i>
											</span>
											<span class="menu-text">Laporan</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Laporan</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'GalleryList' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Laporan.KategoriLaporan.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Kategori Laporan</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'GalleryContent' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Laporan.Laporan.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Laporan</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Gallery' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-icon">
												<i class="fa fa-image"></i>
											</span>
											<span class="menu-text">Gallery</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Gallery</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'GalleryList' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Gallery.List.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Gallery</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'GalleryContent' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Gallery.Content.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Gallery Content</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Master' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
										<a  href="javascript:;" class="menu-link menu-toggle">
											<span class="menu-icon">
												<i class="fa fa-archive"></i>
											</span>
											<span class="menu-text">Menu Master</span>
											<i class="menu-arrow"></i>
										</a>
										<div class="menu-submenu ">
											<i class="menu-arrow"></i>
											<ul class="menu-subnav">
												<li class="menu-item  menu-item-parent" aria-haspopup="true" >
													<span class="menu-link">
														<span class="menu-text">Menu Master</span>
													</span>
												</li>
												<li class="menu-item {{\Request::segment(2) === 'Blok' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
													<a  href="{{route('Master.Blok.index')}}" class="menu-link ">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Blok Rumah</span>
														<span class="menu-label">
														</span>
													</a>
												</li>
												<li class="menu-item menu-item-submenu {{\Request::segment(2) === 'Master' ? 'menu-item-active' : ''}}"">
													<a  href="javascript:;" class="menu-link menu-toggle">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Keluarga</span>
														<i class="menu-arrow"></i>
													</a>
														<div class="menu-submenu ">
															<ul class="menu-subnav">
																<li class="menu-item {{\Request::segment(3) === 'HubKeluarga' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Master.HubKeluarga.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Hubungan Keluarga</span>
																	</a>
																</li>
																<li class="menu-item {{\Request::segment(3) === 'ListKeluarga' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Master.ListKeluarga.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Keluarga</span>
																	</a>
																</li>
																<li class="menu-item {{\Request::segment(3) === 'AnggotaKeluarga' ? 'menu-item-active' : ''}}"  aria-haspopup="true" >
																	<a  href="{{route('Master.AnggotaKeluarga.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Anggota Keluarga</span>
																	</a>
																</li>
															</ul>
														</div>
													</li>
												</li>
												<li class="menu-item menu-item-submenu {{\Request::segment(2) === 'Master' ? 'menu-item-active' : ''}}"">
													<a  href="javascript:;" class="menu-link menu-toggle">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Kegiatan</span>
														<i class="menu-arrow"></i>
													</a>
														<div class="menu-submenu ">
															<ul class="menu-subnav">
																<li class="menu-item {{\Request::segment(3) === 'KatKegiatan' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Master.KatKegiatan.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Kategori Kegiatan</span>
																		<span class="menu-label">
																		</span>
																	</a>
																</li>
																<li class="menu-item {{\Request::segment(3) === 'ListKegiatan' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Master.ListKegiatan.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Kegiatan</span>
																	</a>
																</li>
															</ul>
														</div>
													</li>
												</li>
												<li class="menu-item menu-item-submenu {{\Request::segment(2) === 'Master' ? 'menu-item-active' : ''}}"">
													<a  href="javascript:;" class="menu-link menu-toggle">
														<i class="menu-bullet menu-bullet-line">
														<span></span>
														</i>
														<span class="menu-text">Transaksi</span>
														<i class="menu-arrow"></i>
													</a>
														<div class="menu-submenu ">
															<ul class="menu-subnav">
																<li class="menu-item {{\Request::segment(3) === 'Transaksi' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Master.Transaksi.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Transaksi</span>
																		<span class="menu-label">
																		</span>
																	</a>
																</li>
																<li class="menu-item {{\Request::segment(3) === 'JenisTransaksi' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
																	<a  href="{{route('Master.JenisTransaksi.index')}}" class="menu-link ">
																		<i class="menu-bullet menu-bullet-line">
																		</i>
																		<span class="menu-text">Jenis Transaksi</span>
																	</a>
																</li>
															</ul>
														</div>
													</li>
												</li>						
											</ul>
											<li class="menu-item  menu-item-submenu {{\Request::segment(1) === 'Transaksi' ? 'menu-item-active' : ''}}" aria-haspopup="true"  data-menu-toggle="hover">
												<a  href="javascript:;" class="menu-link menu-toggle">
													<span class="svg-icon menu-icon">
														<i class="fa fa-briefcase"></i>
													</span>
													<span class="menu-text">Transaksi Kegiatan</span>
													<i class="menu-arrow"></i>
												</a>
												<div class="menu-submenu ">
													<i class="menu-arrow"></i>
													<ul class="menu-subnav">
														<li class="menu-item  menu-item-parent" aria-haspopup="true" >
															<span class="menu-link">
																<span class="menu-text">Transaksi Kegiatan</span>
															</span>
														</li>
														<li class="menu-item {{\Request::segment(2) === 'Header' ? 'menu-item-active' : ''}}" aria-haspopup="true" >
															<a  href="{{route('Transaksi.Header.index')}}" class="menu-link ">
																<i class="menu-bullet menu-bullet-line">
																	<span></span>
																</i>
																<span class="menu-text">Transaksi Kegiatan</span>
																<span class="menu-label">
																</span>
															</a>
														</li>
													</ul>
												</div>
											</li>
										</div>
									</li>
								</ul>
								<!--end::Menu Nav-->
							</div>
							<!--end::Menu Container-->
						</div>
						<!--end::Aside Menu-->
					</div>
					<!--end::Aside-->