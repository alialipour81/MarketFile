<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Amazing;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\Comment;
use App\Models\Discount;
use App\Models\Instantoffer;
use App\Models\Poster;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Store;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\dashboard\CheckoutsController;

class IndexController extends Controller
{
    public function index()
    {
        $StoresAcc = Store::where('user_id',auth()->user()->id)->where('status',2)->get();
        $users = User::all();
        $users_deactive = User::where('email_verified_at',null)->get();
        $stores = Store::where('status',2)->get();
        $stores_cancel = Store::where('status',1)->get();
        $stores_barasi = Store::where('status',0)->get();
        $products = Product::where('status',1)->get();
        $products_deactive = Product::where('status',0)->get();
        $comments = Comment::where('child',null)->where('status',1)->get();
        $comments_deactive = Comment::where('child',null)->where('status',0)->get();
        $replies = Comment::where('child','!=',null)->where('status',1)->get();
        $replies_deactive = Comment::where('child','!=',null)->where('status',0)->get();
        $orders = Cart::where('status',1)->get();
        $orders_deactive = Cart::where('status',0)->get();
        $slider = Slider::where('status',1)->get();
        $slider_de = Slider::where('status',0)->get();
        $posters = Poster::where('status',1)->get();
        $posters_de = Poster::where('status',0)->get();
        $instantoffers = Instantoffer::where('status',1)->get();
        $instantoffers_de = Instantoffer::where('status',0)->get();
        $amazings = Amazing::where('status',1)->get();
        $amazings_de = Amazing::where('status',0)->get();
        $discounts = Discount::where('status',1)->get();
        $discounts_de = Discount::where('status',0)->get();
        $tags = Tag::all();
        $categories = Category::all();
        $kharid = [];
        $foroosh = [];
        $All_daramad = [];
        $my_daramad = [];
        $cart_orders = Cart::where('status',1)->orderBy('id', 'desc')->get();
        foreach ($cart_orders as $cart_order){
            array_push($All_daramad,$cart_order->price_final);
            if ($cart_order->user_id == auth()->user()->id){
                array_push($kharid,$cart_order->price_final);
            }
            if ($cart_order->product->store->user_id == auth()->user()->id){
                array_push($foroosh,$cart_order->price_final);
                if ($cart_order->checkout == null ){
                    array_push($my_daramad,$cart_order->price_final);
                }
            }
        }

        $price = CheckoutsController::myCheckout(auth()->user()->id);




        return view('dashboard.index')
            ->with('StoresAcc',$StoresAcc)
            ->with('users',$users)
            ->with('stores',$stores)
            ->with('users_deactive',$users_deactive)
            ->with('stores_cancel',$stores_cancel)
            ->with('stores_barasi',$stores_barasi)
            ->with('products',$products)
            ->with('products_deactive',$products_deactive)
            ->with('comments',$comments)
            ->with('comments_deactive',$comments_deactive)
            ->with('replies',$replies)
            ->with('replies_deactive',$replies_deactive)
            ->with('orders',$orders)
            ->with('orders_deactive',$orders_deactive)
            ->with('slider',$slider)
            ->with('slider_de',$slider_de)
            ->with('posters',$posters)
            ->with('posters_de',$posters_de)
            ->with('instantoffers',$instantoffers)
            ->with('instantoffers_de',$instantoffers_de)
            ->with('amazings',$amazings)
            ->with('amazings_de',$amazings_de)
            ->with('discounts',$discounts)
            ->with('discounts_de',$discounts_de)
            ->with('tags',$tags)
            ->with('categories',$categories)
            ->with('kharid',@$kharid)
            ->with('foroosh',@$foroosh)
            ->with('All_daramad',@$All_daramad)
            ->with('my_daramad',@$my_daramad)
            ->with('checkouts',@$price)
           ;
    }

}

