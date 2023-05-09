<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="fontiran.com:license" content="Y68A9">
    <link rel="icon" href="dashboard/build/images/favicon.ico" type="image/ico"/>
    <title>@yield('title') </title>

    <!-- Bootstrap -->
    <link href="{{ asset('dashboard/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/vendors/bootstrap-rtl/dist/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('dashboard/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('dashboard/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('dashboard/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('dashboard/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('dashboard/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('dashboard/build/css/custom.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dashboard/custom.css') }}">
    <script src="//cdn.ckeditor.com/4.19.1/full/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('link_css')
</head>
<!-- /header content -->
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col hidden-print">
            <div class="left_col scroll-view wid100" >

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix" >
                    <div class="profile_pic">
                        @if(auth()->user()->image == null)
                        <img src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get(auth()->user()->email) }}" alt="..." class="img-circle profile_img" width="55px" height="60px">
                        @else
                            <img src="{{ asset('storage/'.auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="img-circle profile_img">
                        @endif
                    </div>
                    <div class="profile_info">
                        <span>خوش آمدید,</span>
                        <h2> {{ auth()->user()->name }}</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-home"></i> منو ها <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @if(auth()->user()->role == 'admin')
                                        <li><a href="{{ route('categories.index') }}">دسته بندی ها</a></li>
                                    <li><a href="{{ route('tags.index') }}">برچسپ ها </a></li>
                                    @endif
                                    <li><a href="{{ route('stores.index') }}"> فروشگاه شما</a></li>
                                    <li><a href="{{ route('products.index') }}">  محصولات</a></li>
                                        @if(auth()->user()->role == 'admin')
                                            <li><a href="{{ route('posters.index') }}">پوستر ها</a></li>
                                            <li><a href="{{ route('sliders.index') }}"> اسلایدر</a></li>
                                            <li><a href="{{ route('instantoffers.index') }}"> پیشنهادات لحظه ای</a></li>
                                            <li><a href="{{ route('amazings.index') }}"> پیشنهادات  شگغت انگیز</a></li>
                                            <li><a href="{{ route('comments.index') }}">نظرات کاربران</a></li>
                                            <li><a href="{{ route('discounts.index') }}"> تخفیف ها</a></li>
                                            <li><a href="{{ route('users.index') }}">کاربران</a></li>
                                        @endif
                                        <li><a href="{{ route('cart_orders.index') }}">  خرید وفروش</a></li>
                                        <li><a href="{{ route('checkouts.index') }}">  تسویه حساب</a></li>
                                        <li><a href="{{ route('messages.index') }}">  تیکت ها</a></li>
                                        <li><a href="{{ route('favourites.index') }}">علاقه مندی ها </a></li>

                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a href="{{ route('index') }}" data-toggle="tooltip" data-placement="top" title="بازگشت به صفحه اصلی " >
                        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('cart.index') }}" data-toggle="tooltip" data-placement="top" title="رفتن به سبدخرید     " >
                        <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    </a>
                        <a href="{{ route('dashboard.index') }}" data-toggle="tooltip" data-placement="top" title="رفتن به داشبورد" >
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                        </a>
                    <a data-toggle="tooltip" data-placement="top" title="خروج" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav hidden-print">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                @if(auth()->user()->image == null)
                                <img src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get(auth()->user()->email) }}" >
                                @else
                                <img src="{{ asset('storage/'.auth()->user()->image) }}" >
                                @endif
                                {{ auth()->user()->name }}
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li>
                                    <a class="btn btn-block mx-auto" data-toggle="modal" data-target="#profile">
                                        <span> پروفایل</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <div class="modal" id="profile">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="font-large">اطلاعات پروفایل شما</span>
                                        <button type="button" class="close mtap-5" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="media d-flexx">
                                            @if(auth()->user()->image != null)
                                                <img class="rounded"  src="{{ asset('storage/'.auth()->user()->image) }}" width="300px" height="300px">
                                            @else
                                                <img class="rounded"  src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get(auth()->user()->email) }}" >
                                            @endif

                                            <div class="media-body mrigh6">
                                                <h6>{{ auth()->user()->name }}</h6>
                                                <h6>{{ auth()->user()->email }}</h6>
                                                <h6 class="small">
                                                    <span class="text-success">تاریخ ایجاد : {{auth()->user()->created_at->diffForHumans()}}</span>
                                                </h6>
                                                <h6 class="small">
                                                    <span class="text-success">تاریخ آخرین بروزرسانی : {{auth()->user()->updated_at->diffForHumans()}}</span>
                                                </h6>
                                                @if(auth()->user()->image != null)
                                                    <form action="{{ route('users.delimgprofile',auth()->user()->email) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-dange btn-sm smallr" >حذف تصویر پروفایل</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="x_panel">
                                                <div class="x_title">
                                                    <h2 >
                                                        ویرایش اطلاعات پروفایل
                                                    </h2>
                                                    <ul class="nav navbar-right panel_toolbox">
                                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                        </li>
                                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                                        </li>
                                                    </ul>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="x_content">
                                                    <br/>
                                                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="{{ route('users.update',auth()->user()->id) }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> نام کاربری:
                                                                <span class="required">*</span>
                                                            </label>
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <input type="text" id="first-name" class="form-control col-md-7 col-xs-12" name="name_user" value="{{ auth()->user()->name }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">ایمیل:
                                                                 <span class="required">*</span>
                                                            </label>
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <input type="text" id="last-name"   class="form-control col-md-7 col-xs-12" value="{{ auth()->user()->email }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">تصویر پروفایل:</label>
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <input id="middle-name" class="form-control col-md-7 col-xs-12" type="file" name="image_user">
                                                            </div>
                                                        </div>
                                                        <div class="ln_solid"></div>
                                                        <div class="form-group">
                                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                                <button type="submit" class="btn btn-success" name="usereditwithprofile">ویرایش</button>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <span class="image"><img src="build/images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>حسین امانی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="build/images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>حسین امانی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="build/images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>حسین امانی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="build/images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>حسین امانی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong>مشاهده تمام اعلان ها</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->
        <!-- /header content -->

        <!-- page content -->
        @yield('content')

        <!-- /page content -->

        <!-- footer content -->

        <!-- /footer content -->
    </div>
</div>
<div id="lock_screen">
    <table>
        <tr>
            <td>
                <div class="clock"></div>
                <span class="unlock">
                    <span class="fa-stack fa-5x">
                      <i class="fa fa-square-o fa-stack-2x fa-inverse"></i>
                      <i id="icon_lock" class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                </span>
            </td>
        </tr>
    </table>
</div>
@yield('link_js')
<!-- jQuery -->
<script src="{{ asset('dashboard/vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('dashboard/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('dashboard/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('dashboard/vendors/nprogress/nprogress.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('dashboard/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('dashboard/vendors/iCheck/icheck.min.js') }}"></script>

<!-- bootstrap-daterangepicker -->
<script src="{{ asset('dashboard/vendors/moment/min/moment.min.js') }}"></script>

<script src="{{ asset('dashboard/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('dashboard/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- jQuery Sparklines -->
<script src="{{ asset('dashboard/vendors/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- gauge.js -->
<script src="{{ asset('dashboard/vendors/gauge.js/dist/gauge.min.js') }}"></script>
<!-- Skycons -->
<script src="{{ asset('dashboard/vendors/skycons/skycons.js') }}"></script>
<!-- Flot -->
<script src="{{ asset('dashboard/vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('dashboard/vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('dashboard/vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('dashboard/vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('dashboard/vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('dashboard/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('dashboard/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{ asset('dashboard/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<!-- DateJS -->
<script src="{{ asset('dashboard/vendors/DateJS/build/production/date.min.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('dashboard/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
<script src="{{ asset('dashboard/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ asset('dashboard/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>

<!-- Custom Theme Scripts -->
<script src="{{ asset('dashboard/build/js/custom.min.js') }}"></script>


</body>
</html>
