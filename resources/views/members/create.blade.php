@extends('app')

@section('content')
    <h1>Create new member</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'MembersController@store']) !!}
        @include('members._form', ['submitButtonText' => 'Create member'])
    {!! Form::close() !!}
@stop