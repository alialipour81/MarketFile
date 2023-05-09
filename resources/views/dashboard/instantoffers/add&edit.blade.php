@extends('layouts.dashboard.fronts')

@section('title',isset($instantoffer) ? 'ویرایش پیشنهاد' : 'افزودن پیشنهاد')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        {{ isset($instantoffer) ? 'ویرایش پیشنهاد' : 'افزودن پیشنهاد' }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form  action="{{ isset($instantoffer) ? route('instantoffers.update',$instantoffer->id) : route('instantoffers.store')  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                        @isset($instantoffer)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">محصولات  :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="product_id" class="form-control">
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                    @isset($instantoffer) @if($product->id == $instantoffer->product_id) selected @endif  @endisset
                                    >{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @isset($instantoffer)
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت پذیرش :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="status" class="form-control">
                                    @foreach($statuses as $key=>$item)
                                        <option value="{{ $key }}"
                                                @isset($instantoffer)  @if($key == $instantoffer->status) selected @endif @endisset
                                        >{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endisset
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">{{ isset($instantoffer) ? 'ویرایش' : 'افزودن' }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
