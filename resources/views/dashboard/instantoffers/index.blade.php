@extends('layouts.dashboard.fronts')

@section('title','پیشنهادات لحظه ای')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>پیشنهادات لحظه ای
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('instantoffers.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($instantoffers->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('instantoffers.index') }}" method="get">
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
                                <th class="column-title">محصول</th>
                                <th class="column-title"> ادمین </th>
                                <th class="column-title"> وضعیت </th>
                                <th class="column-title"> تاریخ ایجاد </th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($instantoffers as $instantoffer)
                            <tr class="even pointer ">
                                <td>{{ $instantoffer->id }}</td>
                                <td>{{ $instantoffer->product->title }}</td>
                                <td  data-toggle="tooltip" data-placement="top" title="{{ $instantoffer->user->name }}">{{ $instantoffer->user->email }}</td>
                                <td>
                                    @if($instantoffer->status == 0)
                                        <span class="badge badge-danger bg4">عدم نمایش</span>
                                    @else
                                        <span class="badge badge-success bg5"> نمایش</span>
                                    @endif
                                </td>
                                <td>{{ $instantoffer->created_at->diffForhumans() }} </td>
                                <td >
                                    <a href="{{ route('instantoffers.edit',$instantoffer->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('instantoffers.destroy',$instantoffer->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا محصولی به پیشنهاد اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $instantoffers->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
