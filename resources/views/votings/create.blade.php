@extends('app')

@section('content')
    <h1>Δημιουργία ψηφοφορίας</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'VotingsController@store']) !!}
        @include('votings.partials.form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop

@section('footer')
    <script src="{{ URL::asset('js/votings/createVoting.js') }}"></script>
@stop