@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/delivery/delivery.js') }}"></script>
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
                        <li class="active">Phí vận chuyển</li>
                    </ul>
                </div>
            </div>
            @can('thêm phí vận chuyển')
                <section class="form-admin">
                    <form id="delivery-form" data-action="{{ route('admin.delivery.store') }}" method="post"
                          class="row flex-center">
                        @csrf
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tỉnh/Thành phố:</label>
                                <select name="province_id" class="form-control"
                                        data-url="{{ route('admin.delivery.provinces') }}">
                                    <option value="">Chọn tỉnh/thành phố</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Quận/Huyện:</label>
                                <select name="district_id" disabled class="form-control"
                                        data-url="{{ route('admin') }}">
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Phường/Xã:</label>
                                <select name="ward_id" disabled class="form-control">
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Phí vận chuyển:</label>
                                <input type="text" name="feeship" class="form-control" value="{{ old('feeship') }}"/>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success text-uppercase" type="submit">Thêm</button>
                            </div>
                        </div>
                    </form>
                </section>
            @endcan

            <section class="panel">
                <div class="panel-body">
                    <div class="adv-table">
                        <table class="table table-bordered table-striped" id="delivery-table"
                               data-url="{{ route('admin.delivery.data') }}"
                               @can('sửa phí vận chuyển') data-edit="1" @endcan>
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tỉnh/Thành phố</th>
                                <th>Quận/Huyện</th>
                                <th>Phường/Xã</th>
                                <th>Phí vận chuyển</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
