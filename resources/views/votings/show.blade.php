@extends('app')

@section('content')
    <h1>Showing voting</h1>

    <div class="jumbotron">
        <h1>{{ $voting->title }}</h1>

        <p>
            Type: {{ $type->title }}
        </p>
        <p>
            Objective: {{ $objective->title }}
        </p>
    </div>

    <a href="/votings" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
@stop