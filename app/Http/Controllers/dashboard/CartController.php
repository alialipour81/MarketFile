<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Cart\UpdateRequestCart;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
            $cart_orders2 = Cart::orderBy('id', 'desc')->get();
            $cart_orders = Cart::searched()->where(function ($cart_orders) use ($cart_orders2){
                    foreach ($cart_orders2 as $item) {
                        if (auth()->user()->id == $item->user_id || auth()->user()->role == 'admin' || $item->product->store->user_id == auth()->user()->id) {
                            $cart_orders->orWhere('id', $item->id);
                        }else{
                            $cart_orders->orWhere('id','jsgsg66');
                        }
                    }
            })->orderBy('id', 'desc')->Paginate(30);
        return view('dashboard.cart&order.index')
            ->with('cart_orders',$cart_orders);
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
    public function edit($id)
    {
        if (auth()->user()->role == 'user'){
         return back();
        }
        $cart = Cart::where('id',$id)->first();
        $products = Product::where('price','!=',0)->orderBy('id','desc')->get();
        $users = User::orderBy('id','desc')->get();
        $statuses = [
            '0'=>'پرداخت ناموفق ',
            '1'=>'پرداخت موفق',
        ];
        return view('dashboard.cart&order.edit')
            ->with('cart_order',$cart)
            ->with('products',$products)
            ->with('users',$users)
            ->with('statuses',$statuses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestCart $request, $id)
    {
        $cart = Cart::where('id',$id)->first();
        $cart->update([
           'product_id'=>$request->product_id,
           'user_id'=>$request->user_id,
            'code_order'=>$request->code_order,
            'price_final'=>$request->price_final,
            'status'=>$request->status
        ]);
        if (!empty($request->discount)){
            $cart->update([
               'discount'=>$request->discount
            ]);
        }else{
            $cart->update([
                'discount'=>null
            ]);
        }
        session()->flash('success_dashboard','ویرایش خرید با موفقیت انجام شد');
        return redirect(route('cart_orders.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::where('id',$id)->first();
        $cart->delete();
        session()->flash('success_dashboard','حذف خرید با موفقیت انجام شد');
        return redirect(route('cart_orders.index'));
    }
}
