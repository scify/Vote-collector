@extends('app')

@section('content')
    <h1>Δημιουργία αντικειμένου ψηφοφορίας</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VoteObjectivesController@store']) !!}
        @include('voteobjectives._form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop