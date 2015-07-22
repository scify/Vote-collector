@extends('app')

@section('content')
    <h1>Δημιουργία ψηφοφορίας</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VotingsController@store']) !!}
        @include('votings.partials.form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop