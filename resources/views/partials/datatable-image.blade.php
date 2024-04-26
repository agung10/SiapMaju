@php
    $style = isset($style) ?  $style : 'width: 100px;max-width: 100px;height: 100px;object-fit: cover;';
    $src   = isset($src) ? \helper::getYoutubeThumbnail($src) : \helper::imageLoad($folder, $img);
@endphp
<a class="popup-cover" href="{{ $src }}">
    <div class="symbol symbol-60 symbol-sm flex-shrink-0">
        <img class="" src="{{ $src }}" alt="photo" style="{{ $style }}">
    </div>
</a>