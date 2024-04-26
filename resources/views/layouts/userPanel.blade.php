<?php
    $user = \DB::table('users')
               ->select('picture')
               ->where('user_id',\Auth::user()->user_id)
               ->first();


    $avatarExist = is_file(public_path("uploaded_files/users/$user->picture"));

    $avatar = $avatarExist ? asset("uploaded_files/users/$user->picture") : asset('images/noAvatar.jpg')
?>

<style>
    .change-picture{
        position: relative;
        top:36%;
        right:33%;
    }

    .upload-picture-container{
        display:flex;
        flex-direction:column;
        margin-top:20px;
        background:#E5E5E5;
        border-radius:10px;
        padding:10px;
    }

    .form-picture-button-container{
        margin-top:10px;
    }

    .form-picture-button-container .btn-light{
        margin-left:10px;
    }

    @media screen and (max-width:600px){
        .change-picture{
            position: relative;
            top:34%;
            right:34%;
        }
    }
</style>    

<!-- begin::User Panel-->
<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
				<!--begin::Header-->
				<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
					<h3 class="font-weight-bold m-0">
			User Profile
					</h3>
					<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
						<i class="ki ki-close icon-xs text-muted"></i>
					</a>
				</div>
				<!--end::Header-->
				<!--begin::Content-->
				<div class="offcanvas-content pr-5 mr-n5">
					<!--begin::Header-->
					<div class="d-flex align-items-center mt-5">
						<div class="symbol symbol-100 mr-5">
							<div class="symbol-label avatar-picture" style="background-image:url({{$avatar}})">
                                <a href="javascript:void(null)" class="change-picture"><img src="{{asset('images/change.png')}}"  width="35px;" alt="change user picture"></a>
                            </div>
							<i class="symbol-badge bg-success"></i>
						</div>
						<div class="d-flex flex-column">
							<a href="javascript:void(null)" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
                                {{Auth::user()->username}}
                            </a>
							<div class="navi mt-2">
								<a href="javascript:void(null)" class="navi-item">
									<span class="navi-link p-0 pb-2">
										<span class="navi-icon mr-1">
											<span class="svg-icon svg-icon-lg svg-icon-primary">
												<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg-->
												<svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24"/>
														<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000"/>
														<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"/>
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span>
										</span>
										<span class="navi-text text-muted text-hover-primary">{{Auth::user()->email}}</span>
									</span>
								</a>
								<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Sign Out</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                </form>
							</div>
						</div>
					</div>
                    <div class="upload-picture-container" style="display:none;">
                        <p class="font-weight-bold font-size-h5 text-dark-75 ">Upload Profil Picture</p>
                        <form class="upload-avatar-form" enctype='multipart/form-data'>
                        @csrf
                        <input type="file" name="picture">
                        <div class="form-picture-button-container">
                            <button class="btn btn-sm btn-primary btn-save-avatar">Save Picture</button>
                            <button class="btn btn-sm btn-light btn-cancel-avatar">Cancel</button>
                        </div>
                        </form>
                    </div>
					<!--end::Header-->
				</div>
				<!--end::Content-->
			</div>
<!-- end::User Panel-->

<script>
    const avatarStorage = localStorage.getItem('avatar-uploaded');

    if(avatarStorage){
        swal({
            title: avatarStorage,
            icon: 'success',
        });

        localStorage.removeItem('avatar-uploaded');
    }

    document.querySelector('input[name=picture]').addEventListener('change',(e) => {
        inputPictureHandler(e)
    })

    document.querySelector('.upload-avatar-form').addEventListener('submit',(e) => {
        e.preventDefault()

        avatarFormHandler(e)
    })

    document.querySelector('.change-picture').addEventListener('click',(e) => {
        e.preventDefault()

        $('.upload-picture-container').fadeIn('slow')
    })

    document.querySelector('.btn-cancel-avatar').addEventListener('click',(e) => {
        e.preventDefault()

        btnCancelAvatarHandler()
    })

    function btnCancelAvatarHandler(){
        const avatarPicture = document.querySelector('.avatar-picture')
        const oldPicture = '{{$avatar}}'

        avatarPicture.style.backgroundImage = `url(${oldPicture})`

        $('.upload-picture-container').fadeOut('slow')
    }

    async function avatarFormHandler(e){
        loading()
        
        const form = e.currentTarget
        const formData = new FormData(form)

        const savePicture = async () => {
            const user_id = '{{\Auth::user()->user_id}}'
            const url = `{{route('master.user.updateProfilPicture','')}}/${user_id}`

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

        const {status} = await savePicture()

        if(!status) return;

        localStorage.setItem('avatar-uploaded','Profil Picture Berhasil di Update')

        window.location.reload()
    }

    function inputPictureHandler(e){
        const avatarPicture = document.querySelector('.avatar-picture')
        
        const file = e.target.files[0]
        let reader = new FileReader()
        
        reader.onload = () => {
            avatarPicture.style.backgroundImage = `url(${reader.result})`
        }

        reader.readAsDataURL(file)
    }

    function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}
</script>