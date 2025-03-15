<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'customer_name',
        'amount',
        'cashier',
        'payment_method',
        'status',
        'uri_payment'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier');
    }
}
