<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'manager_id',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
