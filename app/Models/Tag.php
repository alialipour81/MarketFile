<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable=['name'];
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }else{
            return $query->where('name','LIKE',"%{$search}%");
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
