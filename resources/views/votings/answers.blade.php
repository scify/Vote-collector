@extends('app')

@section('content')
    <h1>{{ $voting->title }}</h1>

    @include('errors.list')

    <h2>Προεπιλεγμένες απαντήσεις</h2>

    {!! Form::open(['action' => 'VotingsController@saveDefaultAnswers']) !!}
        @include('votings.partials.answersform')
    {!! Form::close() !!}
@stop