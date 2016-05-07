@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="page-header"><span>Your campaigns <a class="btn-sm btn-success pull-right" href="/campaigns/create"><span
                            class="glyphicon glyphicon-plus"></span> Create</a></span></h3>
        <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad blanditiis commodi corporis deserunt doloremque
            earum eligendi eos est expedita facilis hic necessitatibus neque, nobis nostrum pariatur quae repudiandae
            tenetur ut.</p
        <hr/>
        <table class="table-responsive">
        @foreach($campaigns as $campaign)
            <tr>
                <td><h4>{{ $campaign->name }}</h4></td>
                <td rowspan="2">
                    <a href="/campaigns/{{ $campaign->id}}">
                        <i style="font-size: 50px; text-align: right; color: #000;" class="material-icons">keyboard_arrow_right</i>
                    </a>
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
        @endforeach
        </table>


    </div>
@endsection
