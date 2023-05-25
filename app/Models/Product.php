<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'description',
        'active',
        'category_id'
    ];

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_prices')
            ->withPivot('price');
    }

    public function unitPrices()
    {
        return $this->hasMany(UnitPrice::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }


    public function toArray()
    {
        return [
            'product_id' => $this->id,
            'product_name' => $this->name, 
            'description' => $this->description, 
            'unit_prices' => $this->unitPrices, 
        ];
    }
}
