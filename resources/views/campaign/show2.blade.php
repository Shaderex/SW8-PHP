@extends('layouts.app')

<style>
    .smart-phone {
        width: 100%;
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
                <h4 class="page-header">Campaign Information</h4>
                <h3>{{ $campaign->name }}</h3>
                <p>
                    {{ $campaign->description }}
                </p>
                <strong>Campaign identifier: <span class="label label-primary">{{$campaign->id}}</span></strong>
                <div class="checkbox">
                    <label>
                        <input name="is_public" type="checkbox" {{ $campaign->is_private ? '' : 'checked' }} disabled>
                        Publicly Available
                    </label>
                </div>


                <div class="form-group">
                    <h4 class="page-header">Sensors</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <b><i class="material-icons sensor-type-icon">location_on</i>Location</b>
                            <ul>
                                <?php $count = 0; ?>
                                @foreach($campaign->sensors as $sensor)
                                    @if(in_array($sensor->type,[2, 4, 6 ,8]))
                                        <?php $count++; ?>
                                        <li>
                                            {{ $sensor->name }}
                                        </li>
                                    @endif
                                @endforeach
                                @if($count == 0)
                                    <em class="text-muted">none</em>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <b><i class="material-icons sensor-type-icon">directions_run</i>Movement</b>
                            <ul>
                                <?php $count = 0; ?>
                                @foreach($campaign->sensors as $sensor)
                                    @if(in_array($sensor->type,[0, 5, 9]))
                                        <?php $count++; ?>
                                        <li>
                                            {{ $sensor->name }}
                                        </li>
                                    @endif
                                @endforeach
                                @if($count == 0)
                                    <em class="text-muted">none</em>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-6">
                            <b><i class="material-icons sensor-type-icon">favorite</i>Personal Information</b>
                            <ul>
                                <?php $count = 0; ?>
                                @foreach($campaign->sensors as $sensor)
                                    @if(in_array($sensor->type,[10, 12]))
                                        <?php $count++; ?>
                                        <li>
                                            {{ $sensor->name }}
                                        </li>
                                    @endif
                                @endforeach
                                @if($count == 0)
                                    <em class="text-muted">none</em>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <b><i class="material-icons sensor-type-icon">polymer</i>Miscellaneous</b>
                            <ul>
                                <?php $count = 0; ?>
                                @foreach($campaign->sensors as $sensor)
                                    @if(in_array($sensor->type,[1, 7, 11]))
                                        <?php $count++; ?>
                                        <li>
                                            {{ $sensor->name }}
                                        </li>
                                    @endif
                                @endforeach
                                @if($count == 0)
                                    <em class="text-muted">none</em>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <h4 class="page-header">Samples and measurements</h4>


                <div class="form-group">
                    <div class="form-inline">
                        <label>Snapshots per Campaign</label>
                        <p class="pull-right">{{ $campaign->campaign_length }} snapshots</p>
                    </div>
                    <br/>
                    <div class="form-inline">
                        <label>Samples per Snapshot</label>
                        <p class="pull-right">{{ $campaign->samples_per_snapshot }} samples</p>

                    </div>
                    <br/>
                    <div class="form-inline">
                        <label>Sample delay (in milliseconds)</label>
                        <p class="pull-right">{{ $campaign->sample_delay }} ms</p>

                    </div>
                    <br/>
                    <div class="form-inline">
                        <label>Measurement per Sample</label>
                        <p class="pull-right">{{ $campaign->measurements_per_sample }} measurements</p>

                    </div>
                    <br/>
                    <div class="form-inline">
                        <label>Measurement delay (in milliseconds)</label>
                        <p class="pull-right">{{ $campaign->measurement_frequency }} ms</p>

                    </div>
                </div>
                <h4 class="page-header">Questionnaire</h4>
                <strong>Placement</strong>
                <p>The questionnaire is placed in
                    the {{ \DataCollection\Campaign::$placements[$campaign->questionnaire_placement] }} of a
                    snapshot</p>


                @if(count($campaign->questions) > 0)
                    <strong>Questions in the Questionnaire</strong>
                    <ul id="questions-list">
                        @foreach($campaign->questions as $question)
                            <li>{{$question->question}}</li>
                        @endforeach
                    </ul>
                @endif

            </div>

            <div class="col-sm-4 hidden-xs">
                <h4 class="page-header">Progress</h4>
                <ul class="list-group">
                    {{--<h4 class="page-header">Current progress</h4>--}}
                    <li class="list-group-item">
                        <span class="badge">{{ $participantsCount }}</span>
                        Participants joined
                    </li>
                    <li class="list-group-item">
                        <span class="badge">{{ $snapshotCount }}</span>
                        Snapshots submitted
                    </li>
                </ul>
                <a href="/campaigns/{{ $campaign->id }}/snapshots" class="btn btn-primary btn-block">Get snapshot data</a>
                <div class="smart-phone">
                    <div class="phone-view"></div>
                </div>
                <form action="{{ action('CampaignsController@destroy', [$campaign->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <div class="form-group">
                        <input type="submit" value="Delete campaign" class="btn btn-danger btn-block">
                    </div>
                </form>
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

