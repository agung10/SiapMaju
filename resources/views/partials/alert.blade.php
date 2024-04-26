@if(Session::has('notification'))
    <div class="alert alert-custom alert-{{ Session::get('type') }} fade show" role="alert" id="alert-status">
            <div class="alert-icon">
                <i class="fa fa-check">              
                </i>
            </div>
        <div class="alert-text">{{ Session::get('notification') }}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <script>
        $("#alert-status").fadeTo(4000, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    </script>
@endif