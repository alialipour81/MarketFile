@extends('layouts.dashboard.fronts')

@section('title','ویرایش کاربر')

@section('content')

    <div class="right_col" role="main">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                       ویرایش کاربر {{$user->name}}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>
                    @include('layouts.message')
                    @include('layouts.message2')
                    @if($user->image != null)
                    <form action="{{ route('users.delimgprofile',$user->email) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" >حذف تصویر پروفایل</button>
                    </form>
                    @endif
                    <form  action="{{ route('users.update',$user->id)  }}" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left " enctype="multipart/form-data">
                        @csrf
                            @method('PUT')
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نام:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="name" class="form-control col-md-7 col-xs-12"
                                value="{{ $user->name  }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">ایمیل:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="email" class="form-control col-md-7 col-xs-12"
                                       value="{{ $user->email  }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">تصویر پروفایل:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" id="first-name" name="image" class="form-control col-md-7 col-xs-12">
                                @if($user->image != null)
                                    <br><br>
                                    <img src="{{ asset('storage/'.$user->image) }}" alt="{{$user->name}}" width="200px" height="200px" class="shadow rounded">

                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">پسورد جدید:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" name="new_password" class="form-control col-md-7 col-xs-12" placeholder="پسورد جدیدی تنظیم کنید ,حداقل 8 کاراکتر (اختیاری)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">وضعیت تایید ایمیل:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="email_verified_at" class="form-control">
                                    @if($user->email_verified_at == null)
                                    <option value="now">همین حالا تایید کن</option>
                                    @endif
                                    @if($user->email_verified_at != null)
                                    <option value="Accepted" @if($user->email_verified_at != null) selected @endif>تایید شده</option>
                                    @endif
                                    <option value="unconfirmed" @if($user->email_verified_at == null) selected @endif>تایید نشده</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">نقش کاربری:</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="role" class="form-control">
                                  @foreach($roles as $key=>$role)
                                        <option value="{{$key}}"
                                        @if($key == $user->role) selected @endif
                                        >{{$role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt-2">
                                <button type="submit" name="updateuser" class="btn btn-success">ویرایش</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
