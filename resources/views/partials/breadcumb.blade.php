<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    @if(count(\Request::segments())  == 2 )
                    {{ucfirst(\Request::segment(2))}}
                    @elseif(\Request::segment(3) == 'create')
                    Tambah {{ucfirst(\Request::segment(2))}}
                    @elseif(\Request::segment(4) == 'edit')
                    Edit {{ucfirst(\Request::segment(2))}}
                    @else
                    Show {{ucfirst(\Request::segment(2))}}
                    @endif
                </h5>
            </div>
        </div>
    </div>
</div>