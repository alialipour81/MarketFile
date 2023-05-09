@extends('layouts.fronts')
@section('title','فروشگاه اینترنتی کمیاب فایل')
@section('content')

    <main class="main default">
        <div class="container">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <script>
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 6000,
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
            @include('layouts.message')
            <!-- banner -->

            <!-- banner -->
            <div class="row">
                <aside class="sidebar col-12 col-lg-3 order-2 order-lg-1">
                    <div class="sidebar-inner default">
                        @if($instantoffers->count())
                        <div class="widget-suggestion widget card">
                            <header class="card-header">
                                <h3 class="card-title">پیشنهاد لحظه ای</h3>
                            </header>
                            <div id="progressBar">
                                <div class="slide-progress"></div>
                            </div>
                            <div id="suggestion-slider" class="owl-carousel owl-theme">
                                @foreach($instantoffers as $offer)
                                    <div class="item">
                                        <a href="{{ route('product',$offer->product->slug) }}">
                                            <img src="{{ asset('storage/'.$offer->product->image1) }}" class="img-fluid" alt="{{ $offer->product->title }}">
                                        </a>
                                        <h2 class="post-title mt-2 ">
                                            <a href="{{ route('product',$offer->product->slug) }}" class="font-l " >{{ $offer->product->title }}</a>
                                        </h2>
                                        <div class="price">
                                            @if($offer->product->new_price != 0)
                                                <div class="text-center">
                                                    <del><span>{{ number_format($offer->product->price) }}<span>تومان</span></span></del>
                                                </div>
                                            @endif
                                            <div class="text-center">
                                                @if($offer->product->price == 0)
                                                    <ins class="t-d-none text-decoration-none"><span>رایگان</span></ins>
                                                @elseif($offer->product->new_price != 0)
                                                    <ins class="t-d-none text-decoration-none"><span>{{ number_format($offer->product->new_price) }}<span>تومان</span></span></ins>
                                                @else
                                                    <ins class="t-d-none text-decoration-none"><span>{{ number_format($offer->product->price) }}<span>تومان</span></span></ins>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-around align-items-center pishnamayesh">
                                            @if($offer->product->price == 0)
                                                <span class="small color1"><i class="fa fa-cloud-download ml-1"></i>دانلودها:{{ $offer->product->CountDownload}}</span>
                                            @else
                                                <span class="small color1"><i class="fa fa-shopping-cart ml-1"></i>تعدادفروش:{{ $offer->product->carts->count() }}</span>
                                            @endif
                                            <span class="small">
                                                @if($offer->product->AverageStarcompro() != 0)
                                                @for($j=1;$j<=floor($offer->product->AverageStarcompro());$j++)
                                                    <i class="fa fa-star text-warning"></i>
                                                @endfor
                                                    <span> {{ $offer->product->AverageStarcompro() }} از 5</span>
                                                @else
                                                    نظری وجود ندارد
                                                @endif
                                                </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                     @foreach($posters as $key=>$poster)
                         @if($key >= 6)
                        <div class="widget-banner widget card">
                            <a href="{{ $poster->link }}" target="_blank">
                                <img class="img-fluid"
                                     src="{{ asset('storage/'.$poster->image) }}"
                                     alt="">
                            </a>
                        </div>
                            @endif
                        @endforeach

                    </div>
                </aside>
                <div class="col-12 col-lg-9 order-1 order-lg-2">
                    @if($sliders->count())
                    <section id="main-slider" class="carousel slide carousel-fade card" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach($sliders as $key=>$slider)
                            <li data-target="#main-slider" data-slide-to="{{ $key }}" class="@if($loop->last) active @endif"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach($sliders as $key=>$slider)
                            <div class="carousel-item @if($loop->last) active @endif">
                                <a class="d-block" href="{{ $slider->link }}">
                                    <img src="{{ asset('storage/'.$slider->image) }}"
                                         class="d-block w-100" alt="">
                                </a>
                            </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#main-slider" role="button" data-slide="prev">
                            <i class="now-ui-icons arrows-1_minimal-right"></i>
                        </a>
                        <a class="carousel-control-next" href="#main-slider" data-slide="next">
                            <i class="now-ui-icons arrows-1_minimal-left"></i>
                        </a>
                    </section>
                    @endif
                    @if($amazings->count())
                    <section id="amazing-slider" class="carousel slide carousel-fade card" data-ride="carousel">
                        <div class="row m-0">
                            <ol class="carousel-indicators pr-0 d-flex flex-column col-lg-3">
                                @foreach($amazings as $key=>$amazing)
                                <li data-target="#amazing-slider" data-slide-to="{{ $key }}">
                                    <span>  {{ $amazing->product->title }}</span>
                                </li>
                                @endforeach
{{--                                <li class="view-all">--}}
{{--                                    <a href="#" class="btn btn-primary btn-block hvr-sweep-to-left">--}}
{{--                                        <i class="fa fa-arrow-left"></i>مشاهده همه شگفت انگیزها--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                            </ol>
                            <div class="carousel-inner p-0 col-12 col-lg-9">
                                <img class="amazing-title" src="{{ asset('assets/img/amazing-slider/amazing-title-01.png') }}">
                                @foreach($amazings as $amazing)
                                <div class="carousel-item @if($loop->last) active @endif">
                                    <div class="row m-0">
                                        <div class="right-col col-5 d-flex align-items-center">
                                            <a class="w-100 text-center" href="{{ route('product',$amazing->product->slug) }}">
                                                <img src="{{ asset('storage/'.$amazing->product->image1) }}" class="img-fluid" >
                                            </a>
                                        </div>
                                        <div class="left-col col-7">
                                            <div class="price">
                                                @if($amazing->product->new_price != 0)
                                                        <del><span>{{ number_format($amazing->product->price) }}<span>تومان</span></span></del>
                                                @endif
                                                    @if($amazing->product->price == 0)
                                                        <ins><span>رایگان</span></ins>
                                                    @elseif($amazing->product->new_price != 0)
                                                        <ins><span>{{ number_format($amazing->product->new_price) }}<span>تومان</span></span></ins>
                                                    @else
                                                        <ins><span>{{ number_format($amazing->product->price) }}<span>تومان</span></span></ins>
                                                    @endif
                                                <span class="discount-percent">{{ $amazing->product->parcentDiscount($amazing->product->price,$amazing->product->new_price) }} % تخفیف</span>
                                            </div>

                                            <h2 class="product-title">
                                                <a href="{{ route('product',$amazing->product->slug) }}">{{ $amazing->product->title }}</a>
                                            </h2>

                                            <div class="list-group">{!! \Illuminate\Support\Str::limit(nl2br($amazing->product->attrbutes),70) !!}</div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                    <div class="row" id="amazing-slider-responsive">
                        <div class="col-12">
                            <div class="widget widget-product card">
                                <header class="card-header">
                                    <img src="{{ asset('assets/img/amazing-slider/amazing-title-01.png') }}" width="150px" alt="">
                                </header>
                                <div class="product-carousel owl-carousel owl-theme">
                                    @foreach($amazings as $amazingRes)
                                    <div class="item">
                                        <a href="{{ route('product',$amazingRes->product->slug) }}">
                                            <img src="{{ asset('storage/'.$amazingRes->product->image1) }}" class="img-fluid" alt="{{ $amazingRes->product->title }}">
                                        </a>
                                        <h2 class="post-title">
                                            <a href="{{ route('product',$amazingRes->product->slug) }}">{{ $amazingRes->product->title }} </a>
                                        </h2>
                                        <div class="price">
                                            @if($amazingRes->product->new_price != 0)
                                                <del><span>{{ number_format($amazingRes->product->price) }}<span>تومان</span></span></del>
                                            @endif
                                            @if($amazingRes->product->price == 0)
                                                <ins><span>رایگان</span></ins>
                                            @elseif($amazingRes->product->new_price != 0)
                                                <ins><span>{{ number_format($amazingRes->product->new_price) }}<span>تومان</span></span></ins>
                                            @else
                                                <ins><span>{{ number_format($amazingRes->product->price) }}<span>تومان</span></span></ins>
                                            @endif
                                        </div>
                                        <span class="dis-parcent discount-percent">{{ $amazingRes->product->parcentDiscount($amazingRes->product->price,$amazingRes->product->new_price) }} % تخفیف</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                        @endif
                    <div class="row banner-ads">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="widget-banner card">
                                        <a href="#" target="_blank">
                                            <img class="img-fluid" src="{{ asset('storage/'.$posters[3]->image) }}" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="widget-banner card">
                                        <a href="#" target="_top">
                                            <img class="img-fluid" src="{{ asset('storage/'.$posters[2]->image) }}" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="widget-banner card">
                                        <a href="#" target="_top">
                                            <img class="img-fluid" src="{{ asset('storage/'.$posters[1]->image) }}" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="widget-banner card">
                                        <a href="#" target="_top">
                                            <img class="img-fluid" src="{{ asset('storage/'.$posters[0]->image) }}" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="widget widget-product card">
                                <header class="card-header">
                                    <h3 class="card-title">
                                        <span> آخرین محصولات  </span>
                                    </h3>
                                </header>
                                <div class="product-carousel owl-carousel owl-theme">
                                    @foreach($lastproducts as $item)
                                    <div class="item">
                                        <a href="{{ route('product',$item->slug) }}">
                                            <img src="{{ asset('storage/'.$item->image1) }}" class="img-fluid" alt="{{ $item->title }}">
                                        </a>
                                        <h2 class="post-title">
                                            <a href="{{ route('product',$item->slug) }}" class="font-l" >{{ $item->title }}</a>
                                        </h2>
                                        <div class="price">
                                            @if($item->new_price != 0)
                                            <div class="text-center">
                                                <del><span>{{ number_format($item->price) }}<span>تومان</span></span></del>
                                            </div>
                                            @endif
                                            <div class="text-center">
                                                @if($item->price == 0)
                                                <ins><span>رایگان</span></ins>
                                                @elseif($item->new_price != 0)
                                                <ins><span>{{ number_format($item->new_price) }}<span>تومان</span></span></ins>
                                                @else
                                                <ins><span>{{ number_format($item->price) }}<span>تومان</span></span></ins>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-around align-items-center pishnamayesh">
                                            @if($item->price == 0)
                                            <span class="small color1"><i class="fa fa-cloud-download ml-1"></i>دانلودها:{{ $item->CountDownload}}</span>
                                            @else
                                            <span class="small color1"><i class="fa fa-shopping-cart ml-1"></i>تعدادفروش:{{ $item->carts->count() }}</span>
                                            @endif
                                            <span class="small">
                                                     @if($item->AverageStarcompro() != 0)
                                                    @for($j=1;$j<=floor($item->AverageStarcompro());$j++)
                                                        <i class="fa fa-star text-warning"></i>
                                                    @endfor
                                                    <span> {{ $item->AverageStarcompro() }} از 5</span>
                                                @else
                                                    نظری وجود ندارد
                                                @endif
                                                </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="widget widget-product card">
                                <header class="card-header">
                                    <h3 class="card-title">
                                        <span> آخرین قالب ها  </span>
                                    </h3>
                                </header>
                                <div class="product-carousel owl-carousel owl-theme">
                                    @foreach($lasttemplates as $item2)
                                    <div class="item">
                                        <a href="{{ route('product',$item2->slug) }}">
                                            <img src="{{ asset('storage/'.$item2->image1) }}" class="img-fluid" alt="{{ $item2->title }}">
                                        </a>
                                        <h2 class="post-title">
                                            <a href="{{ route('product',$item2->slug) }}" class="font-l" >{{ $item2->title }}</a>
                                        </h2>
                                        <div class="price">
                                            @if($item2->new_price != 0)
                                            <div class="text-center">
                                                <del><span>{{ number_format($item2->price) }}<span>تومان</span></span></del>
                                            </div>
                                            @endif
                                            <div class="text-center">
                                                @if($item2->price == 0)
                                                <ins><span>رایگان</span></ins>
                                                @elseif($item2->new_price != 0)
                                                <ins><span>{{ number_format($item2->new_price) }}<span>تومان</span></span></ins>
                                                @else
                                                <ins><span>{{ number_format($item2->price) }}<span>تومان</span></span></ins>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-around align-items-center pishnamayesh">
                                            @if($item2->price == 0)
                                                <span class="small color1"><i class="fa fa-cloud-download ml-1"></i>دانلودها:{{ $item2->CountDownload}}</span>
                                            @else
                                                <span class="small color1"><i class="fa fa-shopping-cart ml-1"></i>تعدادفروش:{{ $item2->carts->count() }}</span>
                                            @endif
                                            <span class="small">
                                                       @if($item2->AverageStarcompro() != 0)
                                                    @for($j=1;$j<=floor($item2->AverageStarcompro());$j++)
                                                        <i class="fa fa-star text-warning"></i>
                                                    @endfor
                                                    <span> {{ $item2->AverageStarcompro() }} از 5</span>
                                                @else
                                                    نظری وجود ندارد
                                                @endif
                                                </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row banner-ads">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="widget-banner card">
                                        <a href="{{ $posters[4]->link }}" target="_blank">
                                            <img class="img-fluid" src="{{ asset('storage/'.$posters[4]->image) }}" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="widget-banner card">
                                        <a href="{{ $posters[5]->link }}" target="_top">
                                            <img class="img-fluid" src="{{ asset('storage/'.$posters[5]->image) }}" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="widget widget-product card">
                                    <header class="card-header">
                                        <h3 class="card-title">
                                            <span> آخرین برنامه ها  </span>
                                        </h3>
                                    </header>
                                    <div class="product-carousel owl-carousel owl-theme">
                                        @foreach($lastapplications as $item3)
                                            <div class="item">
                                                <a href="{{ route('product',$item3->slug) }}">
                                                    <img src="{{ asset('storage/'.$item3->image1) }}" class="img-fluid" alt="{{ $item3->title }}">
                                                </a>
                                                <h2 class="post-title">
                                                    <a href="{{ route('product',$item3->slug) }}" class="font-l" >{{ $item3->title }}</a>
                                                </h2>
                                                <div class="price">
                                                    @if($item3->new_price != 0)
                                                        <div class="text-center">
                                                            <del><span>{{ number_format($item3->price) }}<span>تومان</span></span></del>
                                                        </div>
                                                    @endif
                                                    <div class="text-center">
                                                        @if($item3->price == 0)
                                                            <ins><span>رایگان</span></ins>
                                                        @elseif($item3->new_price != 0)
                                                            <ins><span>{{ number_format($item3->new_price) }}<span>تومان</span></span></ins>
                                                        @else
                                                            <ins><span>{{ number_format($item3->price) }}<span>تومان</span></span></ins>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-around align-items-center pishnamayesh">
                                                    @if($item3->price == 0)
                                                        <span class="small color1"><i class="fa fa-cloud-download ml-1"></i>دانلودها:{{ $item3->CountDownload}}</span>
                                                    @else
                                                        <span class="small color1"><i class="fa fa-shopping-cart ml-1"></i>تعدادفروش:{{ $item3->carts->count() }}</span>
                                                    @endif
                                                    <span class="small">
                                                        @if($item3->AverageStarcompro() != 0)
                                                            @for($j=1;$j<=floor($item3->AverageStarcompro());$j++)
                                                                <i class="fa fa-star text-warning"></i>
                                                            @endfor
                                                            <span> {{ $item3->AverageStarcompro() }} از 5</span>
                                                        @else
                                                            نظری وجود ندارد
                                                        @endif
                                                </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="widget widget-product card">
                                    <header class="card-header">
                                        <h3 class="card-title">
                                            <span> تصادفی</span>
                                        </h3>
                                    </header>
                                    <div class="product-carousel owl-carousel owl-theme">
                                        @foreach($random as $item4)
                                            <div class="item">
                                                <a href="#">
                                                    <img src="{{ asset('storage/'.$item4->image1) }}" class="img-fluid" alt="{{ $item4->title }}">
                                                </a>
                                                <h2 class="post-title">
                                                    <a href="#" class="font-l" >{{ $item4->title }}</a>
                                                </h2>
                                                <div class="price">
                                                    @if($item4->new_price != 0)
                                                        <div class="text-center">
                                                            <del><span>{{ number_format($item4->price) }}<span>تومان</span></span></del>
                                                        </div>
                                                    @endif
                                                    <div class="text-center">
                                                        @if($item4->price == 0)
                                                            <ins><span>رایگان</span></ins>
                                                        @elseif($item4->new_price != 0)
                                                            <ins><span>{{ number_format($item4->new_price) }}<span>تومان</span></span></ins>
                                                        @else
                                                            <ins><span>{{ number_format($item4->price) }}<span>تومان</span></span></ins>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-around align-items-center pishnamayesh">
                                                    @if($item4->price == 0)
                                                        <span class="small color1"><i class="fa fa-cloud-download ml-1"></i>دانلودها:{{ $item4->CountDownload}}</span>
                                                    @else
                                                        <span class="small color1"><i class="fa fa-shopping-cart ml-1"></i>تعدادفروش:{{ $item4->carts->count() }}</span>
                                                    @endif
                                                    <span class="small">
                                                     @if($item4->AverageStarcompro() != 0)
                                                            @for($j=1;$j<=floor($item4->AverageStarcompro());$j++)
                                                                <i class="fa fa-star text-warning"></i>
                                                            @endfor
                                                            <span> {{ $item4->AverageStarcompro() }} از 5</span>
                                                        @else
                                                            نظری وجود ندارد
                                                        @endif
                                                </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </main>
@endsection
