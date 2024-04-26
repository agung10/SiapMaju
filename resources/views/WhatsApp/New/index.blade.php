@extends('layouts.master')

@section('content')
@include('WhatsApp.New.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                     Tambah No WhatsApp
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="qrcode-container">
                {!! $hmtlImg !!}
                <div class="qrcode-connect-btn" style="{{$whatsappKey === '' ? 'display:none' : ''}}">
                    <i class="fa fa-plug"></i>
                    <span>Connect</span>
                </div>
                <h2 style="{{$whatsappKey === '' ? 'display:none' : ''}}">Scan barcode dengan aplikasi Whatsapp anda lalu Klik tombol Connect</h2>
            </div>

            <div class="send-wa-container">
                <a class="btn btn-primary send-wa-btn">
                    <i class="fa fa-paper-plane"></i>
                    <span>Tes Pesan Whatsapp</span>
                </a>
                <h2>Kirim Pesan Whatsapp <code><b>TEST</b></code> ke nomor terdaftar</h2>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        const WHATSAPP_KEY = '{{$whatsappKey}}'

        $('body').on('click', '.send-wa-btn', async function(){
            const sendResponse = await fetch(`{{route('WhatsApp.create_test')}}`,{
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    method:'POST'
                })
                .then(res => res.json())
                .catch(err => {
                    console.log(err)
                    KTApp.unblockPage()
                    swal.fire('Maaf Terjadi Kesalahan','','error')
                })
            
                if(sendResponse.status) {
                    KTApp.unblockPage()
                    swal.fire('Notification','Pesan Whatsapp berhasil dikirim','success')
                    return;
                }

                swal.fire('Maaf Terjadi Kesalahan','','error')
                    .then(result => {
                        if(result.isConfirmed) window.location.reload()
                    })

        })

        $('body').on('click', '.qrcode-connect-btn', async function(){ 
            loading()

            return await fetch(`{{route('WhatsApp.New.store')}}`,{
                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/json',
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                method:'POST',
                body:JSON.stringify({whatsapp_key:WHATSAPP_KEY})
            })
            .then(async res => {
                KTApp.unblockPage()
                swal.fire('Notification','No Whatsapp berhasil disandingkan','success')
            })
            .catch(err => {
                KTApp.unblockPage()
                swal.fire('Maaf Terjadi Kesalahan','','error')
                                        .then(result => {
                                            if(result.isConfirmed) window.location.reload()
                                        })
            })
        })
    })

</script>
@endsection