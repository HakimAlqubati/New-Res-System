<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $table = 'orders_details';
    protected $fillable = [
        'order_id',
        'product_id',
        'unit_id',
        'quantity',
        'available_quantity',
        'price',
        'available_in_store',
        'updated_at',
        'created_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
