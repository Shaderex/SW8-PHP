@extends('layouts.app')

<style>
    .smart-phone {

        width: 100% px;
        background: url("/images/phone.png");
        background-repeat: no-repeat;
        background-size: contain;
    }

    .smart-phone .phone-view {
        background: blue;
        position: relative;
    }

    .sensor-type-icon {
        font-size: 15pt;
        vertical-align: middle;
        padding-right: 10px;
        margin-left: 0;
        padding-left: 0;
    }
</style>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <form action="{{ action('CampaignsController@store') }}" method="POST">
                    {!! csrf_field() !!}
                    <h4 class="page-header">Campaign Information</h4>
                    <div class="form-group">
                        <label for="name">Campaign Title</label>
                        <input name="name" id="name" type="text" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Campaign Description</label>
                        <textarea name="description" id="description"
                                  class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="is_public" type="checkbox" checked>
                            Publicly Available
                        </label>
                    </div>


                    <div class="form-group">
                        <h4 class="page-header">Sensors</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <b><i class="material-icons sensor-type-icon">location_on</i>Location</b>
                                @foreach(\DataCollection\Sensor::all() as $sensor)
                                    @if(in_array($sensor->type,[2, 4, 6 ,8]))
                                        <div class="checkbox">
                                            <label>
                                                <input name="sensors[{{ $sensor->id}}]" type="checkbox" }>
                                                {{ $sensor->name }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <b><i class="material-icons sensor-type-icon">directions_run</i>Movement</b>
                                @foreach(\DataCollection\Sensor::all() as $sensor)
                                    @if(in_array($sensor->type,[0, 5, 9]))
                                        <div class="checkbox">
                                            <label>
                                                <input name="sensors[{{ $sensor->id}}]" type="checkbox" }>
                                                {{ $sensor->name }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-6">
                                <b><i class="material-icons sensor-type-icon">favorite</i>Personal Information</b>
                                @foreach(\DataCollection\Sensor::all() as $sensor)
                                    @if(in_array($sensor->type,[10, 12]))
                                        <div class="checkbox">
                                            <label>
                                                <input name="sensors[{{ $sensor->id}}]" type="checkbox" }>
                                                {{ $sensor->name }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <b><i class="material-icons sensor-type-icon">polymer</i>Miscellaneous</b>
                                @foreach(\DataCollection\Sensor::all() as $sensor)
                                    @if(in_array($sensor->type,[1, 7, 11]))
                                        <div class="checkbox">
                                            <label>
                                                <input name="sensors[{{ $sensor->id}}]" type="checkbox" }>
                                                {{ $sensor->name }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <h4 class="page-header">Samples and measurements</h4>

                    <div class="form-group">
                        <div class="form-inline">
                            <label>Snapshots per Campaign</label>
                            <input name="campaign_length" id="campaign_length" type="number" class="form-control pull-right" value="{{ old('campaign_length') }}">
                        </div>
                        <br/>
                        <div class="form-inline">
                            <label>Samples per Snapshot</label>
                            <input name="samples_per_snapshot" id="samples_per_snapshot" type="number" class="form-control pull-right" value="{{ old('samples_per_snapshot') }}">
                        </div>
                        <br/>
                        <div class="form-inline">
                            <label>Sample delay (in milliseconds)</label>
                            <input name="sample_delay" id="sample_delay" type="number" class="form-control pull-right" value="{{ old('sample_delay') }}">
                        </div>
                        <br/>
                        <div class="form-inline">
                            <label>Measurement per Sample</label>
                            <input name="measurement_per_sample" id="measurement_per_sample" type="number" class="form-control pull-right" value="{{ old('measurement_per_sample') }}">
                        </div>
                        <br/>
                        <div class="form-inline">
                            <label>Measurement delay (in milliseconds)</label>
                            <input name="measurement_frequency" id="measurement_frequency" type="number" class="form-control pull-right" value="{{ old('measurement_frequency') }}">
                        </div>
                    </div>
                    <h4 class="page-header">Questionnaire</h4>
                    <div class="form-group">
                        <label for="questionnaire_placement">Questionnaire Placement</label>
                        <p class="help-text">Should questionnaire be asked in the start of the snapshot or after af snapshot was collected.</p>
                        <select name="questionnaire_placement" id="questionnaire_placement" class="form-control">
                            @foreach(\DataCollection\Campaign::$placements as $key => $placement)
                                @if ($key == old('questionnaire_placement'))
                                    <option value="{{ $key }}" selected>{{ $placement }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $placement }}</option>
                                @endif
                            @endforeach
                        </select>
                        <br/>
                    </div>
                    <div class="form-inline">
                        <label>Questions in the Questionnaire</label>
                        <ul class="list-unstyled" id="questions-list"></ul>
                        <input style="width:81%;" type="text" id="add-question-text" class="form-control">
                        <a id="add-question-button" style="width:18%;" class="btn-primary btn">Add Question</a>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <input type="submit" class="btn btn-primary btn-block" value="Save Campaign">
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
            <div class="col-sm-4 hidden-xs smart-phone">
                <div class="phone-view">
                </div>
            </div>
        </div>
    </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(updatePhoneView);
        $(window).resize(updatePhoneView);

        function updatePhoneView() {
            var phoneDiv = $(".smart-phone");
            phoneDiv.height(phoneDiv.width() * 1.733333333);
        }

        function addQuestion() {
            var questionList = $("#questions-list");

            var questionTextInput = $("#add-question-text");
            var question = questionTextInput.val();
            questionTextInput.val('');

            if (question == '') {
                return;
            }

            $('<input>').attr({
                type: 'hidden',
                name: 'questions[]',
                value: question,
            }).appendTo('form');
            questionList.append("<li>" + question + "</li>");

        }

        $("#add-question-button").click(addQuestion);


    </script>
@stop