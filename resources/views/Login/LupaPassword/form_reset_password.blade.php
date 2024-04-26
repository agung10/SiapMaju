<!DOCTYPE html>
<html lang="en">
<head>
	<title>Lupa Password</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('assets/media/logos/icon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/main.css')}}">
	<script language="JavaScript" type="text/javascript" src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<!--===============================================================================================-->
</head>
<body>
	@include('layouts.css')
	<div class="limiter">
		<div class="container-login100 mainContainer">
			<div class="wrap-login100" style="opacity:0.8;height:550px;">
				<form method="POST" id="formResetPassword" class="login100-form validate-form p-l-55 p-r-55 p-t-178">
                    @csrf
					<span class="login100-form-title">
						Reset Password
					</span>
                    
					<div class="wrap-input100 validate-input m-b-16 idInput" data-validate="Please enter email">
						<input  class="input100 @error('password') is-invalid @enderror" placeholder="Masukan Password Baru" id="password" type="password" minlength="6" name="password" value="{{ old('password') }}" required autocomplete="off" autofocus>
						<span class="focus-input100"></span>
						
                        @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
					</div>
					<span class="err-input userErr"></span>

					<div class="text-right p-t-13 p-b-23">
						<span class="txt1">
					
						</span>

						<a href="#" class="txt2">
						
						</a>
					</div>

					<div class="container-login100-form-btn signInBTN">
						<button class="login100-form-btn btn-sign-in">
							Reset Password
						</button>
					</div>

					<div class="flex-col-c p-t-170 p-b-40">
						<span class="txt1 p-b-9">
						
						</span>

						<a href="#" class="txt3">
		
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>


<!--===============================================================================================-->
	<script src="{{asset('assets/login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('assets/login/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{asset('assets/login/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/js/jquery.blockUI.js')}}"></script>
	<script src="{{asset('assets/login/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/js/main.js')}}"></script>

<script>
	document.querySelector('#formResetPassword').addEventListener('submit',(e) => {
		e.preventDefault()

		formResetPasswordHandler(e)
	})

	async function formResetPasswordHandler(e){
		const loadingGif = '{{asset("images/loading.gif")}}'

		$.blockUI({css: { backgroundColor: 'transparent', border: '0'},
					message:`<img src="${loadingGif}" width="250" />`
				  });

		const url = `{{route('reset-password-process')}}`
		const email = '{{request()->email}}'
		const password = document.querySelector('#password').value

		

		const resetPassword = async () => {
			return await fetch(url,{
								headers:{
									'X-CSRF-TOKEN':'{{ csrf_token() }}',
									'X-Requested-With':'XMLHttpRequest',
									'Content-Type':'application/json'
								},
								method:'post',
								credentials:'same-origin',
								body:JSON.stringify({email,password})
							  })
							  .then(response => response.json())
							  .catch(() => {
								$.unblockUI();
								swal({
									title:
										"Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...",
									icon: "warning",
								})
							  })
		}

		const res = await resetPassword()

		if(res.status !== 'success'){
			swal('Failed to reset password','','error')
                return;
		}

			$.unblockUI();

			swal({
                title: "Your password has been reset!",
                text: "Please try logging in again!",
                icon: "success",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
                }).then((result) => {
                    if (result) {
                        let urlLogin = "{{ route('login') }}";
                        window.location = urlLogin;
                    }
                })

            return;
	}

</script>
</body>
</html>
