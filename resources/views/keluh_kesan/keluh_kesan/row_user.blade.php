<style>
.img-profile {
    width: 80px !important;
    max-width: 80px !important;
    height: 70px !important;
}
</style>
<span style="width: 250px;">
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40 symbol-sm flex-shrink-0">    
            <img class="img-profile" src="{{ helper::imageLoad('users', $row->picture) }}" alt="photo">
        </div>
        <div class="ml-4">
            <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                {{ $row->username }}
            </div>
            <a href="javascript:void(0);" class="text-muted font-weight-boldtext-hover-primary">
                {{ helper::datePrint($row->updated_at) }}
            </a>                               
        </div>
    </div>
</span>