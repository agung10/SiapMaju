<style>
     body{
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        padding:10px;
    }

    .header-surat-container{
        width:100%;
        display:flex;
        flex-direction:row;
        justify-content:center;
        align-items:center;
        border-top:0px;
        border-left:0px;
        border-right:0px;
        border-bottom-width:7px;
        border-style:double;
        padding:10px;       
    }

    .header-surat-container h3{
        text-align:center;
        height:5px;
    }

    .logo-kota{
        width:70px;
        margin-right:40px;
    }

    .isi-surat-container{
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
        align-items:flex-start;
        margin:50px;
        background:#ececec;
        padding:10px;
        border-radius:10px;
        font-size:18px;
        height:auto;
    }

    .detail-surat{
        display:flex;
        flex-direction:row;
        justify-content:flex-start;
        align-items:flex-start;
    }

    .label{
        width:130px;
    }

    .btn-approve-lurah{
        background-color:#DFAA00;
        padding:0.5rem;
        border:none;
        cursor: pointer;
        font-weight:bold;
    }

    .btn-approve-lurah:hover{
        background-color:#fcbd00;
        transform:scale(1.1,1.1);
    }

    @media only screen and (max-width:600px){
        .header-surat-container h3{
            font-size:11px;
        }

        .isi-surat-container{
            width:100%;
            font-size:15px;
        }
    }
</style>