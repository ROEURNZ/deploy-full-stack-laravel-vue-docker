<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanPay extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title', 'price', 'description', 'recommended'
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
