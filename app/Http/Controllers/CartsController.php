<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CreateRequestCart;
use App\Http\Requests\Discount\UpdateRequestDiscount;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Hekmatinasser\Verta\Verta;
class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $categories = Category::where('parent_id',0)->get();
        $cart = Cart::where('user_id',auth()->user()->id)->where('status',0)->orderBy('id','desc')->get();
        $price = [];
        foreach ($cart as $value){
            $pro = Product::where('id',$value->product_id)->first();
            if ($pro->new_price != 0){
                array_push($price,$pro->new_price);
            }else{
                array_push($price,$pro->price);
            }
        }
        session()->put('price_cart',array_sum($price));
        if (session()->has('discount_active')){
            $dis_name = session()->get('discount_active');
            $dis = Discount::where('name',$dis_name)->first();
            $amalyat1 = (array_sum($price) * $dis->parcent_kasr) / 100;
            $amalyat2 = array_sum($price) - $amalyat1;
            $price =[ $amalyat2 ];
            session()->put('price_cart',array_sum($price));
            foreach ($cart as $c){
                $c->update([
                   'discount'=>$dis_name
                ]);
            }

        }
        if($cart->count() == 0){
            if (session()->has('discount_active')){
                session()->forget('discount_active');
            }
        }
        return view('fronts.cart')
            ->with('categories',$categories)
            ->with('cart',$cart)
            ->with('price_final',array_sum($price))
            ->with('discount',@$dis);
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
    public function store(CreateRequestCart $request)
    {
        $product = Product::where('slug',$request->product)->first();
        if ($product != null && $product->existsproincart($product->id) == false) {
           $cart = Cart::create([
                'product_id' => $product->id,
                'user_id' => auth()->user()->id,
            ]);
            session()->flash('success',' محصول  '.$cart->product->title .' با موفقیت به سبد خرید اضافه شد ');
            return redirect(route('cart.index'));
        }else{
            session()->flash('error','این محصول از قبل در سبد خرید وجود دارد');
        }
        return back();
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        session()->flash('success',' محصول  '.$cart->product->title .' با موفقیت از سبد خرید حذف شد ');
        $cart->delete();
        return redirect(route('cart.index'));
    }

    public function validate_discount(UpdateRequestDiscount $request,$text)
    {
        if ($request->has('setdiscount')) {
            $now = \verta()->format('Y-m-d H:i:s');
            $discount = Discount::where('name', $request->code_discount)->first();
            if ($discount != null) {
                if ($discount->dateTime >= $now) {
                    if ($discount->status == 1) {
                        if ($discount->access == 0 || $discount->access == auth()->user()->id) {
                            if ($discount->count != 0) {
                                if ($discount->price != null) {
                                    $price_cart = session()->get('price_cart');
                                    if ($price_cart < $discount->price) {
                                        session()->flash('error', ' برای استفاده از این کد تخفیف حداقل مبلغ خرید شما باید  ' . number_format($discount->price) . ' تومان باشد ');
                                        return back();
                                    }
                                }
                                if (Cart::existsdiscount($discount->name) == false) {
                                    session()->put('discount_active', $discount->name);
                                    $discount->update([
                                        'count' => $discount->count - 1
                                    ]);
                                    session()->flash('success', '   تخفیف با موفقیت اعمال شد');
                                    return redirect(route('cart.index'));
                                } else {
                                    session()->flash('error', 'کد تخفیف را قبلا استفاده کرده اید');
                                }

                            } else {
                                if ($discount->access != 0) {
                                    session()->flash('error', 'کد تخفیف را قبلا استفاده کرده اید');
                                } else {
                                    session()->flash('error', 'ظرفیت استفاده از کد تخفیف تکمیل شده است');
                                }
                            }
                        } else {
                            session()->flash('error', 'کد تخفیف نامعتبر است');
                        }
                    } else {
                        session()->flash('error', 'کد تخفیف غیر فعال شده است');
                    }
                }else{
                    session()->flash('error', 'کد تخفیف منقضی شده است');
                }
            } else {
                session()->flash('error', 'کد تخفیف نامعتبر است');
            }
        }else{
//            $cart = Cart::where('user_id',auth()->user()->id)->where('discount',session()->get('discount_active'))->where('status',0)->get();
//            if (!empty($cart->toArray())) {
//                foreach ($cart as $item) {
//                    $item->update([
//                        'discount' => null
//                    ]);
//                }
//            }
            session()->forget('discount_active');
            session()->flash('success', ' تخفیف لغو شد');
        }
        return back();
    }
}
