<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransfer extends Model
{
    use HasFactory;


    protected $table = 'orders';
    // protected $primaryKey = '_id';
    public const ORDERED = 'ordered';
    public const PROCESSING = 'processing';
    public const READY_FOR_DELEVIRY = 'ready_for_delivery';
    public const DELEVIRED = 'delevired';
    public const PENDING_APPROVAL = 'pending_approval';
    protected $fillable = [
        'id',
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
        return $this->hasMany(OrderDetails::class)
            ->where('quantity', '>', 0)
            ->where('available_quantity', '>', 0);
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
    public function orders()
    {
        return $this->belongsTo(Order::class, 'id');
    }
    public function scopeInTransfer($query)
    {
        return $query->select('orders.*')
            ->join('orders_details', 'orders_details.order_id', '=', 'orders.id')
            ->whereIn('orders.status', [
                Order::READY_FOR_DELEVIRY,
                Order::DELEVIRED
            ])
            ->where('orders_details.available_quantity', '>', 1)
            ->where('orders_details.quantity', '>', 1)
            ->distinct();
    }
}
