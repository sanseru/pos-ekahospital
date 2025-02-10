<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'total_amount',
        'payment_method',
        'payment_status',
        'customer_name'
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
