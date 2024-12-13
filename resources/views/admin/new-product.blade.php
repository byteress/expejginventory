@extends('admin.base_layout')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">New Product</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button type="reset" class="btn btn-secondary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="text">Reset</span>
            </button>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="img-container">
                                        <input type="file" id="imgInp" class = "school-img" accept="image/*" name = "schoollogo" hidden>
                                        <div class="img-area" data-img="">
                                            <h1><i class="fas fa-image"></i></h1>
                                            {{-- if Image is there <img id="chosen-image" class = "school-img"  src =""> --}}
                                        </div>
                                        <a class="btn btn-primary btn-full select-image"> Featured Image</a>
                                    </div>
                                </div>
                              </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mt-3">
                                        <div class="col-md-9">
                                            <h4>Additional Images </h4>
                                        </div>
                                        <div class="col-md-3">
                                            <a class="btn btn-primary btn-block select-multiple mb-3"><i class="fas fa-plus"></i> Select Images</a>
                                            <input type="file" id="additionalImg" class = "additional-img" accept="image/*" name = "schoollogo" hidden multiple>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body additional-img-container">
                                                    <h1><i class="fas fa-plus"></i></h1>
                                                    {{-- if image exist <img src ="#" class ="additional-images"> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body additional-img-container">
                                                    <h1><i class="fas fa-plus"></i></h1>
                                                    {{-- if image exist <img src ="#" class ="additional-images"> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body additional-img-container">
                                                    <h1><i class="fas fa-plus"></i></h1>
                                                    {{-- if image exist <img src ="#" class ="additional-images"> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body additional-img-container">
                                                    <h1><i class="fas fa-plus"></i></h1>
                                                    {{-- if image exist <img src ="#" class ="additional-images"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Product Code</label>
                                <input type="text" class="form-control" id="product_code" name="product_code" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_description">Product Description</label>
                                <textarea class="form-control" id="product_description" name="product_description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier" name="supplier">
                                    <option value="supplier1">Supplier 1</option>
                                    <option value="supplier2">Supplier 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <select class="form-control" id="branch" name="branch">
                                    <option value="branch1">Branch 1</option>
                                    <option value="branch2">Branch 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="category1">Category 1</option>
                                    <option value="category2">Category 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button" class="btn btn-primary float-right mb-4" onclick="addVariation()"><i class="fas fa-plus"></i> Add Variation</button>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h4>Variation</h4>
                                </div>
                                <div class="card-body">
                                    <div id="variations">
                                        <div class="variation">
                                            <div class="form-group">
                                                <label for="variation_price">Price</label>
                                                <input type="text" class="form-control" name="variation_price[]" placeholder="Price">
                                            </div>
                                            <div class="form-group">
                                                <label for="variation_description">Description</label>
                                                <textarea class="form-control" name="variation_description[]" rows="2" placeholder="Description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Create</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection
