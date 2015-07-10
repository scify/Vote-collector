@extends('app')

@section('content')
    <h1>Create vote objective</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VoteObjectivesController@store']) !!}
        @include('voteobjectives._form', ['submitButtonText' => 'Create objective'])
    {!! Form::close() !!}
@stop