@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="page-header"><span>Your campaigns <a class="btn-sm btn-success pull-right"
                                                        href="/campaigns/create"><span
                            class="glyphicon glyphicon-plus"></span> Create</a></span></h3>
        <p class="lead">
            This is the complete list of all the campaigns you have created. If you want to start a new campaign simply
            press the create button and start gathering data in a few minutes. If you want to see details of one of your
            currently active campaigns simply click one in the list below.
        </p>
        <hr/>


        @foreach($campaigns as $campaign)
            <a href="/campaigns/{{ $campaign->id}}" style="color: black;">

                <table style="width:100%;">
                    <tr>
                        <td style="width:95%;"><h4>{{ $campaign->name }}</h4></td>
                        <td style="width:5%;" rowspan="2">
                            <i style="font-size: 50px; text-align: right; color: #000;" class="material-icons">keyboard_arrow_right</i>
                        </td>
                    </tr>
                    <tr>
                        <td><p>{{ $campaign->description }}</p></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr/>
                        </td>
                    </tr>
                </table>
            </a>

        @endforeach


    </div>
@endsection
