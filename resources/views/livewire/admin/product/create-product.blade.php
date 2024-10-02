<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">New Product</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="#" onclick="history.back()" type="button" class="btn btn-secondary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-chevron-left"></i>
                </span>
                <span class="text">Back</span>
            </a>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="container">
                    @if (session('alert'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('alert') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="img-container">
                                        @error('featuredImage')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        <input x-ref="featuredImage" type="file" id="imgInp" class = "school-img"
                                            accept="image/*" wire:model="featuredImage" hidden>
                                        @if (!$featuredImage || count($errors->get('featuredImage')))
                                            <div @click="$refs.featuredImage.click()" class="img-area" data-img="">
                                                <h1><i class="fas fa-image"></i></h1>
                                            </div>
                                            <a @click="$refs.featuredImage.click()"
                                                class="btn btn-primary btn-full select-image"> Featured Image</a>
                                        @else
                                            <div class="img-area" data-img="">
                                                <img id="chosen-image" class = "school-img"
                                                    src ="{{ $featuredImage->temporaryUrl() }}">
                                            </div>
                                            <a wire:click="removeFeaturedImage"
                                                class="btn btn-danger btn-full select-image"> Remove</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    @error('gallery')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @error('gallery.*')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="row mt-3">
                                        <div class="col-md-9">
                                            <h4>Additional Images </h4>
                                        </div>
                                        <div class="col-md-3">
                                            <a @click="$refs.gallery.click()"
                                                class="btn btn-primary btn-block select-multiple mb-3"><i
                                                    class="fas fa-plus"></i> Select Images</a>
                                            <input x-ref="gallery" type="file" id="additionalImg"
                                                class = "additional-img" accept="image/*" wire:model="gallery" hidden
                                                multiple>
                                        </div>
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body additional-img-container">
                                                        @if (!isset($gallery[$i]) || count($errors->get('gallery')) || count($errors->get('gallery.*')))
                                                            <h1><i class="fas fa-plus"></i></h1>
                                                        @else
                                                            <img src ="{{ $gallery[$i]->temporaryUrl() }}"
                                                                class ="additional-images img-fluid">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Model</label>
                                <input type="text" class="form-control" id="product_name" wire:model="model"
                                    placeholder="">
                                @error('model')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Description</label>
                                <input type="text" class="form-control" id="product_code" wire:model="description"
                                    placeholder="">
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier" wire:model="supplier">
                                    <option selected value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->code }} -
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="regular-price">Regular Price</label>
                                <input type="text" class="form-control" id="regular-price" wire:model="regularPrice"
                                    placeholder="">
                                @error('regularPrice')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sale-price">Sale Price</label>
                                <input type="text" class="form-control" id="sale-price" wire:model="salePrice"
                                    placeholder="">
                                @error('salePrice')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
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
                        </div> --}}
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
