@extends('admin.layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/select2/select2.min.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/select2/select2.min.js') }}"></script>
    <script src="{{ asset('administrator/js/plugins.js') }}"></script>
    <script src="{{ asset('administrator/js/common.js') }}"></script>
    <script src="{{ asset('administrator/user/user.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="breadcumb-item"><a href="{{ route('admin.user.index') }}">Users</a></li>
                        <li class="active">{{ $user->name }}</li>
                    </ul>
                </div>
            </div>
            <section class="form-admin">
                <form action="{{ route('admin.user.update', ['id' => $user->id]) }}" method="post" class="row">
                    @csrf
                    <div class="col-lg-9">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                autofocus value="{{ $user->name }}" />
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                                autofocus value="{{ $user->email }}" />
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Roles:</label>
                            <select name="role_id[]"
                                class="form-control select2_init @error('role_id') is-invalid @enderror" multiple>
                                <option value=""></option>
                                @foreach ($roles as $role)
                                    <option {{ collect($user->roles()->get()->pluck('id')->toArray())->contains($role->id) ? 'selected' : '' }}
                                        value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" autofocus
                                value="{{ old('password') }}" />
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary text-uppercase" type="submit">UPDATE</button>
                        </div>
                    </div>
                </form>
            </section>
        </section>
    </section>
@endsection
