<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            $this->category
            // 'name' => $this->name,
            // 'description' => $this->description,
            // 'active' => $this->active,
            // 'category' => [
            //     'category_id' => $this->category->id,
            //     'category_name' => $this->category->name
            // ],
            // 'unitPrices' => $this->unitPrices
        ];
    }
}
