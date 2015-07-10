@extends('app')

@section('content')
    <h1>Create new group</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'GroupsController@store']) !!}
        @include('groups._form', ['submitButtonText' => 'Create group'])
    {!! Form::close() !!}
@stop