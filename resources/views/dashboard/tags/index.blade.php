@extends('layouts.dashboard.fronts')

@section('title','برچسپ ها')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>برچسپ ها
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('tags.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($tags->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('tags.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام برچسپ  را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">نام</th>
                                <th class="column-title"> تاریخ ایجاد</th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($tags as $tag)
                            <tr class="even pointer ">
                                <td>{{ $tag->id }}</td>
                                <td>{{ $tag->name }}</td>
                                <td>{{ $tag->created_at->diffForhumans() }} </td>
                                <td >
                                    <a href="{{ route('tags.edit',$tag->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('tags.destroy',$tag->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا برچسپی اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $tags->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
