@extends('layouts.dashboard.fronts')

@section('title','خرید وفروش')

@section('content')

    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>خرید وفروش
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($cart_orders->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('cart_orders.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="عنوان محصول @if(auth()->user()->role == 'admin')یا تخفیف @endif یا شناسه پرداخت یا ایمیل خریدار را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">عملیات</th>
                             <th class="column-title">خریدار</th>
                                <th class="column-title">  محصول</th>
                                <th class="column-title">  تصویر محصول</th>
                                <th class="column-title">تخفیف</th>
                                <th class="column-title">   شناسه پرداخت </th>
                                <th class="column-title">   پرداخت نهایی </th>
                                <th class="column-title">    وضعیت پرداخت </th>
                                @if(auth()->user()->role == 'admin')
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                                @endif
                                <th class="column-title"> تاریخ رویداد</th>

                            </tr>
                            </thead>

                            <tbody>
                            @forelse($cart_orders as $key=>$cart_order)
                            <tr class="even pointer ">
                                <td>{{ $key+1 }}</td>
                                <td>
                                @if($cart_order->user_id == auth()->user()->id)
                                    خرید محصول توسط شما
                                    @elseif($cart_order->product->store->user_id == auth()->user()->id)
                                    فروش محصول شما
                                    @else
                                    فروش محصول کاربر
                                    @endif
                                </td>
                                <td data-toggle="tooltip" data-placement="top" title="{{ $cart_order->user->name }}">{{ $cart_order->user->email }}</td>
                                <td><a href="{{ route('product',$cart_order->product->slug) }}" target="_blank">{{ $cart_order->product->title }}</a></td>
                                <td @if(auth()->user()->role == 'admin') data-toggle="tooltip" data-placement="top" title="{{ ' ایجادشده توسط : '.$cart_order->product->user->email }}" @endif>
                                    <img src="{{ asset('storage/'.$cart_order->product->image1) }}" alt="{{$cart_order->product->title}}" width="60px" height="40px" class="shadow rounded">
                                </td>
                                <td>
                                    @if($cart_order->discount != null)
                                        @if($cart_order->discountt != null)
                                    <button data-toggle="modal" data-target="#discount{{$key}}" class="btn btn-secondary btn-sm">جزئیات</button>
                                            <div class="modal" id="discount{{$key}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <span class="font-large"> جزئیات تخفیف</span>
                                                            <button type="button" class="close mtap-5"  data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                             <thead>
                                                             <tr>
                                                                 <th>نام (کدتخفیف)</th>
                                                                 <th>درصد کسر</th>
                                                                 <th>حداقل خرید</th>
                                                                 <th> توضیحات</th>
                                                             </tr>
                                                             </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>{{ $cart_order->discountt->name }}</td>
                                                                    <td>{{ $cart_order->discountt->parcent_kasr }}</td>
                                                                    <td>
                                                                        @if( $cart_order->discountt->price == null)
                                                                            ندارد
                                                                        @else
                                                                            {{ number_format( $cart_order->discountt->price).' تومان ' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{!!  $cart_order->discountt->description !!}</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{ $cart_order->discount.' درصد ' }}
                                        @endif
                                    @else
                                        <span >بدون تخفیف</span>
                                    @endif
                                </td>
                                <td>{{ $cart_order->code_order }}</td>
                                <td>{{ number_format($cart_order->price_final).' تومان ' }}</td>
                                <td>
                                    @if($cart_order->status == 1)
                                    <span class="badge badge-success bg5">پرداخت موفق</span>
                                    @else
                                        <span class="badge badge-success bg4">پرداخت ناموفق</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role == 'admin')
                                <td >
                                    <a href="{{ route('cart_orders.edit',$cart_order->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('cart_orders.destroy',$cart_order->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                                @endif
                                <td>{{ $cart_order->created_at->diffForHumans() }}</td>

                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا خریدوفروشی  صورت نگرفته است:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $cart_orders->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
