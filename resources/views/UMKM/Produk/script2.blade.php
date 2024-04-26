<script type="text/javascript" src="{{ asset('assets/js/jquery-number.min.js') }}"></script>

<script>
    <?= \helper::number_formats('$("input[name=\'harga\']")', 'js', 0) ?>

    window.addEventListener('DOMContentLoaded',() => {
        document.querySelectorAll('input[type=file]').forEach((el,i) => {
        if(el.name == 'image') return
            el.addEventListener('change',() => {
                const umkm_image_id = el.parentElement
                                    .parentElement
                                    .parentElement
                                    .parentElement
                                    .nextElementSibling
                                    .nextElementSibling
                                    .value
                uploadImage(el,umkm_image_id,i)
            })
        })
    })

    const uploadImage = async (el,umkm_image_id,imageIndex) => {
        const formData = new FormData()
        const file = $(el)[0].files[0]

        if(file == null) return;

        formData.append('file_image',file)
        formData.append('imageIndex',imageIndex)

        const res =  await fetch(`{{route('UMKM.Produk.cartImage','')}}/${umkm_image_id}`,{
            headers:{
                'X-CSRF-TOKEN':'{{csrf_token()}}',
                'X-Requested-With':'XMLHttpRequest'
            },
            method:'POST',
            body:formData
        })
    }
</script>