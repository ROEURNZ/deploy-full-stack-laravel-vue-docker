<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartRoom extends Model
{
    use HasFactory;
  
    protected $fillable = ['user1_id', 'user2_id'];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function messages()
    {
        return $this->hasMany(ChartMessages::class);
    }

        // Define a relationship to get the latest message
    public function latestMessage()
    {
        return $this->hasOne(ChartMessages::class)->latest();
    }
}
