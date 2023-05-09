@extends('layouts.dashboard.fronts')

@section('title','علاقه مندی ها')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>علاقه مندی ها
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($favourites->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('favourites.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام محصول   را وارد کنید" value="{{ request()->query('search') }}">
                                        <span class="input-group-btn">
                      <button class="btn btn-default btn-search" type="submit">جستجو</button>
                    </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                            <tr class="headings">
                                <th class="column-title">آیدی</th>
                                <th class="column-title">عنوان محصول</th>
                                <th class="column-title">تصویر محصول</th>
                                <th class="column-title"> تاریخ افزودن</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($favourites as $favourite)
                            <tr class="even pointer ">
                                <td>{{ $favourite->id }}</td>
                                <td>
                                    <a href="{{ route('product',$favourite->product->slug) }}">{{ $favourite->product->title }}</a>
                                </td>
                                <td>
                                    <img src="{{ asset('storage/'.$favourite->product->image1) }}" width="60px" height="40px" class="rounded shadow ">
                                </td>
                                <td>{{ $favourite->created_at->diffForhumans() }} </td>
                                <td>
                                    <form method="post" action="{{ route('favourites.destroy',$favourite->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا محصولی به علاقه مندیتان اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $favourites->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
