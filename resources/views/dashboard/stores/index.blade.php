@extends('layouts.dashboard.fronts')
@php
if (auth()->user()->role == 'admin'){
    $title = "همه فروشگاه ها";
}else{
    $title = "فروشگاه های شما";
}
@endphp
@section('title',$title)

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ $title }}
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('stores.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($stores->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('stores.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام فروشگاه را وارد کنید" value="{{ request()->query('search') }}">
                                        <span class="input-group-btn">
                      <button class="btn btn-default btn-search" type="submit">جستجو</button>
                    </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th class="column-title">آیدی</th>
                                <th class="column-title">نام</th>
                                <th class="column-title"> لوگو</th>
                                <th class="column-title"> توضیحات</th>
                                @if(auth()->user()->role == 'admin')
                                <th class="column-title"> کاربر</th>
                                @endif
                                <th class="column-title">تاریخ ایجاد</th>
                                <th class="column-title">تاریخ آخرین بروزرسانی</th>
                                <th class="column-title">وضعیت</th>
                                <th class="column-title">تیک آبی</th>
                                <th class="column-title">ویرایش</th>
                                @if(auth()->user()->role == 'admin')
                                <th class="column-title">حذف</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($stores as $key=>$store)
                            <tr class="even pointer ">
                               <td>{{ $store->id }}</td>
                               <td>{{ $store->name }}</td>
                               <td>
                                   <img src="{{ asset('storage/'.$store->image) }}" width="60px" height="40px" class="rounded shadow">
                               </td>
                               <td>
                                   <button class="btn btn-secondary btn-sm btn-desc" data-toggle="modal" data-target="#desc{{$key}}">مشاهده</button>
                                   <div class="modal" id="desc{{$key}}">
                                       <div class="modal-dialog">
                                           <div class="modal-content">
                                               <div class="modal-body">
                                                   {!! nl2br($store->description) !!}
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </td>
                                @if(auth()->user()->role == 'admin')
                                    <td  data-toggle="tooltip" data-placement="top" title="{{ $store->user->name }}">{{ $store->user->email}}</td>
                                @endif
                                <td>{{ $store->created_at->diffForhumans() }} </td>
                                <td>{{ $store->updated_at->diffForhumans() }} </td>
                                <td>
                                    @if($store->status == 0)
                                        <span class="bg3  badge badge-warning">در حال بررسی</span>
                                    @elseif($store->status == 1)
                                        <span class="bg4 badge badge-danger">ردشده  </span>
                                    @else
                                        <span class="bg5 badge badge-success">پذیرفته شده  </span>
                                    @endif
                                </td>
                                <td>
                                    @if($store->bluetick == 'no')
                                        <span class="bg6  badge badge-warning">غیر فعال</span>
                                    @else
                                        <span class="bg7 badge badge-success"> فعال  </span>
                                    @endif
                                </td>
                                <td >
                                    <a href="{{ route('stores.edit',$store->name) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                @if(auth()->user()->role == 'admin')
                                <td>
                                    <form method="post" action="{{ route('stores.destroy',$store->name) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا فروشگاهی اضافه نشده است:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $stores->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
