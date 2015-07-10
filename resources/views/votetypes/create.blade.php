@extends('app')

@section('content')
    <h1>Create new vote type</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VoteTypesController@store']) !!}
        @include('votetypes.partials.form', ['submitButtonText' => 'Create vote type'])
    {!! Form::close() !!}
@stop

@section('footer')
    @include('votetypes.partials.js')
@stop