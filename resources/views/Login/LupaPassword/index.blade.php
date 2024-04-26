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
						Login
					</span>
                    
					<div class="wrap-input100 validate-input m-b-16 idInput" data-validate="Please enter email">
						<input  class="input100 @error('email') is-invalid @enderror" placeholder="Masukan Email" id="email" type="text" name="email" value="{{ old('email') }}" required autocomplete="off" autofocus>
						<span class="focus-input100"></span>
						
                        @error('email')
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

					<div class="captcha-container" style="display:flex;align-items:center;justify-content:center;margin-bottom:10px;flex-direction:column;">
						{!! NoCaptcha::display() !!}
						<span class="captcha-error" style="display:none;color:red;font-weight:bold;">Captcha field is required.</span>
						<div class="captcha-spacer" style="height:10px;"></div>
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
	<script src="{{asset('assets/js/jquery.blockUI.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('assets/login/js/main.js')}}"></script>

	{!! NoCaptcha::renderJs() !!}
<script>
	 if('{{session()->has('error')}}'){
                swal({
                    title: 'Login Gagal',
                    text: '{{session()->get('error')}}',
                    icon:'error',
                });
            }
	
	document.querySelector('#formResetPassword').addEventListener('submit',(e) => {
		e.preventDefault()

		const captcha =  document.querySelector('#g-recaptcha-response').value

			if(captcha.length == ''){
				document.querySelector('.captcha-error').style.display = 'block';
			}else{

				document.querySelector('.captcha-error').style.display = 'none';
				resetPassHandler()
			}	
	})

	async function resetPassHandler(){

		let email = document.querySelector('#email').value
			email = email.toLowerCase()
			
		const loadingGif = '{{asset("images/loading.gif")}}'

		$.blockUI({css: { backgroundColor: 'transparent', border: '0'},
					message:`<img src="${loadingGif}" width="250" />`
				  });
		
		const checkEmail = async () => {
			return await fetch(`{{route('Login.LupaPassword.checkEmail')}}`,{
								headers:{
									'X-CSRF-TOKEN':'{{csrf_token()}}',
									'X-Requested-With':'XMLHttpRequest',
									'Content-Type':'application/json'
								},
								method:'post',
								body:JSON.stringify({email})
							  })
							   .then(response => response.json())
							   .catch((e) => {
									$.unblockUI();
									 swal('Maaf Terjadi Kesalahan','','error')
                                     throw e
							   })
		}

		const sendEmailResetPass = async () => {
			return await fetch(`{{route('Login.LupaPassword.sendEmailResetPass')}}`,{
								headers:{
									'X-CSRF-TOKEN':'{{csrf_token()}}',
									'X-Requested-With':'XMLHttpRequest',
									'Content-Type':'application/json'
								},
								method:'post',
								body:JSON.stringify({email})
							  })
							   .then(response => response.json())
							   .catch((e) => {
									$.unblockUI();
									 swal('Maaf Terjadi Kesalahan','','error')
                                     throw e
							   })
		}

		const {status} = await checkEmail()

		if(status == 'failed'){
			$.unblockUI();
			swal("Email account doesn't exist!","Sorry, we couldn't find account associated with this email!",'error')
                    .then(result => {
                        if(result) location.reload()
                    })
			return;
		}

		const sendEmail = await sendEmailResetPass(email)
           
            if(sendEmail.status === false){
				$.unblockUI();
                swal('Failed to reset password','','error')
                return;
            }

			$.unblockUI();

            swal('Email Sent!','An email to reset your password has been sent to your email!','success')
                .then(result => {
                    if(result) window.location = `{{route('login')}}`
                })

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
