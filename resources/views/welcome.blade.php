@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="jumbotron">
            <div class="text-center">
                <h1>Welcome</h1>
                <br/>
                <br/>
                <p><strong>Here you can manage and create new campaigns, that will be available on our mobile
                        platform.</strong></p>

                <br/>
                <br/>
                <br/>
                <p>
                    @if(Auth::check())
                        <a class="btn btn-primary" href="/campaigns">Manage your campaigns</a>
                    @else
                        <a class="btn btn-primary" href="/login" style="margin-right:15px;"> <i class="fa fa-btn fa-sign-in"></i>Login</a>

                        <a class="btn btn-primary" href="/register"><i class="fa fa-btn fa-user"></i>Register</a>
                    @endif
                </p>
            </div>
        </div>
    </div>

@endsection
