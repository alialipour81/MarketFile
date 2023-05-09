@extends('layouts.fronts')

@php
if ($job[0] == 'store' ||  $job[0] == 'categories' ){
    $title = ' فروشگاه '.$job[1];
}elseif (isset($catg) && $job[0] == 'category'){
     if ($catg->InfoWithParent()->count()){
          $title = ' دسته بندی '.$catg->name.'('.$catg->InfoWithParent->name.')';
     }else{
           $title = ' دسته بندی '.$catg->name;
     }
}elseif ($job[0] == 'tag'){
   $title = '  محصولات مربوط به برچسپ '.$tag->name;
}elseif ($job[0] == 'search'){
      $title = '   محصولات با نام   '.$job[1];
}
@endphp

@section('title',$title)

@section('content')
@php
function filter($fil,$fi2){
    if (session()->has('profilter1') && session()->has('profilter2')){
        $sesprofil1 = session()->get('profilter1');
        $sesprofil2 = session()->get('profilter2');
        if ($sesprofil1 == $fil && $sesprofil2 == $fi2){
            return 'btn-info';
        }
    }
}
@endphp


        <!-- main -->
        <main class="search-page default">
            <div class="container">
                <div class="row">
                    <aside class="sidebar-page col-12 col-sm-12 col-md-4 col-lg-3 order-1">

                        <div class="box">

                            <div class="box-header">جستجو در
                                @if($job[0] == 'store' || $job[0] == 'categories') فروشگاه {{$job[1]}} @endif
                                @if( $job[0] == 'category') دسته بندی {{$catg->name}}
                                @if($catg->InfoWithParent()->count())
                                    ({{ $catg->InfoWithParent->name }})
                                @endif
                                @endif
                                @if($job[0] == 'tag' || $job[0] == 'search') {{ $title }}@endif
                                :</div>
                            @if($job[0] != 'search')
                            <div class="box-content">
                                <div class="ui-input ui-input--quick-search">
                                    <form action="{{ route('products',$valAsli) }}" method="get" class="d-flex">
                                        <input type="text" class="ui-input-field ui-input-field--cleanable" name="search" value="{{ request()->query('search') }}" placeholder="نام محصول  مورد نظر +ENTER">
                                    </form>
                                </div>
                            </div>
                            @endif
                            @if($job[0] == 'categories' || $job[0] == 'store' && session()->has('profilter1') && session()->has('profilter2'))
                                <form action="{{ route('products.filter',$job[1]) }}" method="post">
                                    @csrf
                                    <button class="btn btn-back-store text-center mt-3 btn-block" type="submit" name="productsstore">غیر فعال کردن فیلتر</button>
                                </form>
                            @endif
                            @if($job[0] == 'tag' && session()->has('select_categories_store') || $job[0] == 'tag'   && session()->has('profilter1') && session()->has('profilter2'))
                                <form action="{{ route('products.filter',$job[1]) }}" method="post">
                                    @csrf
                                    <button class="btn btn-back-store text-center mt-3 btn-block" type="submit" name="productstag">غیر فعال کردن فیلتر  </button>
                                </form>
                            @endif
                            @if($job[0]  == 'search' && session()->has('select_categories_store'))
                                <form action="{{ route('products.filter',$job[1]) }}" method="post">
                                    @csrf
                                    <button class="btn btn-back-store text-center mt-3 btn-block" type="submit" name="deletefiltercat">غیر فعال کردن فیلتر </button>
                                </form>
                            @endif
                        </div>
                        @if($job[0] != 'category')
                        <div class="box">
                            <div class="box-header">
                                <div class="box-toggle" data-toggle="collapse" href="#collapseExample1" role="button"
                                     aria-expanded="true" aria-controls="collapseExample1">
                                    فیلتر دسته بندی
                                    <i class="now-ui-icons arrows-1_minimal-down"></i>
                                </div>
                            </div>
                            <div class="box-content">
                                <div class="collapse show" id="collapseExample1">
                                    <form action="{{ route('products.filter',$job[1]) }}" method="post">
                                       @if($job[0] == 'tag')
                                            <input type="hidden" name="tag">
                                        @endif
                                           @if($job[0] == 'search')
                                               <input type="hidden" name="search">
                                           @endif
                                           @if($job[0] == 'store')
                                               <input type="hidden" name="store">
                                           @endif
                                        <span class="ui-input-cleaner"></span>
                                    <div class="filter-option">
                                            @csrf
                                            @foreach($categories as $category)
                                                <div class="checkbox text-sefareshi1">
                                                    <input id="{{ $category->created_at }}" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                           @if(session()->has('select_categories_store'))
                                                               @php $cats = session()->get('select_categories_store');  @endphp
                                                               @foreach($cats as $cat)
                                                                   @if($cat->id == $category->id) checked @endif
                                                        @endforeach
                                                        @endif
                                                    >
                                                    <label for="{{ $category->created_at }}">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                                @if($category->parents->count())
                                                    @foreach($category->parents as $parent)
                                                    <div class="checkbox ">
                                                        <input id="{{ $parent->created_at }}" type="checkbox" name="categories[]" value="{{ $parent->id }}"
                                                               @if(session()->has('select_categories_store'))
                                                                   @php $cats = session()->get('select_categories_store');  @endphp
                                                                   @foreach($cats as $cat)
                                                                       @if($cat->id == $parent->id) checked @endif
                                                                   @endforeach
                                                            @endif
                                                        >
                                                        <label for="{{ $parent->created_at }}">
                                                            {{ $parent->name }}
                                                        </label>
                                                    </div>
                                                        @if($parent->parents->count())
                                                            @foreach($parent->parents as $parent2)
                                                                <div class="checkbox mr-3">
                                                                    <input id="{{ $parent2->created_at }}" type="checkbox" name="categories[]" value="{{ $parent2->id }}"
                                                                           @if(session()->has('select_categories_store'))
                                                                               @php $cats = session()->get('select_categories_store');  @endphp
                                                                               @foreach($cats as $cat)
                                                                                   @if($cat->id == $parent2->id) checked @endif
                                                                        @endforeach
                                                                        @endif
                                                                    >
                                                                    <label for="{{ $parent2->created_at }}">
                                                                        {{ $parent2->name }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                    </div>
                                            <button class="btn btn-sm text-light form-control  bg-purple" name="select_categories"  type="submit">اعمال فیلتر
                                            <i class="fa fa-ticket"></i>
                                            </button>

                                    </form>
                                </div>
                            </div>

                        </div>
                        @endif

                    </aside>
                    <div class="col-12 col-sm-12 col-md-8 col-lg-9 order-2">
                        @include('layouts.message')
                        <div class="breadcrumb-section default">
                            <ul class="breadcrumb-list">
                                @if($job[0] == 'store' || $job[0] == 'categories')
                                <li><span> محصولات فروشگاه {{$job[1]}}  </span></li>
                                @endif
                            </ul>
                        </div>
                        <div class="listing default">
                            <div class="listing-counter"> تعداد: {{$products->count()}}</div>
                            <div class="listing-header default">
                                <ul class="listing-sort nav nav-tabs justify-content-center" role="tablist"
                                    data-label="مرتب‌سازی بر اساس :">

                                    <li>
                                        <form action="{{ route('products.filter',$valAsli) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm {{ filter('id','desc') }}" name="jadidtarin" type="submit">جدیدترین</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('products.filter',$valAsli) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm {{ filter('price','asc') }}" name="arzantarin" type="submit">ارزان ترین</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('products.filter',$valAsli) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm {{ filter('price','desc') }}" name="grantarin" type="submit">گران ترین</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('products.filter',$valAsli) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm {{ filter('id','asc') }}" name="ghadimitarin" type="submit">قدیمی ترین</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content default text-center">
                                <div class="tab-pane active" id="related" role="tabpanel" aria-expanded="true">
                                    <div class="container no-padding-right">
                                        <ul class="row listing-items">

                                            @forelse($products as $product)
                                            <li class="col-xl-3 col-lg-4 col-md-6 col-12 no-padding">
                                                <div class="product-box">
                                                    <div
                                                        class="product-seller-details product-seller-details-item-grid">
                                                        @if($job[0] == 'store')
                                                        <span class="product-main-seller"><span
                                                                class="product-seller-details-label">فروشنده:
                                                            </span>{{ $product->store->name }} </span>
                                                        <span class="product-seller-details-badge-container"></span>
                                                        @else
                                                            <span title="{{ $product->store->name }}">
                                                                <span >
                                                                    دسته بندی:
                                                                </span>
                                                                {{ $product->category->name }}
                                                                @if($product->category->InfoWithParent()->count())
                                                                    ({{ $product->category->InfoWithParent->name }})
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <a class="product-box-img" href="{{ route('product',$product->slug) }}">
                                                        <img src="{{ asset('storage/'.$product->image1) }}" alt="">
                                                    </a>
                                                    <div class="product-box-content">
                                                        <div class="product-box-content-row">
                                                            <div class="product-box-title">
                                                                <a href="{{ route('product',$product->slug) }}">
                                                                    {{ $product->title }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="product-box-row product-box-row-price">
                                                            <div class="price">
                                                                <div class="price-value">
                                                                    <div class="price-value-wrapper @if($product->price == 0) text-success  @endif">
                                                                       @if($product->price == 0)
                                                                           رایگان
                                                                        @elseif($product->new_price != 0)
                                                                           {{ number_format($product->new_price) }}
                                                                            <span class="price-currency">تومان</span>
                                                                        @else
                                                                            {{ number_format($product->price) }}
                                                                            <span class="price-currency">تومان</span>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @empty
                                                @if($job[0] == 'store')
                                                  <li class="alert alert-success mx-3 mt-5  rounded btn-block">این فروشگاه فعلا محصولی ثبت نکرده است</li>
                                                @elseif($job[0] == 'categories' )
                                                  <li class="alert alert-success mx-3 mt-5  rounded btn-block">برای این دسته بندی, فروشگاه  فعلا محصولی ثبت نکرده است</li>
                                                @elseif($job[0] == 'category')
                                                  <li class="alert alert-success mx-3 mt-5  rounded btn-block">برای این دسته بندی, فعلا محصولی ثبت نشده است</li>
                                                @elseif($job[0] == 'tag')
                                                  <li class="alert alert-success mx-3 mt-5  rounded btn-block">برای این برچسپ , فعلا محصولی ثبت نشده است</li>
                                                @elseif($job[0] == 'search')
                                                    @if(session()->has('select_categories_store'))
                                                        <li class="alert alert-success mx-3 mt-5  rounded btn-block">برای این دسته بندی , محصولی با این نام وجود ندارد</li>
                                                    @else
                                                        <li class="alert alert-success mx-3 mt-5  rounded btn-block">محصولی با این نام وجود ندارد</li>
                                                    @endif
                                                @endif
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <div class="pager default text-center">
                                <ul class="pager-items">
                                  {{ $products->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- main -->
@endsection
