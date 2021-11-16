<?php

namespace App\Http\Resources\Dashboard\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => view('dashboard.product.ajax.render_image',['product' => $this])->render(),
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => optional($this->category)->name,
            'price' => $this->price,
            'offer_price' => $this->offer_price,
            'activate_link' => view('dashboard.product.ajax.render_activate',['product' => $this])->render(),
            'show_link' => route('dashboard.product.show',$this->id),
            'edit_link' => route('dashboard.product.edit',$this->id),
            'destroy_link' => route('dashboard.product.destroy',$this->id),
            'created_at' => $this->created_at->format("Y-m-d")
        ];
    }
}
