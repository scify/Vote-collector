@extends('app')

@section('content')
    <h1>{{ $voting->title }}</h1>

    @include('errors.list')

    <h2>Επεξεργασία προεπιλεγμένων απαντήσεων</h2>

    {!! Form::open(['url' => 'votings/answers/update', 'method' => 'PUT']) !!}
        @include('votings.partials.answersform', ['edit' => 'true'])
    {!! Form::close() !!}

@stop