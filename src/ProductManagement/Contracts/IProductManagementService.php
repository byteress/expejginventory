<?php

namespace ProductManagementContracts;

use ProductManagementContracts\Utils\Result;

interface IProductManagementService
{
    public function create(
        string $productId,
        string $model,
        string $description,
        string $supplierId,
        int $regularPrice,
        int $salePrice
    ): Result;

    public function update(
        string $productId,
        string $model,
        string $description,
        string $supplierId,
        int $regularPrice,
        int $salePrice
    ): Result;

    public function setFeaturedImage(string $productId, string $featuredImagePath,): Result;

    /**
     * @param array<string> $gallery
     */
    public function setGallery(string $productId, array $gallery): Result;
    public function delete(string $productId): Result;
}