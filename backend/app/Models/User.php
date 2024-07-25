<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Plans;
use App\Models\Category;
use App\Models\CommentProduct;
use App\Models\ReplyComment;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Stripe\Plan;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'post_count',
        'has_paid',
        'next_charge_date',
        'plan_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'post_count' => 0,
        'has_paid' => 0,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define relationship with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Define relationship with Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define relationship with CommentProduct.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(CommentProduct::class);
    }

    /**
     * Define relationship with ReplyComment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(ReplyComment::class);
    }

    /**
     * Define relationship with Store.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Define relationship with PlanPay.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planPay()
    {
        return $this->belongsTo(PlanPay::class);
    }

    /**
     * Define relationship with Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function UserPayment()
    {
        return $this->hasMany(UserPayment::class);
    }
}
