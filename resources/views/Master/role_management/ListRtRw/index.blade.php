@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        List RT/RW/DKM </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <table class="table table-bordered table-checkable table-responsive" id="datatable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th width="300">Nama</th>
                        <th width="300">RT</th>
                        <th width="300">RW</th>
                        <th width="300">DKM</th>
                        <th width="300">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>
<div class="modal" id="RtRwModal" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Ketua RT/RW</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="rt-rw-form">
                    @method('PUT')
                    <div class="form-group">
                        <label>Ketua RT</label>
                        <select name="is_rt" class="form-control">
                            <option disabled selected>Jadikan Ketua RT</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ketua RW</label>
                        <select name="is_rw" class="form-control">
                            <option disabled selected>Jadikan Ketua RW</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ketua DKM</label>
                        <select name="is_dkm" class="form-control">
                            <option disabled selected>Jadikan Ketua DKM</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('Master.role_management.ListRtRw.indexScript')

@endsection