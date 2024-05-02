@extends('admin.base_layout')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">New Branch</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button type ="reset" class="btn btn-secondary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="text">Reset</span>
            </button>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="form-input-1">Branch Name</label>
                            <input type="text" class="form-control" id="form-input-1" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="form-input-1">Branch Address</label>
                            <input type="text" class="form-control" id="form-input-1" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="form-input-1">Branch Phone</label>
                            <input type="text" class="form-control" id="form-input-1" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleFormControlTextarea1">Branch Description</label>
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <button type ="reset" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Create</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
<!-- /.container-fluid -->
@endsection
