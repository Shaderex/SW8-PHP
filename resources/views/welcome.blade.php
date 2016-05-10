@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="jumbotron">
            <div class="text-center">
                <h1>Welcome</h1>
                <p><strong>Here you can manage and create new campaigns, that will be available on our mobile
                        platform.</strong></p>
                <p>
                    @if(Auth::check())
                        <a class="btn btn-primary btn-lg" href="/campaigns">Go to management page</a>
                    @else
                        <a class="btn btn-primary btn-lg" href="/login">Login</a>
                        <a class="btn btn-primary btn-lg" href="/register">Register</a>
                    @endif
                </p>
            </div>
        </div>
    </div>

@endsection
