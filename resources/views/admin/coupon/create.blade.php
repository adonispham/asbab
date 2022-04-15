@extends('admin.layout.app')

@section('js')
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcumb-item"><a href="{{ route('admin.coupon.index') }}">Phiếu giảm giá</a></li>
                        <li class="active">Thêm mã giảm giá</li>
                    </ul>
                </div>
            </div>
            <section class="form-admin">
                <form action="{{ route('admin.coupon.store') }}" method="post" class="row">
                    @csrf
                    <div class="col-lg-9">
                        <div class="form-group">
                            <label>Chương trình:</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                autofocus value="{{ old('name') }}" />
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Mã giảm giá:</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code') }}" />
                            @error('code')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Loại:</label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">Chọn loại hình chiết khấu</option>
                                <option {{ old('type') == 0 ? 'selected' : '' }} value="0">Tiền mặt</option>
                                <option {{ old('type') == 1 ? 'selected' : '' }} value="1">Phần trăm
                                </option>
                            </select>
                            @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Chiết khấu:</label>
                            <input type="text" name="discount" class="form-control @error('discount') is-invalid @enderror"
                                value="{{ old('discount') }}" />
                            @error('discount')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Số lượng:</label>
                            <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity') }}" />
                            @error('quantity')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Hết hàng:</label>
                            <input type="date" name="time_out_of"
                                class="form-control @error('time_out_of') is-invalid @enderror"
                                value="{{ old('time_out_of') }}" />
                            @error('time_out_of')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success text-uppercase" type="submit">Thêm</button>
                        </div>
                    </div>
                </form>
            </section>
        </section>
    </section>
@endsection
