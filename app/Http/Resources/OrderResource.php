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
        // return [
        //     'id' => $this->id,
        //     'description' => $this->description,
        //     'notes' => $this->notes,
        //     'customer' => [
        //         'id' => $this->customer->id,
        //         'name' => $this->customer->name,
        //     ],
        //     'branch' => [
        //         'id' => $this->branch_id,
        //         'name' => $this->branch->name
        //     ],
        //     'status' => $this->status,
        //     'total' => $this->total,
        //     'created_at' => $this->created_at,
        //     'orderDetails' => $this->orderDetails,

        // ];
        //new code
        return [
            'id'=>$this->id,
            'desc'=>$this->description,
            'created_by'=>$this->created_by,
            'created_by_user_name'=>$this->customer->id,
            'request_state_id'=>$this->status,
            'branch_id'=>$this->branch_id,
            'notes'=>$this->notes,
            'total'=>$this->total,
            'order_details'=>[
                'id'=>$this->orderDetails->id,
                'order_id'=>$this->orderDetails->order_id,
                'product_id'=>$this->orderDetails->product_id,
                'id'=>$this->orderDetails->id,

            ],
        ];
    }
}
