<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'order_id',
        'payment_method',
        'account_number',
        'amount',
        'payment_date',
    ];
    public function orders(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
