<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Slider\CreateRequestSlider;
use App\Http\Requests\dashboard\Slider\UpdateRequestSlider;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlidersController extends Controller
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
        $sliders = Slider::searched()->orderBy('id','desc')->Paginate(15);
        return view('dashboard.sliders.index')
            ->with('sliders',$sliders);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.sliders.add&edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestSlider $request)
    {
        Slider::create([
            'user_id'=>auth()->user()->id,
            'title'=>$request->title,
            'image'=>$request->image->store('sliders'),
            'link'=>$request->link,
        ]);
        session()->flash('success_dashboard','اسلاید با موفقیت ایجاد شد');
        return redirect(route('sliders.index'));
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
    public function edit(Slider $slider)
    {
        $statuses = [
            '0'=>'عدم نمایش',
            '1'=>'نمایش',
        ];
        return view('dashboard.sliders.add&edit')
            ->with('slider',$slider)
            ->with('statuses',$statuses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestSlider $request, Slider $slider)
    {
        if ($request->hasFile('image')){
            $this->validate($request,[
                'image'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            ]);
            Storage::delete($slider->image);
            $slider->image = $request->image->store('sliders');
            $slider->save();
        }
        $slider->update([
            'title'=>$request->title,
            'link'=>$request->link,
            'status'=>$request->status,
        ]);
        session()->flash('success_dashboard',' اسلاید ( '.$slider->title.')'.'با موفقیت ویرایش شد');
        return redirect(route('sliders.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        Storage::delete($slider->image);
        session()->flash('success_dashboard',' اسلاید ( '.$slider->title.')'.'با موفقیت حذف شد');
        $slider->delete();
        return redirect(route('sliders.index'));
    }
}
