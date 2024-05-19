<?php

namespace App\Livewire\Admin\Product;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use ProductManagement\Models\Product;
use SupplierManagement\Models\Supplier;

#[Title('Edit Product')]
class EditProduct extends Component
{
    use WithFileUploads;

    public Product $product;

    #[Validate('required|image|max:10000')]
    public $featuredImage = null;

    #[Validate([
        'gallery' => 'array|max:4',
        'gallery.*' => 'image|max:10000'
    ], message: [
        'gallery.max' => 'The :attribute must not be more than 4 images.',
        'gallery.*.max' => 'The :attribute images must not be greater than 10mb.',
    ], attribute: [
        'gallery.*' => 'gallery'
    ])]
    public $gallery = [];

    #[Validate('required|max:255')]
    public $model;

    #[Validate('required|max:255')]
    public $description;

    #[Validate('required')]
    public $supplier;

    #[Validate('required|numeric|between:0,9999999999.99')]
    public $regularPrice;

    #[Validate('required|numeric|between:0,9999999999.99')]
    public $salePrice;

    public $defaultFeaturedImage = null;
    public $defaultGallery = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->model = $product->model;
        $this->description = $product->description;
        $this->supplier = $product->supplier_id;
        $this->regularPrice = $product->regular_price;
        $this->salePrice = $product->sale_price;

        $this->defaultFeaturedImage = $product->getFirstMedia('featured');
        $this->defaultGallery = $product->getMedia('gallery');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.edit-product', [
            'suppliers' => Supplier::all()  
        ]);
    }
}
