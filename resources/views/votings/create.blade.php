@extends('app')

@section('content')
    <h1>Create voting</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VotingsController@store']) !!}
        @include('votings._form', ['submitButtonText' => 'Create voting'])
    {!! Form::close() !!}
@stop