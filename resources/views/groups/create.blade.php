@extends('app')

@section('content')
    <h1>Δημιουργία Κοινοβουλευτικής Ομάδας</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'GroupsController@store']) !!}
        @include('groups._form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop

@section('footer')
    <script type="text/javascript">
        $('select').select2();
    </script>
@stop