@extends('layouts.fronts')
@section('title','تایید ایمیل شما')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg2 text-light">تایید ایمیل حساب خود</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            لینک تایید به ایمیل شما هم اکنون ارسال شد
                            <button type="button" class="close mt-3" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <p class="">برای دسترسی به داشبورد خود ابتدا باید ایمیل خود را تایید کنید روی ارسال لینک تایید ایمیل کلیک کنید</p>
                        <br>
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <div class="parent-btn mt-3">
                            <button class="dk-btn dk-btn-info bg2">
                                ارسال لینک تایید ایمیل
                                <i class="now-ui-icons ui-1_email-85"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
