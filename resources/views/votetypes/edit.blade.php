@extends('app')

@section('content')
    <h1>Edit {{ $votetype->title }}</h1>

    @include('errors.list')

    {!! Form::model($votetype, ['route' => ['votetypes.update', $votetype->id], 'method' => 'PUT']) !!}
        @include('votetypes.partials.form', ['submitButtonText' => 'Save vote type', 'edit' => 'true'])
    {!! Form::close() !!}
@stop

@section('footer')
    @include('votetypes.partials.js')
@stop