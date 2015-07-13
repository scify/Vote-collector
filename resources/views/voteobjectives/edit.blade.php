@extends('app')

@section('content')
    <h1>Επεξεργασία: {{ $vo->title }}</h1>

    @include('errors.list')

    {!! Form::model($vo, ['route' => ['voteobjectives.update', $vo->id], 'method' => 'PUT']) !!}
        @include('voteobjectives._form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop
