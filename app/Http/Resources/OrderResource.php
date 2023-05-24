<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id' => $this->id,
            'categories' => $this->categories,
            // 'customer' => [
            //     'id' => $this->customer->id,
            //     'name' =>  $this->customer->name,
            // ],
            // 'description' => $this->description,
            // 'notes' => $this->notes,   
            // 'orderDetails' => $this->orderDetails->product
            // 'orderDetails' => $this->orderDetails
        ];
    }
}
