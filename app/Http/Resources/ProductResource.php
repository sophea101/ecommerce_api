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
            'code' => $this->code,
            'category' => $this->category?$this->category->name:null,
            'image' => $this->image,
            'net_price' => '$'.$this->net_price,
            'cost' => '$'.$this->cost,
            'discount' => $this->discount."%",
            'product_after_discount' => '$'.($this->cost - ($this->cost/100)*$this->discount),
            'hits' => $this->hits,
            'description' => $this->description
        ];
    }
}
