<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Amazing\CreateRequestAmazing;
use App\Http\Requests\dashboard\Amazing\UpdateRequestAmazing;
use App\Models\Amazing;
use App\Models\Product;
use Illuminate\Http\Request;

class AmazingsController extends Controller
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
        $amazings = Amazing::searched()->orderBy('id','desc')->Paginate(15);
        return view('dashboard.amazings.index')
            ->with('amazings',$amazings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status',1)->where('new_price','!=',0)->orderBy('id','desc')->get();
        return view('dashboard.amazings.add&edit')
            ->with('products',$products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestAmazing $request)
    {
        Amazing::create([
            'product_id'=>$request->product_id,
            'user_id'=>auth()->user()->id
        ]);
        session()->flash('success_dashboard','یک محصول به پیشنهاد شگفت انگیز اضافه شد');
        return redirect(route('amazings.index'));
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
    public function edit(Amazing $amazing)
    {
        $products = Product::where('status',1)->where('new_price','!=',0)->orderBy('id','desc')->get();
        $statuses = [
            '0'=>'عدم نمایش',
            '1'=>'نمایش',
        ];
        return view('dashboard.amazings.add&edit')
            ->with('products',$products)
            ->with('statuses',$statuses)
            ->with('amazing',$amazing);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestAmazing $request, Amazing $amazing)
    {
        if ($amazing->product_id != $request->product_id){
            $ExistsPro = Amazing::where('product_id',$request->product_id)->first();
            if ($ExistsPro != null){
                $this->validate($request,[
                    'product_id'=>['unique:amazings,product_id']
                ]);
            }
        }
        $amazing->update([
            'product_id'=>$request->product_id,
            'status'=>$request->status
        ]);
        session()->flash('success_dashboard','محصول پیشنهاد شگفت انگیز با موفقیت ویرایش شد ');
        return redirect(route('amazings.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amazing $amazing)
    {
        $amazing->delete();
        session()->flash('success_dashboard','محصول از پیشنهاد شگفت انگیز با موفقیت حذف شد ');
        return redirect(route('amazings.index'));
    }
}
