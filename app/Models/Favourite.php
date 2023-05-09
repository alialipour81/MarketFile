<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;
    protected $fillable=['product_id','user_id'];


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
            $info = Product::where('title','LIKE',"%{$search}%")->where('status',1)->get();
            if ($info == null){
                return $query;
            }else{
                return $query->where(function ($query) use ($info){
                    foreach ($info as $value) {
                        $query->orWhere('product_id',$value->id);
                    }
                });
            }

        }
    }

}
