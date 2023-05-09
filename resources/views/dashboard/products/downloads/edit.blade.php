@extends('layouts.dashboard.fronts')

@section('title','ویرایش ')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        ویرایش
                        ({{ $download->product->title }})
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @include('layouts.message2')
                    <form  action="{{ route('downloads.update',$download->slug) }}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                            @method('PUT')
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">عنوان : </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12"
                                value="{{ $download->title }}">
                            </div>
                        </div>
                        @if($download->url == null)
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">فایل جدید دانلود  : </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" id="first-name" name="new_file" class="form-control col-md-7 col-xs-12">
                                    <p class="small text-secondary mt-1">اگر میخواهید فایل جدیدی جایگزین کنید این فیلد را پرکنید</p>
                                </div>
                                <a href="{{ route('downloadTest',$download->slug) }}" class="btn btn-secondary btn-sm btn-desc">تست فایل فعلی</a>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">لینک دانلود  : </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="first-name" name="url" class="form-control col-md-7 col-xs-12"
                                           value="{{ $download->url }}">
                                </div>
                            </div>
                        @endif


                            @if(auth()->user()->role == 'admin')
                         <div class="form-group">
                             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت نمایش</label>
                             <div class="col-md-6 col-sm-6 col-xs-6">
                                 <select name="status" class="form-control col-md-7 col-xs-12 f-iran">
                                     @foreach($status as $key=>$value)
                                         <option value="{{ $key }}" @if($download->status == $key) selected @endif  > {{ $value }} </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                            @else
                                <input type="hidden" name="status" value="{{ $download->status }}">
                            @endif

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">ویرایش</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
