@extends('layouts.dashboard.fronts')

@section('title',isset($collapse) ? 'ویرایش باکس' : 'افزودن باکس')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                     {{ isset($collapse) ? 'ویرایش باکس' : 'افزودن باکس' }}  @isset($collapse) ({{ $collapse->product->title }})@endisset
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @include('layouts.message2')
                    <form  action="{{ isset($collapse) ? route('collapses.update',$collapse->slug) : route('collapses.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                        @isset($collapse)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نوع : </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="type" class="form-control col-md-7 col-xs-12">
                                @foreach($type as $key=>$value)
                                <option value="{{ $key }}"
                                @isset($collapse) @if($collapse->type == $key) selected @endif  @endisset
                                >{{ $value }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">عنوان : </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12"
                                value="{{ isset($collapse) ? $collapse->title : old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">توضیحات</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <textarea  id="collapse-pro" name="content" rows="4" class="form-control col-md-7 col-xs-12 f-iran" placeholder="محتوای باکس را   بنوییسید">{!!  isset($collapse) ? $collapse->content : old('content') !!}</textarea>
                                <script>
                                    CKEDITOR.replace('collapse-pro',{
                                        language:'fa',
                                        filebrowserUploadUrl : "{{ route('collapses.ck.upload',['_token'=>csrf_token()]) }}",
                                        filebrowserUploadMethod : 'form',
                                    });
                                </script>
                            </div>
                        </div>
                        @isset($collapse)
                            @if(auth()->user()->role == 'admin')
                         <div class="form-group">
                             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت نمایش</label>
                             <div class="col-md-6 col-sm-6 col-xs-6">
                                 <select name="status" class="form-control col-md-7 col-xs-12 f-iran">
                                     @foreach($status as $key=>$value)
                                         <option value="{{ $key }}" @if($collapse->status == $key) selected @endif  > {{ $value }} </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                            @else
                                <input type="hidden" name="status" value="{{ $collapse->status }}">
                            @endif

                        @endisset
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ isset($collapse) ? 'ویرایش' : 'افزودن' }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
