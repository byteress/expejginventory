<?php

namespace ProductManagementContracts\Events;

class ProductCreated
{
    public function __construct(
        public string $productId
    ) {
    }
}