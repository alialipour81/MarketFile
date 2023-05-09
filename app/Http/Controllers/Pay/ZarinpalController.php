<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Discount;
use App\Pay\zarinpal;
use Illuminate\Http\Request;

class ZarinpalController extends Controller
{
    public function request( Request $request)
    {
        $MerchantID 	= "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";
        $Amount 		= session()->get('price_cart');
        $Description 	= "خرید محصول از فروشگاه اینترنتی کمیاب فایل";
        $Email 			= auth()->user()->email;
        $Mobile 		= "";
        $CallbackURL 	= route('zarinpal.callback');
        $ZarinGate 		= false;
        $SandBox 		= true;

        $zp 	= new zarinpal();
        $result = $zp->request($MerchantID, $Amount, $Description, $Email, $Mobile, $CallbackURL, $SandBox, $ZarinGate);

        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            // Success and redirect to pay
            $zp->redirect($result["StartPay"]);
        } else {
            // error
            echo "خطا در ایجاد تراکنش";
        }
    }

    public function callback()
    {
        $MerchantID 	= "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";
        $Amount 		= session()->get('price_cart');
        $ZarinGate 		= false;
        $SandBox 		= true;

        $zp 	= new zarinpal();
        $result = $zp->verify($MerchantID, $Amount, $SandBox, $ZarinGate);

        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            $cart = Cart::where('user_id',auth()->user()->id)->where('status',0)->orderBy('id','desc')->get();
            foreach ($cart as $value){
                if (session()->has('discount_active')){
                    $dis_name = session()->get('discount_active');
                    $dis = Discount::where('name',$dis_name)->first();
                    if ($value->product->new_price != 0) {
                        $price_final = ($value->product->new_price * $dis->parcent_kasr) / 100;
                        $price_final = $value->product->new_price - $price_final;
                    } else {
                        $price_final = ($value->product->price * $dis->parcent_kasr) / 100;
                        $price_final = $value->product->price - $price_final;
                    }
                }else{
                    if ($value->product->new_price != 0) {
                        $price_final = $value->product->new_price;
                    } else {
                        $price_final = $value->product->price;
                    }
                    $value->update([
                        'discount'=>null
                    ]);
                }
                $value->update([
                    'status'=>1,
                    'code_order'=>$result['Authority'],
                    'price_final'=>$price_final
                ]);
            }
            if (session()->has('discount_active')){
                session()->forget('discount_active');
            }
            session()->flash('success','پرداخت موفقیت آمیز بود ,هم اکنون به فایل محصولات  خریداری شده دسترسی دارید ');
        } else {
            // error
            session()->flash('error','پرداخت ناموفق بود لطفا مجددا تلاش کنید');
        }
        return redirect(route('cart.index'));

    }
}
