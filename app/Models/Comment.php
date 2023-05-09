<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=[
      'user_id','product_id','comment','star','child','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class,'child','id')->where('status',1)->orderBy('id','desc');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }else{
            $users = User::where('email','LIKE',"%{$search}%")->orderBy('id','desc')->get();
            $products = Product::where('title','LIKE',"%{$search}%")->orderBy('id','desc')->get();
            return $query->where(function ($query) use ($users,$products,$search){
                foreach ($users as $user){
                    $query->orWhere('user_id',$user->id);
                }
                foreach ($products as $product){
                    $query->orWhere('product_id',$product->id);
                }
                $query->orWhere('comment','LIKE',"%{$search}%")->orderBy('id','desc');
            });
        }
    }

}
