<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Comment\CreateRequestComment;
use App\Http\Requests\dashboard\Comment\UpdateRequestComment;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin')->except('store');
    }
    public function index()
    {
        $comments = Comment::searched()->orderBy('id','desc')->Paginate(20);
        $status = [
          '0'=>'عدم نمایش',
          '1'=>'نمایش',
        ];
        return view('dashboard.comments.index')
            ->with('comments',$comments)
            ->with('status',$status);
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
    public function store(CreateRequestComment $request)
    {
        if ($request->has('send_comment')) {
            $this->validate($request,[
               'star'=>['required','integer']
            ]);
            $comment = Comment::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->id,
                'comment' => $request->comment,
                'star' => $request->star,
            ]);
            if (auth()->user()->role == 'admin') {
                $mes = 'نظر شما با موفقیت ثبت شد';
                $comment->status = 1;
                $comment->save();
            } else {
                $mes = 'نظر شما با موفقیت ثبت شد و پس از تایید  ادمین نمایش داده میشود ';

            }
            session()->flash('success', $mes);
        }else{ //reply
           $comment = Comment::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->id,
                'comment' => $request->comment,
                'star' => $request->star,
                'child'=>$request->c_id,
            ]);
            if (auth()->user()->role == 'admin') {
                $mes = 'پاسخ شما با موفقیت ثبت شد';
                $comment->status = 1;
                $comment->save();
            } else {
                $mes = 'پاسخ شما با موفقیت ثبت شد و پس از تایید  ادمین نمایش داده میشود ';

            }
            session()->flash('success', $mes);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestComment $request, Comment $comment)
    {
        if ($request->has('commentadi')){
            $this->validate($request,[
               'star'=>['required','integer']
            ]);
            $comment->update([
               'comment'=>$request->comment,
               'star'=>$request->star,
               'status'=>$request->status
            ]);
            session()->flash('success_dashboard','نظر با موفقیت ویرایش شد');
        }else{
            $comment->update([
                'comment'=>$request->comment,
                'status'=>$request->status
            ]);
            session()->flash('success_dashboard','پاسخ با موفقیت ویرایش شد');
        }
        return redirect(route('comments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if ($comment->child == null){
            session()->flash('success_dashboard',' نظر برای محصول '.$comment->product->title.' با موفقیت حذف شد ');
        }else{
            session()->flash('success_dashboard',' پاسخ برای محصول '.$comment->product->title.' با موفقیت حذف شد ');
        }
        if ($comment->replies()->count()){
            $comment->replies()->delete();
            $comment->delete();
        }else{
            $comment->delete();
        }
        return redirect(route('comments.index'));

    }
}
