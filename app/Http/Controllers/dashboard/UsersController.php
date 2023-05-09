<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Hekmatinasser\Verta\Verta;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin')->except(['update','deleteimageprofile']);
    }
    public function index()
    {
        $users = User::searched()->orderBy('id','desc')->Paginate(50);

        return view('dashboard.users.index')
            ->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = [
          'user'=>'کاربر',
          'admin'=>'ادمین',
        ];
        return view('dashboard.users.edit')
            ->with('user',$user)
            ->with('roles',$roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->has('usereditwithprofile')){
            $this->validate($request,[
               'name_user'=>['required','string'] ,
            ]);

            if ($request->hasFile('image_user')){
                $this->validate($request,[
                    'image_user'=>['required','image','mimes:png,jpeg,jpg','max:1000'] ,
                ]);
                if ($user->image != null){
                    Storage::delete($user->image);
                }
                $user->image = $request->image_user->store('image_user');
                $user->save();
            }
           $user->update([
              'name'=>$request->name_user,
           ]);
            session()->flash('success_dashboard','پروفایل شما با موفقیت ویرایش شد');
            return back();
        }else{
            $this->validate($request,[
                'name'=>['required','string'] ,
                'email'=>['required'] ,
                'email_verified_at'=>['required','string'],
                'role'=>['required','string'],
            ]);
            if ($user->email != $request->email){
                $this->validate($request,[
                   'email'=>['unique:users,email']
                ]);
            }
            if (!empty($request->new_password)){
                $this->validate($request,[
                    'new_password'=>['string','min:8']
                ]);
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
            if ($request->hasFile('image')){
                $this->validate($request,[
                    'image'=>['required','image','mimes:png,jpeg,jpg','max:1000'] ,
                ]);
                if ($user->image != null){
                    Storage::delete($user->image);
                }
                $user->image = $request->image->store('image_user');
                $user->save();
            }
            if ($request->email_verified_at == 'now'){
                $user->email_verified_at = now();
                $user->save();
            }elseif ($request->email_verified_at == 'unconfirmed'){
                $user->email_verified_at = null;
                $user->save();
            }
            $user->update([
               'name'=>$request->name,
               'email'=>$request->email,
               'role'=>$request->role
            ]);
            session()->flash('success_dashboard',' کاربر با ایمیل '.$user->email.' با موفقیت ویرایش شد ');
            return redirect(route('users.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        session()->flash('success_dashboard',' کاربر با ایمیل '.$user->email.' با موفقیت حذف شد ');
        $user->delete();
        return redirect(route('users.index'));
    }

    public function deleteimageprofile($email)
    {
        $user = User::where('email',$email)->first();
        if ($user == null){return back();}
        Storage::delete($user->image);
        $user->image = null;
        $user->save();
        session()->flash('success_dashboard','عکس پروفایل با موفقیت حذف شد');
        return back();
    }
}
