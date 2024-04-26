<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login SiapMaju</title>
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
			<div class="wrap-login100" style="opacity:0.8;height:650px;">
				<form method="POST" action="{{ route('login') }}" class="login100-form validate-form p-l-55 p-r-55 p-t-178 login-form">
                    @csrf
					<span class="login100-form-title">
						Login
					</span>
                    
					<div class="wrap-input100 validate-input m-b-16 idInput" data-validate="Masukan Email Anda">
						<input  class="input100 @error('username') is-invalid @enderror" placeholder="Username" id="username" type="text" name="username" value="{{ old('username') }}"  autocomplete="off" autofocus>
						<span class="focus-input100"></span>
						
                        @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
					</div>
					<span class="err-input userErr"></span>

					<div class="wrap-input100 validate-input passInput" data-validate = "Masukan Password">
						<input class="input100  @error('password') is-invalid @enderror" placeholder="password" id="password" type="password" name="password"  autocomplete="off">
						<span class="focus-input100"></span>
                        @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
					</div>
					<span class="err-input passErr"></span>

					<div class="captcha-container">
						{!! NoCaptcha::display() !!}
						<span class="captcha-error" style="display:none;color:red;font-weight:bold;">Captcha field is required.</span>
						<div class="captcha-spacer" style="height:10px;"></div>
					</div>
					<div class="container-login100-form-btn signInBTN">
						<button class="login100-form-btn btn-sign-in">
							Sign in
						</button>
						<a href="{{route('Login.LupaPassword.index')}}" class="login100-form-btn btn-forgot-password">
							Lupa Password
						</a>
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
	<script src="{{asset('assets/login/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/js/main.js')}}"></script>
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}" defer></script>

	{!! NoCaptcha::renderJs() !!}

	
	<script>

		if('{{session()->has('error')}}'){
					swal({
						title: 'Login Gagal',
						text: '{{session()->get('error')}}',
						icon:'error',
					});
				}
		
		document.querySelector('.login-form').addEventListener('submit',(e) => {
			e.preventDefault()

			loginFormHandler(e)
		})

		function loginFormHandler(e){
			const form = e.currentTarget;
			const valid = $(form).valid()

			const captcha =  document.querySelector('#g-recaptcha-response').value

			if(captcha.length == ''){
				document.querySelector('.captcha-error').style.display = 'block';
			}else{

				document.querySelector('.captcha-error').style.display = 'none';
				form.submit()
			}
		}

	</script>
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/5d6606bc77aa790be33116f9/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->
</body>
</html>
