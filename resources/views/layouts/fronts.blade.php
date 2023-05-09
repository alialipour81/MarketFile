<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <title>@yield('title')</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/font-awesome/css/font-awesome.min.css') }}"/>
    <!-- CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/now-ui-kit.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/plugins/owl.carousel.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/plugins/owl.theme.default.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('dashboard/custom.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('link_css')
</head>

<body class="index-page sidebar-collapse">

<!-- responsive-header -->
<nav class="navbar direction-ltr fixed-top header-responsive">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="{{ route('index')  }}">
                <img src="{{ asset('logo.png') }}" height="24px" alt="KamyabFile">
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navigation" aria-controls="navigation-index" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
            <div class="search-nav default">
                <form action="{{ route('search') }}" method="post" >
                    @csrf
                    <input type="text" placeholder="نام محصول مورد نظر خود را جستجو کنید…" name="search">
                    <button type="submit"><img src="{{ asset('assets/img/search.png') }}" alt="search"></button>
                </form>
                <ul>
                    <li><a data-toggle="tooltip" data-placement="bottom" title="داشبورد" href="{{ route('dashboard.index') }}"><i class="now-ui-icons users_single-02"></i></a></li>
                    <li><a data-toggle="tooltip" data-placement="bottom" title="سبدخرید" href="{{ route('cart.index')  }}"><i class="now-ui-icons shopping_basket"></i></a></li>
                </ul>
            </div>
        </div>

        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <div class="logo-nav-res default text-center">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('logo.png') }}" height="36px" alt="KamyabFile">
                </a>
            </div>
            <ul class="navbar-nav default">
                @foreach($categories as $category)
                <li    @if($category->parents->count()) class="sub-menu" @endif>
                    <a href="{{ route('products','category='.$category->nameEn) }}"> {{ $category->name }}</a>
                    @if($category->parents->count())
                    <ul>
                        @foreach($category->parents as $parent1)
                        <li  @if($parent1->parents->count()) class="sub-menu" @endif>
                            <a
                                @if($parent1->InfoWithParent()->count())
                                    href="{{ route('products','category='.$parent1->InfoWithParent->nameEn.'&'.$parent1->nameEn) }}"
                                @else
                                    href="{{ route('products','category='.$parent1->nameEn) }}"
                                @endif
                            >{{ $parent1->name }} </a>
                            @if($parent1->parents->count())
                            <ul>
                                @foreach($parent1->parents as $parent2)
                                <li>
                                    <a href="{{ route('products','category='.$category->nameEn.'&'.$parent2->InfoWithParent->nameEn.'&'.$parent2->nameEn ) }}">{{ $parent2->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<!-- responsive-header -->

<div class="wrapper default">

    <!-- header -->
    <header class="main-header default" >
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4 col-5">
                    <div class="logo-area default">
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('logo.png') }}" alt="KamyabFile">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 col-sm-8 col-7">
                    <div class="search-area default">
                        <form action="{{ route('search') }}" method="post" class="search">
                            @csrf
                            <input type="text" placeholder="نام محصول مورد نظر خود را جستجو کنید…" name="search">
                            <button type="submit"><img src="{{ asset('assets/img/search.png') }}" alt="search"></button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="user-login dropdown">
                        <a href="#" class="btn btn-neutral dropdown-toggle" data-toggle="dropdown"
                           id="navbarDropdownMenuLink1">
                            @auth
                                {{ auth()->user()->name }}
                            @else
                                ورود / ثبت نام
                            @endauth
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
                            @auth
                                <div class="dropdown-item">
                                    <a href="{{ route('dashboard.index') }}" class="btn btn-info">ورود به  داشبورد </a>
                                </div>
                            @else
                            <div class="dropdown-item">
                                <a href="{{ route('login') }}" class="btn btn-info">ورود  </a>
                            </div>
                            <div class="dropdown-item font-weight-bold">
                                <a class="btn btn-success text-light" href="{{ route('register') }}">ثبت‌نام</a>
                            </div>
                            @endauth
                        </ul>
                    </div>
                    <div class="cart dropdown">
                        <a href="{{ route('cart.index') }}" class="btn "  id="navbarDropdownMenuLink1">
                            سبد خرید
                            <i class="fa fa-shopping-basket"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main-menu">
            <div class="container">
                <ul class="list float-right">
                    @foreach($categories as $category)
                    <li class="list-item list-item-has-children mega-menu mega-menu-col-5">
                        <a class="nav-link" href="{{ route('products','category='.$category->nameEn) }}"  >{{ $category->name }} </a>
                        @if($category->parents()->count())
                        <ul class="sub-menu nav">
                            @foreach($category->parents as $parent1)
                            <li class="list-item list-item-has-children">
                                <i class="now-ui-icons arrows-1_minimal-left"></i><a class="main-list-item nav-link"
                                                                                     @if($parent1->InfoWithParent()->count())
                                                                                         href="{{ route('products','category='.$parent1->InfoWithParent->nameEn.'&'.$parent1->nameEn) }}"
                                                                                     @else
                                                                                         href="{{ route('products','category='.$parent1->nameEn) }}"
                                    @endif
                                >  {{ $parent1->name }}</a>
                                @if($parent1->parents()->count())
                                    <ul class="sub-menu nav">
                                    <li class="list-item list-item-has-children">
                                        <ul class="sub-menu nav">
                                            @foreach($parent1->parents as $parent2)
                                            <li class="list-item">
                                                <a class="nav-link" href="{{ route('products','category='.$category->nameEn.'&'.$parent2->InfoWithParent->nameEn.'&'.$parent2->nameEn ) }}"> {{ $parent2->name }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </header>
    <!-- header -->


  @yield('content')


    <footer class="main-footer default">
        <div class="back-to-top">
            <a href="#"><span class="icon"><i class="now-ui-icons arrows-1_minimal-up"></i></span> <span>بازگشت به
                        بالا</span></a>
        </div>

        <div class="description">
            <div class="container">
                <div class="row">
                    <div class="site-description col-12 col-lg-7">
                        <h1 class="site-title">فروشگاه اینترنتی دیجی کالا، بررسی، انتخاب و خرید آنلاین</h1>
                        <p>
                            دیجی کالا به عنوان یکی از قدیمی‌ترین فروشگاه های اینترنتی با بیش از یک دهه تجربه، با
                            پایبندی به سه اصل کلیدی، پرداخت در
                            محل، 7 روز ضمانت بازگشت کالا و تضمین اصل‌بودن کالا، موفق شده تا همگام با فروشگاه‌های
                            معتبر جهان، به بزرگ‌ترین فروشگاه
                            اینترنتی ایران تبدیل شود. به محض ورود به دیجی کالا با یک سایت پر از کالا رو به رو
                            می‌شوید! هر آنچه که نیاز دارید و به
                            ذهن شما خطور می‌کند در اینجا پیدا خواهید کرد.
                        </p>
                    </div>
                    <div class="symbol col-12 col-lg-5">
                        <a href="#" target="_blank"><img src="assets/img/symbol-01.png" alt=""></a>
                        <a href="#" target="_blank"><img src="assets/img/symbol-02.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <p>
                    استفاده از مطالب فروشگاه اینترنتی دیجی کالا فقط برای مقاصد غیرتجاری و با ذکر منبع بلامانع است.
                    کلیه حقوق این سایت متعلق
                    به شرکت نوآوران فن آوازه (فروشگاه آنلاین دیجی کالا) می‌باشد.
                </p>
            </div>
        </div>
    </footer>
</div>
</body>
<!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="{{ asset('assets/js/plugins/bootstrap-switch.js') }}"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="{{ asset('assets/js/plugins/nouislider.min.js') }}" type="text/javascript"></script>
<!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<!-- Share Library etc -->
<script src="{{ asset('assets/js/plugins/jquery.sharrre.js') }}" type="text/javascript"></script>
<!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('assets/js/now-ui-kit.js') }}" type="text/javascript"></script>
<!--  CountDown -->
<script src="{{ asset('assets/js/plugins/countdown.min.js') }}" type="text/javascript"></script>
<!--  Plugin for Sliders -->
<script src="{{ asset('assets/js/plugins/owl.carousel.min.js') }}" type="text/javascript"></script>
<!--  Jquery easing -->
<script src="{{ asset('assets/js/plugins/jquery.easing.1.3.min.js') }}" type="text/javascript"></script>
<!-- Main Js -->
<script src="{{ asset('assets/js/main.js') }}" type="text/javascript"></script>
@yield('link_js')
</html>
