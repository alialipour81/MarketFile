<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Collapse\CreateRequestCollapse;
use App\Http\Requests\dashboard\Collapse\UpdateRequestCollapse;
use App\Models\Collapse;
use App\Models\Product;
use Illuminate\Http\Request;

class CollapsesController extends Controller
{
    public function __construct()
    {
        $this->middleware('ExistsStore');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function index2(Product $product)
    {
        // auth collapse
        if ($product->user_id != auth()->user()->id && auth()->user()->role != 'admin'){
            session()->flash('error_dashboard','شما قادر به دسترسی باکس هایی هستید که محصولش توسط شما ایجاد شده  باشد');
            return redirect(route('products.index'));
        }

        $collapses = Collapse::searched()->where('product_id',$product->id)->orderBy('id','desc')->Paginate(15);
        session()->put('coll_productid',$product->id);
        session()->put('coll_productslug',$product->slug);
        session()->put('coll_producttitle',$product->title);
        return view('dashboard.products.collapses.index')
            ->with('product',$product)
            ->with('collapses',$collapses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!session()->has('coll_productid') && !session()->has('coll_productslug') ){
            session()->flash('error_dashboard','شما ابتدا باید روی دکمه مدیریت باکس مربوط به محصول کلیک کنید');
            return redirect(route('products.index'));
        }
        $type = [
          'install'=>'بخش نصب و فعال سازی',
          'description'=>'بخش نقد و بررسی',
        ];
        return view('dashboard.products.collapses.add&edit')
            ->with('type',$type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestCollapse $request)
    {
        $ExistsTitleColl = Collapse::where('product_id',session()->get('coll_productid'))->where('title',$request->title)->first();
        if ($ExistsTitleColl != null){
            $this->validate($request, [
                'title'=>['unique:collapses,title']
            ]);
        }
       $collapse = Collapse::create([
            'product_id'=> session()->get('coll_productid'),
            'user_id'=>auth()->user()->id,
            'type'=>$request->type,
            'title'=>$request->title,
            'slug'=>$this->slug($request->title),
            'content'=>$request->content
        ]);
       if (auth()->user()->role  == 'admin'){
           $collapse->status = 1;
           $collapse->save();
           session()->flash('success_dashboard','باکس شما برای این محصول با موفقیت ایجاد شد ');
       }else{
           session()->flash('success_dashboard','باکس شما برای این محصول با موفقیت ایجاد شد و در صورت تایید ادمین نمایش داده میشود');
       }
       return redirect(route('collapses.index2',session()->get('coll_productslug')));
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
    public function edit(Collapse $collapse)
    {

        if ($collapse->product->user_id != auth()->user()->id && auth()->user()->role != 'admin'){
            session()->flash('error_dashboard','شما قادر به ویرایش باکس هایی هستید که محصولش توسط شما ایجاد شده  باشد');
            return redirect(route('products.index'));
        }
        $type = [
            'install'=>'بخش نصب و فعال سازی',
            'description'=>'بخش نقد و بررسی',
        ];
        $status = [
            '0'=> 'عدم نمایش',
            '1'=> 'نمایش',
        ];
        return view('dashboard.products.collapses.add&edit')
            ->with('type',$type)
            ->with('collapse',$collapse)
            ->with('status',$status);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestCollapse $request, Collapse $collapse)
    {
       if ($collapse->title != $request->title){
           $ExistsTitleColl = Collapse::where('product_id',session()->get('coll_productid'))->where('title',$request->title)->first();
           if ($ExistsTitleColl != null){
               $this->validate($request, [
                   'title'=>['unique:collapses,title']
               ]);
           }
       }
        $collapse->update([
            'type'=>$request->type,
            'title'=>$request->title,
            'slug'=>$this->slug($request->title),
            'content'=>$request->content,
            'status'=>$request->status
        ]);
            session()->flash('success_dashboard',' باکس شما با نام  '.$collapse->title.' با موفقیت ویرایش شد ');
        return redirect(route('collapses.index2',$collapse->product->slug));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collapse $collapse)
    {
        session()->flash('success_dashboard',' باکس شما با نام  '.$collapse->title.' با موفقیت حذف شد ');
        $pro_slug = $collapse->product->slug;
        Collapse::where('product_id',$collapse->product_id)->where('slug',$collapse->slug)->delete();
        return redirect(route('collapses.index2',$pro_slug));
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
            $request->file('upload')->move('storage/ck_collapses', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/ck_collapses/' . $filenametostore);
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
