<?php

namespace ProductManagement\Queries\LastSku;

use Illuminate\Support\Facades\DB;

class LastSkuQuery
{
    public function getData(): LastSkuResult|null
    {
        $product = DB::table('products')
            ->select(['sku_code', 'sku_number'])
            ->whereNotNull('sku_number')
            ->orderBy('sku_number', 'desc')
            ->first();

        if(!$product) return null;

        return new LastSkuResult(
                code: $product->sku_code ?? '',
                number: $product->sku_number ?? '',
            );
    }
}