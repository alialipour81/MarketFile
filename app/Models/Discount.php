<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable =[
      'name','price','parcent_kasr','access','dateTime','status','count','description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'access');
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
