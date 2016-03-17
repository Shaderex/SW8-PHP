@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table table-hover">
            <tr>
                <th>Name</th>
                <td>
                    {{ $campaign->name }}
                </td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $campaign->description }}</td>
            </tr>
            <tr>
                <th>Private</th>
                <td>{{ $campaign->is_private ? 'true' : 'false' }}</td>
            </tr>
            <tr>
                <th>Sensors</th>
                <td>
                    @foreach($campaign->sensors as $sensor)
                        {{ $sensor->name .', ' }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Snapshot length</th>
                <td>{{ $campaign->snapshot_length }}</td>
            </tr>
            <tr>
                <th>Sample duration</th>
                <td>{{ $campaign->sample_duration }}</td>
            </tr>
            <tr>
                <th>Sample frequency</th>
                <td>{{ $campaign->sample_frequency }}</td>
            </tr>
            <tr>
                <th>Measurement frequency</th>
                <td>{{ $campaign->measurement_frequency }}</td>
            </tr>
        </table>
        {{--<a href="{{  }}" class="btn btn-primary"></a>--}}
    </div>
@stop
