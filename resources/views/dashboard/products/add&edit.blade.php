@extends('layouts.dashboard.fronts')
@php
    if (isset($product)){
        $title = "ویرایش";
    }else{
        $title = "افزودن";
    }
@endphp
@section('title',$title)

@section('content')
@php
function selected($value1,$value2){
    if ($value1 == $value2) { echo 'selected';}
    }

@endphp
    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('layouts.message')
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ $title }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @include('layouts.message2')
                    <form action="{{ isset($product) ? route('products.update',$product->slug) : route('products.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @csrf
                        @isset($product)
                            @method('PUT')
                        @endisset
                        @if(auth()->user()->role == 'admin' || request()->url() == route('products.create'))
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-1" for="first-name"> نوع محصول  </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select name="type" class="form-control" >
                                    @foreach($types as $key=>$type)
                                        <option value="{{ $key }}"
                                        @isset($product) {{ selected($product->type,$key) }}@endisset
                                        >{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="type" value="{{ $product->type }}">
                        @endif
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">دسته بندی</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select name="category_id" class="form-control">
                                    @foreach($categories as $category)
                                    <option @isset($product) {{ selected($category->id,$product->category_id) }} @endisset class="text-success" value="{{ $category->id }}">{{ $category->name }}</option>
                                        @if($category->parents()->count())
                                            @foreach($category->parents as $parent1)
                                                <option @isset($product) {{ selected($parent1->id,$product->category_id) }} @endisset class="text-info" value="{{ $parent1->id }}">{{ $parent1->name }}</option>
                                                @if($parent1->parents()->count())
                                                    @foreach($parent1->parents as $parent2)
                                                        <option @isset($product) {{ selected($parent2->id,$product->category_id) }} @endisset value="{{ $parent2->id }}">{{ $parent2->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                            @if(auth()->user()->role == 'admin') فروشگاه ها @else فروشگاه شما @endif
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select name="store_id" class="form-control">
                                        @foreach($stores as $store)
                                            <option @isset($product) {{ selected($store->id,$product->store_id) }} @endisset value="{{ $store->id }}">{{ $store->name }}
                                            @if(auth()->user()->role == 'admin')
                                                ({{$store->user->email}})
                                                @endif
                                            </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> برچسپ ها </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select name="tags[]" class="form-control" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                        @isset($product)
                                            {{ $product->existsTag($tag->id) }}
                                        @endisset
                                        >#{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">عنوان</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12" placeholder="عنوان  محصول را بنویسید"
                                value="{{ isset($product) ? $product->title : old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">قیمت اصلی</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" id="first-name" name="price" class="form-control col-md-7 col-xs-12" placeholder="قیمت اصلی  محصول را بنویسید"
                                       value="{{ isset($product) ? $product->price : old('price') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">قیمت فروش</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" id="first-name" name="new_price" class="form-control col-md-7 col-xs-12" placeholder="قیمت فروش(تخفیف)  محصول را بنویسید(اختیاری)"
                                       value="{{ isset($product) ? $product->new_price : old('new_price') }}">
                            </div>
                        </div>
                      @isset($product)
                            <div class="form-group">
                                <div class="col-md-3">تصویر اصلی
                                    <img src="{{ asset('storage/'.$product->image1) }}" width="100%" height="100%" class="rounded shadow">
                                </div>
                                <div class="col-md-3"> تصویر دوم
                                    <img src="{{ asset('storage/'.$product->image2) }}" width="100%" height="100%" class="rounded shadow">
                                </div>
                                <div class="col-md-3"> تصویر سوم
                                    <img src="{{ asset('storage/'.$product->image3) }}" width="100%" height="100%" class="rounded shadow">
                                </div>
                                <div class="col-md-3"> تصویر چهارم
                                    <img src="{{ asset('storage/'.$product->image4) }}" width="100%" height="100%" class="rounded shadow">
                                </div>
                            </div>
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر اصلی </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="file" id="first-name" name="image1" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر دوم </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="file" id="first-name" name="image2" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر سوم </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="file" id="first-name" name="image3" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر چهارم </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="file" id="first-name" name="image4" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">توضیحات</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <textarea  id="desc-pro" name="description" rows="4" class="form-control col-md-7 col-xs-12 f-iran" placeholder="توضیحاتی درباره محصول بنوییسید">{!!  isset($product) ? $product->description : old('description') !!}</textarea>
                                <script>
                                    CKEDITOR.replace('desc-pro',{
                                       language:'fa',
                                       filebrowserUploadUrl : "{{ route('products.ck_upload',['_token'=>csrf_token()]) }}",
                                       filebrowserUploadMethod : 'form',
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">ویژگی ها</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <textarea id="first-name" name="attrbutes" rows="4" class="form-control col-md-7 col-xs-12" placeholder="ویژگی های  محصول بنوییسید">{{ isset($product) ? $product->attrbutes : old('attrbutes') }}</textarea>
                            </div>
                        </div>
                        @isset($product)
                            @if(auth()->user()->role == 'admin')
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >وضعیت نمایش  :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="status" class="form-control">
                                        @foreach($status as $key=>$value)
                                            <option value="{{ $key }}"
                                                   {{ selected($key,$product->status) }}
                                            >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                                <input type="hidden" name="status" value="{{ $product->status }}">
                            @endif
                        @endisset
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ isset($product) ? 'ویرایش' : 'افزودن' }}</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

