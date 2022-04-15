@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/support/support.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang
                                chủ</a></li>
                        <li>Hỗ trợ</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <div class="adv-table">
                        <table class="table table-bordered table-middle table-striped" id="supports-table"
                               @if(auth()->user()->can('trả lời hỗ trợ') || auth()->user()->can('xóa hỗ trợ'))
                               @if (auth()->user()->can('trả lời hỗ trợ') && auth()->user()->can('xóa hỗ trợ'))
                               data-url="{{ route('admin.support.data', ['permission' => 1]) }}"
                               @elseif(auth()->user()->can('trả lời hỗ trợ'))
                               data-url="{{ route('admin.support.data', ['permission' => 2]) }}"
                               @elseif(auth()->user()->can('xóa hỗ trợ'))
                               data-url="{{ route('admin.support.data', ['permission' => 3]) }}"
                               @endif
                               @else
                               data-url="{{ route('admin.support.data', ['permission' => 0]) }}"
                            @endif>
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Người yêu cầu</th>
                                <th>Chủ đề</th>
                                <th>Yêu cầu</th>
                                <th class="text-center">Trạng thái</th>
                                @if(auth()->user()->can('trả lời hỗ trợ') || auth()->user()->can('xóa hỗ trợ'))
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
