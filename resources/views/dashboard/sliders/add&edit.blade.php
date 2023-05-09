@extends('layouts.dashboard.fronts')
@php
    if (isset($slider)){
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
                    <form action="{{ isset($slider) ? route('sliders.update',$slider->id) : route('sliders.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @csrf
                        @isset($slider)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">عنوان</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12" placeholder="عنوانی یا راهنمایی برای این اسلاید بنویسید"
                                value="{{ isset($slider) ? $slider->title : old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="first-name" name="image" class="form-control col-md-7 col-xs-12"><br><br>
                                @isset($slider)
                                    <img src="{{ asset('storage/'.$slider->image) }}" width="100%" height="100%" class="rounded my-2 shadow">
                                @endisset
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">لینک</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="link" class="form-control col-md-7 col-xs-12" placeholder="لینکی برای این اسلاید بنویسید"
                                       value="{{ isset($slider) ? $slider->link : old('link') }}">
                            </div>
                        </div>

                       @isset($slider)
                            @if(auth()->user()->role == 'admin' )
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت پذیرش :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="status" class="form-control">
                                        @foreach($statuses as $key=>$item)
                                            <option value="{{ $key }}"
                                          @isset($slider)  @if($key == $slider->status) selected @endif @endisset
                                            >{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        @else
                            <input type="hidden" name="status" value="{{ $slider->status }}">
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

