@extends('layouts.dashboard.fronts')

@section('title','ویرایش پاسخ')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        ویرایش پاسخ
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message2')
                    <form  action="{{ route('messages.update',$message->id)  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left ">
                        @csrf
                        @method('PUT')
                        @if($message->title != null)
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">عنوان:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="title" class="form-control col-md-7 col-xs-12"
                                value="{{  $message->title}} ">
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">متن نظر : </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="des" name="description" rows="4" class="form-control col-md-7 col-xs-12" placeholder="توضیحاتی   بنوییسید">{!!   $message->description  !!}</textarea>
                                <script>
                                    CKEDITOR.replace('des',{
                                        language:'fa',
                                        filebrowserUploadUrl : "{{ route('messages.uploadImageCKeditor',['_token'=>csrf_token()]) }}",
                                        filebrowserUploadMethod : 'form',
                                    })
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" class="btn btn-success">ویرایش پاسخ</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
