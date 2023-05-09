<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Store\CreateRequestStore;
use App\Http\Requests\dashboard\Store\UpdateRequestStore;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role == 'admin'){
            $stores = Store::searched()->orderBy('id','desc')->Paginate(15);
        }else{
            $stores = Store::where('user_id',auth()->user()->id)->orderBy('id','desc')->Paginate(15);
        }
        return view('dashboard.stores.index')
            ->with('stores',$stores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.stores.add&edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestStore $request)
    {
        Store::create([
            'user_id'=>auth()->user()->id,
           'name'=>$request->name,
            'image'=>$request->image->store('stores'),
            'description'=>$request->description
        ]);
        session()->flash('success_dashboard','فروشگاه شما با موفقیت ایجاد شد ومنتظر تایید از سوی مدیریت باشید');
        return redirect(route('stores.index'));
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
    public function edit(Store $store)
    {
        if ($store->user_id != auth()->user()->id && auth()->user()->role != 'admin'){
            session()->flash('error_dashboard','شما قادر به ویرایش فروشگاه هایی هستید که توسط شما ساخته شده است');
            return redirect(route('stores.index'));
        }
        $statuses =[
          '0' => 'در حال بررسی',
          '1' => 'رد شده',
          '2' => 'پذیرفته شده',
        ];
        $bluetick = [
          'yes'=> 'فعال ',
          'no'=> 'غیرفعال',
        ];
        return view('dashboard.stores.add&edit')
            ->with('store',$store)
            ->with('statuses',$statuses)
            ->with('bluetick',$bluetick);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestStore $request, Store $store)
    {
        if ($request->name != $store->name){
            $CheckWExistsStore = Store::where('name',$request->name)->first();
            if($CheckWExistsStore != null){
                $this->validate($request,['name'=>'unique:stores,name']);
            }
        }
        if ($request->hasFile('image')){
            $this->validate($request,[
               'image'=>['image','mimes:png,jpeg,jpg','max:1000']
            ]);
            Storage::delete($store->image);
            $store->image = $request->image->store('stores');
            $store->save();
        }
        $store->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'status'=>$request->status,
            'bluetick'=>$request->bluetick
        ]);
        if (auth()->user()->role == 'admin'){
            session()->flash('success_dashboard',' فروشگاه  با نام  '.$store->name. ' با موفقیت ویرایش شد ');
        }else{
            session()->flash('success_dashboard',' فروشگاه شما با نام  '.$store->name. ' با موفقیت ویرایش شد ');
        }
        return redirect(route('stores.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        Storage::delete($store->image);
        session()->flash('success_dashboard',' فروشگاه  با نام  '.$store->name. ' با موفقیت حذف شد ');
        $store->delete();
        return redirect(route('stores.index'));


    }
}
