<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Product\CreateRequestAddTemplate;
use App\Http\Requests\dashboard\Product\CreateRequestProduct;
use App\Http\Requests\dashboard\Product\UpdateRequestProduct;
use App\Models\Category;
use App\Models\Download;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('ExistsStore');
        $this->middleware('admin')->only(['deleteTemplate']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role == 'admin'){
            $products = Product::searched()->orderBy('id','desc')->Paginate(15);
        }else{
            $stores = Store::where('user_id',auth()->user()->id)->where('status',2)->get();
            $products = Product::searched()->where(function ($products) use ($stores){
                foreach ($stores as $store){
                    $products->orWhere('store_id',$store->id);
                }
            })->orderBy('id','desc')->Paginate(15);
        }
        return view('dashboard.products.index')
            ->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id',0)->orderBy('id','desc')->get();
        $stores = Store::where('user_id',auth()->user()->id)->where('status',2)->orderBy('id','desc')->get();
        $tags = Tag::orderBy('id','desc')->get();
        $types = [
          'template'=>'قالب',
          'app'=>'برنامه',
        ];
        return view('dashboard.products.add&edit',compact('categories','stores','tags','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestProduct $request)
    {

        if (!empty($request->tags)){
            $this->validate($request,[
               'tags.*'=>['required','integer']
            ]);
        }
        if (!empty($request->price)){
            $this->validate($request,[
                'price'=>['required','numeric']
            ]);
        }
        if (!empty($request->new_price)){
            $this->validate($request,[
                'new_price'=>['required','numeric']
            ]);
        }
        if (!empty($request->new_price) && empty($request->price)){
            session()->flash('error_dashboard','برای انتخاب قیمت فروش یک محصول ابتدا باید قیمت اصلی مشخص شود');
            return back()->withInput();
        }
        if (!empty($request->new_price) && !empty($request->price)) {
            if ($request->new_price > $request->price) {
                session()->flash('error_dashboard', 'قیمت فروش نباید بزرگتر از  قیمت اصلی باشد');
                return back()->withInput();
            }
        }
       $product = Product::create([
            'category_id'=>$request->input('category_id'),
            'store_id'=>$request->input('store_id'),
            'user_id'=>auth()->user()->id,
            'type'=>$request->input('type'),
            'title'=>$request->input('title'),
            'slug'=>$this->slug($request->title),
            'image1'=>$request->image1->store('products'),
            'image2'=>$request->image2->store('products'),
            'image3'=>$request->image3->store('products'),
            'image4'=>$request->image4->store('products'),
            'attrbutes'=>$request->input('attrbutes'),
            'description'=>$request->input('description')
        ]);
        if (!empty($request->price)){
            $product->price = $request->price;
            $product->save();
        }
        if (!empty($request->new_price)){
            $product->new_price = $request->new_price;
            $product->save();
        }
        if (!empty($request->tags)){
            $product->tags()->attach($request->tags);
        }
        if (auth()->user()->role == 'admin'){
            $product->status = 1;
            $product->save();
        }
        if ($request->type == 'app'){
            $type = 'برنامه';
        }else{
            $type = 'قالب';
        }
        session()->flash('success_dashboard',$type . ' با موفقیت ایجاد شد ');
        return redirect(route('products.index'));

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
    public function edit(Product $product)
    {
        if ($product->user_id != auth()->user()->id && auth()->user()->role != 'admin'){
            session()->flash('error_dashboard','شما قادر به ویرایش محصولاتی  هستید که توسط شما ساخته شده است');
            return redirect(route('products.index'));
        }
        $categories = Category::where('parent_id',0)->orderBy('id','desc')->get();
        if (auth()->user()->role == 'admin'){
            $stores = Store::where('status', 2)->orderBy('id', 'desc')->get();
        }else {
            $stores = Store::where('user_id', auth()->user()->id)->where('status', 2)->orderBy('id', 'desc')->get();
        }
        $tags = Tag::orderBy('id','desc')->get();
        $status = [
            '0'=> 'عدم نمایش',
            '1'=> 'نمایش',
        ];
        $types = [
            'template'=>'قالب',
            'app'=>'برنامه',
        ];
        return view('dashboard.products.add&edit',compact('categories','stores','tags','types','product','status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestProduct $request, Product $product)
    {
        if ($request->new_price > $request->price){
            session()->flash('error_dashboard','قیمت فروش نباید بزرگتر از  قیمت اصلی باشد');
            return back()->withInput();
        }
        if ($request->title != $product->title){
            $CheckWExistsProduct = Product::where('title',$request->title)->first();
            if($CheckWExistsProduct != null){
                $this->validate($request,['title'=>'unique:products,title']);
            }
        }
        if (!empty($request->tags)){
            $this->validate($request,[
                'tags.*'=>['required','integer']
            ]);
            $product->tags()->sync($request->tags);
        }

        $product->update([
            'category_id'=>$request->category_id,
            'store_id'=>$request->store_id,
            'type'=>$request->type,
            'title'=>$request->title,
            'slug'=>$this->slug($request->title),
            'attrbutes'=>$request->attrbutes,
            'description'=>$request->description,
            'status'=>$request->status,
            'price'=>$request->price,
            'new_price'=>$request->new_price,
        ]);
        $product->user_id = $product->store->user_id;$product->save();
        for ($j=1;$j<=4;$j++){
            $img = 'image'.$j;
            if ($request->hasFile($img)){
                $this->validate($request,[
                   $img=> ['image','mimes:png,jpeg,jpg','max:1000']
                ]);
                Storage::delete($product->$img);
                $product->$img = $request->$img->store('products');
                $product->save();
            }
        }
        if ($product->new_price == 0 || $product->status == 0){
            if ($product->amazing()->count()){
                $product->amazing()->delete();
            }
        }
        if ( $product->status == 0){
            if ($product->instantoffer()->count()){
                $product->instantoffer()->delete();
            }
        }



        if ($request->type == 'app'){
            $type = 'برنامه';
        }else{
            $type = 'قالب';
        }
        session()->flash('success_dashboard',$type . ' با موفقیت ویرایش شد ');
        return redirect(route('products.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        for ($j=1;$j<=4;$j++){
            $img = 'image'.$j;
            Storage::delete($product->$img);
        }
        if ($product->type == 'app'){
            $type = 'برنامه';
        }else{
            $type = 'قالب';
        }
        session()->flash('success_dashboard',$type . ' با موفقیت حذف شد ');
        $product->collapses()->delete();
        if ($product->tags()->count()){
            $product->tags()->detach();
        }
        if ($product->amazing()->count()){
            $product->amazing()->delete();
        }
        if ($product->instantoffer()->count()){
            $product->instantoffer()->delete();
        }

        foreach ($product->downloads as $download){
            if ($download->url == null) {
                Storage::delete($download->file);
            }
            Download::where('product_id',$download->product_id)->where('slug',$download->slug)->delete();
        }
        $product->delete();
        return redirect(route('products.index'));
    }

    public function file_template(CreateRequestAddTemplate $request,Product $product)
    {
        if ($request->has('addfile')) {
            $product->update([
                'file' => $request->file->store('templates')
            ]);
            session()->flash('success_dashboard', 'فایل قالب محصول (' . $product->title . ')' . ' با موفقیت اضافه شد ');

        }elseif($request->has('updatefile')){
            Storage::delete($product->file);
            $product->update([
                'file' => $request->file->store('templates')
            ]);
            session()->flash('success_dashboard', 'فایل قالب محصول (' . $product->title . ')' . ' با موفقیت ویرایش شد ');
        }
        return redirect(route('products.index'));
    }

    public function deleteTemplate(Request $request,Product $product)
    {
        Storage::delete($product->file);
        $product->file = null;
        $product->save();
        session()->flash('success_dashboard', 'فایل قالب محصول (' . $product->title . ')' . ' با موفقیت حذف شد ');
        return redirect(route('products.index'));
    }


    public function CK_upload(Request $request)
    {
        if($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename . '_' . time() . rand(1000,1000000000000000).'.' . $extension;

            //Upload File
            $request->file('upload')->move('ck_uploads', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('ck_uploads/' . $filenametostore);
            $message = 'آپلود فایل با موفقیت انجام شد روی ok کلیک کنید';
            $result = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$message')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $result;
        }
    }

    public function slug($value)
    {
        $ex = explode(' ',$value);
        return implode('-',$ex);
    }
}
