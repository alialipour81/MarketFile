<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Poster\CreateRequestPoster;
use App\Http\Requests\dashboard\Poster\UpdateRequestPoster;
use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostersController extends Controller
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
        $posters = Poster::searched()->orderBy('id','desc')->Paginate(15);
        return view('dashboard.posters.index')
            ->with('posters',$posters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.posters.add&edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestPoster $request)
    {
        Poster::create([
            'user_id'=>auth()->user()->id,
           'title'=>$request->title,
            'image'=>$request->image->store('posters'),
            'link'=>$request->link,
        ]);
        session()->flash('success_dashboard','پوستر با موفقیت ایجاد شد');
        return redirect(route('posters.index'));
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
    public function edit(Poster $poster)
    {
        $statuses = [
          '0'=>'عدم نمایش',
          '1'=>'نمایش',
        ];
        return view('dashboard.posters.add&edit')
            ->with('poster',$poster)
            ->with('statuses',$statuses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestPoster $request, Poster $poster)
    {
        if ($request->hasFile('image')){
             $this->validate($request,[
                 'image'=>['required','image','mimes:png,jpeg,jpg,gif','max:1000'],
             ]);
            Storage::delete($poster->image);
            $poster->image = $request->image->store('posters');
            $poster->save();
        }
        $poster->update([
            'title'=>$request->title,
            'link'=>$request->link,
            'status'=>$request->status,
        ]);
        session()->flash('success_dashboard',' پوستر ( '.$poster->title.')'.'با موفقیت ویرایش شد');
        return redirect(route('posters.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poster $poster)
    {
        Storage::delete($poster->image);
        session()->flash('success_dashboard',' پوستر ( '.$poster->title.')'.'با موفقیت حذف شد');
        $poster->delete();
        return redirect(route('posters.index'));
    }
}
