<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable =[
        'product_id','user_id','status','discount','code_order','price_final'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
     return $this->belongsTo(User::class);
    }
    static public function existsdiscount($discounname)
    {
        $checkexistsdiscountincart = Cart::where('user_id',auth()->user()->id)->where('discount',$discounname)->first();
        if ($checkexistsdiscountincart == null){
            return false;
        }else{
            return true;
        }
    }
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }else{
            $pro = Product::where('title','LIKE',"%{$search}%")->where('status',1)->orderBy('id', 'desc')->get();
            $cart_orders = Cart::where('status', 1)->where('code_order',$search)->orderBy('id', 'desc')->get();
            $discount = Cart::where('discount','LIKE',"%{$search}%")->where('status',1)->orderBy('id','desc')->get();
            $user = User::where('email','LIKE',"%{$search}%")->orderBy('id','desc')->get();
            if ($pro == null && $cart_orders == null && $discount == null && $user == null){
                return $query;
            }else{
                return $query->where(function ($query) use ($pro,$cart_orders,$discount,$user){
                    foreach ($pro as $item){
                        $query->orWhere('product_id',$item->id);
                    }
                    foreach ($cart_orders as $item2){
                        $query->orWhere('code_order',$item2->code_order);
                    }
                    foreach ($discount as $item3){
                        $query->orWhere('discount',$item3->discount);
                    }
                        foreach ($user as $item4){
                            $query->orWhere('user_id',$item4->id);
                        }


                });
            }

        }
    }

    public function discountt()
    {
        return $this->belongsTo(Discount::class,'discount','name');
    }
}
