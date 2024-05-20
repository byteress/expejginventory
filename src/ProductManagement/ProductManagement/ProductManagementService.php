<?php

namespace ProductManagement;

use Exception;
use ProductManagement\Models\Product;
use ProductManagementContracts\Events\ProductCreated;
use ProductManagementContracts\Exceptions\InvalidDomainException;
use ProductManagementContracts\IProductManagementService;
use ProductManagementContracts\Utils\Result;

class ProductManagementService implements IProductManagementService
{
    public function create(
        string $productId,
        string $model,
        string $description,
        string $supplierId,
        int $regularPrice,
        int $salePrice,
    ): Result {
        try {

            $product = new Product();

            $product->id = $productId;
            $product->model = $model;
            $product->description = $description;
            $product->supplier_id = $supplierId;
            $product->regular_price = $regularPrice;
            $product->sale_price = $salePrice;
            $product->save();

            event(new ProductCreated($product->id));

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function update(
        string $productId,
        string $model,
        string $description,
        string $supplierId,
        int $regularPrice,
        int $salePrice
    ): Result {
        try {
            Product::whereId($productId)
                ->update([
                    'model' => $model,
                    'description' => $description,
                    'supplier_id' => $supplierId,
                    'regular_price' => $regularPrice,
                    'sale_price' => $salePrice,
                ]);

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function setFeaturedImage(string $productId, string $featuredImagePath,): Result
    {
        try {
            $product = Product::find($productId);

            if (!$product) throw new InvalidDomainException('Product not found.', ['productId' => 'Product not found.']);

            $product->clearMediaCollection('featured');

            $product->addMedia($featuredImagePath)
                ->withResponsiveImages()
                ->toMediaCollection('featured');

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    /**
     * @param array<string> $gallery
     * */
    public function setGallery(string $productId, array $gallery): Result
    {
        try {
            $product = Product::find($productId);

            if (!$product) throw new InvalidDomainException('Product not found.', ['productId' => 'Product not found.']);

            $product->clearMediaCollection('gallery');

            foreach ($gallery as $image) {
                $product->addMedia($image)
                    ->withResponsiveImages()
                    ->toMediaCollection('gallery');
            }

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function delete(string $productId): Result
    {
        try {
            $product = Product::find($productId);

            if (!$product) throw new InvalidDomainException('Product not found.', ['productId' => 'Product not found.']);

            $product->delete();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }
}
