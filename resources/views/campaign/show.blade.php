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

        <h3>Questionnaire</h3>
        <ul id="items">
            @foreach($campaign->questions as $question)
                <li>{{ $question->question }}</li>
            @endforeach
            {{--Order kunne gøres med jquery liste --}}
        </ul>
        <a href="{{ action('QuestionsController@add', [$campaign->id]) }}" class="btn btn-block btn-primary">Add question</a>

    </div>
@stop

@section('scripts')
    <script src="/js/Sortable.min.js"></script>
    <script>
        var el = document.getElementById('items');
        var sortable = Sortable.create(el);
    </script>
@stop
