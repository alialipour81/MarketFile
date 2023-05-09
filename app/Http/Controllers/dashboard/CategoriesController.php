<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Category\CreateRequestCategory;
use App\Http\Requests\dashboard\Category\UpdateRequestCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
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
        $categories = Category::searched()->orderBy('id','desc')->Paginate(15);
        return view('dashboard.categories.index')
            ->with('categories',$categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id',0)->orderBy('id','desc')->get();
        return view('dashboard.categories.add&edit')
            ->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequestCategory $request)
    {
        $CheckExistsCat = Category::where('name',$request->name)->where('parent_id',$request->parent_id)->first();
        $CheckExistsCat2 = Category::where('nameEn',$request->nameEn)->where('parent_id',$request->parent_id)->first();
        if ($CheckExistsCat != null){
         $this->validate($request,['name'=>'unique:categories,name']);
        }
        if ($CheckExistsCat2 != null){
            $this->validate($request,['nameEn'=>'unique:categories,nameEn']);
        }
        Category::create([
            'name'=> $request->name,
            'nameEn'=> $request->nameEn,
            'parent_id'=>$request->parent_id
        ]);
        session()->flash('success_dashboard','دسته بندی با موفقیت ایجاد شد');
        return redirect(route('categories.index'));
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
    public function edit(Category $category)
    {
        $categories = Category::where('parent_id',0)->orderBy('id','desc')->get();
        return view('dashboard.categories.add&edit')
            ->with('categories',$categories)
            ->with('category',$category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestCategory $request, Category $category)
    {
        if ($category->name != $request->name || $category->parent_id != $request->parent_id){
            $CheckExistsCat = Category::where('name',$request->name)->where('parent_id',$request->parent_id)->first();
            if ($CheckExistsCat != null ){
                $this->validate($request,['name'=>'unique:categories,name']);
            }
        }
        if ($category->nameEn != $request->nameEn || $category->parent_id != $request->parent_id){
            $CheckExistsCat = Category::where('nameEn',$request->nameEn)->where('parent_id',$request->parent_id)->first();
            if ($CheckExistsCat != null ){
                $this->validate($request,['nameEn'=>'unique:categories,nameEn']);
            }
        }
        $category->update([
           'name'=>$request->name,
           'nameEn'=>$request->nameEn,
           'parent_id'=>$request->parent_id
        ]);
        session()->flash('success_dashboard',' دسته بندی آیدی '.$category->id.' با موفقیت ویرایش شد ');
        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        if ($category->parents()->count()){
         foreach ($category->parents as $parent){
             if ($parent->parents()->count()){
                 $parent->parents()->delete();
                 $parent->delete();
             }else{
                 $parent->delete();
             }
         }
         $category->delete();
            session()->flash('success_dashboard','دسته بندی مورد نظر(+زیر دسته بندی هایش) با موفقیت حذف شد');
        }else{
            $category->delete();
            session()->flash('success_dashboard','دسته بندی مورد نظر با موفقیت حذف شد');
        }
        return redirect(route('categories.index'));
    }
}
