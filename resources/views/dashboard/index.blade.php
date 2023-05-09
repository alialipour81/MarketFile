@extends('layouts.dashboard.fronts')
@section('title','داشبورد')
@section('content')

    <div class="right_col" role="main">
        @include('layouts.message')
        @include('layouts.message2')
        <!-- top tiles -->
        <div class="row">

            <div class="col-md-12">
                <div class="">
                    <div class="x_content">

                        <div class="row top_tiles" style="margin: 10px 0;">

                            <div class="col-md-3 tile">
                                <span>درآمد فعلی من (  بر اساس تسویه حساب )</span>
                                <h2>{{ number_format(array_sum(@$my_daramad) - array_sum($checkouts)).' تومان ' }}</h2>
                                <span class="sparkline_two" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                            </div>
                            <div class="col-md-3 tile">
                                <span>کل فروش من </span>
                                <h2>{{ number_format(array_sum(@$foroosh)).' تومان ' }}</h2>
                                <span class="sparkline_two" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                            </div>

                                <div class="col-md-3 tile">
                                    <span>کل خرید من</span>
                                    <h2>{{ number_format(array_sum(@$kharid)).' تومان ' }}</h2>
                                    <span class="sparkline_two" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                                </div>
                            @if(auth()->user()->role == 'admin')
                            <div class="col-md-3 tile">
                                <span> کل درامد(خرید و فروش همه)</span>
                                <h2>{{ number_format(array_sum(@$All_daramad)).' تومان ' }}</h2>
                                <span class="sparkline_two" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->role == 'admin')
        <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> تعداد کاربران</span>
                <div class="count">{{ number_format($users->count()) }}</div>
                <span class="count_bottom">ایمیل تاییدنشده : {{ number_format($users_deactive->count()) }}</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-shopping-cart"></i> فروشگاه های فعال</span>
                <div class="count">{{ number_format($stores->count()) }}</div>
                <span class="count_bottom">ردشده  : {{ number_format($stores_cancel->count()) }}</span> /
                <span class="count_bottom">درحال بررسی  : {{ number_format($stores_barasi->count()) }}</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-shopping-bag"></i> همه محصولات(فعال)</span>
                <div class="count green">{{ number_format($products->count()) }}</div>
                <span class="count_bottom">غیرفعال  : {{ number_format($products_deactive->count()) }}</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-comments"></i>  نظرات فعال</span>
                <div class="count">{{ number_format($comments->count()) }}</div>
                <span class="count_bottom">غیرفعال  : {{ number_format($comments_deactive->count()) }}</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-comment"></i><i class="fa fa-reply"></i>  پاسخ ها (فعال)</span>
                <div class="count green">{{ number_format($replies->count()) }}</div>
                <span class="count_bottom">غیرفعال  : {{ number_format($replies_deactive->count()) }}</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-shopping-basket"></i> خریدها(موفق) </span>
                <div class="count">{{ number_format($orders->count())}}</div>
                <span class="count_bottom">ناموفق  : {{ number_format($orders_deactive->count()) }}</span>
            </div>
        </div>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-sliders"></i>  اسلایدر (فعال)</span>
                    <div class="count">{{ number_format($slider->count()) }}</div>
                    <span class="count_bottom"> غیرفعال : {{ number_format($slider_de->count()) }}</span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-tag"></i> پوستر های فعال</span>
                    <div class="count">{{ number_format($posters->count()) }}</div>
                    <span class="count_bottom">غیرفعال  : {{ number_format($posters_de->count()) }}</span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-toggle-off"></i>  پیشنهادات لحظه ای (فعال)</span>
                    <div class="count ">{{ number_format($instantoffers->count()) }}</div>
                    <span class="count_bottom">غیرفعال  : {{ number_format($instantoffers_de->count()) }}</span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-archive"></i>   پیشنهادات شگفت انگیز(فعال)</span>
                    <div class="count">{{ number_format($amazings->count()) }}</div>
                    <span class="count_bottom">غیرفعال  : {{ number_format($amazings_de->count()) }}</span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-cc-discover"></i>   تخفیف ها ( فعال)</span>
                    <div class="count green">{{ number_format($discounts->count()) }}</div>
                    <span class="count_bottom">غیرفعال  : {{ number_format($discounts_de->count()) }}</span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-tags"></i>برچسپ ها</span>
                    <div class="count">{{ number_format($tags->count())}}</div>
                    <span class="count_bottom">........</span>
                </div>
            </div>
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-free-code-camp"></i> دسته بندی ها</span>
                    <div class="count">{{ number_format($categories->count()) }}</div>
                    <span class="count_bottom"> .......... </span>
                </div>
            </div>
        @endif
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>میانبر ها </h3>
                </div>


            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">

                        <div class="x_content">



                            <div class="bs-docs-section">
                                <div class="bs-glyphicons">
                                    <ul class="bs-glyphicons-list">

                                        <a href="{{ route('stores.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                                                <span class="glyphicon-class">فروشگاه شما</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('products.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                                <span class="glyphicon-class">محصولات شما</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('cart_orders.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>
                                                <span class="glyphicon-class">خریدوفروش های شما</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('checkouts.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon glyphicon-usd" aria-hidden="true"></span>
                                                <span class="glyphicon-class">تسویه حساب</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('messages.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon glyphicon-comment" aria-hidden="true"></span>
                                                <span class="glyphicon-class">تیکت ها</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('favourites.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                                                <span class="glyphicon-class">علاقه مندی های شما</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                                                <span class="glyphicon-class">صفحه اصلی سایت</span>
                                            </li>
                                        </a>
                                        <a href="{{ route('cart.index') }}">
                                            <li>
                                                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                                <span class="glyphicon-class">سبدخرید</span>
                                            </li>
                                        </a>


                                    </ul>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
