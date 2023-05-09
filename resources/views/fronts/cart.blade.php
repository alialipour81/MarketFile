@extends('layouts.fronts')
@section('title','سبد خرید')
@section('content')
    <main class="cart-page default">
        <div class="container">
            <div class="row">
                <div class="cart-page-content col-xl-9 col-lg-8 col-md-12 order-1">
                    <div class="cart-page-title">
                        <h1>   سبد خرید</h1>
                    </div>
                    <div class="table-responsive checkout-content default">
                        @include('layouts.message')
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <script>
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 5000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        icon: 'error',
                                        title: '{{ $error }}'
                                    })
                                </script>
                            @endforeach
                        @endif
                        <table class="table">
                            <tbody>
                            @forelse($cart as $value)
                            <tr class="checkout-item">
                                <td>
                                    <img src="{{ asset('storage/'.$value->product->image1) }}" alt="{{ $value->product->title }}" width="100px" height="80px">
                                    <form action="{{ route('cart.destroy',$value->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="checkout-btn-remove btn-outline-danger mr-2"></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('product',$value->product->slug) }}" data-toggle="tooltip" title="{{ $value->product->title }}" class="checkout-title">
                                        {{ $value->product->title }}
                                    </a>
                                </td>
                                <td>
                                    فروشنده :  <a data-toggle="tooltip" title="{{ $value->product->store->name }}" class="btn-link-border " href="{{ route('products','store='.$value->product->store->name) }}">{{ $value->product->store->name }}</a>
                                </td>
                                <td>
                                    <div class="price-value   price-discount  bg-success ">
                                        <span>
                                            @if($value->product->new_price !=0)
                                                {{ number_format($value->product->new_price) }}
                                            @else
                                                {{ number_format($value->product->price) }}
                                            @endif
                                            تومان
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <div class="cart-empty">
                                    <div class="cart-empty-icon">
                                        <i class="now-ui-icons shopping_basket"></i>
                                    </div>
                                    <div class="cart-empty-title">سبد خرید شما خالیست!</div>
                                    <div class="parent-btn">
                                        <a href="{{ route('index') }}" class="dk-btn dk-btn-success">
                                            بازگشت به صفحه اصلی
                                            <i class="fa fa-location-arrow"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($cart->count())
                    <section class="page-content default">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="checkout-price-options">
                                    <div class="checkout-price-options-form">
                                        <section class="checkout-price-options-container">
                                            <div class="checkout-price-options-header">
                                                <span>استفاده از کد تخفیف </span>
                                            </div>
                                            <div class="checkout-price-options-content">
                                                <p class="checkout-price-options-description">
                                                    با ثبت کد تخفیف، درصد کد تخفیف از “مبلغ قابل پرداخت” کسر می‌شود.
                                                </p>
                                                <form action=" {{ route('validate.discount','discount') }}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                <div class="checkout-price-options-row d-flex align-items-center justify-content-center">
                                                        <div class="checkout-price-options-form-field mt-2">
                                                            <label class="ui-input">
                                                                <input class="ui-input-field " name="code_discount" type="text"
                                                                       placeholder="مثلا 837A2CS" @if(session()->has('discount_active')) value="{{ session()->get('discount_active') }}" @endif>
                                                            </label>
                                                        </div>
                                                        <div class="checkout-price-options-form-button">
                                                            <button type="submit" class="btn  btn-sef1 hov py-3" @if(session()->has('discount_active')) name="canceldiscount" @else name="setdiscount" @endif>
                                                                @if(session()->has('discount_active'))
                                                                لغو کد تخفیف
                                                                @else
                                                                اعمال کد تخفیف
                                                                @endif
                                                            </button>
                                                        </div>
                                                </div>
                                                </form>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    @endif
                </div>




                <aside class="cart-page-aside col-xl-3 col-lg-4 col-md-6 center-section order-2">
                    <div class="checkout-aside">
                        <div class="checkout-summary">
                            <div class="checkout-summary-main">

                                <div class="checkout-summary-content">
                                    @if($price_final != 0)
                                    <div class="checkout-summary-price-title">مبلغ قابل پرداخت:</div>
                                    <div class="checkout-summary-price-value">
                                        <span class="checkout-summary-price-value-amount">{{ number_format($price_final) }}</span>تومان
                                    </div>
                                        <form action="{{ route('zarinpal.request') }}" method="post">
                                            @csrf
                                            <div  class="selenium-next-step-shipping">
                                                <div class="parent-btn">
                                                    <button class="dk-btn btn-sef1">
                                                        خرید وپرداخت نهایی
                                                        <i class="now-ui-icons shopping_credit-card"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <span class="badge badge-danger empty-cart  p-3 my-3 font-14">فعلا محصولی موجود نیست</span>
                                    @endif
                                    <div>
                                            <span>
                                                محصولات موجود در سبد شما  رزرو شده‌اند، برای دریافت فایل دانلود باید مبلغ آن را پرداخت کنید
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(session()->has('discount_active'))
                        <div class="checkout-feature-aside">
                            <div class="mb-3 text-center alert rounded alert-sef1">
                                <span class="  font-14 my-1">تخفیف فعال است</span><br>
                                <span class="  font-14 my-1"> درصد تخفیف : {{'%'.$discount->parcent_kasr}} </span><br>
                                <span class="  font-14 my-1"> حداقل مبلغ خرید :
                                @if($discount->price == null)
                                    دلخواه
                                    @else
                                    {{ number_format($discount->price).' تومان ' }}
                                    @endif
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="mt-2 font-14 text-dark">توضیحات تخفیف :</span>
                                <p class="text-right text-justify text-dark ">
                                    {!! $discount->description !!}
                                </p>
                            </div>

                        </div>
                        @endif

                    </div>
                </aside>
            </div>
        </div>
    </main>
@endsection
