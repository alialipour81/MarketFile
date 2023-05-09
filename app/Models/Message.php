<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable=[
      'user_id','title','description','child'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Message::class,'child')->orderBy('id','desc');
    }

    public function parent()
    {
        return $this->hasOne(Message::class,'id','child');
    }

    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if (!$search){
            return $query;
        }else{
            $users = User::where('email','LIKE',"%{$search}%")->get();
            $messages = Message::where('title','LIKE',"%{$search}%")->where('child',0)->orderBy('id','desc')->get();
            $query->where(function ($query) use ($users,$messages){
               foreach ($users as $user){
                   $query->orWhere('user_id',$user->id)->where('child',0);
               }
               if (auth()->user()->role == 'admin'){
                   foreach ($messages as $message){
                       $query->orWhere('id',$message->id);
                   }
               }
            });
        }
    }
}

