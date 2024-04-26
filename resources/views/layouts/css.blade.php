<style>
	/* Login Page CSS */
		.mainContainer{
			background:url('{{asset("images/login-cover.jpg")}}');
			background-size:cover;
		}

		.resetBTN{
			background-color:rgba(0, 140, 255, 1);
			width:150px;
		}

		.btn-nextLogin,.btn-sign-in{
			background-color:#4664b8;
		}

		.err-input{
			color:red;
			font-weight:bold;
			padding:10px;
			margin:10px;
		}

		.login100-form-title{
			background-color:#4664b8;
		}

		.login-form{
			display:flex;
			flex-direction:column;
			justify-content:center;
			align-items:center;
		}

		.btn-forgot-password{
			background:#354f5b;
			margin-top:10px;
		}
	/* End Login Page CSS */
	.logo-masjid{
		font-size:20px;
		color:white;
		padding:10px;
		margin:10px;
	}

	.loadingBoostrap{
		background-color:transparent;
		justify-content:center;
		align-items:center;
	}

	.loadingText{
		font-weight:bold;
		margin:10px;
		margin-top:20px;
	}
	
	.inputContainer{
		justify-content:space-around;
	}

	.imageContainer{
		display:flex;
		width:270px;
		height:270px;
		border-style:solid;
		border-color:gray;
		border-width:1px;
		justify-content:center;
		align-items:center;
		border-radius:10px;
	}

	.imagePicture{
		width:250px;
		height:250px;
		object-fit:contain;
	}

	.imageContainerEdit{
		display:flex;
		width:270px;
		height:270px;
		border-style:solid;
		border-color:gray;
		border-width:1px;
		justify-content:center;
		align-items:center;
		border-radius:10px;
		margin-top:20px;
	}
	.imagePengumumanContainer{
		display:flex;
		flex-direction:row;
		margin-top:20px;
		justify-content:space-around;
	}
	.pengumumanPictureLabel{
		text-align:center;
		margin:10px;
	}
	.imageContainerPengumuman{
		display:flex;
		width:150px;
		height:150px;
		border-style:solid;
		border-color:gray;
		border-width:1px;
		justify-content:center;
		align-items:center;
		border-radius:10px;
		margin-top:20px;
		margin-bottom:20px;
	}
	.imagePicturePengumuman{
		width:130px;
		height:130px;
		object-fit:contain;
	}
	.imgMateri{
		width:400px;
		height:400px;
		object-fit:contain;
		justify-content:center;
		align-self:center;
		margin:10px;
	}

	.detailTransText{
		padding:10px;
	}

	.transRow{
		border:none;
		background: linear-gradient(#000, #000) center bottom 5px /calc(100% - 10px) 1px no-repeat;
 	    background-color: #fcfcfc;
		text-align:center;
	}

	.headerSaveButtonContainer{
		
		margin-right:30px;
		padding:20px;
	}

	.errorHeader{
		font-size:10px;
		color:red;
		padding: 10px;
	}

	.sendButtonContainer{
		margin-top:30px;
		margin-bottom:20px;
		margin-left:3px;
		padding:10px;
	}

	#select-spinner.spinner {
		width: 20px;
		height: 20px;
		position: absolute;
		right: 39px;
		margin-top: -28px;
	}

	.form-group .l-center{
		display: block;
		text-align: center;
	}

	.symbol {
		display: inline-block;
	    position: relative;
	    border-radius: 0.675rem;
	    flex-shrink: 0 !important;
	    /* width: 100px; */
	}
	.symbol > img {
		max-width: 130px;
	    height: 100%;
        display: flex;
	    border-radius: 0.675rem;
	    vertical-align: middle;
	    border-style: none;
	}
</style>