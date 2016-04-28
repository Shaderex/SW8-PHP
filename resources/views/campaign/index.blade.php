@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Campaigns created by {{ Auth::user()->name }}</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Sensors</th>
            </tr>
            </thead>
            <tbody>
            @foreach($campaigns as $campaign)
                <tr>
                    <td>
                        <a href="/campaigns/{{ $campaign->id}}">
                            {{ $campaign->name }}
                        </a>
                    </td>
                    <td>{{ $campaign->description }}</td>
                    <td>
                        @foreach($campaign->sensors as $sensor)
                            {{ $sensor->name }};
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection
