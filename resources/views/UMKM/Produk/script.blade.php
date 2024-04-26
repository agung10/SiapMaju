<script type="text/javascript" src="{{ asset('assets/js/jquery-number.min.js') }}"></script>
<script>
    <?= \helper::number_formats('$("input[name=\'harga\']")', 'js', 0) ?>
    
    $('document').ready(() => {
        const umkm_id = localStorage.getItem('umkm_id') || null
        
        // if has localstorage from umkm page set select umkm value
        if(umkm_id){
            setUmkmSelect(umkm_id)
        }
    })
    function setUmkmSelect(umkm_id){
        $('select[name=umkm_id]').val(umkm_id).trigger('change')
        localStorage.removeItem('umkm_id')
    }
</script>