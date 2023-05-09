@extends('layouts.dashboard.fronts')

@section('title',' باکس   '.$product->title)

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> باکس های  ({{$product->title}})
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapses-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('collapses.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($collapses->count())
                            <div class="title_right box-search">
                                <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                    <form action="{{ route('collapses.index2',session()->get('coll_productslug')) }}" method="get">
                                        <div class="input-group">
                                            <input type="text"  name="search" class="form-control inp-search" placeholder="عنوان باکس را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">نوع</th>
                                <th class="column-title">نام</th>
                                <th class="column-title">محتوا</th>
                                <th class="column-title"> تاریخ ایجاد</th>
                                <th class="column-title"> وضعیت </th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($collapses as $key=>$collapse)
                                <tr class="even pointer ">
                                    <td>{{ $key }}</td>
                                    <td>
                                        @if($collapse->type == 'install')
                                            <span class="badge badge-primary bg2">بخش نصب و  فعال سازی</span>
                                        @else
                                            <span class="badge badge-primary bg2">بخش نقد و بررسی</span>
                                        @endif
                                    </td>
                                    <td>{{ $collapse->title }}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm btn-desc" data-toggle="modal" data-target="#desc{{$key}}">محتوا</button>
                                        <div class="modal" id="desc{{$key}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body ">
                                                      {!! $collapse->content !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $collapse->updated_at->diffForhumans() }} </td>
                                    <td>
                                        @if($collapse->status == 0)
                                            <span class="bg4 badge badge-danger">عدم نمایش  </span>
                                        @else
                                            <span class="bg5 badge badge-success"> نمایش  </span>
                                        @endif
                                    </td>
                                    <td >
                                        <a href="{{ route('collapses.edit',$collapse->slug) }}" class="btn btn-info btn-sm">ویرایش</a>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('collapses.destroy',$collapse->slug) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-info">فعلا باکسی اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $collapses->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
