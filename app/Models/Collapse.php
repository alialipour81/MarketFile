<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collapse extends Model
{
    use HasFactory;
    protected $fillable = [
      'product_id','user_id','type','title','content','status','slug'
    ];
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
