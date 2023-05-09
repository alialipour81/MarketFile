@extends('layouts.dashboard.fronts')

@section('title',isset($category) ? 'ویرایش دسته بندی' : 'افزودن دسته بندی')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ isset($category) ? 'ویرایش دسته بندی' : 'افزودن دسته بندی' }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form action="{{ isset($category) ? route('categories.update',$category->id) : route('categories.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                        @csrf
                        @isset($category)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نام (به فارسی)</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="name" class="form-control col-md-7 col-xs-12"
                                value="{{ isset($category) ? $category->name : old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نام (به انگلیسی)</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="nameEn" class="form-control col-md-7 col-xs-12"
                                       value="{{ isset($category) ? $category->nameEn : old('nameEn') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">جزو</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="parent_id"  class="form-control">
                                    <option value="0"
                                     @isset($category)
                                           @if($category->parent_id == 0) selected @endif
                                     @endisset
                                   >دسته بندی اصلی</option>
                                    @if(isset($category) && $category->parent_id != 0 || request()->url() == route('categories.create')))
                                    @foreach($categories as $cat)
                                        @if(isset($category) && $category->id != $cat->id || request()->url() == route('categories.create'))
                                        <option class="text-success" value="{{ $cat->id }}"
                                                @isset($category)
                                                    @if($category->parent_id == $cat->id) selected @endif
                                                @endisset
                                        > {{ $cat->name }} </option>
                                        @endif
                                        @foreach($cat->parents as $parent)
                                                @if(isset($category) && $category->id != $parent->id || request()->url() == route('categories.create'))
                                            <option value="{{ $parent->id }}"
                                            @isset($category)
                                                @if($category->parent_id == $parent->id) selected @endif
                                            @endisset
                                            >{{ $parent->name }} ({{ $parent->InfoWithParent->name }})</option>
                                                @endif
                                        @endforeach
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ isset($category) ? 'ویرایش' : 'افزودن' }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
