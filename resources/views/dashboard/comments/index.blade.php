@extends('layouts.dashboard.fronts')

@section('title','نظرات کاربران ')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('layouts.message2')
            <div class="x_panel">
                <div class="x_title">
                    <h2>نظرات کاربران
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    <div class="table-responsive">
                        @if($comments->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('comments.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="بخشی از نظر یا نام محصول یا ایمیل کاربر  را  بنویسید" value="{{ request()->query('search') }}">
                                        <span class="input-group-btn">
                      <button class="btn btn-default btn-search" type="submit">جستجو</button>
                    </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        <table class="table table-striped jambo_table bulk_action small">
                            <thead>
                            <tr class="headings">
                                <th class="column-title">آیدی</th>
                                <th class="column-title">محصول</th>
                                <th class="column-title">کاربر</th>
                                <th class="column-title">متن نظر</th>
                                <th class="column-title">امتیاز</th>
                                <th class="column-title">وضعیت</th>
                                <th class="column-title">  تاریخ ایجاد </th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($comments as $key=>$comment)
                            <tr class="even pointer ">
                                <td>{{ $comment->id }}</td>
                                <td>
                                    <a href="{{ route('product',$comment->product->slug) }}" target="_blank">{{ $comment->product->title }}</a>
                                </td>
                                <td  data-toggle="tooltip" data-placement="top" title="{{ $comment->user->name }}">{{ $comment->user->email }}
                                @if($comment->user->role == 'admin') (ادمین)@elseif($comment->user->role == 'user') (کاربر) @endif
                                </td>
                                <td>
                                    {!! nl2br(\Illuminate\Support\Str::limit($comment->comment,40)) !!}
                                </td>
                                <td>
                                    @if($comment->child == null)
                                    @for($j=1;$j<=$comment->star;$j++)
                                        <i class="fa fa-star text-warning gold"></i>
                                    @endfor
                                    @else
                                        <span class="badge badge-info col-sef1">پاسخ است</span>
                                    @endif
                                </td>
                                <td>
                                    @if($comment->status == 0)
                                        <span class="badge badge-danger bg4">عدم نمایش</span>
                                    @else
                                        <span class="badge badge-success bg5"> نمایش</span>
                                    @endif
                                </td>
                                <td>{{ $comment->created_at->diffForhumans() }} </td>
                                <td >
                                    <a  data-target="#edit{{$key}}" data-toggle="modal" class="btn btn-info btn-sm">ویرایش</a>
                                    <div class="modal" id="edit{{$key}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <span>ویرایش نظر {{$comment->user->name}}</span>
                                                    <a type="button" class="close mx-lg-auto"  data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true" class="text-dark">&times;</span>
                                                    </a>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('comments.update',$comment->id)  }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="1">متن نظر</label>
                                                            <textarea name="comment" id="1" rows="4" class="form-control">{{$comment->comment}}</textarea>
                                                        </div>
                                                        @if($comment->star != null)
                                                            <div class="form-group">
                                                                <label for="2">امتیاز</label>
                                                                <select name="star" id="2" class="form-control">
                                                                    <option value="1" @if($comment->star == 1) selected @endif>
                                                                        یک ستاره
                                                                    </option>
                                                                    <option value="2" @if($comment->star == 2) selected @endif>
                                                                        دو ستاره
                                                                    </option>
                                                                    <option value="3" @if($comment->star == 3) selected @endif>
                                                                        سه ستاره
                                                                    </option>
                                                                    <option value="4" @if($comment->star == 4) selected @endif>
                                                                        چهار ستاره
                                                                    </option>
                                                                    <option value="5" @if($comment->star == 5) selected @endif>
                                                                        پنج ستاره
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <label for="3">وضعیت نمایش </label>
                                                            <select name="status" id="3" class="form-control">
                                                                @foreach($status as $key=>$value)
                                                                    <option value="{{$key}}"
                                                                            @if($key == $comment->status) selected @endif
                                                                    >{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="submit" @if($comment->child == null) name="commentadi" @else name="reply"  @endif class="btn btn-secondary btn-block btn-desc">ویرایش</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <form method="post" action="{{ route('comments.destroy',$comment->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا نظری  وجود ندارد:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $comments->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
