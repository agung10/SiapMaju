<script>
    // Initialization
    jQuery(document).ready(function() {
        // action to check url sosmed was valid before submit
        $('form.forme').submit(function(e) {
            let urlSosmed = $('.form-sosmed').find('.sosmed-url').val()

            if(typeof urlSosmed !== 'undefined' && urlSosmed != '')
            {
                if(isUrlValid(urlSosmed) == false) {
                    e.preventDefault()
                    e.stopImmediatePropagation();
                    Swal.fire('Sosial media URL tidak valid', 'mohon gunakan url lengkap contoh:https://example.com', 'error')
                    return false;
                }
            }

            $(this).valid() && disableButton();
        });

        // action to count url sosmed when typed or pasted (max 255 char)
        $('.sosmed-url').on("input", function(){
            const thisVal = $(this).val()

            if(thisVal.length > 255) 
            {
                const trimmedString = thisVal.substring(0, 255)
                $(this).val(trimmedString)

                Swal.fire('URL tidak boleh melebihi 255 karakter', '', 'error')
                return false;
            }
        });

        $('a.btn-kembali').on('click', backToIndex);

        function disableButton(){
            $('.btn-submit').addClass('spinner spinner-white spinner-left disabled')
            $('.btn-submit').prop("disabled", true);
        }

        function backToIndex(e){
            e.preventDefault();
            let redirect    = $('a.submenu.active').attr('href');
            window.location = redirect;
        }

        $('body').on('click', '.btn-new-sosmed', function() {
            let form = $(this).parents('.form-sosmed')

            if(sosmedValidated(form))
            {
                let selectSosmed = form.find('select')
                selectSosmed.select2("destroy")

                let formHtml = form.clone()

                // show delete button for cloned element
                let btnDelete = formHtml.find(".btn-delete-sosmed")
                btnDelete.removeClass('d-none')

                // empty input for cloned element
                formHtml.find('.sosmed-url').val("")
                formHtml.find('.sosmed-url').removeClass("is-valid")

                form.find('.action-medsos').addClass('d-none')

                $(formHtml).insertAfter(form).hide().slideDown("slow")

                $(".sosmed-id").select2({
                    placeholder: '-- Pilih Sosmed --', 
                    width: '100%'
                })
            }
        })

        $('body').on('click', '.btn-delete-sosmed', function() {
            // check if has previous form sosmed
            let form = $(this).parents('.form-sosmed')
            let prevForm = form.prev()
            // show button on previous form
            prevForm.find('.action-medsos').removeClass('d-none')
            // delete this form
            form.slideUp("slow", function() { 
                $(this).remove(); 
            });
        })

        function sosmedValidated(form) {
            let selectSosmed = form.find('select').val()
            let urlSosmed = form.find('.sosmed-url').val()

            if(selectSosmed == '' || urlSosmed == '')
            {
                Swal.fire('Sosial media dan URL tidak boleh kosong', '', 'error')
                return false;
            }

            if(isUrlValid(urlSosmed) == false)
            {
                Swal.fire('Sosial media URL tidak valid', 'mohon gunakan url lengkap contoh:https://example.com', 'error')
                return false;
            }

            return true;
        }

        function isUrlValid(string) {
            let url;

            try {
                url = new URL(string);
            } catch (_) {
                return false;  
            }

            return url.protocol === "http:" || url.protocol === "https:";
        }
    });
</script>