<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\google\CreateRequestGoogle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function next()
    {
       return Socialite::driver('google')->redirect();
    }

    public function handel()
    {
        $user = Socialite::driver('google')->user();
        $userIsExists = User::where('email',$user->getEmail())->first();
        if ($userIsExists != null){
            Auth::loginUsingId($userIsExists->id);
            session()->flash('success','ورود شما موفقیت آمیز بود');
            return redirect(route('index'));
        }else{
            session()->put('NameWithgoogle',$user->getName());
            session()->put('EmailWithgoogle',$user->getEmail());
            return redirect(route('register'));
        }


    }

    public function register(CreateRequestGoogle $request)
    {
       $user = User::create([
            'name'=>$request->name,
            'email'=>session()->get('EmailWithgoogle'),
            'email_verified_at'=>now(),
           'password'=>Hash::make($request->password)
        ]);
       session()->forget(['NameWithgoogle','EmailWithgoogle']);
        Auth::loginUsingId($user->id);
        session()->flash('success','ثبت نام شما با موفقیت انجام شد');
        return redirect(route('index'));
    }
}
