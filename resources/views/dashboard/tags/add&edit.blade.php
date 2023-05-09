@extends('layouts.dashboard.fronts')

@section('title',isset($tag) ? 'ویرایش برچسپ' : 'افزودن برچسپ')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ isset($tag) ? 'ویرایش برچسپ' : 'افزودن برچسپ' }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form  action="{{ isset($tag) ? route('tags.update',$tag->id) : route('tags.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                        @isset($tag)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نام</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="name" class="form-control col-md-7 col-xs-12"
                                value="{{ isset($tag) ? $tag->name : old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ isset($tag) ? 'ویرایش' : 'افزودن' }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
