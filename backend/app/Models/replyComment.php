<?php

namespace App\Models;

use App\Models\CommentProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class replyComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'comment_id',
        'text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(CommentProduct::class);
    }
}
