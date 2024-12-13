<?php

namespace App\Livewire\Admin\Product;

use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use OrderContracts\IOrderService;
use ProductManagementContracts\IProductManagementService;
use Str;
use SupplierManagement\Models\Supplier;

#[Title('Create Product')]
class CreateProduct extends Component
{
    use WithFileUploads;

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

    public function save(IProductManagementService $productManagementService, IOrderService $orderService)
    {
        $this->validate();

        $productId = Str::uuid()->toString();

        DB::beginTransaction();

        $createResult = $productManagementService->create(
            $productId,
            $this->model,
            $this->description,
            $this->supplier,
            $this->regularPrice * 100,
            $this->salePrice * 100
        );

        if ($createResult->isFailure()) {
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($createResult->getError()));
            return;
        }

        $featuredImageResult = $productManagementService->setFeaturedImage($productId, $this->featuredImage->getRealPath());

        if ($featuredImageResult->isFailure()) {
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($featuredImageResult->getError()));
            return;
        }

        $galleryResult = $productManagementService->setGallery($productId, array_map(function ($image) {
            return $image->getRealPath();
        }, $this->gallery));

        if ($galleryResult->isFailure()) {
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($galleryResult->getError()));
            return;
        }

        $orderResult = $orderService->setPrice($productId, $this->regularPrice * 100, $this->salePrice * 100);
        if ($orderResult->isFailure()) {
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($orderResult->getError()));
            return;
        }

        DB::commit();
        $this->reset();
        session()->flash('success', 'Product created successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.create-product', [
            'suppliers' => Supplier::all()
        ]);
    }
}
