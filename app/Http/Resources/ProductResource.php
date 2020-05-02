<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'product_code' => $this->product_code,
            'category' => $this->category?$this->category->name:null,
            'image' => $this->image,
            'unit_price' => '$'.$this->unit_price,
            'qty' => '$'.$this->qty,
            'discount' => $this->discount."%",
            'product_after_discount' => '$'.($this->unit_price - ($this->unit_price/100)*$this->discount),
            'hits' => $this->hits,
            'description' => $this->description
        ];
    }
}
