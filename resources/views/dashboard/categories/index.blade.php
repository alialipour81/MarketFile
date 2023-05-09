@extends('layouts.dashboard.fronts')

@section('title','دسته بندی ها')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 >دسته بندی
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">

                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('categories.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($categories->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('categories.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام دسته بندی را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">نام به فارسی</th>
                                <th class="column-title">نام به انگلیسی</th>
                                <th class="column-title"> وضعیت</th>
                                <th class="column-title"> تاریخ ایجاد</th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($categories as $category)
                            <tr class="even pointer ">
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->nameEn }}</td>
                                <td class=" ">
                                    @if($category->parent_id == 0)
                                        <span class="badge badge-success color-blue">دسته بندی اصلی</span>
                                    @else

                                        @if( $category->InfoWithParent->InfoWithParent()->count())
                                            <span class="badge badge-info color-pink">  (زیر دسته بندی: {{$category->InfoWithParent->name}})</span>
                                            <span class="badge badge-info color-pink">(دسته بندی اصلی: {{$category->InfoWithParent->InfoWithParent->name}})</span>
                                        @else
                                            <span class="badge badge-info color-pink">  ( دسته بندی اصلی: {{$category->InfoWithParent->name}})</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $category->created_at->diffForhumans() }} </td>
                                <td >
                                    <a href="{{ route('categories.edit',$category->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('categories.destroy',$category->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا دسته بندی اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
