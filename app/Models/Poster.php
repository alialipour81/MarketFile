<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    use HasFactory;
    protected $fillable = [
      'user_id','title','image','link','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }else{
            $searched = Poster::where('title','LIKE',"%{$search}%")->orWhere('link','LIKE',"%{$search}%")->orderBy('id','desc')->get();
            return $query->where(function ($query) use ($searched){
                foreach ($searched as $value){
                    $query->orWhere('id',$value->id);
                }
            });
        }
    }
}
