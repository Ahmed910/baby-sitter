<?php

namespace App\Http\Resources\Dashboard\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product_count = isset($this->additional['product_count']) ? $this->additional['product_count'] : 0;
        return [
            'data'              => SingleProductResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($product_count),
            "recordsFiltered"   =>  intval($product_count),
        ];
    }
}
