@extends('layouts.dashboard.fronts')

@section('title',' مدیریت دانلود '.$product->title)
@php
    function get_file_size($url) {        $file = $url;   $ch = curl_init($file);   curl_setopt($ch, CURLOPT_NOBODY, true);   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   curl_setopt($ch, CURLOPT_HEADER, true);   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   $data = curl_exec($ch);   curl_close($ch);   if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {       $fileSize = (int)$matches[1];       return $fileSize;   }}
@endphp
@section('content')
    <div class="right_col" role="main">
        @include('layouts.message2')
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>مدیریت دانلود({{$product->title}})
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li ><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="ml-3">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#add-link">افزودن لینک <i class="fa fa-plus ml-2"></i></button>
                            <div id="add-link" class="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6>افزودن لینک</h6>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('downloads.store') }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="title" placeholder="عنوان لینک  وارد کنید" value="{{ old('title') }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="url" placeholder="یک لینک وارد کنید" value="{{ old('url') }}">
                                                </div>
                                                <button class="btn btn-primary btn-sm" type="submit" name="add_link">ارسال</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>
                        <li class="ml-3">
                            <button class="btn btn-success  btn-sm" data-toggle="modal" data-target="#add-file"> افزودن فایل <i class="fa fa-plus"></i></button>
                            <div id="add-file" class="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6>افزودن فایل</h6>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('downloads.store') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="titlefile" placeholder="عنوان لینک  وارد کنید" value="{{ old('titlefile') }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="file" class="form-control" name="file" >
                                                </div>
                                                <button class="btn btn-primary btn-sm" type="submit" name="add_file">آپلود</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('layouts.message')
                    <div class="table-responsive">
                        @if($downloads->count())
                        <div class="title_right box-search">
                            <div class="col-md-12col-sm-12 col-xs-12 form-group pull-right top_search">
                                <form action="{{ route('downloads.index2',session()->get('download_productslug')) }}" method="get">
                                    <div class="input-group">
                                        <input type="text"  name="search" class="form-control inp-search" placeholder="عنوان دانلود  را وارد کنید" value="{{ request()->query('search') }}">
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
                                <th class="column-title">عنوان</th>
                                <th class="column-title">لینک/فایل</th>
                                <th class="column-title"> تاریخ ایجاد</th>
                                <th class="column-title">  وضعیت </th>
                                <th class="column-title">ویرایش</th>
                                <th class="column-title">حذف</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($downloads as $key=>$download)
                            <tr class="even pointer ">
                                <td>{{ $key +1}}</td>
                                <td>{{ $download->title }}</td>
                                <td>
                                    @if(!empty($download->url))
                                        <a href="{{ $download->url }}" class="btn btn-secondary btn-sm btn-desc">تست لینک</a>
                                        <button class="btn btn-secondary btn-sm btn-sizefile " disabled>
                                        حجم فایل :
                                        @php
                                            $sizebyte = get_file_size($download->url);
                                            $sizekeyl = $sizebyte /1024;
                                            $sizemeg = $sizekeyl /1024;
                                            echo round($sizemeg,2).' مگابایت ';
                                        @endphp
                                        </button>
                                    @else
                                        <a href="{{ route('downloadTest',$download->slug) }}" class="btn btn-secondary btn-sm btn-desc">تست فایل</a>
                                        <button class="btn btn-secondary btn-sm btn-sizefile " disabled>
                                            حجم فایل :
                                            @php
                                                $sizebyte = filesize(\Illuminate\Support\Facades\Storage::path($download->file));
                                                $sizekeyl = $sizebyte /1024;
                                                $sizemeg = $sizekeyl /1024;
                                                echo round($sizemeg,2).' مگابایت ';
                                            @endphp
                                        </button>
                                    @endif
                                </td>
                                <td>{{ $download->created_at->diffForhumans() }} </td>
                                <td>
                                    @if($download->status == 0)
                                        <span class="badge badge-danger bg4">عدم نمایش</span>
                                    @else
                                        <span class="badge badge-success bg5"> نمایش</span>
                                    @endif
                                </td>
                                <td >
                                    <a href="{{ route('downloads.edit',$download->slug) }}" class="btn btn-info btn-sm">ویرایش</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('downloads.destroy',$download->slug) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <div class="alert alert-info">فعلا لینک یا  فایلی اضافه نکردید:)</div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $downloads->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
