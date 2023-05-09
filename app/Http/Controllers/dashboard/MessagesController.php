<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\Message\CreateRequestMessage;
use App\Http\Requests\dashboard\Message\ReplyRequestMessage;
use App\Http\Requests\dashboard\Message\UpdateRequestMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use function PHPUnit\Framework\callback;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role == 'admin'){
            $messages = Message::searched()->where('child',0)->orderBy('id','desc')->Paginate(30);
        }else{
            $messages = Message::searched()->where('user_id',auth()->user()->id)->where('child',0)->orderBy('id','desc')->Paginate(30);
        }

        return view('dashboard.messages.index')
            ->with('messages',$messages)
;
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
    public function store(CreateRequestMessage $request)
    {
       $message = Message::create([
           'user_id'=>auth()->user()->id,
           'title'=>$request->title,
           'description'=>$request->description,
        ]);
       session()->flash('success_dashboard','تیکت با موفقیت ارسال شد');
       session()->put('messageClick',$message);
       return redirect(route('messages.index'));
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
    public function edit(Message $message)
    {
        if (auth()->user()->role == 'user') {return back();}
        return view('dashboard.messages.edit')
            ->with('message',$message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestMessage $request, Message $message)
    {

        if ($message == null || auth()->user()->role == 'user') { return back();}
        if ($request->has('title')){
           if ($request->title != null){
               if ($request->title != $message->title) {
                   $MES = Message::where('title',$request->title)->where('user_id',$message->user_id)->first();
                   if ($MES != null) {
                       $this->validate($request, [
                           'title' => ['required', 'string', 'unique:messages,title']
                       ]);
                   }
                   $message->update([
                       'title'=>$request->title
                   ]);
               }

           }
        }
        $message->update([
           'description'=>$request->description
        ]);
        session()->flash('success_dashboard','پاسخ با موفقیت ویرایش شد ');
        if ($message->title == null) {
            $message2 = Message::where('id', $message->parent->id)->first();
        }else{
            $message2 = Message::where('id', $message->id)->first();
        }
        session()->put('messageClick', $message2);
        return redirect(route('messages.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        if ($message->replies()->count()){
            $message->replies()->delete();
            session()->forget('messageClick');
        }else{
            $message2 = Message::where('id',$message->parent->id)->first();
            session()->put('messageClick',$message2);
        }
            $message->delete();
        session()->flash('success_dashboard', 'تیکت  با موفقیت حذف شد');
        return redirect(route('messages.index'));
    }

    public function uploadImageCKeditor(Request $request)
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
            $request->file('upload')->move('storage/ck_messages', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/ck_messages/' . $filenametostore);
            $message = 'آپلود فایل با موفقیت انجام شد روی ok کلیک کنید';
            $result = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$message')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $result;
        }
    }

    public function clickMessage($value)
    {
        if (auth()->user()->role == 'user'){
            $message = Message::where('title',$value)->where('user_id',auth()->user()->id)->first();
        }else{
            $message = Message::where('id',$value)->first();
        }
        if ( $message != null){
            session()->put('messageClick',$message);
        }else{
            return back();
        }
        return back();
    }

    public function reply_ticket(ReplyRequestMessage $request,Message $message)
    {
        Message::create([
           'user_id'=>auth()->user()->id,
            'description'=>$request->newmessage,
            'child'=>$message->id
        ]);
        if (auth()->user()->role == 'admin') {
            session()->flash('success_dashboard', 'پاسخ با موفقیت ارسال شد');
        }else {
            session()->flash('success_dashboard', 'پیام جدید شما با موفقیت ارسال شد');
        }
        session()->put('messageClick',$message);
        return back();
    }
}
