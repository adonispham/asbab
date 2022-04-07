@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/user/customer.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="active">Customers</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <form class="adv-table">
                        @can('vip customer')
                            <div class="flex-end center mb-15">
                                <div class="btn-group text-right">
                                    <a data-url="{{ route('admin.customer.update') }}" class="btn btn-success btn-update-vip">
                                        VIP <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        @endcan
                        <table class="table table-bordered table-striped" id="customers-table"
                            @if(auth()->user()->can('vip customer'))
                                data-url="{{ route('admin.customer.data', ['permission' => 1]) }}"
                            @else
                                data-url="{{ route('admin.customer.data', ['permission' => 0]) }}"
                            @endif>
                            <thead>
                                <tr>
                                    @can('vip customer')
                                        <th class="text-center"><input type="checkbox" name="check" data-toggle="checkall"
                                                data-target=".item" /></th>
                                    @endcan
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Orders</th>
                                    <th>Amount</th>
                                    <th class="text-center">Level</th>
                                </tr>
                            </thead>
                        </table>
                    </form>
                </div>
            </section>
        </section>
    </section>
@endsection
