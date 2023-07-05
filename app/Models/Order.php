<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const ORDERED = 'ordered';
    public const PROCESSING = 'processing';
    public const READY_FOR_DELEVIRY = 'ready_for_delivery';
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
        'updated_by',
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

    public function scopeReadyForDelivery($query)
    {
        return $query->where('status', self::READY_FOR_DELEVIRY);
    }

    public function scopeInTransfer($query)
    {
        return $query->select('orders.*')
            ->join('orders_details', 'orders_details.order_id', '=', 'orders.id')
            ->where('orders_details.available_in_store', 1)->distinct();
    }

   
    public function customer_name(){
        return 'dddd';
    }
}
