@extends('layouts.dashboard.fronts')

@section('title','کاربران')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>کاربران
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
                        @if($users->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('users.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام یا ایمیل یا نقش   را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">ایمیل</th>
                                <th class="column-title">وضعیت ایمیل</th>
                                <th class="column-title">نقش </th>
                                <th class="column-title">تصویر پروفایل </th>
                                <th class="column-title"> تاریخ ایجاد</th>
                                <th class="column-title">  آخرین بروزرسانی</th>
                                <th class="column-title">فروش</th>
                                <th class="column-title">خرید</th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($users as $user)
                                @php
                                $kharid = [];
                                $foroosh = [];
                                $cart_orders = \App\Models\Cart::where('status',1)->orderBy('id', 'desc')->get();
                                foreach ($cart_orders as $cart_order){
                                if ($cart_order->user_id == $user->id){
                                array_push($kharid,$cart_order->price_final);
                                }
                                if ($cart_order->product->store->user_id == $user->id){
                                array_push($foroosh,$cart_order->price_final);
                                }
                                }
                                @endphp
                            <tr class="even pointer ">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->email_verified_at == null)
                                        <span class="bg4 badge badge-danger">تایید نشده  </span>
                                    @else
                                        <span class="bg5 badge badge-success" >{{verta($user->email_verified_at)->format('Y/m/d H:i:s')}}   </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="bg3  badge badge-warning" title="admin">ادمین   </span>
                                    @else
                                        <span class="bg6 badge badge-success" title="user"> کاربر  </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->image == null)
                                        <span >تنظیم نشده</span>
                                    @else
                                        <img src="{{ asset('storage/'.$user->image) }}" alt="{{$user->name}}" width="60px" height="40px" class="shadow rounded">
                                    @endif
                                </td>
                                <td>{{ $user->created_at->diffForhumans() }} </td>
                                <td>{{ $user->updated_at->diffForhumans() }} </td>
                                <td>{{ number_format(array_sum($foroosh)).' تومان ' }}</td>
                                <td>{{ number_format(array_sum($kharid)).' تومان ' }}</td>
                                <td >
                                    <a href="{{ route('users.edit',$user->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('users.destroy',$user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا کاربری ثبت نام نکرده است:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
