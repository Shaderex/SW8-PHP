@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="page-header"><span>Your campaigns <a class="btn-sm btn-success pull-right" href="/campaigns/create"><span
                            class="glyphicon glyphicon-plus"></span> Create</a></span></h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad blanditiis commodi corporis deserunt doloremque
            earum eligendi eos est expedita facilis hic necessitatibus neque, nobis nostrum pariatur quae repudiandae
            tenetur ut.</p
        <hr/>
        @foreach($campaigns as $campaign)
            <div class="row">
                <div class="col-lg-11 v-align">
                    <h3>{{ $campaign->name }}</h3>
                    <p>{{ $campaign->description }}</p>
                </div>
                <div class="col-lg-1 v-align" style="width: 50px;">
                    <a href="/campaigns/{{ $campaign->id}}">
                        <i style="font-size: 70px; text-align: right;" class="material-icons">keyboard_arrow_right</i>
                    </a>
                </div>
            </div>
            <hr/>
        @endforeach


    </div>
@endsection
