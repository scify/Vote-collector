@extends('app')

@section('content')
    <h1>Δημιουργία τύπου ψηφοφορίας</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VoteTypesController@store']) !!}
        @include('votetypes.partials.form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop

@section('footer')
    @include('votetypes.partials.js')
@stop