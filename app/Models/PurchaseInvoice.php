<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'supplier_id',
        'description',
        'invoice_no',
        'store_id',
    ];

    public function purchaseInvoiceDetails()
    {
        return $this->hasMany(PurchaseInvoiceDetail::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
