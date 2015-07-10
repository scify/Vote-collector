@extends('app')

@section('content')
    <h1>Edit {{ $vo->title }}</h1>

    @include('errors.list')

    {!! Form::model($vo, ['route' => ['voteobjectives.update', $vo->id], 'method' => 'PUT']) !!}
        @include('voteobjectives._form', ['submitButtonText' => 'Save objective'])
    {!! Form::close() !!}
@stop
