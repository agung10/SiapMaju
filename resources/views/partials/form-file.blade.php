@php
$imagePath = isset($value) 
    ? \helper::imageLoad($folder, $value) 
    : \helper::imageLoad();
$backgroundImgPath = isset($value) 
    ? "url(". \helper::imageLoad($folder, $value). ")"
    : "url(". \helper::imageLoad(). ")";
@endphp

<div class="form-group {{ isset($multiColumn) ? 'col-lg-4' : 'col-lg-8' }} text-center">
    <label>
        @if( isset($required) )
            <span style="color:red">*</span>
        @endif
        {{ $title }}
    </label>
    <div class="col-xl-12">
        <div class="image-input image-input-outline" id="{{ $name }}">
            @if(isset($disabled))
                <a class="popup-cover" href="{{ $imagePath }}">
                    <div class="image-input-wrapper" style="background-image: {{ $backgroundImgPath }};"></div>
                </a>
            @else
                <div class="image-input-wrapper" style="background-image: {{ $backgroundImgPath }};"></div>
                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Ganti Gambar">
                    <i class="fa fa-pen icon-sm text-muted" style="margin: 7px;"></i>
                    <input type="file" name="{{ $name }}" accept=".png, .jpg, .jpeg"/>
                </label>
                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Batalkan Gambar">
                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                </span>
            @endif
        </div>
        @if(isset($text))
            <span class="form-text text-muted">Allowed file types:  png, jpg, jpeg. Max 5MB</span>
        @endif
    </div>
</div>
<script>
$(function() {
    // Class definition
    let KTImageInputDemo = function () {
        // Private functions
        let initDemos = function () {
            // Example 1
            let id = "{{ $name }}"
            let avatar = new KTImageInput(id);
        }

        return {
            // public functions
            init: function() {
                initDemos();
            }
        };
    }();

    KTUtil.ready(function() {
        KTImageInputDemo.init();
    });

    @if(isset($disabled))
        $('.popup-cover').magnificPopup({
          type: 'image',
        });
    @endif
})

</script>