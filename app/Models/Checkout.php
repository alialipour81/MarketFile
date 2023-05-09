<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $fillable=[
      'user_id','name_cardnumber','cardnumber','price','status','tracking_code','variz'
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
            $users  = User::where('email','LIKE',"%{$search}%")->get();
            $query->where(function ($query) use ($search,$users){
                $query->orWhere('tracking_code','LIKE',"%{$search}%");
                $query->orWhere('variz','LIKE',"%{$search}%");
                $query->orWhere('name_cardnumber','LIKE',"%{$search}%");
                $query->orWhere('cardnumber','LIKE',"%{$search}%");
                $query->orWhere('price','LIKE',"%{$search}%");
                if (auth()->user()->role == 'admin'){
                    foreach ($users as $user){
                        $query->orWhere('user_id',$user->id);
                    }
                }
            })->get();
        }
    }
}
