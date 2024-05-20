<?php

namespace App\Livewire\Admin\Product;

use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use ProductManagement\Models\Product;
use ProductManagementContracts\IProductManagementService;
use SupplierManagement\Models\Supplier;

#[Title('Edit Product')]
class EditProduct extends Component
{
    use WithFileUploads;

    public Product $product;

    #[Validate('nullable|image|max:10000')]
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

        $this->setDefaultImages();
    }
    
    public function setDefaultImages()
    {
        $this->defaultFeaturedImage = $this->product->getFirstMedia('featured');
        $this->defaultGallery = $this->product->getMedia('gallery');
    }

    public function updatedFeaturedImage()
    {
        $this->validateOnly('featuredImage');
    }

    public function updatedGallery()
    {
        $this->validateOnly('gallery');
        $this->validateOnly('gallery.*');
    }

    public function removeFeaturedImage()
    {
        $this->featuredImage = null;
    }

    public function save(IProductManagementService $productManagementService)
    {
        $this->validate();

        $productId = $this->product->id;

        DB::beginTransaction();

        $updateResult = $productManagementService->update(
            $productId,
            $this->model,
            $this->description,
            $this->supplier,
            $this->regularPrice,
            $this->salePrice
        );

        if ($updateResult->isFailure()) {
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($updateResult->getError()));
            return;
        }

        if($this->featuredImage){
            $featuredImageResult = $productManagementService->setFeaturedImage($productId, $this->featuredImage->getRealPath());

            if ($featuredImageResult->isFailure()) {
                DB::rollBack();
                session()->flash('alert', ErrorHandler::getErrorMessage($featuredImageResult->getError()));
                return;
            }
        }
        
        if($this->gallery){
            $galleryResult = $productManagementService->setGallery($productId, array_map(function ($image) {
                return $image->getRealPath();
            }, $this->gallery));

            if ($galleryResult->isFailure()) {
                DB::rollBack();
                session()->flash('alert', ErrorHandler::getErrorMessage($galleryResult->getError()));
                return;
            }
        }

        DB::commit();

        $this->reset('featuredImage', 'gallery');
        $this->setDefaultImages();

        session()->flash('success', 'Product updated successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.edit-product', [
            'suppliers' => Supplier::all()  
        ]);
    }
}
