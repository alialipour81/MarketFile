<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('ExistsStore')->except(['download','downloadTest','Template_download']);
    }
    public function index()
    {

    }
    public function index2(Product $product)
    {
        if ($product->user_id != auth()->user()->id && auth()->user()->role != 'admin'){
            session()->flash('error_dashboard','شما قادر به دسترسی بخش دانلودی هستید که محصولش توسط شما ایجاد شده  باشد');
            return redirect(route('products.index'));
        }
        session()->put('download_productid',$product->id);
        session()->put('download_productslug',$product->slug);
        $downloads = Download::searched()->where('product_id',$product->id)->orderBy('id','desc')->Paginate(15);
        return view('dashboard.products.downloads.index')
            ->with('downloads',$downloads)
            ->with('product',$product);
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
        if ($request->has('add_link')){
            $this->validate($request,[
            'title'=>['required','string'],
            'url'=>['required','url']
            ]);
            $ExistsTitleDown = Download::where('product_id',session()->get('download_productid'))->where('title',$request->title)->first();
            if ($ExistsTitleDown != null){
                $this->validate($request, [
                    'title'=>['unique:downloads,title']
                ]);
            }

           $dow = Download::create([
                'product_id'=> session()->get('download_productid'),
                'user_id'=> auth()->user()->id,
                'title'=>$request->title,
                'slug'=>$this->slug($request->title),
                'url'=> $request->url,
            ]);
            session()->flash('success_dashboard','لینک  دانلود با موفقیت ایجاد شد');

        }else{
           $this->validate($request ,[
               'titlefile'=>['required','string'],
               'file'=>['required','mimes:zip','max:50000']
           ]);
            $ExistsTitleDown = Download::where('product_id',session()->get('download_productid'))->where('title',$request->titlefile)->first();
            if ($ExistsTitleDown != null){
                $this->validate($request, [
                    'titlefile'=>['unique:downloads,title']
                ]);
            }
           $dow = Download::create([
                'product_id'=> session()->get('download_productid'),
                'user_id'=> auth()->user()->id,
                'title'=>$request->titlefile,
                'slug'=>$this->slug($request->titlefile),
                'file'=> $request->file->store('files_download')
            ]);
            session()->flash('success_dashboard','فایل دانلود با موفقیت ایجاد شد');
        }
        if (auth()->user()->role == 'admin'){
            $dow->status = 1;
            $dow->save();
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
    public function edit(Download $download)
    {
        if ($download->product->user_id != auth()->user()->id && auth()->user()->role != 'admin') {
            session()->flash('error_dashboard', 'شما قادر به دسترسی بخش ویرایش دانلودی هستید که محصولش توسط شما ایجاد شده  باشد');
            return redirect(route('products.index'));
        }

        $status = [
            '0'=> 'عدم نمایش',
            '1'=> 'نمایش',
        ];
        return view('dashboard.products.downloads.edit')
            ->with('download',$download)
            ->with('status',$status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Download $download)
    {
        if ($download->url == null){
            $this->validate($request ,[
                'title'=>['required','string'],
                'status'=>['required','numeric']
            ]);
            if ($request->title != $download->title) {
                $ExistsTitleDown = Download::where('product_id',session()->get('download_productid'))->where('title',$request->title)->first();
                if ($ExistsTitleDown != null){
                    $this->validate($request, [
                        'title'=>['unique:downloads,title']
                    ]);
                }
            }
            if ($request->hasFile('new_file')){
                $this->validate($request, [
                    'new_file' => ['mimes:zip', 'max:50000']
                ]);
                Storage::delete($download->file);
                $download->file = $request->new_file->store('files_download');
                $download->save();
            }
            $download->update([
                'title'=>$request->title,
                'slug'=>$this->slug($request->title),
                'status'=>$request->status
            ]);

        }
        else{
            $this->validate($request,[
                'title'=>['required','string'],
                'url'=>['required','url']
            ]);
            if ($request->title != $download->title) {
                $ExistsTitleDown = Download::where('product_id',session()->get('download_productid'))->where('title',$request->title)->first();
                if ($ExistsTitleDown != null){
                    $this->validate($request, [
                        'title'=>['unique:downloads,title']
                    ]);
                }
            }
            $download->update([
                'title'=>$request->title,
                'slug'=>$this->slug($request->title),
                'url'=>$request->url,
                'status'=>$request->status
            ]);
        }
        session()->flash('success_dashboard',' عنوان دانلود ( '.$download->title.' ) '.'با موفقیت ویرایش شد');
        return redirect(route('downloads.index2',session()->get('download_productslug')));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Download $download)
    {
        if ($download->url == null){
            Storage::delete($download->file);
        }
        session()->flash('success_dashboard',' عنوانی دانلود ( '.$download->title.' ) '.'با موفقیت حذف شد');
        $pro_slug = $download->product->slug;
        Download::where('product_id',$download->product_id)->where('slug',$download->slug)->delete();
        return redirect(route('downloads.index2',$pro_slug));
    }

    public function download(Download $download)
    {
        if ($download->url == null) {
            if ($download->product->type == 'app'){
                $product = Product::where('id',$download->product->id)->first();
                $product->update([
                    'CountDownload'=>$product->CountDownload+1
                ]);
            }
           return Storage::download($download->file, $download->slug.'.zip');
        }
        return back();

    }

    public function downloadTest(Download $download)
    {
        if ($download->url == null) {
            return Storage::download($download->file, $download->slug.'.zip');
        }
        return back();

    }

    public function Template_download(Request $request,Product $product)
    {
        if ($product->file != null) {
            $product->update([
               'CountDownload'=>$product->CountDownload+1
            ]);
            return Storage::download($product->file, $product->slug.'.zip');
        }
        return back();
    }
    public function downloadTemplate(Product $product)
    {
        if ($product->type == 'template'){
            return Storage::download($product->file,$product->slug.'.zip');
        }else
            return back();
    }
    public function slug($value)
    {
        $ex = explode(' ',$value);
        return implode('-',$ex);
    }
}
