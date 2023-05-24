<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPrice extends Model
{
    use HasFactory;
    protected $table = 'unit_prices';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function toArray()
    {
        return [
            'unit_id' => $this->unit_id,
            'unit_name' => $this->unit->name,
            'price' => $this->price,
        ];
    }

}
