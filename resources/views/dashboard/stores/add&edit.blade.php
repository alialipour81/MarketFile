@extends('layouts.dashboard.fronts')
@php
    if (isset($store)){
        $title = "ویرایش";
    }else{
        $title = "افزودن";
    }
@endphp
@section('title',$title)

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ $title }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form action="{{ isset($store) ? route('stores.update',$store->name) : route('stores.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @csrf
                        @isset($store)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نام</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="name" class="form-control col-md-7 col-xs-12" placeholder="یک نام برای فروشگاهتان در نظر بگیرید مثلا ایران کالا"
                                value="{{ isset($store) ? $store->name : old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">لوگو</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="first-name" name="image" class="form-control col-md-7 col-xs-12">
                                @isset($store)
                                    <br><br>
                                    <img src="{{ asset('storage/'.$store->image) }}" width="100px" height="100px" class="rounded my-2 shadow">
                                @endisset
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">توضیحات</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="des-store" name="description" rows="4" class="form-control col-md-7 col-xs-12" placeholder="توضیحاتی درباره فروشگاهتان بنوییسید">{!!  isset($store) ? $store->description : old('description') !!}</textarea>
                                <script>
                                    CKEDITOR.replace('des-store',{
                                        language: 'fa'
                                    })
                                </script>
                            </div>
                        </div>
                       @isset($store)
                            @if(auth()->user()->role == 'admin' )
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت پذیرش :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="status" class="form-control">
                                        @foreach($statuses as $key=>$item)
                                            <option value="{{ $key }}"
                                          @isset($store)  @if($key == $store->status) selected @endif @endisset
                                            >{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت تیک آبی :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="bluetick" class="form-control">
                                        @foreach($bluetick as $key=>$value)
                                            <option value="{{ $key }}"
                                                    @if($key == $store->bluetick) selected @endif
                                            >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        @else
                            <input type="hidden" name="status" value="{{ $store->status }}">
                            <input type="hidden" name="bluetick" value="{{ $store->bluetick }}">
                        @endif
                        @endisset
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ $title  }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

