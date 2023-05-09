@extends('layouts.fronts')

@section('title',$product->title)
    @php
    function get_file_size($url) {        $file = $url;   $ch = curl_init($file);   curl_setopt($ch, CURLOPT_NOBODY, true);   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   curl_setopt($ch, CURLOPT_HEADER, true);   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   $data = curl_exec($ch);   curl_close($ch);   if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {       $fileSize = (int)$matches[1];       return $fileSize;   }}
    @endphp

@section('content')
    <!-- main -->
    <main class="single-product default">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @includeIf('layouts.message')
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
                    <nav>
                        <ul class="breadcrumb">
                            <li>
                                <a href="{{ route('index') }}"><span>فروشگاه اینترنتی کمیاب فایل</span></a>
                            </li>
                            @if($product->category->InfoWithParent()->count())
                            <li>
                                @if($product->category->InfoWithParent->InfoWithParent()->count())
                                    <a href="{{ route('products','category='.$product->category->InfoWithParent->InfoWithParent->nameEn.'&'.$product->category->InfoWithParent->nameEn) }}"><span> {{ $product->category->InfoWithParent->name }}</span></a>
                                @else
                                    <a href="{{ route('products','category='.$product->category->InfoWithParent->nameEn) }}"><span> {{ $product->category->InfoWithParent->name }}</span></a>

                                @endif
                            </li>
                            @endif
                            <li>
                                @if($product->category->InfoWithParent()->count())
                                    @if($product->category->InfoWithParent->InfoWithParent()->count())
                                        <a href="{{ route('products','category='.$product->category->InfoWithParent->InfoWithParent->nameEn.'&'.$product->category->InfoWithParent->nameEn.'&'.$product->category->nameEn) }}"><span> {{ $product->category->name }}</span></a>
                                    @else
                                        <a href="{{ route('products','category='.$product->category->InfoWithParent->nameEn.'&'.$product->category->nameEn) }}"><span> {{ $product->category->name }}</span></a>
                                    @endif
                                @else
                                <a href="{{ route('products','category='.$product->category->nameEn) }}"><span> {{ $product->category->name }}</span></a>
                                @endif
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <article class="product">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="product-gallery default">
                                    <img class="zoom-img" id="img-product-zoom" src="{{ asset('storage/'.$product->image1) }}" data-zoom-image="{{ asset('storage/'.$product->image1) }}" width="411" />

                                    <div id="gallery_01f" style="width:500px;float:left;">
                                        <ul class="gallery-items">
                                            <li>
                                                <a href="tester" id="33" class="elevatezoom-gallery active" data-update="" data-image="{{ asset('storage/'.$product->image1) }}" data-zoom-image="{{ asset('storage/'.$product->image1) }}">
                                                    <img src="{{ asset('storage/'.$product->image1) }}" width="100" /></a>
                                            </li>
                                            <li>
                                                <a href="tester" class="elevatezoom-gallery" data-image="{{ asset('storage/'.$product->image2) }}" data-zoom-image="{{ asset('storage/'.$product->image2) }}"><img src="{{ asset('storage/'.$product->image2) }}" width="100" /></a>
                                            </li>
                                            <li>
                                                <a href="tester" class="elevatezoom-gallery" data-image="{{ asset('storage/'.$product->image3) }}" data-zoom-image="{{ asset('storage/'.$product->image3) }}">
                                                    <img src="{{ asset('storage/'.$product->image3) }}" width="100" />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="tester" class="elevatezoom-gallery" data-image="{{ asset('storage/'.$product->image4) }}" data-zoom-image="{{ asset('storage/'.$product->image4) }}" class="slide-content"><img src="{{ asset('storage/'.$product->image4) }}" height="68" /></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="gallery-options">
                                    @auth
                                    <li>
                                        <form action="{{ route('addprotofavourite',$product->slug) }}" method="post">
                                            @csrf
                                            <button class="add-favorites @if($product->existsproinfavourite($product->id)) text-danger @endif" type="submit"><i class="fa fa-heart"></i></button>
                                            @if($product->existsproinfavourite($product->id))
                                            <span class="tooltip-option">حذف از علاقمندی</span>
                                            @else
                                            <span class="tooltip-option">افزودن به علاقمندی</span>
                                            @endif
                                        </form>

                                    </li>
                                    @endauth
                                    <li>
                                        <button data-toggle="modal" data-target="#myModal"><i class="fa fa-share-alt"></i></button>
                                        <span class="tooltip-option">اشتراک گذاری</span>
                                    </li>
                                </ul>
                                <!-- Modal Core -->
                                <div class="modal-share modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">اشتراک گذاری با :</h4>
                                            </div>
                                            <div class="modal-body ">
                                                {!! $share !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Core -->
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="product-title default">
                                    <h1>
                                        {{ $product->title }}
                                    </h1>
                                </div>
                                <div class="product-directory default">
                                    <ul>
                                        <li>
                                            <span>دسته‌بندی</span> :
                                            <a
                                                @if($product->category->InfoWithParent()->count())
                                                    @if($product->category->InfoWithParent->InfoWithParent()->count() ==0)
                                                        href="{{ route('products','category='.$product->category->InfoWithParent->nameEn.'&'.$product->category->nameEn) }}"
                                                    @else
                                                        href="{{ route('products','category='.$product->category->InfoWithParent->InfoWithParent->nameEn.'&'.$product->category->InfoWithParent->nameEn.'&'.$product->category->nameEn) }}"
                                                    @endif

                                                @else
                                                href="{{ route('products','category='.$product->category->nameEn) }}"
                                                @endif
                                               class="btn-link-border">
                                                {{ $product->category->name }}
                                                @if($product->category->InfoWithParent()->count())
                                                    / {{ $product->category->InfoWithParent->name }}
                                                    @if($product->category->InfoWithParent->InfoWithParent()->count())
                                                       / {{ $product->category->InfoWithParent->InfoWithParent->name }}
                                                    @endif
                                                @endif
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-delivery-seller default">
                                    <p>
                                        <i class="now-ui-icons shopping_shop"></i>
                                        <span>فروشنده:‌</span>
                                        <a href="{{ route('products','store='.$product->store->name) }}" class="btn-link-border">
                                            {{$product->store->name}}
                                         </a>
                                    </p>

                                </div>

                                <div class="price-product defualt">
                                    <div class="price-value   price-discount  bg-success ">
                                        <span>
                                        @if($product->price == 0)
                                            رایگان
                                            @elseif($product->new_price !=0)
                                            {{ number_format($product->new_price) }}
                                            @else
                                                {{ number_format($product->price) }}
                                            @endif
                                        </span>
                                        @if($product->price != 0)
                                        <span class="price-currency">تومان</span>
                                        @endif
                                    </div>
                                    @if($product->new_price != 0)
                                    <div class="price-discount" data-title="تخفیف">
                                            <span>
                                                {{ $product->parcentDiscount($product->price,$product->new_price) }}
                                            </span>
                                        <span>%</span>
                                    </div>
                                    @endif
                                </div>
                                @if($product->new_price != 0)
                                <div class="price-value   price-discount  bg-danger mt-1">
                                    <span><del>{{ number_format($product->price) }}تومان</del></span>
                                </div>
                                @endif

                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 center-breakpoint">
                                @if($product->store->bluetick == 'yes')
                                    <div class="d-flex bluetick">
                                        <i class="fa fa-check-circle text-info fa-2x mr-2"></i>
                                        <p >این فروشگاه دارای تیک آبی از سوی تیم کمیاب فایل می باشد</p>
                                    </div>
                                @endif
                                <div class="product-params default">
                                    <ul @if($product->type == 'template') data-title="ویژگی های قالب " @else data-title="ویژگی های برنامه" @endif>
                                        <li>
                                            <span class="attrbutes-pro">   {!! nl2br($product->attrbutes) !!} </span>
                                        </li>
                                    </ul>
                                    <div class="product-add default">
                                      @if($product->price != 0)
                                          @auth
                                                @if($product->existsproincart($product->id) == false)
                                                <form action="{{ route('cart.store') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="product" value="{{ $product->slug }}">
                                                    <div class="parent-btn">
                                                        <button  class="dk-btn bg-purple" type="submit">
                                                            افزودن به سبد خرید
                                                            <i class="now-ui-icons shopping_cart-simple"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                                @else
                                                    <div class="parent-btn">
                                                        <a href="{{ route('cart.index') }}"  class="dk-btn bg-purple" type="submit">
                                                             درسبدخرید مشاهده کنید
                                                            <i class="now-ui-icons shopping_basket"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endauth
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="col-12 default no-padding">
                        <div class="product-tabs default">
                            <div class="box-tabs default">
                                <ul class="nav" role="tablist">
                                    <li class="box-tabs-tab">
                                        <a class="active" data-toggle="tab" href="#desc" role="tab" aria-expanded="true">
                                            <i class="now-ui-icons objects_umbrella-13"></i> نقد و بررسی
                                        </a>
                                    </li>

                                    <li class="box-tabs-tab">
                                        <a data-toggle="tab" href="#questions" role="tab" aria-expanded="false">
                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>نصب و فعال سازی
                                        </a>
                                    </li>
                                    <li class="box-tabs-tab">
                                        <a data-toggle="tab" href="#comments" role="tab" aria-expanded="false">
                                            <i class="now-ui-icons shopping_shop"></i> نظرات کاربران
                                        </a>
                                    </li>
                                </ul>
                                <div class="card-body default">
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="desc" role="tabpanel" aria-expanded="true">
                                            <article>
                                                <h2 class="param-title">
                                                    نقد و بررسی تخصصی
                                                    <span>{{ $product->title }}</span>
                                                </h2>
                                                <div class="parent-expert default">
                                                    <div class="content-expert">
                                                        <p >
                                                        {!! $product->description !!}
                                                        </p>
                                                        <div class="sum-more">
                                                            <span class="show-more btn-link-border">
                                                                نمایش بیشتر
                                                            </span>
                                                            <span class="show-less btn-link-border">
                                                                بستن
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="shadow-box"></div>
                                                </div>

                                                <div class="accordion default mt-4" id="accordionExample">
                                                    @foreach($product->collapses as $key=>$collapse)
                                                        @if($collapse->status == 1  && $collapse->type == 'description')
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <h5 class="mb-0">
                                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapseOne">
                                                                   {{ $collapse->title }}
                                                                </button>
                                                            </h5>
                                                        </div>

                                                        <div id="collapse{{ $key }}" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                                            <div class="card-body">
                                                                <p>{!! $collapse->content !!}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                @if($product->tags->count())
                                                <div>
                                                    <span>برچسپ ها :</span>
                                                    @foreach($product->tags as $tag)
                                                        <a href="{{ route('products','tag='.$tag->name) }}" class="btn bg-tag btn-sm">#{{$tag->name}}</a>
                                                    @endforeach
                                                </div>
                                                @endif

                                            </article>
                                        </div>

                                        <div class="tab-pane form-comment" id="questions" role="tabpanel" aria-expanded="false">
                                            @if($product->file != null && $product->price == 0 || $product->existsproincart($product->id,true,1) == true)
                                                <form action="{{ route('product.Template_download',$product->slug) }}" method="post">
                                                    @csrf
                                                <div class="parent-btn">
                                                        <button type="submit" class="dk-btn bg-success">
                                                            دانلود مستقیم
                                                            {{ $product->title }}
                                                            @if($product->file != null)
                                                            <span class="mr-2 smallll">
                                                             @php
                                                                 $sizebyte = filesize(\Illuminate\Support\Facades\Storage::path($product->file));
                                                                 $sizekeyl = $sizebyte /1024;
                                                                 $sizemeg = $sizekeyl /1024;
                                                                 echo '('. round($sizemeg,2).' مگابایت )';
                                                             @endphp
                                                        </span>
                                                            @endif

                                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                                        </button>
                                                </div>
                                                </form>
                                            <hr>
                                            @elseif($product->price != 0)
                                                <div class="cart-empty">
                                                    <div class="cart-empty-icon">
                                                        <i class="fa fa-lock fa-4x "></i>
                                                    </div>
                                                    <div class="cart-empty-title">برای دانلود فایل این محصول باید آن را خریداری کنید</div>
                                                </div>
                                            @endif
                                            @if($product->price == 0 ||  $product->existsproincart($product->id,true,1) == true)
                                            @foreach($product->downloads as $download)
                                                @if($download->status == 1)
                                                <div class="parent-btn ">
                                                    <a
                                                        @empty($download->url)
                                                        href="{{ route('download',$download->slug) }}"
                                                        @else
                                                            href="{{ $download->url }}"
                                                        @endempty
                                                       class="dk-btn bg1">
                                                       {{ $download->title }}
                                                        @if($download->file != null)
                                                        <span class="mr-2 smallll">
                                                             @php
                                                                 $sizebyte = filesize(\Illuminate\Support\Facades\Storage::path($download->file));
                                                                 $sizekeyl = $sizebyte /1024;
                                                                 $sizemeg = $sizekeyl /1024;
                                                                 echo '('. round($sizemeg,2).' مگابایت )';
                                                             @endphp
                                                        </span>
                                                        @else
                                                            <span class="mr-2 smallll">
                                                             @php
                                                                 $sizebyte = get_file_size($download->url);
                                                                 $sizekeyl = $sizebyte /1024;
                                                                 $sizemeg = $sizekeyl /1024;
                                                                 echo '('. round($sizemeg,2).' مگابایت )';
                                                             @endphp
                                                        </span>
                                                        @endif
                                                        <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                                    </a>
                                                </div>
                                                @endif
                                            @endforeach
                                                @endif

                                            <div class="accordion default mt-5" id="accordionExample">
                                                @foreach($product->collapses as $key=>$collapse)
                                                    @if($collapse->status == 1  && $collapse->type == 'install')
                                                        <div class="card">
                                                            <div class="card-header bg-card" id="headingOne">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapseOne">
                                                                        {{ $collapse->title }}
                                                                    </button>
                                                                </h5>
                                                            </div>

                                                            <div id="collapse{{ $key }}" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <p>{!! $collapse->content !!}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="comments" role="tabpanel" aria-expanded="false">
                                            <article>
                                                <h2 class="param-title">
                                                    نظرات کاربران
                                                    <span>{{$product->comments()->count()}} نظر</span>
                                                </h2>
                                                <h3>
                                                    @if($product->AverageStarcompro() != 0)
                                                        @for($j=1;$j<=floor($product->AverageStarcompro());$j++)
                                                            <i class="fa fa-star text-warning fa-2x"></i>
                                                        @endfor
                                                        <span> {{ $product->AverageStarcompro() }} از 5</span>
                                                    @endif

                                                </h3>
                                                @auth
                                                <button class="btn bg-sefareshi2 px-5 py-3" data-toggle="modal" data-target="#send-comment">ارسال نظر</button>
                                                <div class="modal" id="send-comment">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header ">
                                                                <span>ارسال نظر برای {{$product->title}}</span>
                                                                <a type="button" class="close mr-auto"  data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true" class="text-dark mr-5">&times;</span>
                                                                </a>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('comments.store') }}" method="post">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <textarea id="1" name="comment" rows="4" class="form-control" placeholder="نظری برای این محصول بنویسید">{{ old('comment') }}</textarea>
                                                                    </div>
                                                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                                                    <div class="form-group">
                                                                        <div class="my-2">
                                                                            <div id="reviewStars-input">
                                                                                <input id="star-4" type="radio" name="star" value="5"/>
                                                                                <label title="عالی" for="star-4"></label>

                                                                                <input id="star-3" type="radio" name="star" value="4"/>
                                                                                <label title="خوب" for="star-3"></label>

                                                                                <input id="star-2" type="radio" name="star" value="3"/>
                                                                                <label title="متوسط" for="star-2"></label>

                                                                                <input id="star-1" type="radio" name="star" value="2"/>
                                                                                <label title="قابل قبول" for="star-1"></label>

                                                                                <input id="star-0" type="radio" name="star" value="1" />
                                                                                <label title="ضعیف" for="star-0"></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn bg-sefareshi2 mt-2 btn-block" type="submit" name="send_comment">ارسال نظر</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endauth
                                                @if($product->comments()->count())
                                                <div class="comments-area default">
                                                    <ol class="comment-list">
                                                        @foreach($product->comments as $key=>$comment)
                                                        <li>
                                                            <div class="comment-body">
                                                                <div class="comment-author">
                                                                    @if($comment->user->image == null)
                                                                    <img alt="{{ $comment->user->name }}" src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get($comment->user->email) }}" class="avatar"><cite class="fn">{{ $comment->user->name }}</cite>
                                                                    @else
                                                                    <img alt="{{ $comment->user->name }}" src="{{ asset('storage/'.$comment->user->image) }}" class="avatar"><cite class="fn">{{ $comment->user->name }}</cite>
                                                                    @endif
                                                                    <span class="says">گفت:</span> </div>

                                                                <div class="commentmetadata "><a >
                                                                        @for($j=1;$j<=$comment->star;$j++)
                                                                            <i class="fa fa-star text-warning"></i>
                                                                        @endfor
                                                                    </a> </div>
                                                                <div class="commentmetadata mr-3"><a >
                                                                        {{ $comment->created_at->diffForHumans() }}</a>
                                                                </div>
                                                                @if($comment->product->existsproincart2($comment->product->id,$comment->user_id) == true)
                                                                    <span class="badge badge-success bg-success text-light mr-2">خریدار محصول</span>
                                                                @endif

                                                                <p>   {!! nl2br($comment->comment) !!}</p>
                                                                @auth
                                                                <div class="reply"><a class="comment-reply-link" data-toggle="modal" data-target="#reply-comment{{$key}}">پاسخ</a></div>
                                                                <div class="modal" id="reply-comment{{ $key }}">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header ">
                                                                                <span>پاسخ به نظر  {{$comment->user->name}}</span>
                                                                                <a type="button" class="close mx-lg-auto"  data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true" class="text-dark">&times;</span>
                                                                                </a>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="{{ route('comments.store') }}" method="post">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <textarea id="1" name="comment" rows="4" class="form-control" placeholder="پاسخی برای این نظر بنویسید">{{ old('comment') }}</textarea>
                                                                                    </div>
                                                                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                                                                    <input type="hidden" name="c_id" value="{{ $comment->id }}">
                                                                                    <button class="btn bg-sefareshi2 mt-2 btn-block" type="submit" name="reply_comment">پاسخ به نظر</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endauth
                                                            </div>
                                                            @if($comment->replies()->count())
                                                                @foreach($comment->replies as $reply)

                                                                    <div class="comment-body mr-5">
                                                                        <div class="comment-author">
                                                                            @if($reply->user->image == null)
                                                                                <img alt="{{ $reply->user->name }}" src="{{ \Creativeorange\Gravatar\Facades\Gravatar::get($reply->user->email) }}" class="avatar"><cite class="fn">{{ $reply->user->name }}</cite>
                                                                            @else
                                                                                <img alt="{{ $reply->user->name }}" src="{{ asset('storage/'.$reply->user->image) }}" class="avatar"><cite class="fn">{{ $reply->user->name }}</cite>
                                                                            @endif
                                                                            <span class="says">در پاسخ به {{$comment->user->name}} گفت:</span> </div>

                                                                        <div class="commentmetadata mr-3"><a >
                                                                                {{ $reply->created_at->diffForHumans() }}</a> </div>
                                                                        @if($reply->product->existsproincart2($reply->product->id,$reply->user_id) == true)
                                                                            <span class="badge badge-success bg-success text-light mr-2">خریدار محصول</span>
                                                                        @endif

                                                                        <p>   {!! nl2br($reply->comment) !!}</p>

                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </li>
                                                            @endforeach
                                                    </ol>
                                                </div>
                                                @else
                                                    <div class="text-center py-4 mt-2 bluetick text-large">فعلا هیچ نظری برای این محصول ثبت نشده است</div>
                                                    @endif
                                            </article>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- main -->
@endsection
@section('link_css')
    <link rel="stylesheet" href="{{ asset('assets/css/star.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/share.js') }}"></script>

@endsection
