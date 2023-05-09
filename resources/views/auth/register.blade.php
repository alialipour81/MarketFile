@extends('layouts.fronts')
@section('title','ثبت نام')

@section('content')

    <div class="wrapper default">
        <div class="container">
            <div class="row">
                <div class="main-content col-12 col-md-7 col-lg-5 mx-auto">
                    <div class="account-box">
                        <div class="account-box-title">ثبت‌نام در کمیاب فایل</div>
                        <div class="account-box-content">
                            <form class="form-account" action="@if(session()->has('NameWithgoogle') && session()->has('EmailWithgoogle')) {{ route('google.register') }} @else {{ route('register') }} @endif" method="post">
                                @csrf
                                <div class="form-account-title">نام ونام خانوادگی:</div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons users_single-02"></i></label>
                                    <input class="input-field @error('name') is-invalid @enderror" type="text" placeholder="نام ونام  خود را وارد نمایید" name="name" value="@if(session()->has('NameWithgoogle')) {{ session()->get('NameWithgoogle') }} @else {{ old('name') }} @endif">
                                    @error('name')
                                    <span class="small text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if(!session()->has('EmailWithgoogle'))
                                <div class="form-account-title">ایمیل: </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons ui-1_email-85"></i></label>
                                    <input class="input-field @error('email') is-invalid @enderror" type="email" placeholder=" ایمیل خود را وارد نمایید" name="email" value=" {{ old('email') }}">
                                    @error('email')
                                    <span class="small text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif

                                <div class="form-account-title">پسورد: </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field @error('password') is-invalid @enderror" type="password" placeholder="کلمه عبور خود را وارد نمایید" name="password">
                                    @error('password')
                                    <span class="small text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-account-title">تکرار پسورد: </div>
                                <div class="form-account-row">
                                    <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                    <input class="input-field" type="password" placeholder="تکرار کلمه عبور خود را وارد نمایید" name="password_confirmation">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}"></div>
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="invalid-feedback" style="display: block;">
                                              <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                           </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-account-row form-account-submit">
                                    <div class="parent-btn">
                                        <button class="dk-btn dk-btn-primary">
                                            ثبت نام در کمیاب فایل
                                            <i class="now-ui-icons users_circle-08"></i>
                                        </button>
                                    </div>
                                </div>


                            </form>
                        </div>
                        <div class="account-box-footer">
                            <span>قبلا در کمیاب فایل ثبت‌نام کرده‌اید؟</span>
                            <a href="{{ route('login') }}" class="btn-link-border">وارد شوید</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('link_css')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
