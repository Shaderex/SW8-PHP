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
        <hr>
        <h3>Questionnaire</h3>
        <form action="{{ action('QuestionsController@changeOrder', [$campaign->id]) }}" method="POST">
            {!! csrf_field() !!}
            <ul id="items">
                @foreach($campaign->questions()->orderBy('order')->get() as $question)
                    <li>
                        {{ $question->question }}
                        <input type="hidden" name="order[]" value="{{ $question->id }}">
                    </li>
                @endforeach
            </ul>
            <input type="submit" class="btn btn-primary " value="Save order">
            <a href="{{ action('QuestionsController@create', [$campaign->id]) }}" class="btn btn-success">
                <span class="glyphicon glyphicon-plus"></span>
                Add question
            </a>
        </form>
        <hr>
        <h3>Participant (device ids)</h3>
        <ul>
            @foreach($campaign->participants as $participant)
                <li>{{ $participant->device_id }}</li>
            @endforeach
        </ul>

    </div>
@stop

@section('scripts')
    <script src="/js/Sortable.min.js"></script>
    <script>
        var el = document.getElementById('items');
        var sortable = Sortable.create(el);
    </script>
@stop
