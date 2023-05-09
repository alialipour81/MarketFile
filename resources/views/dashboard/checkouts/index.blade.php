@extends('layouts.dashboard.fronts')

@section('title','تسویه حساب')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>تسویه حساب
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="mrr-5">
                            <a data-toggle="modal" data-target="#question" class="btn-add btn-sm ">  راهنما <i class="fa fa-question-circle"></i></a>

                        </li>
                        <li class="ml-3">
                            <a data-toggle="modal" data-target="#checkout" class="btn-add btn-sm ">ایجاد درخواست تسویه <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="modal" id="checkout">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="font-large"> ایجاد درخواست تسویه</span>
                                <button type="button" class="close mtap-5"  data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                    <div class="x_panel">
                                        <div class="x_content">
                                            <br/>
                                            @if($myDaramadWihoutformat >= 5000)
                                            <form class="form-horizontal form-label-left" method="post" action="{{ route('checkouts.store') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-3"> نام ونام خانوادگی:</label>
                                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="نام ونام خانوادگی متعلق به شماره کارت را بنویسید">
                                                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-3">شماره کارت :</label>
                                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                                        <input type="text" class="form-control" name="cardnumber" value="{{ old('cardnumber') }}" placeholder="شماره کارت باید 16 رقم باشد">
                                                        <span class="fa fa-credit-card form-control-feedback left" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-3">قیمت (به تومان) :</label>
                                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                                        <input type="text" class="form-control" name="price" value="{{ old('price') }}" placeholder="قیمت باید بدون کاراکتر وارد شود برای مثال ; 30000 ">
                                                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-9 col-md-offset-3">
                                                        <button type="submit" class="btn btn-success btn-block">ثبت درخواست</button>
                                                    </div>
                                                </div>


                                            </form>
                                            @else
                                                <div class="alert alert-info">برای ثبت درخواست تسویه باید درامد شما بزرگتر از 5,000 تومان باشد </div>
                                            @endif
                                            <span class="price-checkout">قیمت قابل برداشت(درامد) : {{$myDaramad.' تومان '}}</span>

                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal" id="question">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="font-large">   راهنمای تسویه حساب</span>
                                <button type="button" class="close mtap-5"  data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">


                                            <br/>
                                            <ul>
                                                <li>
                                                    <div class="alert alert-info ">
                                                        <p class="text-justify">وقتی شما یک درخواست تسویه ثبت میکنید مبلغ آن درخواست از درامد شما کسر میشود و اگر این درخواست رد شده باشد مبلغ آن به حساب شما بر میگردد</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="alert alert-info ">
                                                        <p class="text-justify">برای ثبت درخواست تسویه حداقل درامد شما باید 5,000 تومان باشد</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="alert alert-info ">
                                                        <p class="text-justify">اگر وضعیت درخواست ' رد شده' یا 'واریز شده' باشد امکان ویرایش آن وجود ندارد </p>
                                                    </div>
                                                </li>
                                            </ul>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($checkouts->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('checkouts.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="کد رهگیری یا نام ونام خانوادگی یا شماره کارت یا قیمت یا تاریخ واریز @if(auth()->user()->role == 'admin') یا ایمیل کاربر  @endif را میتوانید جست وجو کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">کدرهگیری</th>
                                <th class="column-title">نام ونام خانوادگی</th>
                                <th class="column-title"> شماره کارت </th>
                                <th class="column-title">قیمت</th>
                                <th class="column-title"> تاریخ درخواست واریز</th>
                                <th class="column-title"> تاریخ  واریز</th>
                                <th class="column-title">وضعیت</th>
                                @if(auth()->user()->role == 'admin')
                                <th class="column-title">کاربر</th>
                                @endif
                                <th class="column-title">ویرایش</th>
                                @if(auth()->user()->role == 'admin')
                                <th class="column-title">حذف</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($checkouts as $key=>$checkout)
                                @php
                                    $my_daramad = \App\Http\Controllers\dashboard\CheckoutsController::myDaramad($checkout->user_id);
                                    $price = \App\Http\Controllers\dashboard\CheckoutsController::myCheckout($checkout->user_id);
                                    $myDaramad = number_format( array_sum($my_daramad) - array_sum($price));

                                @endphp
                            <tr class="even pointer ">
                                @if(auth()->user()->role == 'user')
                                <td>{{ $key+1 }}</td>
                                @else
                                <td>{{ $checkout->id }}</td>
                                @endif
                                <td>{{ $checkout->tracking_code }}</td>
                                <td>{{ $checkout->name_cardnumber }}</td>
                                <td>{{ $checkout->cardnumber }}</td>
                                <td>{{ number_format($checkout->price).' تومان ' }}</td>
                                <td>{{ $checkout->created_at->diffForhumans() }} </td>
                                <td>@if(!empty($checkout->variz)) {{ $checkout->variz }}@endif</td>
                                <td>
                                    @if($checkout->status == 0)
                                        <span class="badge badge-secondary bg3">در انتظار واریز </span>
                                    @elseif($checkout->status == 1)
                                        <span class="badge badge-danger bg4"> رد شده</span>
                                    @else
                                        <span class="badge badge-success bg5"> واریز شده</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role == 'admin')
                                    <td>
                                        <span data-toggle="tooltip" title="{{$checkout->user->name}}">{{ $checkout->user->email }}</span>
                                    </td>
                                @endif
                                <td >
                                    <a data-toggle="modal" data-target="#up-checkout{{$key+1}}" class="btn btn-info btn-sm">ویرایش</a>
                                    <div class="modal" id="up-checkout{{$key+1}}" >
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <span class="font-large"> ویرایش درخواست تسویه
                                                    @if(auth()->user()->role == 'admin')
                                                        ({{$checkout->user->email}})
                                                        @endif
                                                    </span>
                                                    <button type="button" class="close mtap-5"  data-dismiss="modal" aria-hidden="true">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="x_panel">
                                                        <div class="x_content">
                                                            <br/>
                                                            @if(auth()->user()->role == 'admin' || $checkout->status == 0)
                                                                <form class="form-horizontal form-label-left" method="post" action="{{ route('checkouts.update',$checkout->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-3"> نام ونام خانوادگی:</label>
                                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                                            <input type="text" class="form-control" name="name" value="{{ $checkout->name_cardnumber }}" placeholder="نام ونام خانوادگی متعلق به شماره کارت را بنویسید">
                                                                            <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">شماره کارت :</label>
                                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                                            <input type="text" class="form-control" name="cardnumber" value="{{ $checkout->cardnumber }}" placeholder="شماره کارت باید 16 رقم باشد">
                                                                            <span class="fa fa-credit-card form-control-feedback left" aria-hidden="true"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">قیمت (به تومان) :</label>
                                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                                            <input type="text" class="form-control" name="price" value="{{ $checkout->price }}" placeholder="قیمت باید بدون کاراکتر وارد شود برای مثال ; 30000 ">
                                                                            <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                                                                        </div>
                                                                    </div>
                                                                    @if(auth()->user()->role == 'admin')
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">کدرهگیری :</label>
                                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                                            <input type="text" class="form-control" name="tracking_code" value="{{ $checkout->tracking_code }}" placeholder="کدرهگیری را بنویسید ">
                                                                            <span class="fa fa-barcode form-control-feedback left" aria-hidden="true"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">تاریخ واریز :</label>
                                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                                            <input  type="text" id="date" class="form-control" name="variz"
                                                                                    @if(!empty($checkout->variz))
                                                                                    value="{{ $checkout->variz }}"
                                                                                    @else
                                                                                    value="{{ verta()->format('Y-m-d H:i:s') }}"
                                                                                    @endif
                                                                                    placeholder="تاریخ واریز را وارد کنید ">
                                                                            <span class="fa fa-calendar-times-o form-control-feedback left" aria-hidden="true"></span>
                                                                        </div>
                                                                    </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3 col-sm-3 col-xs-3">وضعیت تسویه :</label>
                                                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                                                <select name="status" class="form-control">
                                                                                    @foreach($status as $key=>$value)
                                                                                        <option value="{{$key}}"
                                                                                        @if($key == $checkout->status)selected @endif
                                                                                        >{{ $value }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div class="form-group">
                                                                        <div class="col-md-9 col-md-offset-3">
                                                                            <button type="submit" class="btn btn-success btn-block">ویرایش درخواست</button>
                                                                        </div>
                                                                    </div>


                                                                </form>
                                                            @elseif($checkout->status == 1 || $checkout->status == 2)
                                                                <div class="alert alert-info">امکان ویرایش وجود ندارد </div>
                                                            @endif
                                                            <span class="price-checkout">قیمت قابل برداشت(درامد) : {{$myDaramad.' تومان '}}</span>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @if(auth()->user()->role == 'admin')
                                <td>
                                    <form method="post" action="{{ route('checkouts.destroy',$checkout->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا درخواستی ثبت نکرده اید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $checkouts->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
