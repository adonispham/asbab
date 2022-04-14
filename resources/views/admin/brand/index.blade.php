@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/brand/brand.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang
                                chủ</a></li>
                        <li class="active">Nhãn hàng</li>
                    </ul>
                </div>
            </div>

            <section class="form-admin">
                @can('thêm ngành hàng')
                    <form id="brand-form" data-action="{{ route('admin.brand.store') }}" method="post"
                          class="row flex-center">
                        @csrf
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Nhãn hàng:</label>
                                <input type="text" name="name" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Đường dẫn:</label>
                                <input type="text" name="link" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Ảnh đại diện:</label>
                                <div class="files-view"></div>
                                <div class="file-btn">
                                    <input hidden type="file" name="image_path" class="file-choose"/>
                                    <div class="btn btn-info file-choose-alt choose"><span></span>Chọn</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success text-uppercase" type="submit">Thêm</button>
                            </div>
                        </div>
                    </form>
                @endcan
            </section>

            <section class="panel">
                <div class="panel-body">
                    <div class="adv-table">
                        <table class="table table-bordered table-striped" id="brands-table"
                               @if(auth()->user()->can('sửa ngành hàng') || auth()->user()->can('xóa ngành hàng'))
                               @if (auth()->user()->can('sửa ngành hàng') && auth()->user()->can('xóa ngành hàng'))
                               data-url="{{ route('admin.brand.data', ['permission' => 1]) }}"
                               @elseif(auth()->user()->can('sửa ngành hàng'))
                               data-url="{{ route('admin.brand.data', ['permission' => 2]) }}"
                               @elseif(auth()->user()->can('xóa ngành hàng'))
                               data-url="{{ route('admin.brand.data', ['permission' => 3]) }}"
                               @endif
                               @else
                               data-url="{{ route('admin.brand.data', ['permission' => 0]) }}"
                            @endif>
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Nhãn hàng</th>
                                <th>Ảnh đại diện</th>
                                <th>Đường dẫn</th>
                                @if(auth()->user()->can('sửa ngành hàng') || auth()->user()->can('xóa ngành hàng'))
                                    <th class="text-center">Hành động</th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>

            <div class="modal fade full-width-modal-right" id="editBrand" tabindex="-1" role="dialog"
                 aria-labelledby="editBrandLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content-wrap">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-center"></h4>
                            </div>
                            <form class="modal-body brand-body">
                                @csrf
                                <input hidden type="text" name="url" class="form-control"/>

                                <div class="form-group">
                                    <label>Nhãn hàng:</label>
                                    <input type="text" name="name" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label>Đường dẫn:</label>
                                    <input type="text" name="link" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label>Ảnh đại diện:</label>
                                    <div class="files-view"></div>
                                    <div class="file-btn">
                                        <input hidden type="file" name="image_path" class="file-choose"/>
                                        <div class="btn btn-info file-choose-alt choose"><span></span>Chọn</div>
                                    </div>
                                </div>
                                <div class="flex-end">
                                    <button data-dismiss="modal" class="btn btn-shadow btn-default"
                                            type="button">Đóng
                                    </button>
                                    <button id="update-btn-brand" class="btn btn-shadow btn-success ml-10"
                                            type="submit">Cập nhật
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
