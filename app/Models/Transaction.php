<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'users_id',
        'shipping_price',
        'total_price',
        'address_id',
        'payment_approval',
        'last_edited_id',
        'transaction_status',
    ];

            
}
