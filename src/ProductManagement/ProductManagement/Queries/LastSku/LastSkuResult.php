<?php

namespace ProductManagement\Queries\LastSku;

class LastSkuResult
{
    public function __construct(
        public string $code,
        public string $number,
    ) {
    }
}