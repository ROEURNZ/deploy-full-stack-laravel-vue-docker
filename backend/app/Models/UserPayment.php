<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'payment_intent_id',
        'amount',
        'currency',
        'payment_method'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
