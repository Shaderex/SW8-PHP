@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $campaign->name }}</h1>

        <form action="{{ action('QuestionsController@store', [$campaign->id]) }}" method="post">
            {!! csrf_field() !!}
            <div class="form-group {{ $errors->has('') ? ' has-error' : '' }}">
                <label for="question">Question</label>
                <input class="form-control" type="text" name="question">
            </div>

            <input type="submit" class="btn btn-block btn-primary">
        </form>
    </div>
@stop