@extends('layouts.dashboard.fronts')
@php
if (auth()->user()->role == 'admin'){
    $title = "همه محصول ها";
}else{
    $title = "محصول های شما";
}
@endphp
@section('title',$title)

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('layouts.message2')
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ $title }}
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('products.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    <div class="table-responsive">
                        @if($products->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('products.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام محصول  را وارد کنید" value="{{ request()->query('search') }}">
                                        <span class="input-group-btn">
                      <button class="btn btn-default btn-search" type="submit">جستجو</button>
                    </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        <table class="table table-striped jambo_table bulk_action small">
                            <thead>
                            <tr class="headings">
                                <th class="column-title">آیدی</th>
                                <th class="column-title">نوع</th>
                                <th class="column-title">عنوان</th>
                                <th class="column-title">تصویر اصلی</th>
                                <th class="column-title">دسته بندی </th>
                                <th class="column-title"> فروشگاه </th>
                                <th class="column-title"> وضعیت </th>
                                @if(auth()->user()->role == 'admin')
                                <th class="column-title"> کاربر </th>
                                @endif
                                <th class="column-title">  قیمت اصلی </th>
                                <th class="column-title">درصد تخفیف</th>
                                <th class="column-title">عملیات ها</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($products as $key=>$product)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    @if($product->type == 'app')
                                        <span class="badge badge-danger bg7">برنامه </span>
                                    @else
                                        <span class="badge badge-success bg3"> قالب</span>
                                    @endif
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>
                                    <img src="{{ asset('storage/'.$product->image1) }}" width="60px" height="40px" class="rounded shadow">
                                </td>
                                <td >{{ $product->category->name }}
                                @if($product->category->InfoWithParent()->count())
                                    ({{ $product->category->InfoWithParent->name }})
                                        @if($product->category->InfoWithParent->InfoWithParent()->count())
                                            ({{ $product->category->InfoWithParent->InfoWithParent->name }})
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $product->store->name }}</td>
                                <td>
                                    @if($product->status == 0)
                                        <span class="badge badge-danger bg4">عدم نمایش</span>
                                    @else
                                        <span class="badge badge-success bg5"> نمایش</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role == 'admin')
                                <td data-toggle="tooltip" data-placement="top" title="{{ $product->user->name }}">{{ $product->user->email }}</td>
                                @endif
                                <td>
                                    @if($product->price == 0)
                                        <span class="badge badge-danger bg8">رایگان </span>
                                    @else
                                        <span class="badge badge-success bg8"> {{ number_format($product->price).'تومان' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->new_price == 0)
                                        بدون تخفیف
                                    @else
                                        {{ $product->parcentDiscount($product->price,$product->new_price) }}
                                    @endif
                                </td>
                                <td >
                                    @if($product->type == 'template')

                                        <button class="btn btn-secondary btn-sm btn-desc  btn-template" data-toggle="modal" data-target="#template{{$key}}">
                                        مدیریت قالب
                                        </button>
                                        <div class="modal" id="template{{$key}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header d-flexx">
                                                        <h6>مدیریت  قالب ({{$product->title}})</h6>
                                                    </div>
                                                    <div class="modal-body ">


                                                        <form action="{{ route('products.file_template',$product->slug) }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <input type="file" class="form-control" name="file" >
                                                            </div>
                                                            <button class="btn btn-primary btn-sm" type="submit"   @if($product->file == null) name="addfile" @else name="updatefile" @endif>
                                                                @if($product->file == null) آپلود قالب @else ویرایش قالب @endif
                                                            </button>
                                                        </form>
                                                        @if($product->file != null)
                                                            <h6 class="mt-2">
                                                                حجم فایل فعلی :
                                                                @php
                                                                    $sizebyte = filesize(\Illuminate\Support\Facades\Storage::path($product->file));
                                                                    $sizekeyl = $sizebyte /1024;
                                                                    $sizemeg = $sizekeyl /1024;
                                                                    echo round($sizemeg,2).' مگابایت ';
                                                                @endphp
                                                            </h6>
                                                        @endif
                                                        <hr>

                                                        <p class="valid-feedback">نکته ها :</p>
                                                        <p class="valid-feedback  text-success"> اگر برای محصول(قالب) خود فایلی آپلود نکنید محصول(قالب) شما  تایید و نمایش داده نخواهد شد</p>
                                                        <p class="valid-feedback text-success">حجم فایل شما باید حداکثر 100 مگابایت باشد</p>
                                                        <p class="valid-feedback text-success">فرمت قالب شما باید zip   و فاقد رمزگذاری باشد</p>
                                                        @if($product->file != null && auth()->user()->role == 'admin')
                                                            <form action="{{ route('products.deleteTemplate',$product->slug) }}" method="post" style="display: inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <button class="btn btn-danger btn-sm" type="submit" name="deletetemplate" >
                                                                    حذف قالب
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('download.templateonTest',$product->slug) }}" >
                                                                <button class="btn btn-warning btn-sm" type="submit" >
                                                                    دانلود قالب
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>



                                    @endif
                                    <button class="btn btn-secondary btn-sm btn-desc" data-toggle="modal" data-target="#desc{{$key}}">عملیات ها</button>
                                    <div class="modal" id="desc{{$key}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <span class="font-large">عملیات ها</span>
                                                    <button type="button" class="close mtap-5"  data-dismiss="modal" aria-hidden="true">&times;</button>
                                                </div>
                                                <div class="modal-body d-flexx">
                                                    <a href="{{ route('products.edit',$product->slug) }}" class="btn btn-info ">ویرایش</a>
                                                    <a href="{{ route('downloads.index2',$product->slug) }}" class="btn btn-dark  ">مدیریت دانلود ها</a>
                                                    <a href="{{ route('collapses.index2',$product->slug) }}" class="btn btn-primary ">مدیریت باکس ها</a>
                                                    <form method="post" action="{{ route('products.destroy',$product->slug) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">حذف</button>
                                                    </form>
                                                </div>
                                                <div class="modal-footer d-flexx">
                                                    <button class="btn btn-smm" >تاریخ ایجاد : {{$product->created_at->diffForHumans()}}</button>
                                                        <button class="btn btn-smm">تاریخ آخرین بروزرسانی : {{$product->updated_at->diffForHumans()}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا محصولی  ایجاد نشده است:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
