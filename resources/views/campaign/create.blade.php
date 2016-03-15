@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ action('CampaignsController@store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="name">Name:</label>
                <input name="name" id="name" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="checkbox">
                <label>
                    <input name="private" type="checkbox">
                    Private
                </label>
            </div>
            <div class="form-group">
                <select name="sensors[]" id="sensors" multiple class="form-control">
                    <option value="1">Hej</option>
                    <option value="2">Load</option>
                    <option value="3">Sensore</option>
                    <option value="4">Fra</option>
                    <option value="5">DB</option>
                    <option value="6">Eller</option>
                    <option value="7">Andet</option>
                    <option value="8">STED</option>
                </select>
            </div>
            <div class="form-group">
                <label>Snapshot length (Time in minutes between questionnaires)</label>
                <input type="number" name="snapshot_length" class="form-control">
            </div>
            <div class="form-group">
                <label>Sample duration (The duration of a single sample in a snapshot)</label>
                <input type="number" name="sample_duration" class="form-control">
            </div>
            <div class="form-group">
                <label>Sample frequency (The time between the start of each sample)</label>
                <input type="number" name="sample_frequency" class="form-control">
            </div>
            <div class="form-group">
                <label>Measurement frequency (The time between each measurement in a sample)</label>
                <input type="number" name="measurement_frequency" class="form-control">
            </div>
            <!---  field --->
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block">
            </div>
        </form>
    </div>
@stop