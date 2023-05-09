@extends('layouts.dashboard.fronts')

@section('title',isset($discount) ? 'ویرایش تخفیف' : 'افزودن تخفیف')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('layouts.message')
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ isset($discount) ? 'ویرایش تخفیف' : 'افزودن تخفیف' }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form  action="{{ isset($discount) ? route('discounts.update',$discount->id) : route('discounts.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                        @isset($discount)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> نام (همان کد تخفیف است) :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="name" class="form-control col-md-7 col-xs-12"
                                value="{{ isset($discount) ? $discount->name : old('name') }}" placeholder="مثلا Yalda1401">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> حداقل قیمت خرید  :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="price" class="form-control col-md-7 col-xs-12"
                                       value="{{ isset($discount) ? $discount->price : old('price') }}" placeholder="قیمت را به تومان بنویسید (اختیاری) مثلا 30000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">درصد تخفیف:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="parcent_kasr" class="form-control col-md-7 col-xs-12"
                                       value="{{ isset($discount) ? $discount->parcent_kasr : old('parcent_kasr') }}" placeholder="مثلا 30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> سطح دسترسی :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="access" class="form-control">
                                    <option  onclick="show();" value="0"
                                    @isset($discount) @if($discount->access == 0) selected  @endif @endisset
                                    >برای همه قابل استفاده باشد</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" onclick="@if(!isset($discount)) Hide(); @endif"
                                                @isset($discount)  @if($discount->access == $user->id) selected @endif @endisset
                                        >{{ $user->name }}({{$user->email}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="box_count_use">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تعداد استفاده : </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="count_use" name="count_use" class="form-control col-md-7 col-xs-12"
                                       value="{{ isset($discount) ? $discount->count : old('count_use') }}" placeholder="مثلا 6"

                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">توضیحات :</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <textarea  id="desc-pro2" name="description" rows="4" class="form-control col-md-7 col-xs-12 f-iran" placeholder="توضیحاتی درباره تخفیف بنوییسید">{!!  isset($discount) ? $discount->description : old('description') !!}</textarea>
                                <script>
                                    CKEDITOR.replace('desc-pro2',{
                                        language: 'fa'
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group" id="box_count_use">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" id="ldate"> تاریخ اعتبار : </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="date" name="dateTime" class="form-control col-md-7 col-xs-12 date" data-jdp
                                       value="{{ isset($discount) ? $discount->dateTime : old('dateTime') }}">
                            </div>
                        </div>
                        @isset($discount)
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت پذیرش :</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="status" class="form-control">
                                        @foreach($statuses as $key=>$item)
                                            <option value="{{ $key }}"
                                                    @isset($discount)  @if($key == $discount->status) selected @endif @endisset
                                            >{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endisset
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ isset($discount) ? 'ویرایش' : 'افزودن' }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <h6>نکته ها:</h6>
                <ul>
                    <li>از فیلد "تعداد استفاده" زمانی میتوانید استفاده کنید که سطح دسترسی "برای همه قابل استفاده باشد"  باشد</li>
                    <li>درصد تخفیف باید عددی بین ً1تا 100 باشد</li>
                    <li>اگر میخواهید برای تخفیف حداقل مبلغی تعیین کنید فیلد " حداقل قیمت خرید" را پرکنید در غیر اینصورت آن را خالی بزارید(اگر خالی باشد یعنی قیمت خرید دلخواه است) </li>
                </ul>
            </div>

        </div>

    </div>
@endsection
@section('link_css')
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
@endsection
@section('link_js')
    <!-- show & hide -->
    <script>

        function Hide(){
            var MyText = document.getElementById("box_count_use");
            MyText.style.display = "none";
            document.getElementById('count_use').disabled = true;
        }
        function show(){
            var MyText = document.getElementById("box_count_use");
            MyText.style.display = "block";
            document.getElementById('count_use').disabled = false;

        }
        jalaliDatepicker.startWatch();
        jalaliDatepicker.updateOptions({
           time : true,
        })
    </script>

@endsection


