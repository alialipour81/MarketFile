@extends('layouts.dashboard.fronts')
@php
    if (isset($poster)){
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
                    <form action="{{ isset($poster) ? route('posters.update',$poster->id) : route('posters.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @csrf
                        @isset($poster)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">عنوان</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12" placeholder="عنوانی یا راهنمایی برای این پوستر بنویسید"
                                value="{{ isset($poster) ? $poster->title : old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="first-name" name="image" class="form-control col-md-7 col-xs-12"><br><br>
                                @isset($poster)
                                    <img src="{{ asset('storage/'.$poster->image) }}" width="100%" height="100%" class="rounded my-2 shadow">
                                @endisset
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">لینک</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="link" class="form-control col-md-7 col-xs-12" placeholder="لینکی برای این پوستر بنویسید"
                                       value="{{ isset($poster) ? $poster->link : old('link') }}">
                            </div>
                        </div>

                       @isset($poster)
                            @if(auth()->user()->role == 'admin' )
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت پذیرش :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="status" class="form-control">
                                        @foreach($statuses as $key=>$item)
                                            <option value="{{ $key }}"
                                          @isset($poster)  @if($key == $poster->status) selected @endif @endisset
                                            >{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        @else
                            <input type="hidden" name="status" value="{{ $poster->status }}">
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

