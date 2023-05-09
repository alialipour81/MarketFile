<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','nameEn','parent_id'];

    public function parents()
    {
        return $this->hasMany(Category::class,'parent_id')->orderBy('id','desc');
    }

    public function InfoWithParent()
    {
        return $this->hasOne(Category::class,'id','parent_id');
    }
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }else{
            return $query->where('name','LIKE',"%{$search}%");
        }
    }
}
