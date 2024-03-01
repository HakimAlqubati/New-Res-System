<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'description',
        'active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function toArray()
    {
        return [
            'category_id' => $this->id,
            'category_name' => $this->name,
            'products' => $this->products,
        ];
    }

    //new code
    public function scopeActive($query)
    {
        return $query->where('active', '=', 1);
    }
}
