@extends('app')

@section('content')
    <h1>Δημιουργία βουλευτή</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'MembersController@store']) !!}
        @include('members.partials.form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop