@extends('app')

@section('content')
    <h2>Προβολή αντικειμένου</h2>

    <div class="jumbotron">
        <h1>{{ $vo->title }}</h1>

        <p>
            {{ $vo->description }}
        </p>
    </div>

    <a href="/voteobjectives" class="btn btn-default btn-lg">
        <span class="glyphicon glyphicon-chevron-left"></span> Πίσω
    </a>

@stop