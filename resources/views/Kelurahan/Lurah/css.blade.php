<style>
    .search-surat-container{
        border-radius:20px;
        display:flex;
        flex-direction:row;
    }

    .search-surat-container input{
        border-radius:20px;
        padding:0.5rem;
    }

    .search-surat-container input:focus{
        outline:none;
    }

    .search-submit,.btn-qrcode,.btn-reset{
        background-color:#54ff80b8;
        width:35px;
        height:35px;
        border-radius:25px;
        display:flex;
        justify-content:center;
        align-items:center;
        border:none;
        margin-left:0.7rem;
    }

    .search-submit:hover{
        background-color:#54ff80;
        transform:scale(1.1,1.1);
    }

    .search-submit i{
        color:green;
    }

    .btn-qrcode{
        background-color:#e14444b8;
    }

    .btn-qrcode:hover{
        background-color:#d91a1ab8;
        transform:scale(1.1,1.1);
    }

    .btn-qrcode i{
        color:white;
    }

    .btn-reset{
        background-color:#448be1b8;
    }

    .btn-reset:hover{
        background-color:#1a7dd9b8;
        transform:scale(1.1,1.1);
    }

    .btn-reset i{
        color:white;
    }

    .form-surat-container{
        padding:15px;
    }
    
    .lampiran-container{
        padding:15px 0px 15px 0px;
    }

    .qrscanner-container{
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
    }

    .select-camera-container{
        display:none;
        flex-direction:row;
        align-self:flex-start;
        width:230px;
        justify-content:space-around;
        margin-bottom:0.5rem;
    }

    .camera{
        width:100px;
        border-radius:5px;
        background-color:#08d1ffbd;
        padding:0.2rem;
        display:flex;
        justify-content:center;
        align-items:center;
        font-weight:bold;
        cursor:pointer;
    }

    .camera:hover{
        background-color:#00c2efde;
        transform:scale(1.1,1.1);
    }

    #qrscanner{
        width:450px
    }
    
    
    @media screen and (max-width:600px) {
        .search-surat-container input{
            width:185px
        }

        #qrscanner{
            width:380px
        }
    }
</style>