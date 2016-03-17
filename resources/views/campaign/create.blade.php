@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ action('CampaignsController@store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="name">Name:</label>
                <input name="name" id="name" type="text" class="form-control" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="checkbox">
                <label>
                    <input name="is_private" type="checkbox" {{ old('is_private') ? 'checked' : '' }}>
                    Private
                </label>
            </div>
            <div class="form-group">
                <label for="sensors[]">Sensors</label>
                <select name="sensors[]" class="js-example-basic-multiple form-control" multiple="multiple">
                    @foreach(\DataCollection\Sensor::all() as $sensor)
                        <option value="{{ $sensor->id }}">{{ $sensor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Snapshot length (Time in minutes between questionnaires)</label>
                <input type="number" name="snapshot_length" class="form-control" value="{{old('snapshot_length')}}">
            </div>
            <div class="form-group">
                <label>Sample duration (The duration of a single sample in a snapshot)</label>
                <input type="number" name="sample_duration" class="form-control" value="{{old('sample_duration')}}">
            </div>
            <div class="form-group">
                <label>Sample frequency (The time between the start of each sample)</label>
                <input type="number" name="sample_frequency" class="form-control" value="{{old('sample_frequency')}}">
            </div>
            <div class="form-group">
                <label>Measurement frequency (The time between each measurement in a sample)</label>
                <input type="number" name="measurement_frequency" class="form-control" value="{{old('measurement_frequency')}}">
            </div>
            <!---  field --->
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block">
            </div>
        </form>
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>

@stop

@section('scripts')
    <script type="text/javascript">
        $(".js-example-basic-multiple").select2();
    </script>
@stop

