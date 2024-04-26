<style>
    body{
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        padding:20px;
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
    
    .no-surat-container{
        display:flex;
        flex-direction:row;
        justify-content:space-around;
        align-items:center;
        width:100%;
    }

    .no-surat-wrapper{
        display:flex;
        flex-direction:column;
        flex-shrink:100px;
        justify-content:flex-start;
        align-items:flex-start;
        width:50%;
    }

    .no-surat-wrapper p{
        height:1px;
    }

    .no-surat{
        display:flex;
        flex-direction:row;
    }

    .yth-surat{
        width:50%;
    }
    .yth-surat p{ 
        text-align:right;
        height:5px;
    }

    .yth-kota{
        font-weight:bold;
        text-decoration:underline;
    }

    .isi-surat-container{
        width:100%;
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
        align-items:flex-start;
        margin-top:10px;
    }

    .isi-surat-container p{
        height:2px;
        word-break:break-word;
    }

    .isi-surat{
        display:flex;
        flex-direction:row;
        justify-content:space-between;
    }

    .label{
        width:200px;
    }

    .label-no-surat{
        width:70px;
    }

    .space-p{
        height:10px;
    }

    .ttd-container{
        width: 100%;
        display:flex;
        flex-direction:row;
        justify-content:flex-start;
        align-items:flex-start;
    }

    .space-isi-surat-ttd{
        height:50px;
    }
    
    .qr-code-container{
        display:flex;
        justify-content:center;
        align-items:center;
    }

    .rw-container{
        width:50%;
        display:flex;
        flex-direction:column;
    
    }
    .rt-container{
        width:50%;
        display:flex;
        flex-direction:column;
    }
    
    .ttd-rt-container{
        display:flex;
        flex-direction:row;
        justify-content:flex-end;
    }

    .ttd-rt-spacer{
        width:100px;
    }

    .ttd-img{
        width:145px;
    }

    .rt-container p{
        text-align:right;
    }

    .rt-container .ttd-img{
        width:145px;
        float: left;
    }

    @media screen and (max-width:600px){
        body{
            width:680px;
        }
    }

</style>