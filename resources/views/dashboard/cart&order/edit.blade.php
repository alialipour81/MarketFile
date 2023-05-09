@extends('layouts.dashboard.fronts')

@section('title','ویرایش خرید')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                       ویرایش خرید
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form  action="{{  route('cart_orders.update',$cart_order->id)  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                            @method('PUT')
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">محصولات  :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="product_id" class="form-control">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                @isset($cart_order)  @if($product->id == $cart_order->product_id) selected @endif @endisset
                                        >{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">کاربر  :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="user_id" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                                @isset($cart_order)  @if($user->id == $cart_order->user_id) selected @endif @endisset
                                        >{{ $user->name }} ({{$user->email}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">کد تخفیف:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="discount" class="form-control col-md-7 col-xs-12" placeholder="کدتخفیف را بنویسید (اجباری نیست)"
                                       value="{{ $cart_order->discount }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">شناسه پرداخت :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="code_order" class="form-control col-md-7 col-xs-12" placeholder="شناسه پرداخت را بنویسید "
                                       value="{{ $cart_order->code_order }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">پرداخت نهایی  :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="price_final" class="form-control col-md-7 col-xs-12" placeholder=" قیمت را بنویسید (به تومان) "
                                       value="{{ $cart_order->price_final }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت پرداخت :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="status" class="form-control">
                                    @foreach($statuses as $key=>$item)
                                        <option value="{{ $key }}"
                                                @isset($cart_order)  @if($key == $cart_order->status) selected @endif @endisset
                                        >{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">ویرایش خرید</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
