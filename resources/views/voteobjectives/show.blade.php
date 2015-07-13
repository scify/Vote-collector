@extends('app')

@section('content')
    <h2>Προβολή αντικειμένου</h2>

    <div class="jumbotron">
        <h1>{{ $vo->title }}</h1>

        <p>
            {{ $vo->description }}
        </p>
    </div>
@stop