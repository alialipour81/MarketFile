@extends('layouts.dashboard.fronts')

@section('title','اسلایدر ')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>اسلایدر
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('sliders.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($sliders->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('sliders.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="عنوان یا لینک اسلاید مورد نظر  را وارد کنید" value="{{ request()->query('search') }}">
                                        <span class="input-group-btn">
                      <button class="btn btn-default btn-search" type="submit">جستجو</button>
                    </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        <table class="table table-striped jambo_table bulk_action small">
                            <thead>
                            <tr class="headings">
                                <th class="column-title">آیدی</th>
                                <th class="column-title">عنوان</th>
                                <th class="column-title">تصویر</th>
                                <th class="column-title">لینک</th>
                                <th class="column-title">وضعیت</th>
                                <th class="column-title">ادمین</th>
                                <th class="column-title">  آخرین بروزرسانی</th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($sliders as $slider)
                            <tr class="even pointer ">
                                <td>{{ $slider->id }}</td>
                                <td>{{ $slider->title }}</td>
                                <td>
                                    <img src="{{ asset('storage/'.$slider->image) }}" width="60px" height="40px" class="rounded  shadow">
                                </td>
                                <td>
                                    <a href="{{ $slider->link }}" class="btn btn-secondary btn-sm btn-desc">تست لینک</a>
                                </td>
                                <td>
                                    @if($slider->status == 0)
                                        <span class="badge badge-danger bg4">عدم نمایش</span>
                                    @else
                                        <span class="badge badge-success bg5"> نمایش</span>
                                    @endif
                                </td>
                                <td  data-toggle="tooltip" data-placement="top" title="{{ $slider->user->name }}">{{ $slider->user->email }}</td>
                                <td>{{ $slider->updated_at->diffForhumans() }} </td>
                                <td >
                                    <a href="{{ route('sliders.edit',$slider->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('sliders.destroy',$slider->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا اسلایدی اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $sliders->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
