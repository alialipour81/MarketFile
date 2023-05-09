@extends('layouts.dashboard.fronts')

@section('title','تخفیف ها')

@section('content')
    <div class="right_col" role="main">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>تخفیف ها
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <a href="{{ route('discounts.create') }}" class="btn-add btn-sm ">افزودن <i class="fa fa-plus"></i></a>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    @include('layouts.message2')
                    <div class="table-responsive">
                        @if($discounts->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('discounts.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="نام(کد) تخفیف  را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">نام(کدتخفیف)</th>
                                <th class="column-title">درصد کسر</th>
                                <th class="column-title">حداقل خرید </th>
                                <th class="column-title">سطح دسترسی </th>
                                <th class="column-title"> تاریخ اعتبار</th>
                                <th class="column-title"> تعداد باقیمانده </th>
                                <th class="column-title"> وضعیت نمایش  </th>
                                <th class="column-title">آخرین بروزرسانی</th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($discounts as $discount)
                            <tr class="even pointer ">
                                <td>{{ $discount->id }}</td>
                                <td>{{ $discount->name }}</td>
                                <td>{{ $discount->parcent_kasr }}</td>
                                <td>
                                    @if($discount->price == null)
                                        دلخواه
                                    @else
                                        <span > {{ number_format($discount->price).'تومان' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($discount->access != 0)

                                        <span  data-toggle="tooltip" data-placement="top" title="{{ $discount->user->name }}">  {{ $discount->user->email }}</span>
                                    @else
                                        <span class="badge badge-success bg3"> همه</span>
                                    @endif
                                </td>
                                <td>{{ $discount->dateTime }}</td>
                                <td>{{ $discount->count }}</td>
                                <td>
                                    @if($discount->status == 0)
                                        <span class="badge badge-danger bg4">عدم نمایش</span>
                                    @else
                                        <span class="badge badge-success bg5"> نمایش</span>
                                    @endif
                                </td>
                                <td>{{ $discount->updated_at->diffForhumans() }} </td>
                                <td >
                                    <a href="{{ route('discounts.edit',$discount->id) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('discounts.destroy',$discount->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا تخفیفی اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $discounts->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
