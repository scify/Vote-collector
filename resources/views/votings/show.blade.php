@extends('app')

@section('content')
    <h1>Προβολή ψηφοφορίας</h1>

    <div class="jumbotron">
        <h1>{{ $voting->title }}</h1>

        <p>
            Τύπος: {{ $type->title }}
        </p>
        <p>
            Αντικείμενο: {{ $objective->title }}
        </p>
    </div>

    <a href="/votings" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Πίσω</a>
@stop