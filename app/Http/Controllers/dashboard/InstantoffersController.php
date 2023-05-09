<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Instantoffer\CreateRequestInstantoffer;
use App\Http\Requests\dashboard\Instantoffer\UpdateRequestInstantoffer;
use App\Models\Instantoffer;
use App\Models\Product;
use Illuminate\Http\Request;

class InstantoffersController extends Controller
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
        $instantoffers = Instantoffer::searched()->orderBy('id','desc')->Paginate(15);
        return view('dashboard.instantoffers.index')
            ->with('instantoffers',$instantoffers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status',1)->orderBy('id','desc')->get();
        return view('dashboard.instantoffers.add&edit')
            ->with('products',$products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestInstantoffer $request)
    {
        Instantoffer::create([
           'product_id'=>$request->product_id,
           'user_id'=>auth()->user()->id
        ]);
        session()->flash('success_dashboard','یک محصول به پیشنهادات لحظه ای اضافه شد');
        return redirect(route('instantoffers.index'));
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
    public function edit(Instantoffer $instantoffer)
    {
        $products = Product::where('status',1)->orderBy('id','desc')->get();
        $statuses = [
            '0'=>'عدم نمایش',
            '1'=>'نمایش',
        ];
        return view('dashboard.instantoffers.add&edit')
            ->with('products',$products)
            ->with('statuses',$statuses)
            ->with('instantoffer',$instantoffer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestInstantoffer $request, Instantoffer $instantoffer)
    {
        if ($instantoffer->product_id != $request->product_id){
            $ExistsPro = Instantoffer::where('product_id',$request->product_id)->first();
            if ($ExistsPro != null){
                $this->validate($request,[
                   'product_id'=>['unique:instantoffers,product_id']
                ]);
            }
        }
        $instantoffer->update([
            'product_id'=>$request->product_id,
            'status'=>$request->status
        ]);
        session()->flash('success_dashboard','محصول پیشنهاد با موفقیت ویرایش شد ');
        return redirect(route('instantoffers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instantoffer $instantoffer)
    {
        $instantoffer->delete();
        session()->flash('success_dashboard','محصول پیشنهاد با موفقیت حذف شد ');
        return redirect(route('instantoffers.index'));
    }
}
