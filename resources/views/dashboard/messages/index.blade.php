@extends('layouts.dashboard.fronts')

@section('title','تیکت ها')

@section('content')
    @include('layouts.message')
    @include('layouts.message2')
    <div class="right_col" role="main">
        <div class="">

            <div class="page-title">
                <div class="title_left">
                    <h3>تیکت ها
                    </h3>
                </div>

                <div class="title_right">
                    <form action="{{ route('messages.index') }}" method="get">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="@if(auth()->user()->role == 'admin')ایمیل یا@endif عنوان تیکت را بنویسید" name="search" value="{{ request()->query('search') }}">
                                <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">جستجو</button>
                    </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>تیکت ها
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            @php
                                if(session()->has('messageClick')){
                                  $messageSes = session()->get('messageClick');
                                  }
                            @endphp
                            <div class="row">
                                <div class="col-sm-3 mail_list_column">
                                    <button id="compose" class="btn btn-sm btn-success btn-block" type="button">ایجاد تیکت جدید
                                    </button>
                                    @foreach($messages as $message)
                                    <a
                                    @if(auth()->user()->role == 'admin')
                                        href="{{ route('messages.clickMessage',$message->id) }}"
                                    @else
                                        href="{{ route('messages.clickMessage',$message->title) }}"
                                        @endif
                                    >
                                        <div class="mail_list @if(@$messageSes->id == $message->id) select-message  @endif">
                                            <div class="right ">
                                                @if(@$messageSes->id != $message->id)
                                                    <i class="fa fa-circle "></i>
                                                <i class='fa fa-envelope colsef1'></i>
                                                @endif
                                            </div>
                                            <div class="left">
                                                <h3 data-toggle="tooltip" title="{{ $message->title }}" data-placement="top">{{ \Illuminate\Support\Str::limit($message->title,18) }}
                                                    <small>{{ $message->created_at->diffForHumans() }}</small>
                                                </h3>
                                                <p class="text-justify">{{ $message->user->email }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                </div>
                                <!-- /MAIL LIST -->

                                <!-- CONTENT MAIL -->

                                <div class="col-sm-9 mail_view ">
                                    <div class="inbox-body" dir="rtl">
                                        @if($messages->count())
                                        @if(session()->has('messageClick'))
                                            <!-- Contenedor Principal -->
                                            <div class="comments-container">
                                                <ul id="comments-list" class="comments-list" >
                                                    <li >
                                                        <div class="comment-main-level">
                                                            <!-- Avatar -->
                                                            <div class="comment-avatar" dir="rtl">
                                                                @if($messageSes->user->image == null)
                                                                <img src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get($messageSes->user->email) }}" alt="{{ $messageSes->user->name }}">
                                                                @else
                                                                <img src="{{ asset('storage/'.$messageSes->user->image) }}" alt="{{ $messageSes->user->name }}">
                                                                @endif
                                                            </div>
                                                            <!-- Contenedor del Comentario -->
                                                            <div class="comment-box">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name text-pink">
                                                                        @if(auth()->user()->id == $messageSes->user->id)
                                                                            شما
                                                                        @else
                                                                            {{ $messageSes->user->name }}
                                                                        @endif
                                                                    </h6>
                                                                    <span class="m-top5 ml-8">{{ $messageSes->created_at->diffForHumans() }}</span>
                                                                    <div class="d-flexx">
                                                                        <button data-toggle="modal" data-target="#reply" class="btn btn-sm btn-primary btn-reply1" type="button"><i
                                                                                class="fa fa-reply"></i>
                                                                            @if(auth()->user()->role == 'user')
                                                                                ارسال پیام جدید
                                                                            @else
                                                                                پاسخ
                                                                            @endif
                                                                        </button>
                                                                        @if(auth()->user()->role == 'admin')
                                                                            <a href="{{ route('messages.edit',$messageSes->id) }}" class="btn btn-sm btn-dark btn-reply1 " >ویرایش</a>
                                                                            <form action="{{ route('messages.destroy',$messageSes->id) }}" method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button class="btn btn-danger  btn-sm" type="submit">حذف</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="comment-content text-justify">{!! $messageSes->description !!}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Respuestas de los comentarios -->
                                                        @if($messageSes->replies->count())
                                                        <ul class="comments-list reply-list">
                                                            @foreach($messageSes->replies as $key=>$reply)
                                                            <li>
                                                                <!-- Avatar -->
                                                                <div class="comment-avatar">
                                                                    @if($reply->user->image == null)
                                                                        <img src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get($reply->user->email) }}" alt="{{ $reply->user->name }}">
                                                                    @else
                                                                        <img src="{{ asset('storage/'.$reply->user->image) }}" alt="{{ $reply->user->name }}">
                                                                    @endif
                                                                </div>
                                                                <!-- Contenedor del Comentario -->
                                                                <div class="comment-box">
                                                                    <div class="comment-head ">
                                                                        <h6 class="comment-name text-pink">
                                                                            @if(auth()->user()->id == $reply->user->id)
                                                                                شما
                                                                            @else
                                                                                {{ $reply->user->name }}
                                                                            @endif
                                                                        </h6>
                                                                        <span class="m-top5 ml-8">{{ $reply->created_at->diffForHumans() }}</span>
                                                                        @if(auth()->user()->role == 'admin')
                                                                          <div class="d-flexx">
                                                                                  <a href="{{ route('messages.edit',$reply->id) }}" class="btn btn-sm btn-dark btn-reply1 font-size-8" >ویرایش</a>
                                                                              <form action="{{ route('messages.destroy',$reply->id) }}" method="post">
                                                                                  @csrf
                                                                                  @method('DELETE')
                                                                                  <button class="btn btn-danger btn-sm font-size-8" type="submit">حذف</button>
                                                                              </form>
                                                                          </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="comment-content text-justify">{!! $reply->description !!}</div>
                                                                </div>
                                                            </li>

                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                    </li>


                                                </ul>
                                            </div>
                                        @else
                                            <div class="mail_heading row">
                                                <div class="cart-empty">
                                                    <div class="cart-empty-icon">
                                                        <i class="fa fa-eye"></i>
                                                    </div>
                                                    <div class="cart-empty-title">برای مشاهده روی یک تیکت کلیک کنید</div>

                                                </div>
                                            </div>
                                        @endif
                                        @else
                                            <div class="mail_heading row">
                                                <div class="cart-empty">
                                                    <div class="cart-empty-icon">
                                                        <i class="fa fa-book"></i>
                                                    </div>
                                                    <div class="cart-empty-title">تیکتی موجود نیست</div>

                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                                @if(session()->has('messageClick'))
                                <div class="modal" id="reply" >
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header d-flexx">
                                                                                    <span class="font-large">
                                                                                            @if(auth()->user()->role == 'user')
                                                                                            ارسال پیام جدید به
                                                                                        @else
                                                                                            پاسخ به تیکت
                                                                                        @endif
                                                                                        {{ \Illuminate\Support\Str::limit(@$messageSes->title,30) }}</span>
                                                <button type="button" class="close mtap-5 mr-autoo"  data-dismiss="modal" aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body" >
                                                <form action="{{ route('messages.reply_ticket',@$messageSes->id) }}" method="post">
                                                    @csrf
                                                    <div class="compose-body "><br>


                                                        <textarea  id="reply-message" name="newmessage" rows="5" class="form-control col-md-7 col-xs-12 f-iran" placeholder="پاسخی بنوییسید"> </textarea>
                                                        <script>
                                                            CKEDITOR.replace('reply-message',{
                                                                language:'fa',
                                                                filebrowserUploadUrl : "{{ route('messages.uploadImageCKeditor',['_token'=>csrf_token()]) }}",
                                                                filebrowserUploadMethod : 'form',
                                                            });
                                                        </script>

                                                    </div>

                                                    <div class="compose-footer">
                                                        <button id="send" class="btn-reply2 btn-block btn-sm " type="submit">
                                                            @if(auth()->user()->role == 'user')
                                                                ارسال پیام جدید
                                                            @else
                                                                ارسال پاسخ
                                                            @endif
                                                        </button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- /CONTENT MAIL -->
                                {{ $messages->links() }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    <!-- compose -->
    <div class="compose col-md-6 col-xs-12">
        <div class="compose-header">
            تیکت جدید
            <button type="button" class="close compose-close">
                <span>×</span>
            </button>
        </div>
        <form action="{{ route('messages.store') }}" method="post">
            @csrf
        <div class="compose-body "><br>
            <div class="form-group">
                <label for="1">عنوان :</label>
                <input type="text" id="1" name="title" class="form-control " placeholder="عنوان را بنویسید">
            </div>
            <div class="form-group">
                <label for="send-message">توضیحات:</label>
                    <textarea  id="send-message" name="description" rows="4" class="form-control col-md-7 col-xs-12 f-iran"  placeholder="توضیحاتی بنوییسید"> </textarea>
                    <script>
                        CKEDITOR.replace('send-message',{
                            language:'fa',
                            filebrowserUploadUrl : "{{ route('messages.uploadImageCKeditor',['_token'=>csrf_token()]) }}",
                            filebrowserUploadMethod : 'form',
                        });
                    </script>
            </div>

        </div>

        <div class="compose-footer">
            <button id="send" class="btn btn-sm btn-success" type="submit">ارسال</button>
        </div>

        </form>

    </div>
@endsection
@section('link_css')
    <link rel="stylesheet" href="{{ asset('dashboard/comment-style.css') }}">
@endsection
