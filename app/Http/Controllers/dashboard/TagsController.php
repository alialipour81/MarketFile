<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Tag\CreateRequestTag;
use App\Http\Requests\dashboard\Tag\UpdateRequestTag;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::searched()->orderBy('id','desc')->Paginate(15);
        return view('dashboard.tags.index')
            ->with('tags',$tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tags.add&edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestTag $request)
    {
        Tag::create([
            'name'=>$request->name
        ]);
        session()->flash('success_dashboard','برچسپ با موفقیت اضافه شد');
        return  redirect(route('tags.index'));
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
    public function edit(Tag $tag)
    {
        return view('dashboard.tags.add&edit')
            ->with('tag',$tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestTag $request, Tag $tag)
    {
        if ($request->name != $tag->name){
            $ExistsTag = Tag::where('name',$request->name)->first();
            if ($ExistsTag != null){
                $this->validate($request,['name'=>['unique:tags,name']]);
            }
        }
        $tag->update([
           'name'=>$request->name
        ]);
        session()->flash('success_dashboard',' برچسپ با آیدی '.$tag->id.' با موفقیت ویرایش شد ');
        return  redirect(route('tags.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        session()->flash('success_dashboard',' برچسپی با نام '.'('.$tag->name.')'.' با موفقیت حذف شد ');
        $tag->delete();
        return  redirect(route('tags.index'));
    }
}
