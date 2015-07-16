@extends('app')

@section('head')
    <link href="{{ URL::asset('css/select2-bootstrap.css') }}" rel="stylesheet" />
@stop

@section('content')
    <h1>Δημιουργία βουλευτή</h1>

    @include('errors.list')

    {!! Form::open(['action' => 'MembersController@store']) !!}
        @include('members._form', ['submitButtonText' => 'Δημιουργία'])
    {!! Form::close() !!}
@stop

@section('footer')
    <script type="text/javascript">
        $('select').select2();
    </script>
@stop