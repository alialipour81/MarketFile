@extends('layouts.fronts')
@section('title','بازنشانی پسورد ')
@section('content')


<div class="wrapper default">
    <div class="container">
        <div class="row">
            <div class="main-content col-12 col-md-7 col-lg-10 mx-auto ">
                <div class="account-box ">
                    <div class="account-box-title text-right">  بازنشانی پسورد  </div>
                    <div class="account-box-content">
                        <form class="form-account" method="post" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}" required autocomplete="email" autofocus>
                            <div class="form-account-title">رمز عبور جدید: </div>
                            <div class="form-account-row">
                                <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                <input class="input-field @error('password') is-invalid @enderror" type="password" name="password" placeholder=" پسورد جدید را وارد کنید " required autocomplete="new-password">
                                @error('password')
                                <span class="small text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-account-title">تکرار رمز عبور جدید: </div>
                            <div class="form-account-row">
                                <label class="input-label"><i class="now-ui-icons ui-1_lock-circle-open"></i></label>
                                <input class="input-field " type="password" name="password_confirmation" placeholder=" تکرار پسورد جدید را وارد کنید " required autocomplete="new-password">
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
                                     ذخیره تغییرات
                                        <i class="now-ui-icons ui-1_check"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
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
