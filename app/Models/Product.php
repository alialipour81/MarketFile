<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
      'category_id','store_id','user_id','type','title','slug','price',
      'new_price','image1','image2','image3','image4','attrbutes','description','status','file','CountDownload'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('status',1)->where('child',null)->orderBy('id','desc');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favourite()
    {
        return $this->hasOne(Favourite::class);
    }

    public function amazing()
    {
        return $this->hasOne(Amazing::class);
    }
    public function instantoffer()
    {
        return $this->hasOne(Instantoffer::class);
    }

    public function collapses()
    {
        return $this->hasMany(Collapse::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function existsTag($tag)
    {
       if (in_array($tag,$this->tags->pluck('id')->toArray())){
           echo 'selected';
       }
    }
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }else{
            return $query->where('title','LIKE',"%{$search}%");
        }
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function parcentDiscount ($price,$newprice)
    {
        return floor(round((($price-$newprice) * 100) /$price,PHP_ROUND_HALF_DOWN));
    }

    public function existsproinfavourite($pro_id)
    {
        $checkexistspro = Favourite::where('product_id',$pro_id)->where('user_id',auth()->user()->id)->first();
        if ($checkexistspro == null){
            return false;
        }else{
            return true;
        }
    }
    public function existsproincart($pro_id,$status1=false,$status2=0,$user_id=null)
    {
        if (Auth::check()) {
            if ($user_id == null) {
                $user_id = auth()->user()->id;
            }
            $checkexistspro = Cart::where('product_id', $pro_id)->where('user_id', $user_id)->where('status', $status2)->first();
            if ($checkexistspro == null) {
                return false;
            } else {
                if ($status1 == true && $checkexistspro->status == 1) {
                    return true;
                } elseif ($status1 == true && $checkexistspro->status == 0) {
                    return false;
                }
                return true;
            }
        }
    }

    public function existsproincart2($pro_id,$user_id,$status=1)
    {
        $checkexistspro = Cart::where('product_id', $pro_id)->where('user_id', $user_id)->where('status', $status)->first();
        if ($checkexistspro == null) {
            return false;
        }else{
            return true;
        }
    }

    public function AverageStarcompro()
    {
        $stars = [];
        foreach ($this->comments as $comment){
            array_push($stars,$comment->star);
        }
        if (!empty($stars))
        return array_sum($stars) / count($stars);
        else
            return 0;

    }

    public function carts()
    {
        return $this->hasMany(Cart::class)->where('status',1);
    }



}
