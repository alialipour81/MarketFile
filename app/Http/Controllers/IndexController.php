<?php

namespace App\Http\Controllers;


use App\Models\Amazing;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Instantoffer;
use App\Models\Poster;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jorenvh\Share\Share;
use ZipArchive;
class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id',0)->get();
        $posters = Poster::where('status',1)->get();
        $sliders = Slider::where('status',1)->get();
        $lastproducts = Product::where('status',1)->orderBy('id','desc')->limit(7)->get();
        $lasttemplates = Product::where('status',1)->where('type','template')->orderBy('id','desc')->limit(7)->get();
        $lastapplications = Product::where('status',1)->where('type','app')->orderBy('id','desc')->limit(7)->get();
        $random = Product::inRandomOrder()->where('status',1)->orderBy('id','desc')->limit(7)->get();
        $instantoffers = Instantoffer::where('status',1)->orderBy('id','desc')->get();
        $amazings = Amazing::where('status',1)->orderBy('id','desc')->get();

        return view('fronts.index')
            ->with('categories',$categories)
            ->with('sliders',$sliders)
            ->with('posters',$posters)
            ->with('lastproducts',$lastproducts)
            ->with('lasttemplates',$lasttemplates)
            ->with('lastapplications',$lastapplications)
            ->with('random',$random)
            ->with('instantoffers',$instantoffers)
            ->with('amazings',$amazings);
    }

    public function product(Product $product)
    {
        if ($product->status == 0){return redirect(route('index'));}
        $categories = Category::where('parent_id',0)->get();
      $share =  \Share::page(route('product',$product->slug), $product->title.'-'.'در فروشگاه کمیاب فایل')
          ->facebook()
          ->twitter()
          ->linkedin()
          ->telegram()
          ->whatsapp()
          ->reddit();

        return view('fronts.product')
            ->with('categories',$categories)
            ->with('product',$product)
            ->with('share',$share);
    }

    public function products($value)
    {
        // filter
        $filter1 = 'id';
        $filter2 = 'desc';
        if (session()->has('profilter1')){
            $filter1 = session()->get('profilter1');
        }
        if (session()->has('profilter2')){
            $filter2 = session()->get('profilter2');
        }
        //
        $valAsli = $value;

        $job = explode('=', $value);
        $value = end($job);

        if ($job[0] == 'store'){
            $store = Store::where('name',$value)->first();
            if ($store == null || $store->status == 0){
                return back();
            }
            $products = Product::searched()->where('store_id',$store->id)->where('status',1)->orderBy($filter1,$filter2)->Paginate(12);
        }elseif ( $job[0] == 'categories'  && session()->has('select_categories_store')) {
            $store = Store::where('name', $value)->first();
            if ($store == null || $store->status == 0){
                return back();
            }
            $cats = session()->get('select_categories_store');
            $products = Product::searched()->where('store_id', $store->id)->where('status', 1)->where(function ($products) use ($cats) {
                foreach ($cats as $key => $cat) {
                    if ($cat->parent_id == 0) {
                        if ($cat->parents()->count()) {
                            foreach ($cat->parents as $parent) {
                                if ($parent->parents()->count() == 0) {
                                    $products->orWhere('category_id', $parent->id);
                                } else {
                                    $products->orWhere('category_id', $parent->id);
                                    foreach ($parent->parents as $parent2) {
                                        $products->orWhere('category_id', $parent2->id);
                                    }
                                }
                            }
                            $products->orWhere('category_id',$cat->id);
                        } else {
                            $products->orWhere('category_id', $cat->id);
                        }
                    } else {
                        if ($cat->parents()->count()) {
                            $products->orWhere('category_id', $cat->id);
                            foreach ($cat->parents as $parent) {
                                $products->orWhere('category_id', $parent->id);
                            }
                        } else {
                            $products->orWhere('category_id', $cat->id);
                        }
                    }
                }
            })->orderBy($filter1, $filter2)->Paginate(12);

        }elseif ($job[0] == 'category'){
            if (empty($job[1])){
                return back();
            }
            $cat = explode('&',$job[1]);
            $end_cat = end($cat);
            if (count($cat) > 1){
                foreach ($cat as $C){
                    $catego = Category::where('nameEn',$C)->first();
                    if ($catego == null){
                        return back();
                    }
                    if ($C != $end_cat){
                        foreach ($catego->parents as $parcat){
                            if ($parcat->nameEn == $end_cat){
                                $category = Category::where('id', $parcat->id)->first();
                                if ($category == null){
                                    return back();
                                }
                                break;
                            }
                        }
                    }

                }
            }else {
                $category = Category::where('nameEn', $end_cat)->first();
                if ($category == null){
                    return back();
                }
            }

                foreach ($cat as $item){
                        $category2 = Category::where('nameEn',$item)->first();
                    if ($category2 == null){
                        return back();
                    }
                        if ($category2->parent_id == 0){
                            if ($category2->parents()->count()){
                                $cats = $category2->parents;
                                $products = Product::searched()->where(function ($products) use ($cats,$category2){
                                    foreach ($cats as $item2){
                                        $products->orWhere('category_id',$item2->id);
                                        if ($item2->parents()->count()){
                                            foreach ($item2->parents as $item3){
                                                $products->orWhere('category_id',$item3->id);
                                            }
                                        }
                                    }
                                    $products->orWhere('category_id',$category2->id);
                                })->where('status',1)->orderBy($filter1,$filter2)->Paginate(12);
                            }else{
                                $products = Product::searched()->where('category_id',$category2->id)->where('status',1)->orderBy($filter1,$filter2)->Paginate(12);
                            }
                        }else{
                                foreach ($cat as $item2){
                                    if ($item2 != $end_cat){
                                        $categ = Category::where('nameEn',$item2)->first();
                                        if ($categ == null){
                                            return back();
                                        }
                                        foreach ($categ->parents as $item3){
                                            if ($item3->parents()->count()){
                                                $cats = $item3->parents;
                                                $products = Product::searched()->where(function ($products) use ($cats,$item3){
                                                    $products->orWhere('category_id',$item3->id);
                                                    foreach ($cats as $item4){
                                                        $products->orWhere('category_id',$item4->id);
                                                    }
                                                })->where('status',1)->orderBy($filter1,$filter2)->Paginate(12);
                                                break;
                                            }else{
                                                if ($item3->nameEn == $end_cat){
                                                    $products = Product::searched()->where('category_id',$item3->id)->where('status',1)->orderBy($filter1,$filter2)->Paginate(12);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                        }

                }

            }elseif ($job[0] == 'tag'){
            $tag = Tag::where('name',$value)->first();
            if ($tag == null){
                return back();
            }
            $pro_tag = $tag->products->toArray();

                $products = Product::searched()->where(function ($products) use ($pro_tag) {
                    if ($pro_tag != null) {
                        foreach ($pro_tag as $pro) {
                            if ($pro['status'] == 1) {
                                if (session()->has('select_categories_store')) {
                                    foreach (session()->get('select_categories_store') as $cat) {
                                        if ($cat->parent_id == 0) {
                                            if ($cat->parents()->count()) {
                                                foreach ($cat->parents as $parent) {
                                                    if ($parent->parents()->count() == 0) {
                                                        $products->orWhere('category_id', $parent->id)->where('id', $pro['id']);
                                                    } else {
                                                        $products->orWhere('category_id', $parent->id)->where('id', $pro['id']);
                                                        foreach ($parent->parents as $parent2) {
                                                            $products->orWhere('category_id', $parent2->id)->where('id', $pro['id']);
                                                        }
                                                    }
                                                }
                                                $products->orWhere('category_id',$cat->id);
                                            } else {
                                                $products->orWhere('category_id', $cat->id)->where('id', $pro['id']);
                                            }
                                        } else {
                                            if ($cat->parents()->count()) {
                                                $products->orWhere('category_id', $cat->id);
                                                foreach ($cat->parents as $parent) {
                                                    $products->orWhere('category_id', $parent->id)->where('id', $pro['id']);
                                                }
                                            } else {

                                                $products->orWhere('category_id', $cat->id)->where('id', $pro['id']);
                                            }
                                        }
                                    }
                                } else {
                                    $products->orWhere('id', $pro['id']);
                                }
                            }
                        }
                    }else{
                        $products->orWhere('title','abcdefddh87kkshy23s6784331990i754gggggggggg9ssssss');
                    }
                })->where('status',1)->orderBy($filter1, $filter2)->Paginate(12);

        }elseif ($job[0] == 'search'){
            $cats = session()->get('select_categories_store');
            $products = Product::where('title','LIKE',"%{$job[1]}%")->where('status',1)->where(function ($products) use ($cats){
                if (!empty($cats)) {
                    foreach ($cats as $key => $cat) {
                        if ($cat->parent_id == 0) {
                            if ($cat->parents()->count()) {
                                foreach ($cat->parents as $parent) {
                                    if ($parent->parents()->count() == 0) {
                                        $products->orWhere('category_id', $parent->id);
                                    } else {
                                        $products->orWhere('category_id', $parent->id);
                                        foreach ($parent->parents as $parent2) {
                                            $products->orWhere('category_id', $parent2->id);
                                        }
                                    }
                                }
                                $products->orWhere('category_id',$cat->id);
                            } else {
                                $products->orWhere('category_id', $cat->id);
                            }
                        } else {
                            if ($cat->parents()->count()) {
                                $products->orWhere('category_id', $cat->id);
                                foreach ($cat->parents as $parent) {
                                    $products->orWhere('category_id', $parent->id);
                                }
                            } else {
                                $products->orWhere('category_id', $cat->id);
                            }
                        }
                    }
                }
            })->orderBy($filter1,$filter2)->Paginate(12);
        }else{
            return back();
        }

        $categories = Category::where('parent_id',0)->get();
        return view('fronts.products')
            ->with('categories',$categories)
            ->with('products',$products)
            ->with('job',$job)
            ->with('catg',@$category)
            ->with('tag',@$tag)
            ->with('valAsli',$valAsli);
    }

    public function products_filter(Request $request,$value)
    {
        if ($request->has('jadidtarin')){
            session()->put('profilter1','id');
            session()->put('profilter2','desc');
        }elseif ($request->has('arzantarin')){
            session()->put('profilter1','price');
            session()->put('profilter2','asc');
        }elseif($request->has('grantarin')){
            session()->put('profilter1','price');
            session()->put('profilter2','desc');
        }
        elseif($request->has('ghadimitarin')){
            session()->put('profilter1','id');
            session()->put('profilter2','asc');
        }elseif ($request->has('select_categories')){
            if (empty($request->categories)){
                if (session()->has('select_categories_store') ) { session()->forget('select_categories_store'); }
                session()->flash('error','برای اعمال فیلتر دسته بندی ,حداقل باید یک دسته بندی انتخاب شده باشد');
                if ($request->has('tag')){
                    return redirect(route('products','tag='.$value));
                }
                if ($request->has('search')){
                    return redirect(route('products','search='.$value));
                }
                if ($request->has('store')){
                    return redirect(route('products','store='.$value));
                }
            }
            $categories = [];
            foreach ($request->categories as $category){
                $cat = Category::where('id',$category)->first();
                array_push($categories,$cat);
            }
            session()->put('select_categories_store',$categories);
            if ($request->has('tag')){
                return redirect(route('products','tag='.$value));
            }
            if ($request->has('search')){
                return redirect(route('products','search='.$value));
            }
            return redirect(route('products','categories='.$value));
        }elseif($request->has('productsstore')){
            if (session()->has('profilter1') ) { session()->forget('profilter1'); }
            if (session()->has('profilter2') ) { session()->forget('profilter2'); }
            if (session()->has('select_categories_store') ) { session()->forget('select_categories_store'); }
            return redirect(route('products','store='.$value));
        }elseif ($request->has('productstag')){
            if (session()->has('profilter1') ) { session()->forget('profilter1'); }
            if (session()->has('profilter2') ) { session()->forget('profilter2'); }
            if (session()->has('select_categories_store') ) { session()->forget('select_categories_store'); }
            return redirect(route('products','tag='.$value));
        }elseif ($request->has('deletefiltercat')){
            if (session()->has('select_categories_store') ) { session()->forget('select_categories_store'); }
            return redirect(route('products','search='.$value));
        }
        return redirect(route('products',$value));
    }

    public function addprotofavourite(Request $request,Product $product)
    {
        $checkexistspro = Favourite::where('product_id',$product->id)->where('user_id',auth()->user()->id)->first();
        if ($checkexistspro == null){
            Favourite::create([
                'user_id'=>auth()->user()->id,
                'product_id'=>$product->id
            ]);
            session()->flash('success','این محصول با موفقیت به علاقه مندی هایتان اضافه شد');
        }else{
            session()->flash('success','این محصول با موفقیت از علاقه مندی هایتان حذف شد');
            $product->favourite()->delete();
        }
        return back();
    }

    public function search(Request $request)
    {
        $this->validate($request,[
           'search'=>['required','string']
        ]);
        return redirect(route('products','search='.$request->search));
    }

    public function showTemplate(Request $request,Product $product)
    {
        $zip = new ZipArchive();
        $zip->open(Storage::path($product->file),ZipArchive::CREATE);
        $zip->extractTo(public_path('template/'.rand(10000,999999).time().rand(300,7777)));
        $zip->close();

    }

}
