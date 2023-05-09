<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Discount\CreateRequestDiscount;
use App\Http\Requests\dashboard\Discount\UpdateRequestDiscount;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index()
    {
        $discounts = Discount::searched()->orderBy('id','desc')->Paginate(16);
        return view('dashboard.discounts.index')
            ->with('discounts',$discounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('email_verified_at','!=',null)->orderBy('id','desc')->get();
        return view('dashboard.discounts.add&edit')
            ->with('users',$users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestDiscount $request)
    {

        if (isset($request->price )){
            $this->validate($request,[
               'price'=>['integer','min:1000']
            ]);
        }
        if ($request->has('count_use')){
            $this->validate($request,[
                'count_use'=>['integer','min:1']
            ]);
        }
       $discount = Discount::create([
           'name'=>$request->name,
            'parcent_kasr'=>$request->parcent_kasr,
            'access'=>$request->access,
            'dateTime'=>$request->dateTime,
            'description'=>$request->description
        ]);
        if ($request->has('count_use')  ){
            $discount->count = $request->count_use;
            $discount->save();
        }else{
            $discount->count = 1;
            $discount->save();
        }
        if (isset($request->price)){
            $discount->price = $request->price;
            $discount->save();
        }

        session()->flash('success_dashboard','تخفیف با موفقیت ایجاد شد');
        return redirect(route('discounts.index'));
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
    public function edit(Discount $discount)
    {
        $users = User::where('email_verified_at','!=',null)->orderBy('id','desc')->get();
        $statuses = [
          '0'=>'عدم نمایش',
          '1'=>'نمایش',
        ];
        return view('dashboard.discounts.add&edit')
            ->with('users',$users)
            ->with('discount',$discount)
            ->with('statuses',$statuses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestDiscount $request, Discount $discount)
    {
        if ($request->name != $discount->name) {
            $CheckExistsName = Discount::where('name', $request->name)->first();
            if ($CheckExistsName != null) {
                $this->validate($request,[
                   'name'=>['unique:discounts,name']
                ]);
            }
        }
        if (!empty($request->price)){
            $this->validate($request,[
                'price'=>['integer','min:1000']
            ]);
        }
        $discount->update([
            'name'=>$request->name,
            'parcent_kasr'=>$request->parcent_kasr,
            'access'=>$request->access,
            'dateTime'=>$request->dateTime,
            'count'=>$request->count_use,
            'description'=>$request->description,
            'status'=>$request->status
        ]);
        if (!empty($request->price)){
            $discount->price = $request->price;
            $discount->save();
        }else{
            $discount->price = null;
            $discount->save();
        }

        session()->flash('success_dashboard',' ویرایش برای کد  '.$request->name.' با موفقیت انجام شد ');
        return redirect(route('discounts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $cart = Cart::where('discount',$discount->name)->get();
        if (!empty($cart->toArray())) {
            foreach ($cart as $item) {
                $item->update([
                    'discount' => $discount->parcent_kasr
                ]);
            }
        }
        session()->flash('success_dashboard',' حذف  کدتخفیف  '.$discount->name.' با موفقیت انجام شد ');
        $discount->delete();
        return redirect(route('discounts.index'));
    }
}
