<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartMessages extends Model
{
    use HasFactory;
   
    protected $fillable = ['user_id', 'chat_room_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chatRoom()
    {
        return $this->belongsTo(ChartRoom::class);
    }
}
