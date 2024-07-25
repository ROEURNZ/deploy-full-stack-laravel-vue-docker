<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'order_date',
    ];
    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('qty');
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    public static function list()
    {
        return self::all();
    }
    public static function store($request, $id = null)
    {
        $data = $request->only('user_id', 'order_date');
        $order = self::updateOrCreate(['id' => $id], $data);

        if ($request->has('products')) {
            $products = collect($request->products)->mapWithKeys(function ($product) {
                $productModel = Product::find($product['product_id']);
                $totalPrice = $productModel->price * $product['qty'];
                return [$product['product_id'] => ['qty' => $product['qty'], 'totalPrice' => $totalPrice]];
            });
            $order->products()->sync($products);
        }

        return $order;
    }
    public static function destroy($id)
    {
        return self::find($id)->delete();
    }
}
