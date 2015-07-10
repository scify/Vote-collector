@extends('app')

@section('content')
    <h1>Edit {{ $group->name }}</h1>

    @include('errors.list')

    {!! Form::model($group, ['route' => ['groups.update', $group->id], 'method' => 'PUT']) !!}
        @include('groups._form', ['submitButtonText' => 'Save group'])
    {!! Form::close() !!}
@stop