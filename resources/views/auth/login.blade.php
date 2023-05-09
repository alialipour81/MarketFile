@extends('layouts.fronts')
@section('title','ورود به حساب کاربری')
@section('content')

<div class="wrapper default">
    <div class="container">
        <div class="row">
            <div class="main-content col-12 col-md-7 col-lg-5 mx-auto">
                <div class="account-box">
                    <div class="account-box-title text-right">ورود به کمیاب فایل</div>
                    <div class="account-box-content">
                        <form class="form-account" method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="form-account-title">ایمیل </div>
                            <div class="form-account-row">
                                <label class="input-label"><i class="now-ui-icons fa ui-1_email-85"></i></label>
                                <input class="input-field @error('email') is-invalid @enderror" type="email" name="email" placeholder="ایمیل  خود را وارد نمایید" value="{{ old('email') }}">
                                @error('email')
                                <span class="small text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-account-row">
                                <label class="input-label"><i
                                        class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                <input class="input-field " name="password" type="password" placeholder="رمز عبور خود را وارد نمایید">
                            </div>

                            <div class="form-account-agree">
                                <label class="checkbox-form checkbox-primary">
                                    <input type="checkbox" checked="checked" id="agree" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <span class="checkbox-check"></span>
                                </label>
                                <label for="agree">مرا به خاطر داشته باش</label>
                            </div>
                                      <div class="g-recaptcha" data-sitekey="{{  env('CAPTCHA_SITE_KEY') }}"></div>
                                      @if ($errors->has('g-recaptcha-response'))
                                           <span class="invalid-feedback" style="display: block;">
                                              <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                           </span>
                                      @endif
                            <div class="form-account-row form-account-submit">
                                <div class="parent-btn">
                                    <button class="dk-btn dk-btn-primary">
                                        ورود به کمیاب فایل
                                        <i class="fa fa-sign-in"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <span class="text-center d-block">یا</span>
                        <div class="parent-btn ">
                        <a href="{{ route('google') }}" class="my-3">
                            <button class="dk-btn col-12 dk-btn-danger">
                                ورود با گوگل
                                <i class="fa fa-google"></i>
                            </button>
                        </a>
                        </div>
                    </div>
                    <div class="account-box-footer">

                        <span>کاربر جدید هستید؟</span>
                        <a href="{{ route('register') }}" class="btn-link-border">ثبت‌نام در
                            کمیاب فایل</a>
                        <a href="{{ route('password.request') }}" class="btn-link-border text-secondary form-account-link">رمز
                            عبور خود را فراموش
                            کرده ام</a>
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
