@extends('admin.layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/permission/permission.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Home</a></li>
                        <li class="active">Add Permisson</li>
                    </ul>
                </div>
            </div>

            <section class="form-admin">
                <form action="{{ route('admin.permission.store') }}" method="post" class="row" id="permission-form">
                    @csrf
                    <div class="col-lg-9">
                        <div class="form-group">
                            <label>Module:</label>
                            <select class="form-control text-capitalize" name="module_par"
                                data-url="{{ route('admin.permission.get_actions') }}">
                                <option value="">Select Module</option>
                                @foreach ($modules as $module)
                                    <option class="text-capitalize" value="{{ $module }}">{{ $module }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group flex-between module_actions"></div>
                        <div class="form-group">
                            <button class="btn btn-success text-uppercase" type="submit">Add</button>
                        </div>
                    </div>
                </form>
            </section>
        </section>
    </section>
@endsection
