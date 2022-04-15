@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/newspaper/newspaper.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang
                                chủ</a>
                        </li>
                        <li class="active">Tin tức</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <div class="adv-table">
                        @can('thêm tin tức')
                            <div class="flex-end center mb-15">
                                <div class="btn-group text-right">
                                    <a href="{{ route('admin.news.create') }}" class="btn btn-success">
                                        Thêm mới <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        @endcan
                        <table class="table table-bordered table-striped" id="news-table"
                               @if(auth()->user()->can('sửa tin tức') || auth()->user()->can('xóa tin tức'))
                               @if (auth()->user()->can('sửa tin tức') && auth()->user()->can('xóa tin tức'))
                               data-url="{{ route('admin.news.data', ['permission' => 1]) }}"
                               @elseif(auth()->user()->can('sửa tin tức'))
                               data-url="{{ route('admin.news.data', ['permission' => 2]) }}"
                               @elseif(auth()->user()->can('xóa tin tức'))
                               data-url="{{ route('admin.news.data', ['permission' => 3]) }}"
                               @endif
                               @else
                               data-url="{{ route('admin.news.data', ['permission' => 0]) }}"
                            @endif>
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tiêu đề</th>
                                <th>Ảnh đại diện</th>
                                <th>Mô tả</th>
                                <th>Tác giả</th>
                                @if(auth()->user()->can('sửa tin tức') || auth()->user()->can('xóa tin tức'))
                                    <th class="text-center">Hành động</th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
