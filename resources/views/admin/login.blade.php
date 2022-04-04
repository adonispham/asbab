@extends('admin.layout.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/login/login.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('administrator/login/login.js') }}"></script>
@endsection

@section('content')
    <section class="login-wrap d-flex">
        <section class="bg-login">
            <img src="{{ asset('administrator/images/backgrounds/login_page.jpg') }}" alt="" />
        </section>
        <section class="form-login items-center">
            <form method="post" action="{{ route('admin.login') }}">
                @csrf
                <h1>Asbab Furniture</h1>
                <div class="form-group">
                    <input type="text" name="email" placeholder="Your Email *" />
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password *" />
                </div>
                <div class="form-group flex-between center">
                    <label for="remember"><input type="checkbox" name="remember_me" id="remember" />Remember me</label>
                    <a href="#">Forgot password ?</a>
                </div>
                <button class="btn btn-info btn-shadow btn-login" type="submit">LOG IN</button>
            </form>
        </section>
    </section>
@endsection