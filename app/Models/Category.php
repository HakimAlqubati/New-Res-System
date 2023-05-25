<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
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
}
