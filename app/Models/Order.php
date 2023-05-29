<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const ORDERED = 'ordered';
    public const PROCESSING = 'processing';
    public const READY_FOR_DELEVIRY = 'ready_for_deleviry';
    public const DELEVIRED = 'delevired';
    public const PENDING_APPROVAL = 'pending_approval';
    protected $fillable = [
        'customer_id',
        'status',
        'branch_id',
        'recorded',
        'notes',
        'description',
        'full_quantity',
        'total',
        'active',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }



    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'orders_details', 'order_id', 'product_id')
    //         ->with(['products' => function ($query) {
    //             $query->join('orders_details', 'products.id', '=', 'orders_details.product_id')
    //                 ->where('orders_details.order_id', $this->id);
    //         }])
    //         ->distinct();
    // }


}
