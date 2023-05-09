@extends('layouts.fronts')
@section('title','بازیابی پسورد')
@section('content')

<div class="wrapper default">
    <div class="container">
        <div class="row">
            <div class="main-content col-12 col-md-7 col-lg-10 mx-auto ">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="account-box ">
                    <div class="account-box-title text-right">  بازیابی رمز عبور </div>
                    <div class="account-box-content">
                        <form class="form-account" method="post" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-account-title">ایمیل </div>
                            <div class="form-account-row">
                                <label class="input-label"><i class="now-ui-icons ui-1_email-85"></i></label>
                                <input class="input-field @error('email') is-invalid @enderror" type="email" name="email" placeholder="ایمیل  خود را وارد نمایید" value="{{ old('email') }}">
                                @error('email')
                                <span class="small text-danger">{{ $message }}</span>
                                @enderror
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
                                        بازیابی رمز عبور
                                        <i class="now-ui-icons arrows-1_refresh-69"></i>
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
