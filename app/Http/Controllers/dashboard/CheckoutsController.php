<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Checkout\CreateRequestCheckout;
use App\Http\Requests\dashboard\Checkout\UpdateRequestCheckout;
use App\Models\Cart;
use App\Models\Checkout;
use App\Rules\cardnumber_checkout;
use App\Rules\price_checkout;
use Illuminate\Http\Request;

class CheckoutsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my_daramad = self::myDaramad(auth()->user()->id);
        $price = self::myCheckout(auth()->user()->id);

        if (auth()->user()->role == 'admin') {
            $checkouts = Checkout::searched()->orderBy('id', 'desc')->Paginate(30);
        }else{
            $checkouts = Checkout::searched()->where('user_id',auth()->user()->id)->orderBy('id', 'desc')->Paginate(30);
        }

        $status = [
          '0'=>' در انتظار واریز ',
          '1'=>'رد شده',
          '2'=>'واریز شده',
        ];

        return view('dashboard.checkouts.index')
            ->with('myDaramad',number_format(array_sum($my_daramad) - array_sum($price)))
            ->with('myDaramadWihoutformat',array_sum($my_daramad) - array_sum($price))
            ->with('checkouts',$checkouts)
            ->with('status',$status);
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
    public function store(CreateRequestCheckout $request)
    {
        Checkout::create([
           'user_id'=>auth()->user()->id,
           'name_cardnumber'=>$request->name,
           'cardnumber'=>$request->cardnumber,
           'price'=>$request->price
        ]);
        session()->flash('success_dashboard','درخواست تسویه برای شما با موفقیت ثبت شد');
        return redirect(route('checkouts.index'));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestCheckout $request, Checkout $checkout)
    {
       $my_daramad = self::myDaramad($checkout->user_id);
        $price = self::myCheckout($checkout->user_id);
        $baghimandeh = array_sum($my_daramad) - array_sum($price);
        if ($request->price > ($checkout->price + $baghimandeh)  || $checkout->status == 1 && $request->price >  $baghimandeh){ // 4000 > 53,000-3000
            session()->flash('error_dashboard','قیمت درخواستی شما بیش از موجودی شماست  ');
           return back()->withInput();
        }
        if (!empty($request->variz)){
            $this->validate($request,[
               'variz'=>['date_format:Y-m-d H:i:s']
            ]);
            $checkout->update([
               'variz'=>$request->variz
            ]);
        }elseif(auth()->user()->role == 'admin'){
            $checkout->update([
                'variz'=>null
            ]);
        }
        if ($request->has('status')){
            $this->validate($request,[
               'status'=>['required','numeric']
            ]);
            $checkout->update([
               'status'=>$request->status
            ]);
        }
        $checkout->update([
            'name_cardnumber'=>$request->name,
            'cardnumber'=>$request->cardnumber,
            'price'=>$request->price,
        ]);
        if ($request->has('tracking_code')){
            $checkout->update([
               'tracking_code'=>$request->tracking_code
            ]);
        }
        session()->flash('success_dashboard','درخواست تسویه با موفقیت ویرایش شد');
        return redirect(route('checkouts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout)
    {
        $checkout->delete();
        session()->flash('success_dashboard','درخواست تسویه با موفقیت حذف شد');
        return redirect(route('checkouts.index'));
    }
   public static function myDaramad($user_id)
    {
        $my_daramad = [];
        $cart_orders = Cart::where('status',1)->orderBy('id', 'desc')->get();
        foreach ($cart_orders as $cart_order) {
            if ($cart_order->product->store->user_id == $user_id) {
                if ($cart_order->checkout == null) {
                    array_push($my_daramad, $cart_order->price_final);
                }
            }
        }
        return $my_daramad;
    }
   public static function myCheckout($user_id)
    {
        $price = [];
        $checkouts1 = Checkout::where('user_id',$user_id)->where(function ($checkouts1){
            $checkouts1->orWhere('status',0);
            $checkouts1->orWhere('status',2);
        })->orderBy('id', 'desc')->get();
        foreach ($checkouts1 as $item){
            array_push($price,$item->price);
        }
        return $price;
    }

}
